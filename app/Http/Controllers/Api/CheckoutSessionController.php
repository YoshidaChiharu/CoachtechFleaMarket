<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Stripe\StripeClient;
use App\Models\SoldItem;
use App\Models\PaymentMethod;

class CheckoutSessionController extends Controller
{
    public function createCheckoutSession(Request $request): JsonResponse {
        try {
            $user = $request->user();
            $item_id = $request->item_id;
            $stripe = new StripeClient("sk_test_51QBad1Bli9nlS8GVTqk4Uty9r2jQqd3WwJlYOrZJZmNPZQWZBqPR4VOJNVPWaZMO88CJT7H9fDoXkJuIp6fTDo1K00UkjRgzAt");

            // 自身が過去に作成した checkout session を整理する処理
            // open or expire状態のまま放置されているものを検索し、該当するsold_itemsレコードを削除
            $sold_items = SoldItem::where('user_id', $user->id)->get();
            if ($sold_items->isNotEmpty()) {
                foreach ($sold_items as $sold_item) {
                    $session_id = $sold_item->checkout_session_id;
                    $session = $stripe->checkout->sessions->retrieve($session_id);

                    // 完了（購入済み）セッション  ※通常この状態は発生しないが保険の為
                    if ($session->status === 'complete') {
                        $sold_item->update(['session_completed' => true]);
                    }
                    // 決済前セッション  ※期限切れにして該当するsold_itemsレコードを削除
                    if ($session->status === 'open') {
                        $stripe->checkout->sessions->expire($session->id);
                        SoldItem::destroy($sold_item->id);
                    }
                    // 期限切れセッション  ※該当するsold_itemsレコードを削除
                    if ($session->status === 'expired') {
                        SoldItem::destroy($sold_item->id);
                    }
                }
            }

            // 同一商品に対する checkout session の作成履歴有無の判別
            // ※有る場合は誰かが「購入済み」or「購入処理中（決済前）」状態としてエラーを返す
            $sold_items = SoldItem::where('item_id', $item_id)->get();
            if ($sold_items->isNotEmpty()) {
                foreach ($sold_items as $sold_item) {
                    $session_id = $sold_item->checkout_session_id;
                    $session = $stripe->checkout->sessions->retrieve($session_id);

                    // 「購入済み(決済完了済み)」判別処理
                    if ($session->status === 'complete') {
                        $sold_item->update(['session_completed' => true]);
                        return response()->json([
                            'status' => 'error',
                            'message' => 'この商品は既に売却済みです'
                        ], 500);
                    }

                    // 「購入処理中（決済前）」判別
                    // ※同一商品を複数人が同時決済出来てしまわないよう防止する意図
                    if ($session->status === 'open') {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'しばらく待って再度お試し下さい'
                        ], 500);
                    }
                }
            }

            DB::beginTransaction();

            // 商品の価格IDの取得

            // カスタマーIDを取得（なければここで作成？もしくは別で作成させる）

            //Checkoutセッション作成
            $checkout = $stripe->checkout->sessions->create([
                'line_items' => [[
                    'price'    => 'price_1QdnKOBli9nlS8GV5hoD5foG',
                    'quantity' => 1,
                ],
                ],
                'mode'                   => 'payment',
                'customer'               => 'cus_RWr3vVgmr9PlMD',
                'ui_mode' => 'embedded',
                'return_url' => 'http://localhost/',
                'redirect_on_completion'=> 'if_required',
                'payment_method_options' => [
                    'card' => [
                        'setup_future_usage' => 'on_session',
                    ],
                    'customer_balance' => [
                        'funding_type' => 'bank_transfer',
                        'bank_transfer' => [
                            'type' => 'jp_bank_transfer',
                        ],
                    ]
                ],
                'payment_method_types'   => [PaymentMethod::find($request->paymentMethodId)->payment_method_type],
                // セッションの有効期限設定（設定可能な最短時間：30分）
                'expires_at' => Carbon::now()->addMinutes(30)->format('U'),
                // metadataに user_id, item_id を埋め込む
                'metadata' => [
                    'user_id' => $user->id,
                    'item_id' => $item_id
                ],
            ]);

            // sold_itemテーブルにレコード登録
            SoldItem::create([
                'user_id' => $user->id,
                'item_id' => $item_id,
                'payment_method_id' => $request->paymentMethodId,
                'checkout_session_id' => $checkout->id,
                'session_completed' => false,
                'ship_postcode' => $user->profile->postcode,
                'ship_address' => $user->profile->address,
                'ship_building' => $user->profile->building,
            ]);

            DB::commit();

            return response()->json($checkout);

        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => '不明なエラー'
            ], 500);
        }
    }

    public function expireCheckoutSession(Request $request) {
        $checkout_session_id = $request->checkout_session_id;
        $stripe = new StripeClient("sk_test_51QBad1Bli9nlS8GVTqk4Uty9r2jQqd3WwJlYOrZJZmNPZQWZBqPR4VOJNVPWaZMO88CJT7H9fDoXkJuIp6fTDo1K00UkjRgzAt");

        $session = $stripe->checkout->sessions->retrieve($checkout_session_id);
        if ($session->status === 'open') {
            $stripe->checkout->sessions->expire($checkout_session_id);
        }
    }

    public function completeCheckoutSession(Request $request) {
        $checkout_session_id = $request->checkout_session_id;

        SoldItem::where('checkout_session_id', $checkout_session_id)
                ->first()
                ->update([
                    'session_completed' => true
                ]);
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use App\Services\ItemDetailService;
use App\Models\PaymentMethod;
use Stripe\StripeClient;
use App\Models\SoldItem;
use App\Models\Address;

/**
 * 購入ページ用コントローラークラス
 */
class PurchaseController extends Controller
{
    /**
     * 購入ページ表示
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        // 商品情報
        $item = ItemDetailService::getItemDetail($request->item_id);

        // 支払い方法一覧
        $paymentMethods = PaymentMethod::all()->pluck('name', 'id');

        // 配送先住所一覧
        $addresses = Address::where('user_id', $request->user()->id)->get();
        $addresses = $addresses->map->only('id', 'name', 'postcode', 'address', 'building');

        $profile = $request->user()->profile;
        $addresses->prepend([
            'id' => 0,
            'name' => $request->user()->name . '（プロフィール登録住所）',
            'postcode' => $profile->postcode,
            'address' => $profile->address,
            'building' => $profile->building
        ]);

        return Inertia::render('Purchase', [
            'item' => $item,
            'paymentMethods' => $paymentMethods,
            'addresses' => $addresses,
        ]);
    }

    /**
     * 決済完了後の確認処理
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function confirmPaymentIntentStatus(Request $request): RedirectResponse
    {
        // 不正アクセス対策
        if (!$request->payment_intent) { return to_route('top'); }

        try {
            $payment_intent_id = $request->payment_intent;
            $sold_item = SoldItem::where('payment_intent_id', $payment_intent_id)->first();
            $stripe = new StripeClient(config('stripe.stripe_secret'));

            // sold_itemレコードが意図したものか念のため確認
            if ($sold_item->user_id !== $request->user()->id || $sold_item->item_id !== $request->item_id) {
                return to_route('top');
            }

            $payment_intent = $stripe->paymentIntents->retrieve($payment_intent_id);

            switch ($payment_intent->status) {
                // クレジットカード決済の正常完了
                case 'succeeded':
                    $status = 'success';
                    $message = '購入処理が完了しました';
                    // payment_completedの更新処理
                    $sold_item->update(['payment_completed' => true]);
                    break;

                // 「コンビニ払い」「銀行振込」受付完了（この後ユーザーによる支払いが必要）
                case 'requires_action':
                    $status = 'success';
                    $message = '購入処理が完了しました';
                    break;

                // 支払い処理中（「カード」「コンビニ払い」「銀行振込」ではこのステータスは想定外）
                case 'processing':
                    $status = 'processing';
                    $message = '支払い処理中です。しばらくお待ちください';
                    break;

                // 支払い失敗（「カード」「コンビニ払い」「銀行振込」ではこのステータスは想定外）
                case 'requires_payment_method':
                    $status = 'error';
                    $message = '支払いに失敗しました。別の支払い方法をお試しください';
                    // sold_itemレコードの削除処理
                    $sold_item->delete();
                    break;

                // 上記何れにも該当しない場合（＝予期せぬ不明なエラー発生時）
                default:
                    $status = 'error';
                    $message = '問題が発生しました';
                    // sold_itemレコードの削除処理
                    $sold_item->delete();
                    break;
            }
        } catch (\Exception $e) {
            Log::error($e);
        }

        return to_route('purchase', $request->item_id)->with([
            'status' => $status,
            'message' => $message,
        ]);
    }
}

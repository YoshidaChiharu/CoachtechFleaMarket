<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Services\ItemDetailService;
use App\Models\PaymentMethod;
use Stripe\StripeClient;
use App\Models\Item;
use App\Models\SoldItem;
use App\Models\Address;

class PurchaseController extends Controller
{
    public function create(Request $request) {
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

    public function confirmPaymentIntentStatus(Request $request) {
        try {
            $payment_intent_id = $request->payment_intent;
            $sold_item = SoldItem::where('payment_intent_id', $payment_intent_id)->first();
            $stripe = new StripeClient(config('stripe.stripe_secret'));

            $payment_intent = $stripe->paymentIntents->retrieve($payment_intent_id);

            switch ($payment_intent->status) {
                case 'succeeded': // クレジットカード決済の正常完了
                    $status = 'success';
                    $message = '購入処理が完了しました';
                    // payment_completedの更新処理
                    $sold_item->update(['payment_completed' => true]);
                    break;

                case 'requires_action': // 「コンビニ払い」「銀行振込」受付完了（この後ユーザーによる支払いが必要）
                    $status = 'success';
                    $message = '購入処理が完了しました';
                    break;

                case 'processing': // 今回の実装「カード」「コンビニ払い」「銀行振込」ではこのステータスは想定外
                    $status = 'processing';
                    $message = '支払い処理中です。しばらくお待ちください';
                    break;

                case 'requires_payment_method': // 今回の実装「カード」「コンビニ払い」「銀行振込」ではこのステータスは想定外
                    $status = 'error';
                    $message = '支払いに失敗しました。別の支払い方法をお試しください';
                    // sold_itemレコードの削除処理
                    $sold_item->delete();
                    break;

                default: // 上記何れにも該当しない（＝予期せぬ不明なエラー発生時）の想定
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

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Stripe\StripeClient;
use App\Models\Item;
use App\Models\PaymentMethod;

class PaymentIntentController extends Controller
{
    public function createPaymentIntent(Request $request): JsonResponse {
        try {
            $user = $request->user();
            $item_id = $request->item_id;
            $stripe = new StripeClient("sk_test_51QBad1Bli9nlS8GVTqk4Uty9r2jQqd3WwJlYOrZJZmNPZQWZBqPR4VOJNVPWaZMO88CJT7H9fDoXkJuIp6fTDo1K00UkjRgzAt");

            // 登録住所の確認（無い場合はエラーを返す）
            $ship_postcode = $user->profile->postcode;
            $ship_address = $user->profile->address;
            if (!$ship_postcode || !$ship_address) {
                return response()->json([
                    'status' => 'error',
                    'message' => '配送先が正しく登録されていません'
                ], 500);
            }

            DB::beginTransaction();

            // 商品価格の取得
            $price = Item::find($item_id)->price;

            // Stripe顧客IDを取得（未作成の場合はここで作成）
            $customer_id = $user->stripe_customer_id;
            if ($customer_id === null) {
                $customer = $stripe->customers->create([
                    'name' => $user->name,
                    'email' => $user->email,
                ]);

                $user->update([
                    'stripe_customer_id' => $customer->id,
                ]);

                $customer_id = $customer->id;
            }

            // PaymentIntent作成
            $intent = $stripe->paymentIntents->create([
                'amount' => $price,
                'customer' => $customer_id,
                'currency' => 'jpy',
                'payment_method_types'   => [PaymentMethod::find($request->paymentMethodId)->payment_method_type],
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
            ]);

            DB::commit();

            return response()->json($intent);

        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'エラーが発生しました'
            ], 500);
        }
    }
}

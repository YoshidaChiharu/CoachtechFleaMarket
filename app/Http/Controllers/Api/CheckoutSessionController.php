<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Stripe\StripeClient;

class CheckoutSessionController extends Controller
{
    public function createCheckoutSession(Request $request): JsonResponse {
        $stripe = new StripeClient("sk_test_51QBad1Bli9nlS8GVTqk4Uty9r2jQqd3WwJlYOrZJZmNPZQWZBqPR4VOJNVPWaZMO88CJT7H9fDoXkJuIp6fTDo1K00UkjRgzAt");

       //Checkoutセッション作成
        $checkout = $stripe->checkout->sessions->create([
            'line_items'             => [[
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
            ],
            'payment_method_types'   => ['card']
        ]);

        return response()->json($checkout);
    }
}

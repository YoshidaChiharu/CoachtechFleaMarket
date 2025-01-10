<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Services\ItemDetailService;
use App\Models\PaymentMethod;

class PurchaseController extends Controller
{
    public function create(Request $request) {
        // 商品情報
        $item = ItemDetailService::getItemDetail($request->item_id);
        
        // 支払い方法一覧
        $paymentMethods = PaymentMethod::all()->pluck('name', 'id');

        // ユーザーの登録住所
        $profile = $request->user()->profile;
        $ship_address = [
            'postcode' => $profile->postcode,
            'address' => $profile->address,
            'building' => $profile->building
        ];

        return Inertia::render('Purchase', [
            'item' => $item,
            'paymentMethods' => $paymentMethods,
            'shipAddress' => $ship_address,
        ]);
    }
}

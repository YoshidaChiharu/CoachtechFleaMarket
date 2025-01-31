<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Item;
use App\Models\SoldItem;
use App\Models\Address;

class SoldItemController extends Controller
{
    public function confirmStock(Request $request) {
        try {
            $item = Item::find($request->item_id);

            return response()->json([
                'status' => 'success',
                'isSold' => $item->isSold(),
            ]);

        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 'error',
                'message' => 'エラーが発生しました'
            ], 500);
        }
    }

    public function store(Request $request) {
        try {
            $user = $request->user();

            // 配送先住所の取得
            $address_id = $request->addressId;
            if ($address_id == 0) {
                $ship_name = $user->name;
                $ship_postcode = $user->profile->postcode;
                $ship_address = $user->profile->address;
                $ship_building = $user->profile->building;
            }
            if ($address_id != 0) {
                $address = Address::find($address_id);

                $ship_name = $address->name;
                $ship_postcode = $address->postcode;
                $ship_address = $address->address;
                $ship_building = $address->building;
            }

            // sold_itemテーブルにレコード登録
            $sold_item = SoldItem::create([
                'user_id' => $user->id,
                'item_id' => $request->item_id,
                'payment_method_id' => $request->paymentMethodId,
                'payment_intent_id' => $request->paymentIntentId,
                'payment_completed' => false,
                'ship_name' => $ship_name,
                'ship_postcode' => $ship_postcode,
                'ship_address' => $ship_address,
                'ship_building' => $ship_building,
            ]);

            return response()->json([
                'status' => 'success',
                'soldItemId' => $sold_item->id,
            ]);

        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 'error',
                'message' => 'エラーが発生しました'
            ], 500);
        }
    }

    public function destroy(Request $request) {
        try {
            SoldItem::find($request->sold_item_id)->delete();
            return response()->json([
                'status' => 'success',
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 'error',
                'message' => 'エラーが発生しました'
            ], 500);
        }
    }
}

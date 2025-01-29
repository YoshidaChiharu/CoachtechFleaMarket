<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Item;
use App\Models\SoldItem;

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

            // sold_itemテーブルにレコード登録
            $sold_item = SoldItem::create([
                'user_id' => $user->id,
                'item_id' => $request->item_id,
                'payment_method_id' => $request->paymentMethodId,
                'payment_intent_id' => $request->paymentIntentId,
                'payment_completed' => false,
                'ship_postcode' => $user->profile->postcode,
                'ship_address' => $user->profile->address,
                'ship_building' => $user->profile->building,
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

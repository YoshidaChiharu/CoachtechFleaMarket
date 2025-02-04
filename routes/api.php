<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\PaymentIntentController;
use App\Http\Controllers\Api\SoldItemController;

Route::middleware('verified', 'auth')->group(function () {
    Route::group(['prefix' => 'api'], function () {
        // お気に入り関連
        Route::post('/like/{item_id}', [LikeController::class, 'store']);

        Route::delete('/like/{item_id}', [LikeController::class, 'destroy']);

        // 決済時のPaymentIntent作成処理
        Route::post('/purchase/{item_id}', [PaymentIntentController::class, 'createPaymentIntent']);

        // sold_itemsテーブル操作関連
        Route::get('/purchase/sold_item/{item_id}', [SoldItemController::class, 'confirmStock']);

        Route::post('/purchase/sold_item/{item_id}', [SoldItemController::class, 'store']);

        Route::delete('/purchase/sold_item/{sold_item_id}', [SoldItemController::class, 'destroy']);
    });
});

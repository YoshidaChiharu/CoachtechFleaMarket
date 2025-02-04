<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopPageController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemDetailController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ShipAddressController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminCommentController;
use App\Http\Controllers\Admin\AdminMailController;

/*
|--------------------------------------------------------------------------
| 全ユーザー向けページ
|--------------------------------------------------------------------------
*/
Route::get('/', [TopPageController::class, 'index'])
    ->name('top');

Route::get('/item/{item_id}', [ItemDetailController::class, 'show'])
    ->name('item.detail');

/*
|--------------------------------------------------------------------------
| ログイン済みユーザー向けページ
|--------------------------------------------------------------------------
*/
Route::middleware('verified', 'auth')->group(function () {
    // トップページ(マイリスト)
    Route::get('/mylist', [TopPageController::class, 'showMylist'])
        ->name('top.mylist');

    // マイページ
    Route::get('/mypage', [MyPageController::class, 'index'])
        ->name('mypage');

    Route::get('/mypage/profile', [ProfileController::class, 'edit'])
        ->name('mypage.profile');

    Route::post('/mypage/profile', [ProfileController::class, 'update']);

    // 商品詳細＞コメントページ
    Route::get('/item/comment/{item_id}', [CommentController::class, 'index'])
        ->name('item.comment');

    Route::post('/item/comment/{item_id}', [CommentController::class, 'store']);

    // 購入ページ
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'create'])
        ->name('purchase');

    Route::get('/purchase/complete/{item_id}', [PurchaseController::class, 'confirmPaymentIntentStatus'])
        ->name('purchase.complete');

    // 購入ページ＞配送先変更
    Route::get('/purchase/address/register', [ShipAddressController::class, 'create'])
        ->name('purchase.address.register');

    Route::post('/purchase/address/register', [ShipAddressController::class, 'store']);

    Route::get('/purchase/address/edit/{address_id}', [ShipAddressController::class, 'edit'])
        ->name('purchase.address.edit');

    Route::post('/purchase/address/edit/{address_id}', [ShipAddressController::class, 'update']);

    Route::delete('/purchase/address/edit/{address_id}', [ShipAddressController::class, 'destroy']);

    // 出品ページ
    Route::get('/sell', [SellController::class, 'create'])
        ->name('sell');

    Route::post('/sell', [SellController::class, 'store']);
});

/*
|--------------------------------------------------------------------------
| 管理者専用ページ
|--------------------------------------------------------------------------
*/
Route::middleware('auth', 'admin')->group(function () {
    // ユーザー管理ページ
    Route::get('/admin/user', [AdminUserController::class, 'index'])
        ->name('admin.user');

    Route::post('/admin/user', [AdminUserController::class, 'destroy']);

    // コメント管理ページ
    Route::get('/admin/comment', [AdminCommentController::class, 'index'])
        ->name('admin.comment');

    Route::post('/admin/comment', [AdminCommentController::class, 'destroy']);

    // メール送信ページ
    Route::get('/admin/mail', [AdminMailController::class, 'create'])
        ->name('admin.mail');

    Route::post('/admin/mail', [AdminMailController::class, 'sendMail']);
});

require __DIR__.'/auth.php'; // 認証関連ルーティング
require __DIR__.'/api.php';  // API用ルーティング

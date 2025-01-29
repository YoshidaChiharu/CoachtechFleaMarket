<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\TopPageController;
use App\Http\Controllers\Api\LikeController;
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
use App\Http\Controllers\Api\PaymentIntentController;
use App\Http\Controllers\Api\SoldItemController;

// Route::get('/test', function () { return Inertia::render('Test'); });

Route::get('/', [TopPageController::class, 'index'])->name('top');
Route::get('/item/{item_id}', [ItemDetailController::class, 'show'])->name('item.detail');

// 一般ユーザー向けページ
Route::middleware('verified', 'auth')->group(function () {
    Route::get('/mylist', [TopPageController::class, 'showMylist'])->name('top.mylist');
    Route::post('/api/like/{item_id}', [LikeController::class, 'store']);
    Route::delete('/api/like/{item_id}', [LikeController::class, 'destroy']);
    Route::get('/mypage', [MyPageController::class, 'index'])->name('mypage');
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('mypage.profile');
    Route::post('/mypage/profile', [ProfileController::class, 'update']);
    Route::get('/item/comment/{item_id}', [CommentController::class, 'index'])->name('item.comment');
    Route::post('/item/comment/{item_id}', [CommentController::class, 'store']);

    Route::get('/purchase/{item_id}', [PurchaseController::class, 'create'])->name('purchase');
    Route::post('/api/purchase/{item_id}', [PaymentIntentController::class, 'createPaymentIntent']);
    Route::get('/api/purchase/sold_item/{item_id}', [SoldItemController::class, 'confirmStock']);
    Route::post('/api/purchase/sold_item/{item_id}', [SoldItemController::class, 'store']);
    Route::delete('/api/purchase/sold_item/{sold_item_id}', [SoldItemController::class, 'destroy']);
    Route::get('/purchase/complete/{item_id}', [PurchaseController::class, 'confirmPaymentIntentStatus'])->name('purchase.complete');

    Route::get('/purchase/address/{item_id}', [ShipAddressController::class, 'edit'])->name('purchase.address');
    Route::post('/purchase/address/{item_id}', [ShipAddressController::class, 'update']);
    Route::get('/sell', [SellController::class, 'create'])->name('sell');
    Route::post('/sell', [SellController::class, 'store']);
});

// 管理者専用ページ
Route::middleware('auth', 'admin')->group(function () {
    Route::get('/admin/user', [AdminUserController::class, 'index'])->name('admin.user');
    Route::post('/admin/user', [AdminUserController::class, 'destroy']);
    Route::get('/admin/comment', [AdminCommentController::class, 'index'])->name('admin.comment');
    Route::post('/admin/comment', [AdminCommentController::class, 'destroy']);
    Route::get('/admin/mail', [AdminMailController::class, 'create'])->name('admin.mail');
    Route::post('/admin/mail', [AdminMailController::class, 'sendMail']);
});

require __DIR__.'/auth.php';

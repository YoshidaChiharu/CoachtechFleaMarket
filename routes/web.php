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
use App\Http\Controllers\Api\CheckoutSessionController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ShipAddressController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminCommentController;

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/', [TopPageController::class, 'index'])->name('top');
Route::get('/item/{item_id}', [ItemDetailController::class, 'show'])->name('item.detail');

// Route::get('/test', function () { return Inertia::render('Test'); });

// 一般ユーザー向けページ
Route::middleware('auth')->group(function () {
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/mylist', [TopPageController::class, 'showMylist'])->name('top.mylist');
    Route::post('/api/like/{item_id}', [LikeController::class, 'store']);
    Route::delete('/api/like/{item_id}', [LikeController::class, 'destroy']);
    Route::get('/mypage', [MyPageController::class, 'index'])->name('mypage');
    Route::get('/mypage/purchased', [MyPageController::class, 'showPurchased'])->name('mypage.purchased');
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('mypage.profile');
    Route::post('/mypage/profile', [ProfileController::class, 'update']);
    Route::get('/item/comment/{item_id}', [CommentController::class, 'index'])->name('item.comment');
    Route::post('/item/comment/{item_id}', [CommentController::class, 'store']);
    Route::post('/api/purchase/{item_id}', [CheckoutSessionController::class, 'createCheckoutSession']);
    Route::delete('/api/purchase/{checkout_session_id}', [CheckoutSessionController::class, 'expireCheckoutSession']);
    Route::post('/api/purchase/completed/{checkout_session_id}', [CheckoutSessionController::class, 'completeCheckoutSession']);
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'create'])->name('purchase');
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
});

require __DIR__.'/auth.php';

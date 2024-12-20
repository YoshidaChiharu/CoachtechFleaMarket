<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\TopPageController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\MyPageController;

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/', [TopPageController::class, 'index'])->name('top');

// Route::get('/test', function () {
//     return Inertia::render('Test');
// });

Route::middleware('auth')->group(function () {
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/mylist', [TopPageController::class, 'showMylist'])->name('top.mylist');
    Route::post('/api/like/{item_id}', [LikeController::class, 'store']);
    Route::delete('/api/like/{item_id}', [LikeController::class, 'destroy']);
    Route::get('/mypage', [MyPageController::class, 'index'])->name('mypage');
    Route::get('/mypage/purchased', [MyPageController::class, 'showPurchased'])->name('mypage.purchased');
});

require __DIR__.'/auth.php';

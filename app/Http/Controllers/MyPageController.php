<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Item;
use Inertia\Inertia;
use App\Services\ItemService;

class MyPageController extends Controller
{
    public function index(Request $request) {
        // 出品済み商品を取得（お気に入り商品かどうかの「is_like」フラグ付き）
        $item_service = new ItemService;
        $items = $item_service->getSellItemsWithLike();

        // ページネーション
        $items = new LengthAwarePaginator
        (
            $items->forPage($request->page, 20),
            $items->count(),
            20,
            $request->page,
            ['path' => $request->url()]
        );

        return Inertia::render('MyPage', [
            'items' => $items
        ]);
    }

    public function showPurchased(Request $request) {
        // 購入済み商品を取得（お気に入り商品かどうかの「is_like」フラグ付き）
        $item_service = new ItemService;
        $items = $item_service->getPurchasedItemsWithLike();

        // ページネーション
        $items = new LengthAwarePaginator
        (
            $items->forPage($request->page, 20),
            $items->count(),
            20,
            $request->page,
            ['path' => $request->url()]
        );

        return Inertia::render('MyPage', [
            'items' => $items
        ]);
    }

}

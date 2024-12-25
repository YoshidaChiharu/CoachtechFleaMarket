<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Item;
use Inertia\Inertia;
use Inertia\Response;
use App\Services\ItemService;

class TopPageController extends Controller
{
    public function index(Request $request) {
        // 全商品を取得（お気に入り商品かどうかの「is_like」フラグ付き）
        $item_service = new ItemService;
        if ($request->searchWord) {
            $items = $item_service->searchItemsWithLike($request->searchWord);
        } else {
            $items = $item_service->getAllItemsWithLike();
        }

        // ページネーション
        $items = new LengthAwarePaginator
        (
            $items->forPage($request->page, 20),
            $items->count(),
            20,
            $request->page,
            ['path' => $request->url()]
        );

        return Inertia::render('Top', [
            'items' => $items
        ]);
    }

    public function showMylist(Request $request) {
        // お気に入り登録商品を取得
        $item_service = new ItemService;
        $items = $item_service->getLikeItemsWithLike();

        // ページネーション
        $items = new LengthAwarePaginator
        (
            $items->forPage($request->page, 20),
            $items->count(),
            20,
            $request->page,
            ['path' => $request->url()]
        );

        return Inertia::render('Top', [
            'items' => $items
        ]);
    }
}

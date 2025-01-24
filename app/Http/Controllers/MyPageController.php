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
        // 商品一覧の取得処理
        $item_service = new ItemService;

        if ($request->filter === 'purchased') {
            // 購入済み商品を取得（お気に入り商品かどうかの「is_like」フラグ付き）
            $items = $item_service->getPurchasedItemsWithLike();
        } else {
            // 出品済み商品を取得（お気に入り商品かどうかの「is_like」フラグ付き）
            $items = $item_service->getSellItemsWithLike();
        }

        // ページネーション
        $items = new LengthAwarePaginator
        (
            $items->forPage($request->page, 20),
            $items->count(),
            20,
            $request->page,
            [
                'path' => request()->url() .
                (
                    request()->except('page')
                    ? '?' .  http_build_query(request()->except('page'))
                    : ''
                )
            ]
        );

        return Inertia::render('MyPage', [
            'items' => $items,
            'user' => [
                'name' => $request->user()->name,
                'image_url' => $request->user()->profile->image_url,
            ],
        ]);
    }
}

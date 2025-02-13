<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use Inertia\Response;
use App\Services\ItemService;

/**
 * トップページ用コントローラークラス
 */
class TopPageController extends Controller
{
    /**
     * トップページ（おすすめ）の表示
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        // 全商品を取得（お気に入り商品かどうかの「is_like」フラグ付き）
        $item_service = new ItemService;
        if ($request->searchWord) {
            $items = $item_service->searchItemsWithLike($request->searchWord);
        } else {
            $items = $item_service->getAllItemsWithLike();
        }

        // ページネーション
        $path = $request->url();
        if ($request->searchWord) {
            $path = $path . "/?searchWord=" . $request->searchWord;
        }
        $items = new LengthAwarePaginator
        (
            $items->forPage($request->page, 20),
            $items->count(),
            20,
            $request->page,
            ['path' => $path ]
        );

        return Inertia::render('Top', [
            'items' => $items
        ]);
    }

    /**
     * トップページ（マイリスト）の表示
     *
     * @param Request $request
     * @return Response
     */
    public function showMylist(Request $request): Response
    {
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

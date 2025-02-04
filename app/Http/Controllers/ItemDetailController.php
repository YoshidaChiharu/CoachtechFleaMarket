<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Services\ItemDetailService;

/**
 * アイテム詳細ページ用コントローラークラス
 */
class ItemDetailController extends Controller
{
    /**
     * アイテム詳細ページ表示
     *
     * @param Request $request
     * @return Response
     */
    public function show(Request $request): Response
    {
        $item = ItemDetailService::getItemDetail($request->item_id);

        return Inertia::render('ItemDetail', [
            'item' => $item
        ]);
    }
}

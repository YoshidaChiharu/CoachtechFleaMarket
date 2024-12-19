<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Like;
use Inertia\Inertia;
use Inertia\Response;

class TopPageController extends Controller
{
    public function index() {
        $items = Item::all();

        // $items配列内に 'is_like' フラグを追加
        $items->map(function ($item) {
            $item['is_like'] = false;
            return $item;
        });

        // お気に入り登録商品を判別
        if(Auth::user()) {
            $like_item_ids = Auth::user()->likeItems->pluck('id')->toArray();

            // お気に入り商品なら$item['is_like']をtrueへ変更
            foreach ($items as $item) {
                if (in_array($item->id, $like_item_ids)) {
                    $item['is_like'] = true;
                }
            }
        }

        $items->toArray();

        return Inertia::render('Top', [
            'items' => $items
        ]);
    }

    public function showMylist() {
        $items = Auth::user()->likeItems;

        // $items配列内に 'is_like' フラグを追加
        $items->map(function ($item) {
            $item['is_like'] = true;
            return $item;
        });

        $items->toArray();

        return Inertia::render('Top', [
            'items' => $items
        ]);
    }
}

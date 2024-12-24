<?php
namespace App\Services;

use \Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;

class ItemService
{
    private $items;

    /**
     * 全商品を取得する（お気に入り商品判別フラグ付き）
     *
     * @return Collection
     */
    public function getAllItemsWithLike() : Collection {
        $this->items = Item::all();

        // is_likeフラグを付与
        $this->withLike();

        return $this->items;
    }

    /**
     * お気に入り登録商品を取得する
     *
     * @return Collection
     */
    public function getLikeItemsWithLike() : Collection {
        $this->items = Auth::user()->likeItems;

        // is_likeフラグを付与
        $this->withLike();

        return $this->items;
    }

    /**
     * 自身が出品した商品を取得する（お気に入り商品判別フラグ付き）
     *
     * @return Collection
     */
    public function getSellItemsWithLike() : Collection {
        $this->items = Auth::user()->items;

        // is_likeフラグを付与
        $this->withLike();

        return $this->items;
    }

    /**
     * 自身が購入した商品を取得する（お気に入り商品判別フラグ付き）
     *
     * @return Collection
     */
    public function getPurchasedItemsWithLike() : Collection {
        $this->items = Auth::user()->purchasedItems;

        // is_likeフラグを付与
        $this->withLike();

        return $this->items;
    }

    /**
     * 文字列検索して該当する商品を取得する
     * （検索対象：商品名／商品説明文／カテゴリー／商品の状態）
     *
     * @param string $search_word 検索文字列
     * @return Collection
     */
    public function searchItemsWithLike(string $search_word) : Collection {
        // ここに検索処理を書く

        return $this->items;
    }

    /**
     * itemsにお気に入り商品かどうかの判別フラグ is_like を付与する
     *
     * @return void
     */
    private function withLike() : void {
        // $items配列内に 'is_like' フラグを追加
        $this->items->map(function ($item) {
            $item['is_like'] = $item->isLike();
            return $item;
        });
    }
}
<?php
namespace App\Services;

use \Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Category;
use Ramsey\Uuid\Type\Integer;

/**
 * 商品情報取得用サービスクラス
 * 
 * @property Collection $items 商品一覧のコレクション
 * @method Collection getAllItemsWithLike() 全商品取得メソッド
 * @method Collection getLikeItemsWithLike() お気に入り登録商品取得メソッド
 * @method Collection getSellItemsWithLike() 出品済み商品取得メソッド
 * @method Collection getPurchasedItemsWithLike() 購入済み商品取得メソッド
 * @method Collection searchItemsWithLike(string $search_word) 文字列検索メソッド
 * @method private void withLike() お気に入り商品判別フラグ付与メソッド
 */
class ItemService
{
    private Collection $items;

    /**
     * 全商品を取得する（お気に入り商品判別フラグ付き）
     *
     * @return Collection
     */
    public function getAllItemsWithLike() : Collection {
        $this->items = Item::all();

        // is_likeフラグを付与（ログイン済みの場合のみ）
        if (Auth::user()) {
            $this->withLike();
        }

        // is_soldフラグを付与
        $this->withSold();

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

        // is_soldフラグを付与
        $this->withSold();

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

        // is_soldフラグを付与
        $this->withSold();

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

        // is_soldフラグを付与
        $this->withSold();

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
        // [商品名] [商品説明文] 検索
        $this->items = Item::where('name', 'like', "%{$search_word}%")
                           ->orWhere('description', 'like', "%{$search_word}%")
                           ->get();

        // [カテゴリー] 検索
        $results = Category::where('name', 'like', "%{$search_word}%")->get();
        foreach ($results as $result) {
            $this->items = ($this->items)->merge($result->items);
        }

        // [商品の状態] 検索
        $results = Condition::where('name', 'like', "%{$search_word}%")->get();
        foreach ($results as $result) {
            $this->items = ($this->items)->merge($result->items);
        }

        // is_likeフラグを付与
        $this->withLike();

        // is_soldフラグを付与
        $this->withSold();

        return $this->items;
    }

    /**
     * itemsにログインユーザーのお気に入り商品かどうかの判別フラグ is_like を付与する
     *
     * @return void
     */
    private function withLike(): void {
        // $items配列内に 'is_like' フラグを追加
        $this->items->map(function ($item) {
            $item['is_like'] = $item->isLike();
            return $item;
        });
    }

    private function withSold(): void {
        // $items配列内に 'is_sold' フラグを追加
        $this->items->map(function ($item) {
            $item['is_sold'] = $item->isSold();
            return $item;
        });
    }
}
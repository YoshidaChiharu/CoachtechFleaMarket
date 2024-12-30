<?php
namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\Item;

/**
 * 商品詳細ページの表示情報取得用サービスクラス
 *
 * @method static Array getItemDetail() 全商品取得メソッド
 */
class ItemDetailService
{
    public static function getItemDetail(int $item_id): Array {
        $item = Item::where('id', $item_id)->first();
        $item_detail = $item->toArray();

        // 売却済みフラグ
        $item_detail['is_sold'] = $item->soldItem->isNotEmpty();

        // お気に入り登録有無フラグ
        $item_detail['is_like'] = $item->isLike();

        // お気に入り登録者数
        $item_detail['likes_count'] = $item->likes->count();

        // コメント情報
        $comments = $item->comments;
        foreach ($comments as $comment) {
            $item_detail['comments'][] = [
                'name' => $comment->user->profile->name,
                'image_url' => $comment->user->profile->image_url,
                'comment' => $comment->comment,
                'is_mine' => ($comment->user_id == Auth::id()),
            ];
        }

        // コメント数
        $item_detail['comments_count'] = $comments->count();

        // カテゴリー情報
        $item_detail['category_names'] = $item->categories->pluck('name')->toArray();

        // 商品の状態
        $item_detail['condition_name'] = $item->condition->name;

        // 出品者情報
        $item_detail['user_name'] =  $item->user->profile->name;

        return $item_detail;
    }
}
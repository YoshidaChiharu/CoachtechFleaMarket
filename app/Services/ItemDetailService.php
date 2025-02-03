<?php
namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\Item;

/**
 * 商品詳細ページの表示情報取得用サービスクラス
 *
 * @method static Array getItemDetail() 商品の詳細情報取得メソッド
 */
class ItemDetailService
{
    /**
     * 商品の詳細情報を纏めた配列を取得
     *
     * @param integer $item_id
     * @return Array{
     *      id: integer              商品ID,
     *      name: string             商品名,
     *      brand: string|null       ブランド名,
     *      price: integer           商品価格,
     *      description: string      商品説明文,
     *      image_url: string        商品画像のURL,
     *      condition_id: integer    商品状態を表すID,
     *      user_id: integer         出品者のユーザーID,
     *      stripe_price_id: string  Stripeの価格情報のID,
     *      created_at: Carbon|null  商品情報の登録日,
     *      updated_at: Carbon|null  商品情報の更新日,
     *      deleted_at: Carbon|null  商品情報の削除日,
     *      is_sold: boolean         商品が売却済みかどうかのフラグ,
     *      is_like: boolean         ログインユーザーがお気に入り登録済みかどうかのフラグ,
     *      likes_count: integer     商品をお気に入り登録している人数,
     *      comments: array{
     *          name: string           コメント投稿者のユーザー名,
     *          image_url: string      コメント投稿者のアイコン画像URL,
     *          comment: string        コメント本文,
     *          is_mine: boolean       ログインユーザー本人が投稿したコメントかどうかのフラグ,
     *      }[],
     *      comments_count: integer  コメント数,
     *      category_names: string[] 設定されているカテゴリー名の配列,
     *      condition_name: string   設定されている商品状態の文字列,
     *      user_name: string        出品者のユーザー名,
     * }
     */
    public static function getItemDetail(int $item_id): Array {
        $item = Item::where('id', $item_id)->first();
        $item_detail = $item->toArray();

        // 売却済みフラグ
        $item_detail['is_sold'] = $item->isSold();

        // お気に入り登録有無フラグ
        $item_detail['is_like'] = $item->isLike();

        // お気に入り登録者数
        $item_detail['likes_count'] = $item->likes->count();

        // コメント情報
        $comments = $item->comments;
        foreach ($comments as $comment) {
            $item_detail['comments'][] = [
                'name' => $comment->user->name ?? 'Unknown User',
                'image_url' => $comment->user->profile->image_url ?? '/img/default_user_icon.png',
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
        $item_detail['user_name'] =  $item->user->name;

        dd($item_detail);
        return $item_detail;
    }
}
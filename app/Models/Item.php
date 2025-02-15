<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * itemsテーブルモデル
 *
 * @property int $id
 * @property string $name
 * @property string|null $brand
 * @property int $price
 * @property string $description
 * @property string $image_url
 * @property string $image_path
 * @property int $condition_id
 * @property int $user_id
 * @property string $stripe_price_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Category> $categories
 * @property-read int|null $categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\Condition $condition
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Like> $likes
 * @property-read int|null $likes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $purchasedUser
 * @property-read int|null $purchased_user_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SoldItem> $soldItems
 * @property-read int|null $sold_items_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereConditionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereStripePriceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item withoutTrashed()
 * @mixin \Eloquent
 */
class Item extends Model
{
    use SoftDeletes;

    /**
     * モデルの"booted"メソッド
     *
     * @return void
     */
    protected static function booted(): void
    {
        // 商品削除時にリレーション先も併せて削除
        static::deleting(function (Item $item) {
            $item->likes()->delete();
            $item->categories()->detach();
            $item->comments()->delete();
        });
    }

    /**
     * 変更不可プロパティ
     *
     * @var list<string>
     */
    protected $guarded = ['id'];

    /*
    |--------------------------------------------------------------------------
    | アクセサ・ミューテタ
    |--------------------------------------------------------------------------
    */

    /**
     * image_path 取得
     * image_url のパス部分のみを抽出
     *
     * @param [type] $value
     * @return string
     */
    public function getImagePathAttribute($value) : string
    {
        return str_replace("https://coachtechfleamarket-bucket-20250215.s3.ap-northeast-1.amazonaws.com", "", $this->image_url);
    }

    /*
    |--------------------------------------------------------------------------
    | リレーション
    |--------------------------------------------------------------------------
    */

    /**
     * 商品に紐づくお気に入り情報を取得
     *
     * @return HasMany
     */
    public function likes(): HasMany {
        return $this->hasMany('App\Models\Like');
    }

    /**
     * 出品者を取得
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * 購入者を取得
     *
     * @return BelongsToMany
     */
    public function purchasedUser(): BelongsToMany {
        return $this->belongsToMany('App\Models\User', 'sold_items');
    }

    /**
     * 商品に紐づく購入済み情報の取得
     *
     * @return HasMany
     */
    public function soldItems(): HasMany {
        return $this->hasMany('App\Models\SoldItem');
    }

    /**
     * 商品に設定されているカテゴリー一覧の取得
     *
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany {
        return $this->belongsToMany('App\Models\Category');
    }

    /**
     * 商品に設定されている商品状態（未使用or良好or傷／汚れあり）の取得
     *
     * @return BelongsTo
     */
    public function condition(): BelongsTo {
        return $this->belongsTo('App\Models\Condition');
    }

    /**
     * 商品に投稿されているコメント一覧の取得
     *
     * @return HasMany
     */
    public function comments(): HasMany {
        return $this->hasMany('App\Models\Comment');
    }

    /*
    |--------------------------------------------------------------------------
    | その他自作メソッド
    |--------------------------------------------------------------------------
    */

    /**
     * ログインユーザーがお気に入り登録している商品かどうかの判別メソッド
     *
     * @return boolean
     */
    public function isLike(): bool {
        $like = $this->likes->where('user_id', Auth::user()->id ?? null);
        return $like->isNotEmpty();
    }

    /**
     * 商品が売却済みかどうかの判別メソッド
     *
     * @return boolean
     */
    public function isSold(): bool {
        $sold_items = $this->soldItems;
        return $sold_items->isNotEmpty();
    }
}

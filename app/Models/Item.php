<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
 * @property int $condition_id
 * @property int $user_id
 * @property string $stripe_price_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Category> $categories
 * @property-read int|null $categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $commentUsers
 * @property-read int|null $comment_users_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\Condition $condition
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $likeUsers
 * @property-read int|null $like_users_count
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

    protected $guarded = ['id'];

    public function likes(): HasMany {
        return $this->hasMany('App\Models\Like');
    }

    public function likeUsers(): BelongsToMany {
        return $this->belongsToMany('App\Models\User', 'likes');
    }

    public function user(): BelongsTo {
        return $this->belongsTo('App\Models\User');
    }

    public function purchasedUser(): BelongsToMany {
        return $this->belongsToMany('App\Models\User', 'sold_items');
    }

    public function soldItems(): HasMany {
        return $this->hasMany('App\Models\SoldItem');
    }

    public function categories(): BelongsToMany {
        return $this->belongsToMany('App\Models\Category');
    }

    // public function categoryItems(): HasMany {
    //     return $this->hasMany('App\Models\Category_item');
    // }

    public function condition(): BelongsTo {
        return $this->belongsTo('App\Models\Condition');
    }

    public function comments(): HasMany {
        return $this->hasMany('App\Models\Comment');
    }

    public function commentUsers(): BelongsToMany {
        return $this->belongsToMany('App\Models\User', 'comments');
    }

    /**
     * ログインユーザーがお気に入り登録している商品かどうかの判別メソッド
     *
     * @return boolean
     */
    public function isLike(): bool {
        $like = $this->likes->where('user_id', Auth::user()->id ?? null);
        return $like->isNotEmpty();
    }

    public function isSold(): bool {
        $sold_items = $this->soldItems;
        return $sold_items->isNotEmpty();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        $sold_items = $this->soldItems->where('session_completed', true);
        return $sold_items->isNotEmpty();
    }
}

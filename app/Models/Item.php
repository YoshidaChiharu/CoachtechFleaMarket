<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Item extends Model
{
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

    public function soldItem (): HasMany {
        return $this->hasMany('App\Models\SoldItem');
    }

    public function categories (): BelongsToMany {
        return $this->belongsToMany('App\Models\Category');
    }

    public function condition (): BelongsTo {
        return $this->belongsTo('App\Models\Condition');
    }

    public function comments (): HasMany {
        return $this->hasMany('App\Models\Comment');
    }

    public function commentUsers (): BelongsToMany {
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
}

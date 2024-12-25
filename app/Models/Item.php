<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Item extends Model
{
    protected $guarded = ['id'];

    public function likes() {
        return $this->hasMany('App\Models\Like');
    }

    public function likeUsers() {
        return $this->belongsToMany('App\Models\User', 'likes');
    }

    public function user() {
        return $this->hasOne('App\Models\User');
    }

    public function purchasedUser() {
        return $this->belongsToMany('App\Models\User', 'sold_items');
    }

    public function categories () {
        return $this->belongsToMany('App\Models\Category');
    }

    /**
     * お気に入り登録された商品かどうかの判別メソッド
     *
     * @return boolean
     */
    public function isLike() : bool {
        $like = $this->likes->where('user_id', Auth::user()->id);
        return $like->isNotEmpty();
    }
}

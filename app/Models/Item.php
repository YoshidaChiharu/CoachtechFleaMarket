<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $guarded = ['id'];

    public function likes() {
        return $this->hasMany('App\Models\Like');
    }

    public function likeUsers() {
        return $this->belongsToMany('App\Models\User', 'likes');
    }
}

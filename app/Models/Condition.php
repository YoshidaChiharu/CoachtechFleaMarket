<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    protected $guarded = ['id'];

    public function items() {
        return $this->hasMany('App\Models\Item');
    }
}

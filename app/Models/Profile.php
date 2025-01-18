<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function user() {
        return $this->hasOne('App\Models\User');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoldItem extends Model
{
    protected $guarded = ['id'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'session_completed' => 'boolean',
        ];
    }
}

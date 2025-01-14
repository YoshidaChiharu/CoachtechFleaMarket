<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
        'stripe_customer_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function likes(): HasMany {
        return $this->hasMany('App\Models\Like');
    }

    public function likeItems(): BelongsToMany {
        return $this->belongsToMany('App\Models\Item', 'likes');
    }

    public function items(): HasMany {
        return $this->hasMany('App\Models\Item');
    }

    public function checkoutItems(): BelongsToMany {
        return $this->belongsToMany('App\Models\Item', 'sold_items')
                    ->withPivot('session_completed');
    }

    public function profile(): HasOne {
        return $this->hasOne('App\Models\Profile');
    }

    public function comments(): HasMany {
        return $this->hasMany('App\Models\Comment');
    }

    public function getPurchasedItems() {
        $items = $this->checkoutItems->filter(function($item) {
            return $item->pivot->session_completed;
        });
        return $items;
    }
}

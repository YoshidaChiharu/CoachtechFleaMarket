<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;

/**
 * usersテーブルモデル
 *
 * @property int $id
 * @property int $role_id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $stripe_customer_id
 * @property string|null $remember_token
 * @property string $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Item> $purchasedItems
 * @property-read int|null $checkout_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Item> $items
 * @property-read int|null $items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Item> $likeItems
 * @property-read int|null $like_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Like> $likes
 * @property-read int|null $likes_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Profile|null $profile
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User searchCreateAt(string|null $date)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User searchEmail(string|null $email)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User searchId(int|null $id)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User searchName(string|null $name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStripeCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTrashed()
 * @mixin \Eloquent
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * 変更可能プロパティ
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
     * 非表示プロパティ
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * キャストするプロパティ
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

    /**
     * モデルの"booted"メソッド
     *
     * @return void
     */
    protected static function booted(): void
    {
        // ユーザー削除時にリレーション先も併せて削除
        static::deleting(function (User $user) {
            $user->profile()->delete();
            $user->likes()->delete();
            $user->items->each(function($item){
                $item->delete();
            });
        });
    }

    /*
    |--------------------------------------------------------------------------
    | アクセサ・ミューテタ
    |--------------------------------------------------------------------------
    */

    /**
     * Created_at カラムのアクセサ
     * 「yyyy-mm-dd hh:ii:ss」形式の文字列として取得
     *
     * @param [type] $value
     * @return string
     */
    public function getCreatedAtAttribute($value) : string
    {
        return Carbon::parse($value)->timezone('Asia/Tokyo')->format('Y-m-d H:i:s');
    }

    /*
    |--------------------------------------------------------------------------
    | リレーション
    |--------------------------------------------------------------------------
    */

    /**
     * お気に入り情報の取得
     *
     * @return HasMany
     */
    public function likes(): HasMany {
        return $this->hasMany('App\Models\Like');
    }

    /**
     * お気に入り登録済み商品の取得
     *
     * @return BelongsToMany
     */
    public function likeItems(): BelongsToMany {
        return $this->belongsToMany('App\Models\Item', 'likes');
    }

    /**
     * 出品済み商品の取得
     *
     * @return HasMany
     */
    public function items(): HasMany {
        return $this->hasMany('App\Models\Item');
    }

    /**
     * 購入した商品の取得
     *
     * @return BelongsToMany
     */
    public function purchasedItems(): BelongsToMany {
        return $this->belongsToMany('App\Models\Item', 'sold_items');
    }

    /**
     * プロフィール情報の取得
     *
     * @return HasOne
     */
    public function profile(): HasOne {
        return $this->hasOne('App\Models\Profile');
    }

    /**
     * 投稿済みコメント情報の取得
     *
     * @return HasMany
     */
    public function comments(): HasMany {
        return $this->hasMany('App\Models\Comment');
    }

    /*
    |--------------------------------------------------------------------------
    | ローカルスコープ
    |--------------------------------------------------------------------------
    */

    /**
     * ユーザーIDでの検索
     *
     * @param Builder $query
     * @param integer|null $id
     * @return Builder|User
     */
    public function scopeSearchId(Builder $query, int|null $id): Builder|User {
        if (isset($id)) {
            return $query->where('id', $id);
        } else {
            return $query;
        }
    }

    /**
     * ユーザー名での検索
     *
     * @param Builder $query
     * @param string|null $name
     * @return Builder|User
     */
    public function scopeSearchName(Builder $query, string|null $name): Builder|User {
        if (isset($name)) {
            return $query->where('name', 'like', "%{$name}%");
        } else {
            return $query;
        }
    }

    /**
     * メールアドレスでの検索
     *
     * @param Builder $query
     * @param string|null $email
     * @return Builder|User
     */
    public function scopeSearchEmail(Builder $query, string|null $email): Builder|User {
        if (isset($email)) {
            return $query->where('email', 'like', "%{$email}%");
        } else {
            return $query;
        }
    }

    /**
     * ユーザー登録日での検索
     *
     * @param Builder $query
     * @param string|null $date
     * @return Builder|User
     */
    public function scopeSearchCreateAt(Builder $query, string|null $date): Builder|User {
        if (isset($date)) {
            return $query->where('created_at', 'like', "{$date}%");
        } else {
            return $query;
        }
    }

}

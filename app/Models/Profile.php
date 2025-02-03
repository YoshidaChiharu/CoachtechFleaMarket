<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * profilesテーブルモデル
 *
 * @property int $id
 * @property int $user_id
 * @property string $image_url
 * @property string|null $postcode
 * @property string|null $address
 * @property string|null $building
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereBuilding($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile wherePostcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile withoutTrashed()
 * @mixin \Eloquent
 */
class Profile extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function user() {
        return $this->hasOne('App\Models\User');
    }
}

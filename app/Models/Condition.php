<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * conditionsテーブルモデル
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Item> $items
 * @property-read int|null $items_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Condition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Condition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Condition query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Condition whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Condition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Condition whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Condition whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Condition extends Model
{
    /**
     * 変更不可プロパティ
     *
     * @var list<string>
     */
    protected $guarded = ['id'];

    /*
    |--------------------------------------------------------------------------
    | リレーション
    |--------------------------------------------------------------------------
    */

    /**
     * 該当の商品状態（未使用or良好or傷／汚れあり）が設定されている商品を一覧取得
     *
     * @return HasMany
     */
    public function items(): HasMany {
        return $this->hasMany('App\Models\Item');
    }
}

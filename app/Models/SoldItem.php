<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * sold_itemsテーブルモデル
 *
 * @property int $id
 * @property int $user_id
 * @property int $item_id
 * @property int $payment_method_id
 * @property string $payment_intent_id
 * @property bool $payment_completed
 * @property string $ship_name
 * @property string $ship_postcode
 * @property string $ship_address
 * @property string|null $ship_building
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoldItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoldItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoldItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoldItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoldItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoldItem whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoldItem wherePaymentCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoldItem wherePaymentIntentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoldItem wherePaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoldItem whereShipAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoldItem whereShipBuilding($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoldItem whereShipName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoldItem whereShipPostcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoldItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoldItem whereUserId($value)
 * @mixin \Eloquent
 */
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
            'payment_completed' => 'boolean',
        ];
    }
}

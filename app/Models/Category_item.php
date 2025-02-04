<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * items⇔categoriesの中間テーブル用モデル
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category_item newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category_item newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category_item query()
 * @mixin \Eloquent
 */
class Category_item extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryItemFactory> */
    use HasFactory;

    /**
     * 変更不可プロパティ
     *
     * @var list<string>
     */
    protected $guarded = ['id'];
}

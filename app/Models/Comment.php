<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use App\Models\Item;
use App\Models\User;

/**
 * commentsテーブルモデル
 *
 * @property int $id
 * @property int $user_id
 * @property int $item_id
 * @property string $comment
 * @property string $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Item $item
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment commentSearch($comment)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment createdAtSearch($date)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment idSearch($id)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment itemNameSearch($item_name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment userNameSearch($user_name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereUserId($value)
 * @mixin \Eloquent
 */
class Comment extends Model
{
    protected $guarded = ['id'];

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

    public function user(): BelongsTo {
        return $this->belongsTo('App\Models\User');
    }

    public function item(): BelongsTo {
        return $this->belongsTo('App\Models\Item');
    }

    public function scopeIdSearch($query, $id) {
        if (!empty($id)) {
            $query->find($id);
        }
    }

    public function scopeItemNameSearch($query, $item_name) {
        if (!empty($item_name)) {
            $ids = Item::where('name', 'like', "%{$item_name}%")->get()->pluck('id');
            $query->whereIn('item_id', $ids);
        }
    }

    public function scopeUserNameSearch($query, $user_name) {
        if (!empty($user_name)) {
            $ids = User::where('name', 'like', "%{$user_name}%")->get()->pluck('id');
            $query->whereIn('user_id', $ids);
        }
    }

    public function scopeCommentSearch($query, $comment) {
        if (!empty($comment)) {
            $query->where('comment', 'like', "%{$comment}%");
        }
    }
    public function scopeCreatedAtSearch($query, $date) {
        if (!empty($date)) {
            $query->where('created_at', 'like', "{$date}%");
        }
    }
}

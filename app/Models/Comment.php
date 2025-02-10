<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Builder;
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment commentSearch(string|null $comment)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment createdAtSearch(string|null $date)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment idSearch(int|null $id)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment itemNameSearch(string|null $item_name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment userNameSearch(string|null $user_name)
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
    /**
     * 変更不可プロパティ
     *
     * @var list<string>
     */
    protected $guarded = ['id'];

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
     * コメント投稿者のUserモデルを取得
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * コメントに紐づく商品のItemモデルを取得
     *
     * @return BelongsTo
     */
    public function item(): BelongsTo {
        return $this->belongsTo('App\Models\Item');
    }

    /*
    |--------------------------------------------------------------------------
    | ローカルスコープ
    |--------------------------------------------------------------------------
    */

    /**
     * コメントIDでの検索
     *
     * @param Builder $query
     * @param int|null $id
     * @return Builder|Comment
     */
    public function scopeIdSearch(Builder $query, int|null $id): Builder|Comment {
        if (isset($id)) {
            return $query->where('id', $id);
        } else {
            return $query;
        }
    }

    /**
     * 商品名での検索
     *
     * @param Builder $query
     * @param string|null $item_name
     * @return Builder|Comment
     */
    public function scopeItemNameSearch(Builder $query, string|null $item_name): Builder|Comment {
        if (isset($item_name)) {
            $ids = Item::where('name', 'like', "%{$item_name}%")->get()->pluck('id');
            return $query->whereIn('item_id', $ids);
        } else {
            return $query;
        }
    }

    /**
     * 投稿者名での検索
     *
     * @param Builder $query
     * @param string|null $user_name
     * @return Builder|Comment
     */
    public function scopeUserNameSearch(Builder $query, string|null $user_name): Builder|Comment {
        if (isset($user_name)) {
            $ids = User::where('name', 'like', "%{$user_name}%")->get()->pluck('id');
            return $query->whereIn('user_id', $ids);
        } else {
            return $query;
        }
    }

    /**
     * コメント文字列での検索
     *
     * @param Builder $query
     * @param string|null $comment
     * @return Builder|Comment
     */
    public function scopeCommentSearch(Builder $query, string|null $comment): Builder|Comment {
        if (isset($comment)) {
            return $query->where('comment', 'like', "%{$comment}%");
        } else {
            return $query;
        }
    }

    /**
     * 投稿日での検索
     *
     * @param Builder $query
     * @param string|null $date
     * @return Builder|Comment
     */
    public function scopeCreatedAtSearch(Builder $query, string|null $date): Builder|Comment {
        if (isset($date)) {
            return $query->where('created_at', 'like', "{$date}%");
        } else {
            return $query;
        }
    }
}

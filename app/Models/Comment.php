<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

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
}

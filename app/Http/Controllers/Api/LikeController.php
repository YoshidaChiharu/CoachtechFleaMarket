<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Like;

/**
 * お気に入り関連API
 */
class LikeController extends Controller
{
    /**
     * お気に入り登録処理
     */
    public function store(Request $request): void
    {
        try {
            Like::create([
                'user_id' => Auth::id(),
                'item_id'=> $request->item_id,
            ]);
        } catch (\Exception $e) {
            Log::error($e);
        }
    }

    /**
     * お気に入り削除処理
     */
    public function destroy(Request $request): void
    {
        try {
            $like = Like::where('user_id', Auth::id())
                        ->where('item_id', $request->item_id)
                        ->first();
            Like::destroy($like->id);
        } catch (\Exception $e) {
            Log::error($e);
        }
    }
}

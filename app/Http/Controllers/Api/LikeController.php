<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Like;

class LikeController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
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

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
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd('お気に入り登録', $request->item_id);
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // dd('お気に入り削除', $id);
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

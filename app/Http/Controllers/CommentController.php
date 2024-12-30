<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use App\Http\Requests\CommentRequest;
use App\Services\ItemDetailService;
use App\Models\Comment;

class CommentController extends Controller
{
    public function index(Request $request) {
        $item = ItemDetailService::getItemDetail($request->item_id);

        return Inertia::render('Comment', [
            'item' => $item
        ]);
    }

    public function store(CommentRequest $request) {
        try {
            Comment::create([
                'user_id' => $request->user()->id,
                'item_id' => $request->item_id,
                'comment' => $request->comment,
            ]);
        } catch (\Exception $e) {
            Log::error($e);
        }
    }
}

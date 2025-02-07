<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Requests\CommentRequest;
use App\Services\ItemDetailService;
use App\Models\Comment;

/**
 * コメントページ用コントローラークラス
 */
class CommentController extends Controller
{
    /**
     * コメントページ表示
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $item = ItemDetailService::getItemDetail($request->item_id);

        return Inertia::render('Comment', [
            'item' => $item
        ]);
    }

    /**
     * コメント投稿
     *
     * @param CommentRequest $request
     * @return RedirectResponse
     */
    public function store(CommentRequest $request): RedirectResponse
    {
        try {
            Comment::create([
                'user_id' => $request->user()->id,
                'item_id' => $request->item_id,
                'comment' => $request->comment,
            ]);
        } catch (\Exception $e) {
            Log::error($e);
        }

        return to_route('item.comment', $request->item_id);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Comment;

class AdminCommentController extends Controller
{
    public function index(Request $request) {
        $searchParam = $request->searchParam;
        $id = $searchParam['id'] ?? null;
        $item_name = $searchParam['itemName'] ?? null;
        $user_name = $searchParam['userName'] ?? null;
        $comment = $searchParam['comment'] ?? null;
        $date = $searchParam['date'] ?? null;

        $comments = Comment::IdSearch($id)
                           ->ItemNameSearch($item_name)
                           ->UserNameSearch($user_name)
                           ->CommentSearch($comment)
                           ->CreatedAtSearch($date)
                           ->get();

        $comments = $comments->map(function ($comment) {
            $comment['item_name'] = $comment->item->name;
            $comment['user_name'] = $comment->user->name ?? '<< Deleted User >>';
            return $comment->only('id', 'comment', 'created_at', 'item_name', 'user_name');
        });

        // ページネーション
        $comments = new LengthAwarePaginator
        (
            $comments->forPage($request->page, 20),
            $comments->count(),
            20,
            $request->page,
            [
                'path' => request()->url() .
                (
                    request()->except('page')
                    ? '?' .  http_build_query(request()->except('page'))
                    : ''
                )
            ]
        );

        return Inertia::render('Admin/AdminComment', [
            'comments' => $comments,
        ]);
    }

    public function destroy(Request $request) {
        try {
            Comment::find($request->comment_id)->delete();
            $status = 'success';
            $message = 'コメントを削除しました';
        } catch (\Exception $e) {
            $status = 'error';
            $message = '削除に失敗しました';
            Log::error($e);
        }
        return to_route('admin.comment')->with([
            'status' => $status,
            'message' => $message,
        ]);
    }
}

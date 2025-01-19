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
        // $searchParam = $request->searchParam;
        // $id = $searchParam['id'] ?? null;
        // $name = $searchParam['name'] ?? null;
        // $email = $searchParam['email'] ?? null;
        // $date = $searchParam['date'] ?? null;

        // $users = User::SearchId($id)
        //              ->SearchName($name)
        //              ->SearchEmail($email)
        //              ->SearchCreateAt($date)
        //              ->get();

        $comments = Comment::all()->map(function ($comment) {
            $comment['item_name'] = $comment->item->name;
            $comment['user_name'] = $comment->user->name ?? '<< Deleted User >>';
            return $comment->only('id', 'comment', 'created_at', 'item_name', 'user_name');
        });

        // ページネーション
        $comments = new LengthAwarePaginator
        (
            $comments->forPage($request->page, 30),
            $comments->count(),
            30,
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
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\User;

class AdminUserController extends Controller
{
    public function index(Request $request) {
        $searchParam = $request->searchParam;
        $id = $searchParam['id'] ?? null;
        $name = $searchParam['name'] ?? null;
        $email = $searchParam['email'] ?? null;
        $date = $searchParam['date'] ?? null;

        $users = User::SearchId($id)
                     ->SearchName($name)
                     ->SearchEmail($email)
                     ->SearchCreateAt($date)
                     ->get();

        // ページネーション
        $users = new LengthAwarePaginator
        (
            $users->forPage($request->page, 30),
            $users->count(),
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

        return Inertia::render('Admin/AdminUser', [
            'users' => $users,
        ]);
    }

    public function destroy(Request $request) {
        // dd('ユーザー削除', User::find($request->user_id));
        try {
            User::find($request->user_id)->delete();
            $status = 'success';
            $message = 'ユーザーを削除しました';
        } catch (\Exception $e) {
            $status = 'error';
            $message = '削除に失敗しました';
            Log::error($e);
        }
        return to_route('admin.user')->with([
            'status' => $status,
            'message' => $message,
        ]);
    }
}

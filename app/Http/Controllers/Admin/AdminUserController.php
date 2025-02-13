<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\User;

/**
 * 「管理画面」＞「ユーザー管理」ページ用コントローラークラス
 */
class AdminUserController extends Controller
{
    /**
     * ユーザー管理ページ表示
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response {
        $searchParam = $request->searchParam;
        $id = $searchParam['id'] ?? null;
        $name = addcslashes($searchParam['name'] ?? null, '%_\\');
        $email = addcslashes($searchParam['email'] ?? null, '%_\\');
        $date = $searchParam['date'] ?? null;

        $users = User::searchId($id)
                     ->searchName($name)
                     ->searchEmail($email)
                     ->searchCreateAt($date)
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

    /**
     * ユーザー削除
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse {
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

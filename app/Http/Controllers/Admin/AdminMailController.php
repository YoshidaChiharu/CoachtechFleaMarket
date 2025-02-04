<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\User;
use App\Jobs\SendAdminMail;
use Illuminate\Support\Facades\Bus;

/**
 * 「管理画面」＞「メール送信」ページ用コントローラークラス
 */
class AdminMailController extends Controller
{
    /**
     * メール作成ページ表示
     *
     * @return Response
     */
    public function create(): Response {
        return inertia::render('Admin/AdminMail');
    }

    /**
     * adminメール送信
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function sendMail(Request $request): RedirectResponse {
        try {
            // 送信対象は全ユーザー  ※管理者（role_id:1）のみ除外
            $users = User::where('role_id', '>=', 2)->get();

            // 全ユーザー分のメール送信ジョブを作成してディスパッチ
            $jobs = $users->map(function ($user) use ($request) {
                return new SendAdminMail($user, $request->subject, $request->mainText);
            });
            $batch = Bus::batch($jobs)->dispatch();

            $status = 'success';
            $message = 'メール送信しました';
        } catch (\Exception $e) {
            $status = 'error';
            $message = 'メール送信に失敗しました';
            Log::error($e);
        }

        return to_route('admin.mail')->with([
            'status' => $status,
            'message' => $message,
        ]);
    }
}

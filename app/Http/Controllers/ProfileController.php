<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

/**
 * プロフィールページ用コントローラークラス
 */
class ProfileController extends Controller
{
    /**
     * プロフィール編集ページ表示
     *
     * @param Request $request
     * @return Response
     */
    public function edit(Request $request): Response
    {
        $profile = $request->user()->profile;

        return Inertia::render('Profile', [
            'userName' => $request->user()->name,
            'profile' => $profile,
        ]);
    }

    /**
     * プロフィールの更新
     *
     * @param ProfileUpdateRequest $request
     * @return RedirectResponse
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        try {
            // usersテーブルの更新処理（ユーザー名のみ）
            $request->user()->update(['name' => $request->name]);

            // profilesテーブルの更新処理（郵便番号、住所、建物名、アイコン画像）
            $param = [
                'postcode' => $request->postcode,
                'address' => $request->address,
                'building' => $request->building,
            ];

            // アイコン画像の保存
            if ($request->image) {
                $image = $request->file('image');
                $file_name = $image->getClientOriginalName();
                $image_path = Storage::disk('s3')->putFileAs('user_images', $image, $file_name);
                $param['image_url'] = Storage::disk('s3')->url($image_path);
            }

            $profile = $request->user()->profile;
            $profile->update($param);

            $status = 'success';
            $message = 'プロフィールを編集しました';
        } catch (\Exception $e) {
            Log::error($e);

            $status = 'error';
            $message = 'プロフィール編集に失敗しました';
        }

        return to_route('mypage')->with([
            'status' => $status,
            'message' => $message,
        ]);
    }
}

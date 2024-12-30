<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        $profile = $request->user()->profile;

        return Inertia::render('Profile', [
            'profile' => $profile,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request)
    {
        $profile = $request->user()->profile;

        try {
            $param = [
                'name' => $request->name,
                'postcode' => $request->postcode,
                'address' => $request->address,
                'building' => $request->building,
            ];

            // サムネイル画像の保存
            if ($request->image) {
                $image = $request->file('image');
                $file_name = $image->getClientOriginalName();
                $image_path = $image->storeAs('img', $file_name, 'public');
                $param['image_url'] = str_replace("img", "/storage/img", $image_path);
            }

            $profile->update($param);
        } catch (\Exception $e) {
            Log::error($e);
        }

        return Inertia::render('Profile', [
            'profile' => $profile,
        ]);
    }
}

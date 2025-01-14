<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => [
                'required',
                'string',
                'between:8,20',
                'regex:/^[a-zA-Z0-9-_+@]+$/'
            ],
        ]);

        try {
            DB::beginTransaction();

                $user = User::create([
                    'role_id' => '2',
                    'name' => 'User',
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);
                Profile::create([
                    'user_id' => $user->id,
                    'name' => "User Name",
                    'image_url' => '/img/default_user_icon.png',
                ]);

                event(new Registered($user));

                Auth::login($user);

            DB::commit();
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
        }

        return redirect(route('top', absolute: false));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\User;

class AdminUserController extends Controller
{
    public function index(Request $request) {
        $users = User::all();

        return Inertia::render('Admin/AdminUser', [
            'users' => $users,
        ]);
    }
}

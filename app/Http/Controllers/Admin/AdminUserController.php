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

        return Inertia::render('Admin/AdminUser', [
            'users' => $users,
        ]);
    }
}

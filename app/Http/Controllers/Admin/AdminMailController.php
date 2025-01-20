<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class AdminMailController extends Controller
{
    public function create() {
        return inertia::render('Admin/AdminMail');
    }
}

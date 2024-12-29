<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\ItemDetailService;

class CommentController extends Controller
{
    public function index(Request $request) {
        $item = ItemDetailService::getItemDetail($request->item_id);

        return Inertia::render('Comment', [
            'item' => $item
        ]);
    }
}

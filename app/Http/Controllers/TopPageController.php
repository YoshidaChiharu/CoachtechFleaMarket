<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Like;
use Inertia\Inertia;
use Inertia\Response;

class TopPageController extends Controller
{
    public function index() {
        $items = Item::all()->toArray();

        return Inertia::render('Top', [
            'items' => $items
        ]);
    }
}

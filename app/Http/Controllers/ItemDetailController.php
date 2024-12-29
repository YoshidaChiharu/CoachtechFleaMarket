<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\ItemDetailService;

class ItemDetailController extends Controller
{
    public function show(Request $request) {
        $item = ItemDetailService::getItemDetail($request->item_id);

        return Inertia::render('ItemDetail', [
            'item' => $item
        ]);
    }
}

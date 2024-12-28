<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Item;
use App\Services\ItemService;

class ItemDetailController extends Controller
{
    public function show(Request $request) {
        $item_service = new ItemService;
        $item = $item_service->getItemDetail($request->item_id);

        return Inertia::render('ItemDetail', [
            'item' => $item
        ]);
    }
}

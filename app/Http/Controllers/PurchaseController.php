<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Services\ItemDetailService;

class PurchaseController extends Controller
{
    public function create(Request $request) {
        $item = ItemDetailService::getItemDetail($request->item_id);

        return Inertia::render('Purchase', [
            'item' => $item
        ]);
    }
}

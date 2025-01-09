<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Services\ItemDetailService;
use App\Models\PaymentMethod;

class PurchaseController extends Controller
{
    public function create(Request $request) {
        $item = ItemDetailService::getItemDetail($request->item_id);
        $paymentMethods = PaymentMethod::all()->pluck('name', 'id');

        return Inertia::render('Purchase', [
            'item' => $item,
            'paymentMethods' => $paymentMethods
        ]);
    }
}

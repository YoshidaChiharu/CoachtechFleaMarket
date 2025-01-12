<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Category;
use App\Models\Condition;

class SellController extends Controller
{
    public function create() {
        return Inertia::render('Sell', [
            'categories' => Category::all()->pluck('name', 'id'),
            'conditions' => Condition::all()->pluck('name', 'id'),
        ]);
    }

    public function store(Request $request) {
        // 商品登録処理
        dd($request);
    }
}

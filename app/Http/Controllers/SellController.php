<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Item;
use Stripe\StripeClient;
use App\Http\Requests\ItemRegisterRequest;

class SellController extends Controller
{
    public function create() {
        return Inertia::render('Sell', [
            'categories' => Category::all()->pluck('name', 'id'),
            'conditions' => Condition::all()->pluck('name', 'id'),
        ]);
    }

    public function store(ItemRegisterRequest $request) {
        $user = $request->user();

        try {
            DB::beginTransaction();

            $stripe = new StripeClient("sk_test_51QBad1Bli9nlS8GVTqk4Uty9r2jQqd3WwJlYOrZJZmNPZQWZBqPR4VOJNVPWaZMO88CJT7H9fDoXkJuIp6fTDo1K00UkjRgzAt");

            // Stripe顧客情報の作成処理
            if ($user->stripe_customer_id === null) {
                $customer = $stripe->customers->create([
                    'name' => $user->name,
                    'email' => $user->email,
                ]);

                $user->update([
                    'stripe_customer_id' => $customer->id,
                ]);
            }

            // Stripeの商品＆価格情報の作成処理
            $price = $stripe->prices->create([
                'currency' => 'jpy',
                'unit_amount' => $request->price,
                'product_data' => ['name' => $request->name],
            ]);

            // 画像の保存
            $image = $request->file('image');
            $file_name = $image->getClientOriginalName();
            $image_path = $image->storeAs('img', $file_name, 'public');
            $image_path = str_replace("img", "/storage/img", $image_path);

            // 商品登録処理
            $item = Item::create([
                'name' => $request->name,
                'brand' => $request->brand,
                'price' => $request->price,
                'description' => $request->description,
                'image_url' => $image_path,
                'condition_id' => $request->condition_id,
                'user_id' => $user->id,
                'stripe_price_id' => $price->id,
            ]);

            // カテゴリー登録処理
            $item->categories()->attach($request->categories);

            DB::commit();
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
        }

        return to_route('top');
    }
}

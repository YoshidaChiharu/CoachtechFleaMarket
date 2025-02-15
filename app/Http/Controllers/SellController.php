<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Item;
use Stripe\StripeClient;
use App\Http\Requests\ItemRegisterRequest;

/**
 * 出品ページ用コントローラークラス
 */
class SellController extends Controller
{
    /**
     * 出品ページ表示
     *
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('Sell', [
            'categories' => Category::all()->pluck('name', 'id'),
            'conditions' => Condition::all()->pluck('name', 'id'),
        ]);
    }

    /**
     * 出品処理
     *
     * @param ItemRegisterRequest $request
     * @return RedirectResponse
     */
    public function store(ItemRegisterRequest $request): RedirectResponse
    {
        $user = $request->user();

        try {
            DB::beginTransaction();

            if (config('app.env') !== 'testing') {
                $stripe = new StripeClient(config('stripe.stripe_secret'));

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
            }

            // 画像の保存
            $image = $request->file('image');
            $file_name = $image->getClientOriginalName();
            $image_path = Storage::disk('s3')->putFileAs('item_images', $image, $file_name);
            $image_url = Storage::disk('s3')->url($image_path);

            // 商品登録処理
            $item = Item::create([
                'name' => $request->name,
                'brand' => $request->brand,
                'price' => $request->price,
                'description' => $request->description,
                'image_url' => $image_url,
                'condition_id' => $request->condition_id,
                'user_id' => $user->id,
                'stripe_price_id' => $price->id ?? 'dummy_price_id',
            ]);

            // カテゴリー登録処理
            $item->categories()->attach($request->categories);

            DB::commit();

            $status = 'success';
            $message = '出品しました';
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();

            $status = 'error';
            $message = '出品に失敗しました';
        }

        return to_route('item.detail', $item->id)->with([
            'status' => $status,
            'message' => $message,
        ]);
    }
}

<?php

namespace Tests\Feature\UserPage;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\SoldItem;
use Inertia\Testing\AssertableInertia;

class MyPageTest extends TestCase
{
    public function test_マイページ表示(): void
    {
        $user = User::find(1);

        $response = $this->actingAs($user)->get(route('mypage'));

        $response->assertOk();
    }

    public function test_出品した商品タブ表示(): void
    {
        $user = User::find(1);

        $response = $this->actingAs($user)->get(route('mypage', ['filter' => 'sell']));

        $response->assertOk();
    }

    public function test_購入した商品タブ表示(): void
    {
        $user = User::find(1);

        $response = $this->actingAs($user)->get(route('mypage', ['filter' => 'purchased']));

        $response->assertOk();
    }

    public function test_出品した商品タブで表示されるのは自身の出品商品のみ(): void
    {
        Item::create([
            'name' => 'test',
            'price' => '1000',
            'description' => 'test',
            'image_url' => '/img/dummy_item.png',
            'condition_id' => '1',
            'user_id' => '2',
            'stripe_price_id' => 'price_dummy',
        ]);
        $user = User::find(1);
        $count = Item::where('user_id', $user->id)->count();

        $response = $this->actingAs($user)->get(route('mypage', ['filter' => 'sell']));

        $response->assertInertia(function (AssertableInertia $page) use ($count) {
            $page->component('MyPage')
                 ->where('items.total', $count);
        });
    }

    public function test_購入した商品タブで表示されるのは自身の購入商品のみ(): void
    {
        $user = User::find(1);
        $items = Item::inRandomOrder()->take(2)->get();
        SoldItem::insert([
            [
                'user_id' => $user->id,
                'item_id' => $items[0]->id,
                'payment_method_id' => 1,
                'payment_intent_id' => 'pi_dummy_1',
                'payment_completed' => 1,
                'ship_name' => 'test',
                'ship_postcode' => '1234567',
                'ship_address' => 'test',
            ],
            [
                'user_id' => 2,
                'item_id' => $items[1]->id,
                'payment_method_id' => 1,
                'payment_intent_id' => 'pi_dummy_2',
                'payment_completed' => 1,
                'ship_name' => 'test',
                'ship_postcode' => '1234567',
                'ship_address' => 'test',
            ],
        ]);
        $count = $user->purchasedItems->count();

        $response = $this->actingAs($user)->get(route('mypage', ['filter' => 'purchased']));

        $response->assertInertia(function (AssertableInertia $page) use ($count) {
            $page->component('MyPage')
                 ->where('items.total', $count);
        });
    }
}

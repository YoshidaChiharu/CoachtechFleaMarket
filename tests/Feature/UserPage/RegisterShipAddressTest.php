<?php

namespace Tests\Feature\UserPage;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Address;

class RegisterShipAddressTest extends TestCase
{
    public function test_配送先登録ページ表示(): void
    {
        $user = User::find(1);
        $item_id = Item::inRandomOrder()->first()->id;

        $response = $this->actingAs($user)->get(route('purchase.address.register', ['item_id' => $item_id]));

        $response->assertOk();
    }

    public function test_配送先登録処理(): void
    {
        $user = User::find(1);
        $item_id = Item::inRandomOrder()->first()->id;
        $param = [
            'item_id' => $item_id,
            'user_id' => $user->id,
            'name' => 'テスト配送先宛名',
            'postcode' => '1234567',
            'address' => 'テスト配送先住所 99-99-99',
            'building' => 'テスト配送先建物名 9999号室',
        ];

        $response = $this->actingAs($user)->post(route('purchase.address.register', $param));

        $response->assertRedirect(route('purchase', ['item_id' => $item_id, 'modalOpen' => true]));
        $this->assertDatabaseHas(Address::class, [
            'user_id' => $user->id,
            'name' => 'テスト配送先宛名',
            'postcode' => '1234567',
            'address' => 'テスト配送先住所 99-99-99',
            'building' => 'テスト配送先建物名 9999号室',
        ]);
    }
}

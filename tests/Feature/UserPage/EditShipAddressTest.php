<?php

namespace Tests\Feature\UserPage;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Address;

class EditShipAddressTest extends TestCase
{
    public function test_配送先編集ページ表示(): void
    {
        $user = User::find(1);
        $item_id = Item::inRandomOrder()->first()->id;
        $address = Address::create([
            'user_id' => $user->id,
            'name' => 'テスト配送先宛名',
            'postcode' => '1234567',
            'address' => 'テスト配送先住所 99-99-99',
            'building' => 'テスト配送先建物名 9999号室',
        ]);

        $response = $this->actingAs($user)->get(route('purchase.address.edit', [
            'address_id' => $address->id,
            'item_id' => $item_id,
        ]));

        $response->assertOk();
    }

    public function test_不正なアドレスIDでアクセスされた場合のリダイレクト(): void
    {
        $user = User::find(1);
        $item_id = Item::inRandomOrder()->first()->id;
        $address_id = 0;

        $response = $this->actingAs($user)->get(route('purchase.address.edit', [
            'address_id' => $address_id,
            'item_id' => $item_id,
        ]));

        $response->assertRedirect(route('top'));
    }

    public function test_配送先編集処理(): void
    {
        $user = User::find(1);
        $item_id = Item::inRandomOrder()->first()->id;
        $address = Address::create([
            'user_id' => $user->id,
            'name' => 'テスト配送先宛名',
            'postcode' => '1234567',
            'address' => 'テスト配送先住所 99-99-99',
            'building' => 'テスト配送先建物名 9999号室',
        ]);
        $param = [
            'address_id' => $address->id,
            'item_id' => $item_id,
        ];
        $form_edited = [
            'user_id' => $user->id,
            'name' => 'edited_テスト配送先宛名',
            'postcode' => '9999999',
            'address' => 'edited_テスト配送先住所 99-99-99',
            'building' => 'edited_テスト配送先建物名 9999号室',
        ];

        $response = $this->actingAs($user)->post(route('purchase.address.edit', array_merge($param, $form_edited)));

        $response->assertRedirect(route('purchase', ['item_id' => $item_id, 'modalOpen' => true]));
        $this->assertDatabaseHas(Address::class, $form_edited);
    }

    public function test_配送先編集削除(): void
    {
        $user = User::find(1);
        $item_id = Item::inRandomOrder()->first()->id;
        $address = Address::create([
            'user_id' => $user->id,
            'name' => 'テスト配送先宛名',
            'postcode' => '1234567',
            'address' => 'テスト配送先住所 99-99-99',
            'building' => 'テスト配送先建物名 9999号室',
        ]);
        $param = [
            'address_id' => $address->id,
            'item_id' => $item_id,
        ];

        $response = $this->actingAs($user)->delete(route('purchase.address.edit', $param));

        $response->assertRedirect(route('purchase', ['item_id' => $item_id, 'modalOpen' => true]));
        $this->assertDatabaseMissing(Address::class, [
            'id' => $address->id,
        ]);
    }
}

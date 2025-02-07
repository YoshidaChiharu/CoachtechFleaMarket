<?php

namespace Tests\Feature\UserPage;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class ItemDetailTest extends TestCase
{
    public function test_未ログイン状態での商品詳細ページ表示(): void
    {
        $item_id = Item::inRandomOrder()->first()->id;

        $response = $this->get(route('item.detail', $item_id));

        $response->assertOk();
    }

    public function test_ログイン済み状態での商品詳細ページ表示(): void
    {
        $user = User::find(1);
        $item_id = Item::inRandomOrder()->first()->id;

        $response = $this->actingAs($user)->get(route('item.detail', $item_id));

        $response->assertOk();
    }
}

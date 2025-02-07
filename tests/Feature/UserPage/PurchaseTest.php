<?php

namespace Tests\Feature\UserPage;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class PurchaseTest extends TestCase
{
    public function test_未ログイン状態で購入ページ表示した際のリダイレクト(): void
    {
        $item_id = Item::inRandomOrder()->first()->id;

        $response = $this->get(route('purchase', $item_id));

        $response->assertRedirect(route('login'));
    }

    public function test_ログイン済み状態での購入ページ表示(): void
    {
        $user = User::find(1);
        $item_id = Item::inRandomOrder()->first()->id;

        $response = $this->actingAs($user)->get(route('purchase', $item_id));

        $response->assertOk();
    }
}

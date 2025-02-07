<?php

namespace Tests\Feature\UserPage;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;

class CommentTest extends TestCase
{
    public function test_コメントページ表示(): void
    {
        $user = User::find(1);
        $item_id = Item::inRandomOrder()->first()->id;

        $response = $this->actingAs($user)->get(route('item.comment', $item_id));

        $response->assertOk();
    }

    public function test_コメント投稿(): void
    {
        $user = User::find(1);
        $item_id = Item::inRandomOrder()->first()->id;
        $comment = 'test';

        $response = $this->actingAs($user)->post(route('item.comment', [
            'item_id' => $item_id,
            'comment' => $comment,
        ]));

        $response->assertRedirect(route('item.comment', $item_id));
        $this->assertDatabaseHas(Comment::class, [
            'user_id' => 1,
            'item_id' => $item_id,
            'comment' => $comment,
        ]);
    }
}

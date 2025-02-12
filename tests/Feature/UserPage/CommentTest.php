<?php

namespace Tests\Feature\UserPage;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;
use PHPUnit\Framework\Attributes\DataProvider;

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

    #[DataProvider('commentFormDataProvider')]
    public function test_コメント投稿時のバリデーション(string|null $comment, array $expected): void
    {
        $user = User::find(1);
        $item_id = Item::inRandomOrder()->first()->id;

        $response = $this->actingAs($user)->post(route('item.comment', [
            'item_id' => $item_id,
            'comment' => $comment,
        ]));

        $response->assertInvalid($expected);
    }

    public static function commentFormDataProvider(): array
    {
        return [
            'コメント未入力' => [
                'comment' => null,
                'expected' => ['comment' => 'コメントは必ず指定してください。'],
            ],
            'コメント1000文字以上' => [
                'comment' => 'dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext /',
                'expected' => ['comment' => 'コメントは、1000文字以下で指定してください。'],
            ],
        ];
    }
}

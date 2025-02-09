<?php

namespace Tests\Feature\AdminPage;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\User;
use App\Models\Comment;
use App\Models\Item;
use Inertia\Testing\AssertableInertia;

class AdminCommentTest extends TestCase
{
    public function test_管理者以外でコメント管理ページを表示した際のリダイレクト(): void
    {
        $user = User::where('role_id', 2)->first();

        $response = $this->actingAs($user)->get(route('admin.comment'));

        $response->assertRedirect(route('top'));
    }

    public function test_管理者でのコメント管理ページ表示(): void
    {
        $user = User::where('role_id', 1)->first();

        $response = $this->actingAs($user)->get(route('admin.comment'));

        $response->assertOk();
    }

    public function test_IDでのコメント検索(): void
    {
        $user = User::where('role_id', 1)->first();
        $test_param = Comment::inRandomOrder()->first()->id;
        $search_param = [
            'id' => $test_param,
        ];

        $response = $this->actingAs($user)->get(route('admin.comment', ['searchParam' => $search_param]));

        $response->assertInertia(function (AssertableInertia $page) use ($test_param) {
            $page->component('Admin/AdminComment')
                 ->where('comments.total', 1)
                 ->has('comments.data.0', function (AssertableInertia $page) use ($test_param) {
                    $page->where('id', $test_param)
                         ->etc();
                 });
        });
    }

    public function test_商品名でのコメント検索(): void
    {
        $user = User::where('role_id', 1)->first();
        $test_param = 'ダミー商品_' . rand(1, 5);
        $search_param = [
            'itemName' => $test_param,
        ];

        $response = $this->actingAs($user)->get(route('admin.comment', ['searchParam' => $search_param]));

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Admin/AdminComment')
            ->has('comments.data', fn (AssertableInertia $page) => $page
                ->each(fn (AssertableInertia $page) => $page
                    ->where('item_name', function ($item_name) use ($test_param) {
                        return Str::contains($item_name, $test_param);
                    })
                    ->etc()
                )
            )
        );
    }

    public function test_投稿者名でのコメント検索(): void
    {
        $user = User::where('role_id', 1)->first();
        $test_param = User::inRandomOrder()->first()->name;
        $search_param = [
            'userName' => $test_param,
        ];

        $response = $this->actingAs($user)->get(route('admin.comment', ['searchParam' => $search_param]));

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Admin/AdminComment')
            ->has('comments.data', fn (AssertableInertia $page) => $page
                ->each(fn (AssertableInertia $page) => $page
                    ->where('user_name', function ($user_name) use ($test_param) {
                        return Str::contains($user_name, $test_param);
                    })
                    ->etc()
                )
            )
        );
    }

    public function test_コメント本文でのコメント検索(): void
    {
        $user = User::where('role_id', 1)->first();
        $test_param = 'ダミーコメント_' . rand(1, 10);
        $search_param = [
            'comment' => $test_param,
        ];
        $count = Comment::where('comment', 'like', $test_param . '%')->count();

        $response = $this->actingAs($user)->get(route('admin.comment', ['searchParam' => $search_param]));

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Admin/AdminComment')
            ->where('comments.total', $count)
            ->has('comments.data', fn (AssertableInertia $page) => $page
                ->each(fn (AssertableInertia $page) => $page
                    ->where('comment', function ($comment) use ($test_param) {
                        return Str::contains($comment, $test_param);
                    })
                    ->etc()
                )
            )
        );
    }

    public function test_投稿日でのコメント検索(): void
    {
        $user = User::where('role_id', 1)->first();
        $test_date_time = Comment::inRandomOrder()->first()->created_at;
        $test_param = Str::of($test_date_time)->explode(' ')->first();
        $search_param = [
            'date' => $test_param,
        ];
        $count = Comment::where('created_at', 'like', $test_param . '%')->count();

        $response = $this->actingAs($user)->get(route('admin.comment', ['searchParam' => $search_param]));

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Admin/AdminComment')
            ->where('comments.total', $count)
            ->has('comments.data', fn (AssertableInertia $page) => $page
                ->each(fn (AssertableInertia $page) => $page
                    ->where('created_at', $test_date_time)
                    ->etc()
                )
            )
        );
    }

    public function test_コメント削除(): void
    {
        $user = User::where('role_id', 1)->first();
        $test_comment_id = Comment::inRandomOrder()->first()->id;

        $response = $this->actingAs($user)->post(route('admin.comment', ['comment_id' => $test_comment_id]));

        $response->assertRedirect(route('admin.comment'));
        $this->assertDatabaseMissing(Comment::class, [
            'id' => $test_comment_id,
        ]);
    }
}

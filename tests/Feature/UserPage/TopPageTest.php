<?php

namespace Tests\Feature\UserPage;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Like;
use Inertia\Testing\AssertableInertia;

class TopPageTest extends TestCase
{
    public function test_未ログイン状態でのトップページ表示(): void
    {
        $response = $this->get(route('top'));

        $response->assertOk();
    }

    public function test_ログイン済み状態でのトップページ表示(): void
    {
        $user = User::find(1);

        $response = $this->actingAs($user)->get(route('top'));

        $response->assertOk();
    }

    public function test_未ログインのトップページでは全商品表示される()
    {
        $count = Item::all()->count();

        $response = $this->get(route('top'));

        $response->assertInertia(function (AssertableInertia $page) use ($count) {
            $page->component('Top')
                 ->where('items.total', $count);
        });
    }

    public function test_ログイン済みのトップページでは自身の出品商品を除外して表示される()
    {
        $user = User::find(1);
        $count = Item::where('user_id', '!=', $user->id)->count();

        $response = $this->actingAs($user)->get(route('top'));

        $response->assertInertia(function (AssertableInertia $page) use ($count) {
            $page->component('Top')
                 ->where('items.total', $count);
        });
    }

    public function test_未ログイン状態でマイリスト表示した際のリダイレクト(): void
    {
        $response = $this->get(route('top.mylist'));

        $response->assertRedirect(route('login'));
    }

    public function test_ログイン済み状態でのマイリスト表示(): void
    {
        $user = User::find(1);

        $response = $this->actingAs($user)->get(route('top.mylist'));

        $response->assertOk();
    }

    public function test_マイリストに表示されるのはお気に入り登録商品のみ(): void
    {
        $user = User::find(1);
        Like::create([
            'user_id' => $user->id,
            'item_id' => Item::where('user_id', '!=', $user->id)->inRandomOrder()->first()->id,
        ]);

        $response = $this->actingAs($user)->get(route('top.mylist'));

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Top')
            ->has('items.data', fn (AssertableInertia $page) => $page
                ->each(fn (AssertableInertia $page) => $page
                    ->where('is_like', true)
                    ->etc()
                )
            )
        );
    }

    public function test_マイリストには自身の出品商品を除外して表示される(): void
    {
        $user = User::find(1);
        Like::insert([
            [
                'user_id' => $user->id,
                'item_id' => Item::where('user_id', '!=', $user->id)->inRandomOrder()->first()->id,
            ],
            [
                'user_id' => $user->id,
                'item_id' => Item::where('user_id', $user->id)->inRandomOrder()->first()->id,
            ]
        ]);
        $count = $user->likeItems->where('user_id', '!=', $user->id)->count();

        $response = $this->actingAs($user)->get(route('top.mylist'));

        $response->assertInertia(function (AssertableInertia $page) use ($count) {
            $page->component('Top')
                 ->where('items.total', $count);
        });
    }

    public function test_商品名での商品検索(): void
    {
        $search_word = mb_substr(Item::inRandomOrder()->first()->name, -2);

        $response = $this->get(route('top', ['searchWord' => $search_word]));

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Top')
            ->has('items.data', fn (AssertableInertia $page) => $page
                ->each(fn (AssertableInertia $page) => $page
                    ->where('name', function ($name) use ($search_word) {
                        return Str::contains($name, $search_word);
                    })
                    ->etc()
                )
            )
        );
    }

    public function test_商品説明文での商品検索(): void
    {
        $search_word = mb_substr(Item::inRandomOrder()->first()->description, -2);

        $response = $this->get(route('top', ['searchWord' => $search_word]));

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Top')
            ->has('items.data', fn (AssertableInertia $page) => $page
                ->each(fn (AssertableInertia $page) => $page
                    ->where('description', function ($description) use ($search_word) {
                        return Str::contains($description, $search_word);
                    })
                    ->etc()
                )
            )
        );
    }

    public function test_カテゴリ名での商品検索(): void
    {
        $category = Category::find(1);
        $search_word = $category->name;
        $count = $category->items->count();

        $response = $this->get(route('top', ['searchWord' => $search_word]));

        $response->assertInertia(function (AssertableInertia $page) use ($count) {
            $page->component('Top')
                 ->where('items.total', $count);
        });
    }

    public function test_商品状態での商品検索(): void
    {
        $condition = Condition::find(1);
        $search_word = $condition->name;
        $count = $condition->items->count();

        $response = $this->get(route('top', ['searchWord' => $search_word]));

        $response->assertInertia(function (AssertableInertia $page) use ($count) {
            $page->component('Top')
                 ->where('items.total', $count);
        });
    }

    public function test_お気に入り登録(): void
    {
        $user = User::find(1);
        $item = Item::create([
            'name' => 'test',
            'price' => '1000',
            'description' => 'test',
            'image_url' => '/img/dummy_item.png',
            'condition_id' => '1',
            'user_id' => '1',
            'stripe_price_id' => 'price_dummy',
        ]);

        $response = $this->actingAs($user)->post('api/like/'.$item->id);

        $response->assertOk();
        $this->assertDatabaseHas(Like::class, [
            'user_id' => 1,
            'item_id' => $item->id,
        ]);
    }

    public function test_お気に入り削除(): void
    {
        $like = Like::latest('id')->first();
        $user = User::find($like->user_id);

        $response = $this->actingAs($user)->delete('api/like/'.$like->item_id);

        $response->assertOk();
        $this->assertDatabaseMissing(Like::class, [
            'id' => $like->id,
        ]);
    }
}

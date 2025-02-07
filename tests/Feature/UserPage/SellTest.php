<?php

namespace Tests\Feature\UserPage;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Category_item;

class SellTest extends TestCase
{
    public function test_未ログイン状態で出品ページ表示した際のリダイレクト(): void
    {
        $response = $this->get(route('sell'));

        $response->assertRedirect(route('login'));
    }

    public function test_ログイン済み状態での出品ページ表示(): void
    {
        $user = User::find(1);

        $response = $this->actingAs($user)->get(route('sell'));

        $response->assertOk();
    }

    public function test_出品処理_出品した商品の存在確認(): void
    {
        $user = User::find(1);
        Storage::fake('local');
        $file = UploadedFile::fake()->image('test_'.Str::random(20).'.png');
        $categories = Category::inRandomOrder()->take(3)->get()->pluck('id');
        $form = [
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'price' => '99999',
            'description' => 'テスト商品説明文',
            'image' => $file,
            'condition_id' => 1,
            'categories' => $categories,
        ];

        $response = $this->actingAs($user)->post(route('sell'), $form);

        $item = Item::latest('id')->first();
        $image_path = str_replace("/storage/img", "img", $item->image_url);
        $response->assertRedirect(route('item.detail', $item->id));
        $this->assertDatabaseHas(Item::class, [
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'price' => '99999',
            'description' => 'テスト商品説明文',
            'condition_id' => 1,
        ]);

        // アップロードしたテスト用画像ファイルの削除
        Storage::disk('public')->delete($image_path);
    }

    public function test_出品処理_商品画像ファイルの存在確認(): void
    {
        $user = User::find(1);
        Storage::fake('local');
        $file = UploadedFile::fake()->image('test_'.Str::random(20).'.png');
        $categories = Category::inRandomOrder()->take(3)->get()->pluck('id');
        $form = [
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'price' => '99999',
            'description' => 'テスト商品説明文',
            'image' => $file,
            'condition_id' => 1,
            'categories' => $categories,
        ];

        $response = $this->actingAs($user)->post(route('sell'), $form);

        $item = Item::latest('id')->first();
        $image_path = str_replace("/storage/img", "img", $item->image_url);
        Storage::disk('public')->assertExists($image_path);

        // アップロードしたテスト用画像ファイルの削除
        Storage::disk('public')->delete($image_path);
    }

    public function test_出品処理_カテゴリーの登録確認(): void
    {
        $user = User::find(1);
        Storage::fake('local');
        $file = UploadedFile::fake()->image('test_'.Str::random(20).'.png');
        $categories = Category::inRandomOrder()->take(3)->get()->pluck('id');
        $form = [
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'price' => '99999',
            'description' => 'テスト商品説明文',
            'image' => $file,
            'condition_id' => 1,
            'categories' => $categories,
        ];

        $response = $this->actingAs($user)->post(route('sell'), $form);

        $item = Item::latest('id')->first();
        $image_path = str_replace("/storage/img", "img", $item->image_url);
        $this->assertSame($categories->count(), $item->categories->count());

        // アップロードしたテスト用画像ファイルの削除
        Storage::disk('public')->delete($image_path);
    }
}

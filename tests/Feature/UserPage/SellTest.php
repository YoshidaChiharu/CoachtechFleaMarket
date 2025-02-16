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
use PHPUnit\Framework\Attributes\DataProvider;

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
        $response->assertRedirect(route('item.detail', $item->id));
        $this->assertDatabaseHas(Item::class, [
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'price' => '99999',
            'description' => 'テスト商品説明文',
            'condition_id' => 1,
        ]);

        // アップロードしたテスト用画像ファイルの削除
        Storage::disk('public')->delete($item->image_path);
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
        Storage::disk('public')->assertExists($item->image_path);

        // アップロードしたテスト用画像ファイルの削除
        Storage::disk('public')->delete($item->image_path);
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
        $this->assertSame($categories->count(), $item->categories->count());

        // アップロードしたテスト用画像ファイルの削除
        Storage::disk('public')->delete($item->image_path);
    }

    #[DataProvider('sellFormDataProvider')]
    public function test_出品時のバリデーション(array $form, array $expected): void
    {
        $user = User::find(1);

        $response = $this->actingAs($user)->post(route('sell'), $form);

        $response->assertInvalid($expected);
    }

    public static function sellFormDataProvider(): array
    {
        return [
            'カテゴリーが未入力' => [
                'form' => [
                    'name' => 'テスト商品',
                    'brand' => 'テストブランド',
                    'price' => '99999',
                    'description' => 'テスト商品説明文',
                    'image' => UploadedFile::fake()->image('test.png'),
                    'condition_id' => 1,
                    'categories' => null,
                ],
                'expected' => ['categories' => 'カテゴリーは必ず指定してください。'],
            ],
            '商品の状態が未入力' => [
                'form' => [
                    'name' => 'テスト商品',
                    'brand' => 'テストブランド',
                    'price' => '99999',
                    'description' => 'テスト商品説明文',
                    'image' => UploadedFile::fake()->image('test.png'),
                    'condition_id' => null,
                    'categories' => [1, 2, 3],
                ],
                'expected' => ['condition_id' => '商品の状態を選択してください'],
            ],
            '商品名が未入力' => [
                'form' => [
                    'name' => null,
                    'brand' => 'テストブランド',
                    'price' => '99999',
                    'description' => 'テスト商品説明文',
                    'image' => UploadedFile::fake()->image('test.png'),
                    'condition_id' => 1,
                    'categories' => [1, 2, 3],
                ],
                'expected' => ['name' => '名前は必ず指定してください。'],
            ],
            '商品の説明が未入力' => [
                'form' => [
                    'name' => 'テスト商品',
                    'brand' => 'テストブランド',
                    'price' => '99999',
                    'description' => null,
                    'image' => UploadedFile::fake()->image('test.png'),
                    'condition_id' => 1,
                    'categories' => [1, 2, 3],
                ],
                'expected' => ['description' => '説明は必ず指定してください。'],
            ],
            '販売価格が未入力' => [
                'form' => [
                    'name' => 'テスト商品',
                    'brand' => 'テストブランド',
                    'price' => null,
                    'description' => 'テスト商品説明文',
                    'image' => UploadedFile::fake()->image('test.png'),
                    'condition_id' => 1,
                    'categories' => [1, 2, 3],
                ],
                'expected' => ['price' => '価格は必ず指定してください。'],
            ],
            '画像ファイルが未選択' => [
                'form' => [
                    'name' => 'テスト商品',
                    'brand' => 'テストブランド',
                    'price' => '99999',
                    'description' => 'テスト商品説明文',
                    'image' => null,
                    'condition_id' => 1,
                    'categories' => [1, 2, 3],
                ],
                'expected' => ['image' => '画像は必ず指定してください。'],
            ],
            '商品名に101文字以上入力' => [
                'form' => [
                    'name' => Str::random(101),
                    'brand' => 'テストブランド',
                    'price' => '99999',
                    'description' => 'テスト商品説明文',
                    'image' => UploadedFile::fake()->image('test.png'),
                    'condition_id' => 1,
                    'categories' => [1, 2, 3],
                ],
                'expected' => ['name' => '名前は、100文字以下で指定してください。'],
            ],
            'ブランド名に51文字以上入力' => [
                'form' => [
                    'name' => 'テスト商品',
                    'brand' => Str::random(51),
                    'price' => '99999',
                    'description' => 'テスト商品説明文',
                    'image' => UploadedFile::fake()->image('test.png'),
                    'condition_id' => 1,
                    'categories' => [1, 2, 3],
                ],
                'expected' => ['brand' => 'ブランドは、50文字以下で指定してください。'],
            ],
            '商品の説明に1001文字以上入力' => [
                'form' => [
                    'name' => 'テスト商品',
                    'brand' => 'テストブランド',
                    'price' => '99999',
                    'description' => Str::random(1001),
                    'image' => UploadedFile::fake()->image('test.png'),
                    'condition_id' => 1,
                    'categories' => [1, 2, 3],
                ],
                'expected' => ['description' => '説明は、1000文字以下で指定してください。'],
            ],
            '販売価格に299円以下を入力' => [
                'form' => [
                    'name' => 'テスト商品',
                    'brand' => 'テストブランド',
                    'price' => 299,
                    'description' => 'テスト商品説明文',
                    'image' => UploadedFile::fake()->image('test.png'),
                    'condition_id' => 1,
                    'categories' => [1, 2, 3],
                ],
                'expected' => ['price' => '300円以上の価格を設定してください'],
            ],
            '販売価格に10,000,000円以上を入力' => [
                'form' => [
                    'name' => 'テスト商品',
                    'brand' => 'テストブランド',
                    'price' => 10000000,
                    'description' => 'テスト商品説明文',
                    'image' => UploadedFile::fake()->image('test.png'),
                    'condition_id' => 1,
                    'categories' => [1, 2, 3],
                ],
                'expected' => ['price' => '9999999円以下の価格を設定してください'],
            ],
            '画像に画像ファイル以外を指定' => [
                'form' => [
                    'name' => 'テスト商品',
                    'brand' => 'テストブランド',
                    'price' => '99999',
                    'description' => 'テスト商品説明文',
                    'image' => UploadedFile::fake()->create('test.txt'),
                    'condition_id' => 1,
                    'categories' => [1, 2, 3],
                ],
                'expected' => ['image' => '画像には画像ファイルを指定してください。'],
            ],
        ];
    }
}

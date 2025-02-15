<?php

namespace Tests\Feature\UserPage;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use PHPUnit\Framework\Attributes\DataProvider;

class ProfileTest extends TestCase
{
    public function test_プロフィールページ表示(): void
    {
        $user = User::find(1);

        $response = $this->actingAs($user)->get(route('mypage.profile'));

        $response->assertOk();
    }

    public function test_プロフィール編集処理(): void
    {
        $user = User::find(1);
        $form = [
            'name' => 'edited_'. $user->name,
            'postcode' => '9999999',
            'address' => 'edited_テスト住所 99-99-99',
            'building' => 'edited_テスト建物名 9999号室',
        ];

        $response = $this->actingAs($user)->post(route('mypage.profile', $form));

        $response->assertRedirect(route('mypage'));
        $this->assertSame($user->name, $form['name']);
        $this->assertDatabaseHas(Profile::class, [
            'user_id' => $user->id,
            'postcode' => '9999999',
            'address' => 'edited_テスト住所 99-99-99',
            'building' => 'edited_テスト建物名 9999号室',
        ]);
    }

    public function test_画像アップロード(): void
    {
        $user = User::find(1);
        Storage::fake('local');
        $file = UploadedFile::fake()->image('test_'.Str::random(20).'.png');
        $form = [
            'name' => $user->name,
            'image' => $file,
        ];

        $response = $this->actingAs($user)->post(route('mypage.profile'), $form);

        $response->assertRedirect(route('mypage'));
        $image_path = $user->profile->image_path;
        Storage::disk('s3')->assertExists($image_path);

        // アップロードしたテスト用画像ファイルの削除
        Storage::disk('s3')->delete($image_path);
    }

    #[DataProvider('profileFormDataProvider')]
    public function test_プロフィール編集時のバリデーション(array $form, array $expected): void
    {
        $user = User::find(1);

        $response = $this->actingAs($user)->post(route('mypage.profile'), $form);

        $response->assertInvalid($expected);
    }

    public static function profileFormDataProvider(): array
    {
        return [
            'ユーザー名未入力' => [
                'form' => [
                    'name' => null,
                ],
                'expected' => ['name' => '名前は必ず指定してください。'],
            ],
            'ユーザー名に51文字以上を入力' => [
                'form' => [
                    'name' => 'dummytext dummytext dummytext dummytext dummytext d',
                ],
                'expected' => ['name' => '名前は、50文字以下で指定してください。'],
            ],
            '郵便番号6桁以下' => [
                'form' => [
                    'name' => 'Test User',
                    'postcode' => '123456',
                ],
                'expected' => ['postcode' => '郵便番号は7桁で指定してください。'],
            ],
            '郵便番号に数字以外を入力' => [
                'form' => [
                    'name' => 'Test User',
                    'postcode' => 'testnum',
                ],
                'expected' => ['postcode' => '使用できない文字が含まれています'],
            ],
            '住所に256文字以上を入力' => [
                'form' => [
                    'name' => 'Test User',
                    'address' => 'dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummyt',
                ],
                'expected' => ['address' => '住所は、255文字以下で指定してください。'],
            ],
            '建物名に256文字以上を入力' => [
                'form' => [
                    'name' => 'Test User',
                    'building' => 'dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummytext dummyt',
                ],
                'expected' => ['building' => '建物名は、255文字以下で指定してください。'],
            ],
            '画像に画像ファイル以外を指定' => [
                'form' => [
                    'name' => 'Test User',
                    'image' => UploadedFile::fake()->create('test.txt'),
                ],
                'expected' => ['image' => '画像には画像ファイルを指定してください。'],
            ],
        ];
    }
}

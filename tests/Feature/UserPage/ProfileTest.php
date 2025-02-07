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
        $image_path = str_replace("/storage/img", "img", $user->profile->image_url);
        Storage::disk('public')->assertExists($image_path);

        // アップロードしたテスト用画像ファイルの削除
        Storage::disk('public')->delete($image_path);
    }
}

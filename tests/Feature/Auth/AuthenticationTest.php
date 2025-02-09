<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_ログインページ表示(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_ログイン(): void
    {
        $user = User::inRandomOrder()->first();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('top', absolute: false));
    }

    public function test_メール認証未完了状態でログインした場合のリダイレクト(): void
    {
        $user = User::create([
            'role_id' => '2',
            'name' => 'Test User',
            'email' => 'test2@example.com',
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('verification.notice', absolute: false));
    }

    /**
     * @dataProvider loginFormDataProvider
     */
    public function test_入力エラーによるログイン失敗(string|null $email, string|null $password, array $expected): void
    {
        $response = $this->post('/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $this->assertGuest();
        $response->assertInvalid($expected);
    }

    public static function loginFormDataProvider(): array
    {
        return [
            'メールアドレス未入力' => [
                'email' => null,
                'password' => 'password',
                'expected' => ['email' => 'メールアドレスは必ず指定してください。'],
            ],
            'パスワード未入力' => [
                'email' => 'test@example.com',
                'password' => null,
                'expected' => ['password' => 'パスワードは必ず指定してください。'],
            ],
            'パスワード入力間違い' => [
                'email' => 'test@example.com',
                'password' => 'wrong-password',
                'expected' => ['email' => 'ログイン情報が存在しません。'],
            ],
        ];
    }

    public function test_ログアウト(): void
    {
        $user = User::inRandomOrder()->first();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/login');
    }
}

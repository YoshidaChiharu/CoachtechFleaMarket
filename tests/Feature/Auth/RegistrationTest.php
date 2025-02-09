<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_会員登録ページ表示(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_会員登録(): void
    {
        $response = $this->post('/register', [
            'email' => 'test@example.net',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('verification.notice', absolute: false));
    }

    /**
     * @dataProvider registerUserDataProvider
     */
    public function test_会員登録時のバリデーション(string|null $email, string|null $password, array $expected): void
    {
        $response = $this->post('/register', [
            'email' => $email,
            'password' => $password,
        ]);

        $response->assertInvalid($expected);
    }

    public static function registerUserDataProvider(): array
    {
        return [
            'メールアドレス未入力' => [
                'email' => null,
                'password' => 'password',
                'expected' => ['email' => 'メールアドレスは必ず指定してください。'],
            ],
            'メールアドレス重複' => [
                'email' => 'test@example.com',
                'password' => 'password',
                'expected' => ['email' => 'メールアドレスの値は既に存在しています。'],
            ],
            'パスワード未入力' => [
                'email' => 'test@example.net',
                'password' => null,
                'expected' => ['password' => 'パスワードは必ず指定してください。'],
            ],
            'パスワード7文字以下' => [
                'email' => 'test@example.net',
                'password' => 'pass123',
                'expected' => ['password' => 'パスワードは、8文字から、20文字の間で指定してください。'],
            ],
            'パスワード20文字以上' => [
                'email' => 'test@example.net',
                'password' => 'password__password__@',
                'expected' => ['password' => 'パスワードは、8文字から、20文字の間で指定してください。'],
            ],
            'パスワードに使用できない文字を入力' => [
                'email' => 'test@example.net',
                'password' => '/password',
                'expected' => ['password' => '使用できない文字が含まれています'],
            ],
        ];
    }
}

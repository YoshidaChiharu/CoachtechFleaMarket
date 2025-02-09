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
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('top', absolute: false));
    }

    public function test_パスワード入力間違いによるログイン失敗(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
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

    public function test_ログアウト(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/login');
    }
}

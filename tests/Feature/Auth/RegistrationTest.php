<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Str;

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
            'name' => 'Test User',
            'email' => 'test@example.net',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('verification.notice', absolute: false));
    }
}

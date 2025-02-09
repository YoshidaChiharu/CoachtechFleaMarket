<?php

namespace Tests\Feature\AdminPage;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AdminMailTest extends TestCase
{
    public function test_管理者以外でメール送信ページを表示した際のリダイレクト(): void
    {
        $user = User::where('role_id', 2)->first();

        $response = $this->actingAs($user)->get(route('admin.mail'));

        $response->assertRedirect(route('top'));
    }

    public function test_管理者でのメール送信ページ表示(): void
    {
        $user = User::where('role_id', 1)->first();

        $response = $this->actingAs($user)->get(route('admin.mail'));

        $response->assertOk();
    }

    public function test_メール送信処理(): void
    {
        $user = User::where('role_id', 1)->first();
        $form = [
            'subject' => 'Adminメールテスト_題名',
            'mainText' => 'Adminメールテスト_本文',
        ];

        $response = $this->actingAs($user)->post(route('admin.mail'), $form);

        $response->assertRedirect(route('admin.mail'));
        $response->assertSessionHas('status', 'success');
        $response->assertSessionHas('message', 'メール送信しました');
    }
}

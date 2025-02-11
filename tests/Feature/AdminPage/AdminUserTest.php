<?php

namespace Tests\Feature\AdminPage;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\User;
use Inertia\Testing\AssertableInertia;

class AdminUserTest extends TestCase
{
    public function test_管理者以外でユーザー管理ページを表示した際のリダイレクト(): void
    {
        $user = User::where('role_id', 2)->first();

        $response = $this->actingAs($user)->get(route('admin.user'));

        $response->assertRedirect(route('top'));
    }

    public function test_管理者でのユーザー管理ページ表示(): void
    {
        $user = User::where('role_id', 1)->first();

        $response = $this->actingAs($user)->get(route('admin.user'));

        $response->assertOk();
    }

    public function test_IDでのユーザー検索(): void
    {
        $user = User::where('role_id', 1)->first();
        $test_param = User::inRandomOrder()->first()->id;
        $search_param = [
            'id' => $test_param,
        ];

        $response = $this->actingAs($user)->get(route('admin.user', ['searchParam' => $search_param]));

        $response->assertInertia(function (AssertableInertia $page) use ($test_param) {
            $page->component('Admin/AdminUser')
                 ->where('users.total', 1)
                 ->has('users.data.0', function (AssertableInertia $page) use ($test_param) {
                    $page->where('id', $test_param)
                         ->etc();
                 });
        });
    }

    public function test_名前でのユーザー検索(): void
    {
        $user = User::where('role_id', 1)->first();
        $test_param = User::inRandomOrder()->first()->name;
        $search_param = [
            'name' => $test_param,
        ];

        $response = $this->actingAs($user)->get(route('admin.user', ['searchParam' => $search_param]));

        $response->assertInertia(function (AssertableInertia $page) use ($test_param) {
            $page->component('Admin/AdminUser')
                 ->has('users.data.0', function (AssertableInertia $page) use ($test_param) {
                    $page->where('name', $test_param)
                         ->etc();
                 });
        });
    }

    public function test_メールアドレスでのユーザー検索(): void
    {
        $user = User::where('role_id', 1)->first();
        $test_param = User::inRandomOrder()->first()->email;
        $search_param = [
            'email' => $test_param,
        ];

        $response = $this->actingAs($user)->get(route('admin.user', ['searchParam' => $search_param]));

        $response->assertInertia(function (AssertableInertia $page) use ($test_param) {
            $page->component('Admin/AdminUser')
                 ->where('users.total', 1)
                 ->has('users.data.0', function (AssertableInertia $page) use ($test_param) {
                    $page->where('email', $test_param)
                         ->etc();
                 });
        });
    }

    public function test_登録日でのユーザー検索(): void
    {
        $user = User::where('role_id', 1)->first();
        $test_date_time = User::inRandomOrder()->first()->created_at;
        $test_param = Str::of($test_date_time)->explode(' ')->first();
        $search_param = [
            'date' => $test_param,
        ];
        $count = User::where('created_at', 'like', $test_param . '%')->count();

        $response = $this->actingAs($user)->get(route('admin.user', ['searchParam' => $search_param]));

        $response->assertInertia(fn (AssertableInertia $page) => $page
        ->component('Admin/AdminUser')
        ->where('users.total', $count)
        ->has('users.data', fn (AssertableInertia $page) => $page
            ->each(fn (AssertableInertia $page) => $page
                ->where('created_at', function ($created_at) use ($test_param) {
                    return Str::contains($created_at, $test_param);
                })
                ->etc()
            )
        )
    );
    }

    public function test_ユーザー削除(): void
    {
        $user = User::where('role_id', 1)->first();
        $test_user_id = User::where('role_id', 2)->inRandomOrder()->first()->id;

        $response = $this->actingAs($user)->post(route('admin.user', ['user_id' => $test_user_id]));

        $response->assertRedirect(route('admin.user'));
        $this->assertDatabaseMissing(User::class, [
            'id' => $test_user_id,
            'deleted_at' => null,
        ]);
    }
}

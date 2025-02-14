<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\ConditionSeeder;
use Database\Seeders\ItemSeeder;
use Database\Seeders\PaymentMethodSeeder;
use Database\Seeders\ProfileSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CategoryItemSeeder;
use Database\Seeders\LikeSeeder;
use Database\Seeders\CommentSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        User::factory(10)->create();
        $this->call(ProfileSeeder::class);
        $this->call(ConditionSeeder::class);
        $this->call(ItemSeeder::class);
        $this->call(PaymentMethodSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(CategoryItemSeeder::class);
        $this->call(LikeSeeder::class);
        $this->call(CommentSeeder::class);
    }
}

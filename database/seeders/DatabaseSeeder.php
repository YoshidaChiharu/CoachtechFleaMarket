<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\ConditionSeeder;
use Database\Seeders\ItemSeeder;
use Database\Seeders\PaymentMethodSeeder;
use Database\Seeders\ProfileSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);
        User::factory(10)->create();
        $this->call(ProfileSeeder::class);
        $this->call(ConditionSeeder::class);
        $this->call(ItemSeeder::class);
        $this->call(PaymentMethodSeeder::class);
    }
}

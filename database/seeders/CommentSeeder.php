<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Item;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        for ($i=1; $i<=100; $i++) {
            $param = [
                'user_id' => fake()->numberBetween(1, User::count()),
                'item_id' => fake()->numberBetween(1, Item::count()),
                'comment' => 'ダミーコメント_' . $i
            ];
            DB::table('comments')->insert($param);
        }
    }
}

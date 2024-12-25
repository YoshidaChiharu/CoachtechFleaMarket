<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Condition;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $condition_num = Condition::count();

        for ($i=1; $i<=50; $i++) {
            $param = [
                'name' => "商品名_{$i}",
                'price' => (fake()->numberBetween(1, 100)) * 1000,
                'description' => '商品詳細テキスト',
                'image_url' => '/img/dummy_item.png',
                'condition_id' => fake()->numberBetween(1, $condition_num),
                'user_id' => '1',
            ];
            DB::table('items')->insert($param);
        }
    }
}

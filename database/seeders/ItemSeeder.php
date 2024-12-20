<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i=1; $i<=50; $i++) {
            $param = [
                'name' => "商品名_{$i}",
                'price' => '1000',
                'description' => '商品詳細テキスト',
                'image_url' => 'img/dummy_item.png',
                'condition_id' => '1',
                'user_id' => '1',
            ];
            DB::table('items')->insert($param);
        }
    }
}

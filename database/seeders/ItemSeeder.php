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
        $file_path = database_path('json/items.json');
        $json = file_get_contents($file_path);
        $items = json_decode($json, true);

        foreach ($items as $item) {
            $param = [
                'name' => $item['name'],
                'price' => $item['price'],
                'description' => $item['description'],
                'image_url' => $item['image_url'],
                'condition_id' => $item['condition_id'],
                'user_id' => $item['user_id'],
                'stripe_price_id' => $item['stripe_price_id'],
            ];
            DB::table('items')->insert($param);
        }
    }
}

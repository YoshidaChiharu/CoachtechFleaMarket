<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Item;
use App\Models\Category;

class CategoryItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // item_id と category_id の組み合わせを被りなくランダム取得
        $data_num = 70; // 作成するダミーデータの数
        $item_ids = Item::all()->pluck('id');
        $category_ids = Category::all()->pluck('id');
        $matrix = $item_ids->crossJoin($category_ids);
        $key_pairs = fake()->unique()->randomElements($matrix, $data_num);

        foreach ($key_pairs as $key_pair) {
            $param = [
                'item_id' => $key_pair[0],
                'category_id' => $key_pair[1],
            ];
            DB::table('category_item')->insert($param);
        }
    }
}

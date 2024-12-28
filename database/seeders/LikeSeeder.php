<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Item;

class LikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // user_id と item_id の組み合わせを被りなくランダム取得
        $data_num = 100; // 作成するダミーデータの数
        $user_ids = User::all()->pluck('id');
        $item_ids = Item::all()->pluck('id');
        $matrix = $user_ids->crossJoin($item_ids);
        $key_pairs = fake()->unique()->randomElements($matrix, $data_num);

        foreach ($key_pairs as $key_pair) {
            $param = [
                'user_id' => $key_pair[0],
                'item_id' => $key_pair[1],
            ];
            DB::table('likes')->insert($param);
        }
    }
}

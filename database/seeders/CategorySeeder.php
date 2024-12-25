<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $param = [
            ['name' => 'レディース'],
            ['name' => 'メンズ'],
            ['name' => 'コスメ/美容'],
            ['name' => 'キッズ/ベビー/マタニティ'],
            ['name' => 'エンタメ/ホビー'],
            ['name' => '楽器'],
            ['name' => 'チケット'],
            ['name' => 'インテリア/住まい/日用品'],
            ['name' => 'スマホ/家電/カメラ'],
            ['name' => 'ハンドメイド'],
            ['name' => '食品/飲料/酒'],
            ['name' => 'スポーツ/アウトドア'],
            ['name' => '自動車/バイク'],
            ['name' => 'その他'],
        ];
        DB::table('categories')->insert($param);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $param = [
            ['name' => '未使用'],
            ['name' => '良好'],
            ['name' => '傷／汚れあり'],
        ];
        DB::table('conditions')->insert($param);
    }
}

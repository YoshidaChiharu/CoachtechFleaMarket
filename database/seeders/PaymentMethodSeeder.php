<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $param = [
            ['name' => 'クレジットカード決済'],
            ['name' => 'コンビニ払い'],
            ['name' => '銀行振込'],
        ];
        DB::table('payment_methods')->insert($param);
    }
}

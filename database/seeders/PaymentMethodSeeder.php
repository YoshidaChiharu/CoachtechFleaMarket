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
            [
                'name' => 'クレジットカード決済',
                'payment_method_type' => 'card'
            ],
            [
                'name' => 'コンビニ払い',
                'payment_method_type' => 'konbini'
            ],
            [
                'name' => '銀行振込',
                'payment_method_type' => 'customer_balance'
            ],
        ];
        DB::table('payment_methods')->insert($param);
    }
}

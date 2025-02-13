<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user_num = User::count();
        for ($i=1; $i<=$user_num; $i++) {
            $param = [
                'user_id' => $i,
                'image_url' => '/img/default_user_icon.png',
            ];
            DB::table('profiles')->insert($param);
        }
    }
}

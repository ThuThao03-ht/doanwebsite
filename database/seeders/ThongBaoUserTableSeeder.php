<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThongBaoUserTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('thongbao_user')->insert([
            [
                'thongbao_id' => 1,
                'user_id' => 2,
                'da_doc' => true,
                'thoi_gian_doc' => now(),
                'created_at' => now(),
                 'updated_at' => now(),
            ],
            [
                'thongbao_id' => 1,
                'user_id' => 3,
                'da_doc' => true,
                'thoi_gian_doc' => now(),
                'created_at' => now(),
                 'updated_at' => now(),
            ],
             [
                'thongbao_id' => 2,
                'user_id' => 2,
                'da_doc' => false,
                'thoi_gian_doc' => null,
                'created_at' => now(),
                 'updated_at' => now(),
            ],
            [
                'thongbao_id' => 2,
                'user_id' => 3,
                'da_doc' => false,
                'thoi_gian_doc' => null,
                'created_at' => now(),
                 'updated_at' => now(),
            ],
        ]);
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GiangVienTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('giangvien')->insert([
            [
                'ma_gv' => 'GV001',
                'ho_ten' => 'Phạm Khánh Băng',
                'bo_mon' => 'Khoa CNTT',
                'email' => 'phamkhanhbang@example.com',
                'sdt' => '0900000002',
                'user_id' => 4,
                'created_at' => now(),
                 'updated_at' => now(),
            ],
            [
                'ma_gv' => 'GV002',
                'ho_ten' => 'Nguyễn Văn Chương',
                'bo_mon' => 'Khoa CNTT',
                'email' => 'nguyenvanchuong@example.com',
                'sdt' => '0900000003',
                'user_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
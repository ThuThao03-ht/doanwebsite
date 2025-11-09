<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhanCongGiangVienTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('phancong_giangvien')->insert([
            [
                'dk_id' => 1, // sinh viên 1 đã đăng ký
                'gv_id' => 1, // GV001 - Phạm Khánh Băng
                'ngay_phancong' => now(),
                'ghi_chu' => 'Hướng dẫn đề tài về Laravel.',
                'created_at' => now(),
                 'updated_at' => now(),
            ],
            [
                'dk_id' => 2, // sinh viên 2 đã đăng ký
                'gv_id' => 2, // GV002 - Nguyễn Văn Chương
                'ngay_phancong' => now(),
                'ghi_chu' => 'Hướng dẫn đề tài ReactJS Frontend.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        

        ]);
    }
}
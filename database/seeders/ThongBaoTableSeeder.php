<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThongBaoTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('thongbao')->insert([
    [
        'tieude' => 'Thông báo thực tập',
        'noidung' => 'Hạn chót nộp hồ sơ 30/10',
        'nguoi_gui_id' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'tieude' => 'Lịch gặp doanh nghiệp',
        'noidung' => 'Ngày 15/11 tại phòng A101',
        'nguoi_gui_id' => 1,
        'created_at' => now(),
         'updated_at' => now()
    ],
]);

    }
}
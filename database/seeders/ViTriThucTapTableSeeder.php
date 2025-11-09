<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ViTriThucTapTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('vitri_thuctap')->insert([
            [
                'ma_vitri' => 'VT0001',
                'dn_id' => 1,
                'ten_vitri' => 'Thực tập PHP Laravel',
                'mo_ta' => 'Tham gia phát triển web',
                'yeu_cau' => 'Biết Laravel cơ bản',
                'soluong' => 2,
                'so_luong_da_dangky' => 1,
                'trang_thai' => 'con_han',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ma_vitri' => 'VT0002',
                'dn_id' => 2,
                'ten_vitri' => 'Thực tập ReactJS',
                'mo_ta' => 'Tham gia phát triển giao diện web',
                'yeu_cau' => 'Biết cơ bản ReactJS',
                'soluong' => 3,
                'so_luong_da_dangky' => 1,
                'trang_thai' => 'con_han',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
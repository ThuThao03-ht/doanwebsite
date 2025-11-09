<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DoanhNghiepDanhGiaTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('doanhnghiep_danhgia')->insert([
            [
                'dn_id' => 1,
                'dk_id' => 1,
                'diemso' => 9,
                'nhanxet' => 'Rất hài lòng với sinh viên',
                'created_at' => now(),
                 'updated_at' => now()
            ],
            [
                'dn_id' => 2,
                'dk_id' => 2,
                'diemso' => 8.5,
                'nhanxet' => 'Sinh viên làm việc tích cực và nhanh nhẹn',
                'created_at' => now(),
                 'updated_at' => now()
            ],
        ]);
    }
}
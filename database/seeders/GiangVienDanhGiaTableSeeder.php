<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GiangVienDanhGiaTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('giangvien_danhgia')->insert([
            [
                'gv_id' => 1,
                'dk_id' => 1,
                'diemso' => 8.5,
                'nhanxet' => 'Sinh viên làm việc nghiêm túc',
                'created_at' => now(),
                 'updated_at' => now(),
            ],
            [
                'gv_id' => 2,
                'dk_id' => 2,
                'diemso' => 9.0,
                'nhanxet' => 'Sinh viên có tinh thần học hỏi tốt',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
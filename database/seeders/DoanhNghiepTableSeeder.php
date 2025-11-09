<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DoanhNghiepTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('doanhnghiep')->insert([
            [
                'ten_dn' => 'Công ty ABC',
                'dia_chi' => '123 Đường XYZ, Hà Nội',
                'email' => 'abc@example.com',
                'lien_he' => 'Nguyễn Minh An',
                'website' => 'http://abc.com',
                'logo' => null,
                'mo_ta' => 'Doanh nghiệp phần mềm',
                'leader_user_id' => 6,
                'created_at' => now(),
                 'updated_at' => now(),
            ],
            [
                'ten_dn' => 'Công ty DEF',
                'dia_chi' => '456 Đường QWE, TP.HCM',
                'email' => 'def@example.com',
                'lien_he' => 'Phạm Quỳnh Uyên',
                'website' => 'http://def.com',
                'logo' => null,
                'mo_ta' => 'Công ty phát triển ứng dụng',
                'leader_user_id' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
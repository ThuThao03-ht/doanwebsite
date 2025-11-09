<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SinhVienTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('sinhvien')->insert([
            [
                'ma_sv' => 'SV001',
                'ho_ten' => 'Nguyễn Thanh Bảo',
                'lop' => 'CNTT1',
                'nganh' => 'Công nghệ thông tin',
                'email' => 'nguyenthanhbao@example.com',
                'sdt' => '0900000001',
                'user_id' => 2,
                'created_at' => now(),
                // 'updated_at' => now(),
            ],
            [
                'ma_sv' => 'SV002',
                'ho_ten' => 'Bùi Uyên Nhi',
                'lop' => 'CNTT2',
                'nganh' => 'Công nghệ thông tin',
                'email' => 'buiuyennhi@example.com',
                'sdt' => '0900000002',
                'user_id' => 3,
                'created_at' => now(),
                // 'updated_at' => now(),
            ],
              
        ]);
    }
}
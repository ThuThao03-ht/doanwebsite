<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DangKyThucTapTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('dangky_thuctap')->insert([
            [
                'sv_id' => 1,
                'vitri_id' => 1,
                'ngay_dangky' => now(),
                'trang_thai' => 'da_duyet',
                'created_at' => now(),
                 'updated_at' => now()
            ],
            [
                'sv_id' => 2,
                'vitri_id' => 2,
                'ngay_dangky' => now(),
                'trang_thai' => 'da_duyet',
                'created_at' => now(),
                 'updated_at' => now()
            ],
            
        ]);
    }
}
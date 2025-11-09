<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TienDoTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tiendo')->insert([
    [
        'dk_id' => 1,
        'noi_dung' => 'Hoàn thành báo cáo tuần 1',
        'ngay_capnhat' => now(),
        'file_dinhkem' => 'file.pdf',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'dk_id' => 1,
        'noi_dung' => 'Hoàn thành báo cáo tuần 2',
        'ngay_capnhat' => now(),
        'file_dinhkem' => 'file_tiendo_t2.pdf',
        'created_at' => now(),
        'updated_at' => now(),
    ],
]);

    }
}
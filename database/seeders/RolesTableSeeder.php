<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            ['role_name'=>'Admin','created_at'=>now(),'updated_at' => now()],
            ['role_name'=>'SinhVien','created_at'=>now(),'updated_at' => now()],
            ['role_name'=>'GiangVien','created_at'=>now(),'updated_at' => now()],
            ['role_name'=>'DoanhNghiep','created_at'=>now(),'updated_at' => now()],
        ]);
    }
}
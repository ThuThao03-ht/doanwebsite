<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        // Xóa dữ liệu cũ trước khi insert
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('users')->insert([
            [
                'username' => 'admin',
                'password_hash' => Hash::make('123456'),
                'avatar' => null,
                'role_id' => 1,
                'nguoi_tao_id' => null,
                'mat_khau_moi' => 0,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'SV001',
                'password_hash' => Hash::make('123456'),
                'avatar' => null,
                'role_id' => 2,
                'nguoi_tao_id' => 1,
                'mat_khau_moi' => 1,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'SV002',
                'password_hash' => Hash::make('123456'),
                'avatar' => null,
                'role_id' => 2,
                'nguoi_tao_id' => 1,
                'mat_khau_moi' => 1,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'GV001',
                'password_hash' => Hash::make('123456'),
                'avatar' => null,
                'role_id' => 3,
                'nguoi_tao_id' => 1,
                'mat_khau_moi' => 1,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'GV002',
                'password_hash' => Hash::make('123456'),
                'avatar' => null,
                'role_id' => 3,
                'nguoi_tao_id' => 1,
                'mat_khau_moi' => 1,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'Công ty ABC',
                'password_hash' => Hash::make('123456'),
                'avatar' => null,
                'role_id' => 4,
                'nguoi_tao_id' => 1,
                'mat_khau_moi' => 1,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'Công ty DEF',
                'password_hash' => Hash::make('123456'),
                'avatar' => null,
                'role_id' => 4,
                'nguoi_tao_id' => 1,
                'mat_khau_moi' => 1,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],

             
        ]);
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// Import tất cả seeder
use Database\Seeders\RolesTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\SinhVienTableSeeder;
use Database\Seeders\GiangVienTableSeeder;
use Database\Seeders\DoanhNghiepTableSeeder;
use Database\Seeders\ViTriThucTapTableSeeder;
use Database\Seeders\DangKyThucTapTableSeeder;
use Database\Seeders\TienDoTableSeeder;
use Database\Seeders\BaoCaoThucTapTableSeeder;
use Database\Seeders\GiangVienDanhGiaTableSeeder;
use Database\Seeders\DoanhNghiepDanhGiaTableSeeder;
use Database\Seeders\ThongBaoTableSeeder;
use Database\Seeders\ThongBaoUserTableSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Thêm thứ tự để tránh lỗi foreign key
        $this->call([
            RolesTableSeeder::class,
            UsersTableSeeder::class,
            SinhVienTableSeeder::class,
            GiangVienTableSeeder::class,
            DoanhNghiepTableSeeder::class,
            ViTriThucTapTableSeeder::class,
            DangKyThucTapTableSeeder::class,
            TienDoTableSeeder::class,
            BaoCaoThucTapTableSeeder::class,
            GiangVienDanhGiaTableSeeder::class,
            DoanhNghiepDanhGiaTableSeeder::class,
            ThongBaoTableSeeder::class,
            ThongBaoUserTableSeeder::class,
            PhanCongGiangVienTableSeeder::class,
        ]);
    }
}
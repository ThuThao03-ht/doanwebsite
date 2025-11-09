<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BaoCaoThucTapTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('baocao_thuctap')->insert([
            [
                'dk_id' => 1,
                'tieu_de' => 'Báo cáo thực tập',
                'noi_dung'=>'Báo cáo thực tập của công ty ABC về lĩnh vực phát triển phần mềm. Với những kinh nghiệm quý báu thu được trong quá trình thực tập, sinh viên đã áp dụng kiến thức học được vào thực tế, hoàn thành các nhiệm vụ được giao một cách xuất sắc.',
                'file_baocao' => 'baocao_thuctap.pdf',
                'created_at' => now(),
                 'updated_at' => now(),
            ],
            [
                'dk_id' => 2,
                'tieu_de' => 'Báo cáo thực tập',
                'noi_dung'=>'Báo cáo thực tập của công ty DEF về lĩnh vực phát triển ứng dụng di động. Trong thời gian thực tập, sinh viên đã tích lũy được nhiều kỹ năng thực tiễn và đóng góp vào các dự án phát triển ứng dụng một cách hiệu quả.',
                'file_baocao' => 'baocao_kttt.pdf',
                'created_at' => now(),
                 'updated_at' => now(),
            ],
        ]);
    }
}
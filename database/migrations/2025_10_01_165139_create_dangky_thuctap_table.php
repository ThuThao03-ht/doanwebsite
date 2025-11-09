<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dangky_thuctap', function (Blueprint $table) {
            $table->id('dk_id');

            // Khóa ngoại sinh viên
            $table->unsignedBigInteger('sv_id');
            $table->foreign('sv_id')->references('sv_id')->on('sinhvien')->onDelete('cascade');

            // Khóa ngoại vị trí
            $table->unsignedBigInteger('vitri_id');
            $table->foreign('vitri_id')->references('vitri_id')->on('vitri_thuctap')->onDelete('cascade');

            // Ngày đăng ký (default = ngày hiện tại)
            $table->date('ngay_dangky')->default(DB::raw('CURRENT_DATE'));

            // Trạng thái
            $table->enum('trang_thai', [
                'cho_duyet',
                'da_duyet',
                'tu_choi',
                'dang_thuctap',
                'hoan_thanh'
            ])->default('cho_duyet');

            $table->timestamps();
            $table->boolean('is_delete')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dangky_thuctap');
    }
};
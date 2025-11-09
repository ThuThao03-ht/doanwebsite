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
        Schema::create('doanhnghiep_danhgia', function (Blueprint $table) {
            $table->id('dg_dn_id');

            // FK đến bảng dangky_thuctap
            $table->unsignedBigInteger('dk_id');
            $table->foreign('dk_id')
                  ->references('dk_id')
                  ->on('dangky_thuctap')
                  ->onDelete('cascade');

            // FK đến bảng doanhnghiep
            $table->unsignedBigInteger('dn_id');
            $table->foreign('dn_id')
                  ->references('dn_id')
                  ->on('doanhnghiep')
                  ->onDelete('cascade');

            // Người đánh giá (user_id trong bảng users)
            $table->unsignedBigInteger('nguoi_danhgia_id')->nullable();
            $table->foreign('nguoi_danhgia_id')
                  ->references('user_id')
                  ->on('users')
                  ->nullOnDelete();

            $table->decimal('diemso', 4, 2)->nullable(); // 0.00 - 10.00
            $table->text('nhanxet')->nullable();
            $table->date('ngay_danhgia')->default(DB::raw('CURRENT_DATE'));

            $table->timestamps();
            $table->boolean('is_delete')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doanhnghiep_danhgia');
    }
};
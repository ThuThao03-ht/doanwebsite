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
        Schema::create('giangvien_danhgia', function (Blueprint $table) {
            $table->id('dg_id');

            // Khóa ngoại đến bảng dangky_thuctap
            $table->unsignedBigInteger('dk_id');
            $table->foreign('dk_id')
                  ->references('dk_id')
                  ->on('dangky_thuctap')
                  ->onDelete('cascade');

            // Khóa ngoại đến bảng giangvien
            $table->unsignedBigInteger('gv_id');
            $table->foreign('gv_id')
                  ->references('gv_id')
                  ->on('giangvien')
                  ->onDelete('cascade');

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
        Schema::dropIfExists('giangvien_danhgia');
    }
};
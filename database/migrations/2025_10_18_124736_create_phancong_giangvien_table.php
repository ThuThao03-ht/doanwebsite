<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('phancong_giangvien', function (Blueprint $table) {
            $table->id('pc_id');
            $table->unsignedBigInteger('dk_id'); // Mã đăng ký thực tập

            //  Cho phép gv_id và ngay_phancong có thể null
            $table->unsignedBigInteger('gv_id')->nullable(); 
            $table->date('ngay_phancong')->nullable();

            $table->text('ghi_chu')->nullable();
            $table->timestamps();
            $table->boolean('is_delete')->default(false);

            //  Khóa ngoại
            $table->foreign('dk_id')
                ->references('dk_id')
                ->on('dangky_thuctap')
                ->onDelete('cascade');

            $table->foreign('gv_id')
                ->references('gv_id')
                ->on('giangvien')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phancong_giangvien');
    }
};
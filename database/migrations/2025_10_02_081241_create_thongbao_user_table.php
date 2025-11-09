<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('thongbao_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thongbao_id')
                  ->constrained('thongbao', 'tb_id')
                  ->onDelete('cascade');
            $table->foreignId('user_id')
                  ->constrained('users', 'user_id')
                  ->onDelete('cascade');
            $table->boolean('da_doc')->default(false); // 0 = chưa đọc, 1 = đã đọc
            $table->timestamp('thoi_gian_doc')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('thongbao_user');
    }
};
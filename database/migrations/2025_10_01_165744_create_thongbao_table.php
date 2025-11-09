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
        Schema::create('thongbao', function (Blueprint $table) {
            $table->id('tb_id');
            $table->string('tieude', 150)->nullable();
            $table->text('noidung')->nullable();
            $table->date('ngay_gui')->default(DB::raw('CURRENT_DATE'));
            $table->foreignId('nguoi_gui_id')->nullable()->constrained('users', 'user_id');
            $table->enum('doi_tuong', ['tat_ca', 'sinhvien', 'giangvien', 'doanhnghiep'])->default('tat_ca');
            $table->timestamps();
            $table->boolean('is_delete')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thongbao');
    }
};
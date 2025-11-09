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
        Schema::create('baocao_thuctap', function (Blueprint $table) {
            $table->id('baocao_id');

            // Khóa ngoại tới đăng ký thực tập
            $table->unsignedBigInteger('dk_id');
            $table->foreign('dk_id')
                  ->references('dk_id')
                  ->on('dangky_thuctap')
                  ->onDelete('cascade');

            $table->string('tieu_de', 150)->nullable();
            $table->text('noi_dung')->nullable();
            $table->date('ngay_nop')->default(DB::raw('CURRENT_DATE'));
            $table->string('file_baocao', 255)->nullable();

            $table->timestamps();
            $table->boolean('is_delete')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('baocao_thuctap');
    }
};
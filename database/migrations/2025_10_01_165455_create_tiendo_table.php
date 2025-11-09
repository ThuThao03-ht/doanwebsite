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
        Schema::create('tiendo', function (Blueprint $table) {
            $table->id('tiendo_id');

            // Khóa ngoại tới đăng ký thực tập
            $table->unsignedBigInteger('dk_id');
            $table->foreign('dk_id')
                  ->references('dk_id')
                  ->on('dangky_thuctap')
                  ->onDelete('cascade');

            $table->text('noi_dung');
            $table->date('ngay_capnhat')->default(DB::raw('CURRENT_DATE'));
            $table->string('file_dinhkem', 255)->nullable();

            $table->timestamps();
            $table->boolean('is_delete')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiendo');
    }
};
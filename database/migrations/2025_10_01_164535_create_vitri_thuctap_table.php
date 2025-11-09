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
        Schema::create('vitri_thuctap', function (Blueprint $table) {
            $table->id('vitri_id');

            // ✅ Thêm mã vị trí thực tập
            $table->string('ma_vitri', 20)->unique();

            // ✅ Tham chiếu doanh nghiệp
            $table->unsignedBigInteger('dn_id');
            $table->foreign('dn_id')
                ->references('dn_id')
                ->on('doanhnghiep')
                ->onDelete('cascade');

            $table->string('ten_vitri', 150);
            $table->text('mo_ta')->nullable();
            $table->text('yeu_cau')->nullable();
            $table->integer('soluong')->default(1);
            $table->integer('so_luong_da_dangky')->default(0);

            // ✅ Thêm trạng thái mới "day" (đã đủ số lượng)
          $table->enum('trang_thai', ['con_han', 'het_han', 'day'])->default('con_han');
            $table->timestamps();
            $table->boolean('is_delete')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vitri_thuctap');
    }
};
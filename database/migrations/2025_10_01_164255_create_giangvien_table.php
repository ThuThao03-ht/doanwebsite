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
        Schema::create('giangvien', function (Blueprint $table) {
            $table->id('gv_id');
            $table->string('ma_gv',20)->unique();
            $table->string('ho_ten',100);
            $table->string('bo_mon',100)->nullable();
            $table->string('email',100)->unique();
            $table->string('sdt',20)->nullable();

            // FK tới users.user_id
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('user_id')->on('users')->nullOnDelete();

            $table->timestamps();
            $table->boolean('is_delete')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('giangvien');
    }
};
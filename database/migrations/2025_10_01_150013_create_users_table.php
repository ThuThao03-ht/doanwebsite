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
 // bảng users
Schema::create('users', function (Blueprint $table) {
    $table->id('user_id');
    $table->string('username',50)->unique();
    $table->string('password_hash');
    $table->string('avatar')->nullable();
    $table->unsignedBigInteger('role_id');  
    $table->unsignedBigInteger('nguoi_tao_id')->nullable();
    $table->boolean('mat_khau_moi')->default(true);
    $table->enum('status',['active','inactive'])->default('active');
    $table->timestamps();
    $table->boolean('is_delete')->default(false);

    // fix FK
    $table->foreign('role_id')->references('role_id')->on('roles');
    $table->foreign('nguoi_tao_id')->references('user_id')->on('users');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
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
        Schema::create('doanhnghiep', function (Blueprint $table) {
            $table->id('dn_id');
            $table->string('ten_dn',150);
            $table->string('dia_chi',255)->nullable();
            $table->string('email',100)->unique();
            $table->string('lien_he',100)->nullable();
            $table->string('website',100)->nullable();
            $table->string('logo',255)->nullable();
            $table->text('mo_ta')->nullable();

            // FK tới users.user_id
            $table->unsignedBigInteger('leader_user_id')->nullable();
            $table->foreign('leader_user_id')
                  ->references('user_id')->on('users')
                  ->nullOnDelete();

            $table->timestamps();
            $table->boolean('is_delete')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doanhnghiep');
    }
};
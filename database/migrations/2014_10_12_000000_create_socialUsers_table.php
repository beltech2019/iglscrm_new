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
        Schema::create('tb_social_user', function (Blueprint $table) {
            $table->id('id');
            $table->string('user_id')->unique()->nullable();
            $table->string('name');
            $table->timestamp('date_modified')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->string('user_name')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_social_user');
    }
};

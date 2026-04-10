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
        Schema::create('tb_column', function (Blueprint $table) {
            $table->id('id');
            $table->string('column');
            $table->string('type');
            $table->integer('is_show')->default(1);
            $table->integer('sort_order')->default(0);
            $table->string('db_field');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_column');
    }
};

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
        Schema::create('tb_activity', function (Blueprint $table) {
            $table->id('id');
            $table->string('text');
            $table->string('email')->nullable();
            $table->integer('created_by')->nullable();
            $table->string('type')->nullable();
            $table->tinyInteger('is_mail')->default('0');
            $table->integer('post_id');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_activity');
    }
};

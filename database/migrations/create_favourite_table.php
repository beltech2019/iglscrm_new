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
        Schema::create('tb_favourite', function (Blueprint $table) {
            $table->id('id');
            $table->string('user_id');
            $table->string('type');
            $table->timestamp('date_created')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->string('type_id');
            $table->string('status')->default('0');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_favourite');
    }
};

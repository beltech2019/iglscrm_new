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
        Schema::create('page_languages', function (Blueprint $table) {
            $table->id('language_id');
            $table->string('update_counter')->nullable();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('language_code');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_languages');
    }
};

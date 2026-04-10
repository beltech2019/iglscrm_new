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
        Schema::create('page_control_type', function (Blueprint $table) {
            $table->id('control_type_id');
            $table->string('update_counter')->nullable();
            $table->string('control_name');
            $table->enum('control_type',['Multiple','Single'])->default('Multiple');
            $table->string('control_description')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_control_type');
    }
};

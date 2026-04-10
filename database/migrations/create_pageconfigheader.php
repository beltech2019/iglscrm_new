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
        Schema::create('page_config_header', function (Blueprint $table) {
            $table->id('page_config_header_id');
            $table->string('page_type');
            $table->string('update_counter')->nullable();
            $table->string('total_questions')->nullable();
            $table->string('catagory')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_config_header');
    }
};

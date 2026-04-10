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
        Schema::create('page_config', function (Blueprint $table) {
            $table->id('page_config_id');
            $table->string('update_counter')->nullable();
            $table->string('page_name');
            $table->string('Page_desc')->nullable();
            $table->string('Page_number')->nullable();
            $table->string('icon_name')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_config');
    }
};

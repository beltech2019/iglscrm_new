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
        Schema::create('page_config_details', function (Blueprint $table) {
            $table->id('page_config_details_id');
            $table->foreign('page_config_header_id')
            ->references('page_config_header_id')->on('page_config_header')->onDelete('cascade');
            $table->foreign('page_config_id')
              ->references('page_config_id')->on('page_config')->onDelete('cascade');
            $table->string('update_counter')->nullable();
            $table->string('answer_type')->nullable();
            $table->string('range');
            $table->string('page_question_i18_id')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_config_details');
    }
};

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
        Schema::create('page_question_mapping', function (Blueprint $table) {
            $table->id('page_question_mapping_id');
            $table->foreign('page_config_id ')
            ->references('page_config_id ')->on('page_config')->onDelete('cascade');
            $table->foreign('page_question_config_id ')
              ->references('page_question_config_id ')->on('page_question_config')->onDelete('cascade');
            $table->string('update_counter')->nullable();
            $table->tinyInteger('is_mandatory?')->default('0');
            $table->string('show_if_response(x_y)');
            $table->enum('qtype',['Main','Sub'])->default('Main');
            $table->tinyInteger('special_logic_code	')->default('0');
            $table->enum('response_arrangement',['Horizontal','Vertical'])->default('Horizontal');
            $table->string('no_of_columns');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_question_mapping');
    }
};

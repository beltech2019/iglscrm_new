<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_report_mapping', function (Blueprint $table) {
            $table->id('id');
            $table->integer('report_id');
            $table->string('field_lable')->nullable();
            $table->string('field_key')->nullable();
            $table->integer('order_by')->nullable();
            $table->string('operator')->nullable();
            $table->string('table_name')->nullable();
            $table->string('logic')->nullable();
            $table->string('value')->nullable();
            $table->string('custom_field_value')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_report_mapping');
    }
};
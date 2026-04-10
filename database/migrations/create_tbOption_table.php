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
        Schema::create('tb_option', function (Blueprint $table) {
            $table->id('id');
            $table->string('key');
            $table->string('value');
            $table->string('subvalue')->nullable();
            $table->integer('sortOrder')->default(0);
            $table->string('specialLogic')->nullable();
            $table->integer('status')->default(1);
            $table->string('label')->nullable();
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
        Schema::dropIfExists('tb_option');
    }
};
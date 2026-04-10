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
        Schema::create('tb_post_type_rules', function (Blueprint $table) {
            $table->id('ruleid');
            $table->string('keyword');
            $table->string('category');
            $table->string('type');
            $table->enum('status',['Active','Inactive'])->default('Active');
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
        Schema::dropIfExists('tb_post_type_rules');
    }
};
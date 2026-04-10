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
        Schema::create('tb_user_assign_rule', function (Blueprint $table) {
            $table->id('id');
            $table->string('user_id');
            $table->string('name')->nullable();
            $table->string('Keyword');
            $table->string('social_type')->nullable();
            $table->string('assign_type')->nullable();
            $table->string('enable')->default('1');
            $table->string('from_date');
            $table->string('to_date');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_user_assign_rule');
    }
};

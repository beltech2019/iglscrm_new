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
        Schema::create('tb_conversation', function (Blueprint $table) {
            $table->id('id');
            $table->string('conversation_id');
            $table->string('socialpost_id');
            $table->string('message_id');
            $table->string('sender_id');
            $table->string('sender_name')->nullable();
            $table->string('sender_username')->nullable();
            $table->string('source');
            $table->string('message_time');
            $table->longText('message');
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
        Schema::dropIfExists('tb_conversation');
    }
};
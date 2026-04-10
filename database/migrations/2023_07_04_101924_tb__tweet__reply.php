<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_tweet_reply', function (Blueprint $table) {
            $table->id('post_id');
            $table->string('replyToTweetId');
            $table->string('tweeter_id');
            $table->string('tweeter_text');
            $table->string('socialUser_id');
            $table->string('media_type')->default('Text');
            $table->string('user_id')->nullable();
            $table->string('url')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_tweet_reply');
    }


};

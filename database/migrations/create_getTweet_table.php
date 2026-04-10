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
        Schema::create('tb_getTweet', function (Blueprint $table) {
            $table->id('id');
            $table->string('getTweet_id');
            $table->text('postMessage')->nullable();
            $table->string('socialUser-name')->nullable();
            $table->string('socialUser-userName')->nullable();
            $table->string('socialUser_id')->nullable();
            $table->enum('source',['Facebook','Twitter','Instagram','Linkedin','Whatsapp']);
            $table->string('postUrl')->nullable();
            $table->string('postDate')->nullable();
            $table->string('istpostDate')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('email_id')->nullable();
            $table->string('assigned_to')->nullable();
            $table->enum('status',['New','Pending','Under Process','Allocated to IGL Representatives','Unallocated','Discarded','Duplicate'])->default('New');
            $table->string('converted')->default('0');
            $table->string('convertLead')->default('0');
            $table->enum('post_category',['Feedback Positive','Feedback Negative','Complaint','Query','Information','Spam'])->nullable();
            $table->tinyInteger('post_reply')->default('0');
            $table->tinyInteger('responed')->defalut('0');
            $table->longText('other_info')->nullable('0');
            $table->timestamp('responseDate')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->string('type')->nullable();
            $table->string('dm_status')->nullable();
            $table->string('dm_startdate')->nullable();
            $table->string('conversation_id')->nullable();
            $table->string('bp_number')->nullable();
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
        Schema::dropIfExists('tb_getTweet');
    }
};
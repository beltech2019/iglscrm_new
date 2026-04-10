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
        Schema::create('tb_socialTicket', function (Blueprint $table) {
            $table->id('id');
            $table->string('ticket_id')->nullable();
            $table->string('getTweet_id');
            $table->text('postMessage')->nullable();
            $table->string('socialUser')->nullable();
            $table->string('subSource')->nullable();
            $table->enum('source',['Facebook','Twitter','Instagram','Linkedin','Whatsapp',]);
            $table->string('socialUser_id')->nullable();
            $table->enum('status',['New','Assigned','Pending with team','Move to internal Team','Resolved','Rejected','Recived','Close','Duplicate']);
            $table->enum('priority',['Low','Medium','High'])->nullable();
            $table->string('converted')->nullable();
            // $table->string('number')->nullable();
            $table->enum('type',['Complaint','Emergency','Request Service','Other Query'])->nullable();
            $table->string('bipNumber')->nullable();
            $table->text('subject')->nullable();
            $table->string('suggestion')->nullable();
            $table->string('description')->nullable();
            $table->string('additional_Text')->nullable();
            $table->string('resolution')->nullable();
            $table->enum('final_state',['Open','In Process','Close'])->nullable();
            $table->string('date_Created')->nullable();
            $table->string('postUrl')->nullable();
            $table->string('postDate')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('email_id')->nullable();
            $table->string('assigned_to')->nullable();
            $table->tinyInteger('internalUpdate')->default('0');
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
        Schema::dropIfExists('tb_socialTicket');
    }
};
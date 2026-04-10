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
        Schema::create('tb_leads', function (Blueprint $table) {
            $table->id('id');
            $table->string('getTweet_id')->nullable();
            $table->string('greeting_first_name')->nullable();
            $table->string('socialUser_id');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->enum('type',['Hot', 'Warm', 'Cold'])->nullable();
            $table->text('title')->nullable();
            $table->string('department')->nullable();
            $table->string('customer_name')->nullable();
            $table->enum('status',['New','Assigned','In Process','Converted','Recycled','Dead','Duplicate']);
            $table->string('office_phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('website')->nullable();
            $table->enum('approval_status',['In Review', 'Qualified', 'Not Qualified'])->nullable();
            $table->string('primary_address')->nullable();
            $table->string('primary_city')->nullable();
            $table->string('primary_state')->nullable();
            $table->string('primary_postal_code')->nullable();
            $table->string('primary_country')->nullable();
            $table->string('other_address')->nullable();
            $table->string('other_city')->nullable();
            $table->string('other_state')->nullable();
            $table->string('other_postal_code')->nullable();
            $table->string('other_country')->nullable();
            $table->string('email_address')->nullable();
            $table->string('converted')->nullable();
            $table->text('description')->nullable();
            $table->string('fax')->nullable();
            $table->string('partner_contacts')->nullable();
            $table->enum('lead_source',['Facebook', 'Twitter','Instagram','Linkedin', 'Whatsapp','Other','Portal','Call','Inbounced Email']);
            $table->string('assigned_to')->nullable();
            $table->timestamp('created_date')->nullable();
            $table->string('leadBy')->nullable();
            $table->integer('leadById')->nullable();
            $table->string('leadId')->nullable();
            $table->string('convertedtoticket')->nullable();
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
        Schema::dropIfExists('tb_leads');
    }
};
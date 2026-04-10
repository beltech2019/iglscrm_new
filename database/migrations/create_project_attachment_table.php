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
        Schema::create('tb_projectattachment', function (Blueprint $table) {
            $table->id('id');
            $table->string('attachment_id');
            $table->longText('fileName');
            $table->longText('filePath');
            $table->longText('fileUrl');
            $table->string('upload_time');
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
        Schema::dropIfExists('tb_projectattachment');
    }
};
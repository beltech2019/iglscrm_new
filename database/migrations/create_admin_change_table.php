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
        Schema::create('tb_admin_change', function (Blueprint $table) {
            $table->id();
            $table->text('new_value')->nullable();
            $table->text('old_value')->nullable();
            $table->string('change_by')->nullable();
            $table->timestamp('change_date')->nullable();
            $table->string('table_name');
            $table->string('field_id')->nullable();
            $table->string('field')->nullable();
            $table->string('assignto_by_id')->nullable();
            $table->string('operation')->nullable();
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
        Schema::dropIfExists('tb_admin_change');
    }
};
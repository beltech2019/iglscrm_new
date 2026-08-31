<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('salesforce_address_masters', function (Blueprint $table) {
        $table->id();

        $table->string('salesforce_id')->unique();
        $table->string('name')->nullable();

        $table->string('area')->nullable();
        $table->string('zone')->nullable();
        $table->string('control_room_name')->nullable();

        $table->string('city')->nullable();
        $table->string('state')->nullable();
        $table->string('pincode')->nullable();

        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salesforce_address_masters');
    }
};

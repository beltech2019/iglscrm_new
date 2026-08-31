<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salesforce_cases', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('ticket_id');

            $table->string('salesforce_case_id')->nullable();
            $table->string('case_number')->nullable();

            $table->string('record_type')->nullable();
            $table->string('status')->nullable();

            $table->timestamps();

            $table->index('ticket_id');
            $table->index('salesforce_case_id');
            $table->index('case_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salesforce_cases');
    }
};
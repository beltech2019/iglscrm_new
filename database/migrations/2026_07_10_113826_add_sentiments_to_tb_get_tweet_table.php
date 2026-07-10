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
        Schema::table('tb_getTweet', function (Blueprint $table) {
            $table->enum('sentiment', [
                'Positive',
                'Negative',
                'Neutral'
            ])->nullable()->after('post_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_getTweet', function (Blueprint $table) {
            $table->dropColumn('sentiment');
        });
    }
};
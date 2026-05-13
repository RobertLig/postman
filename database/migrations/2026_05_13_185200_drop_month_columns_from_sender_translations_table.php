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
        Schema::table('sender_translations', function (Blueprint $table) {
            $table->dropColumn([
                'posting_month',
                'reception_month',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sender_translations', function (Blueprint $table) {
            $table->text('posting_month')->after('reception_place');
            $table->text('reception_month')->after('posting_month');
        });
    }
};

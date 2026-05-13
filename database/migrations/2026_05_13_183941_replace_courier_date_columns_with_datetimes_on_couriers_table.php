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
        Schema::table('couriers', function (Blueprint $table) {

            // New columns
            $table->dateTime('posting_at')->nullable()->after('user_id');
            $table->dateTime('reception_at')->nullable()->after('posting_at');

            // Remove old columns
            $table->dropColumn([
                'posting_day',
                'posting_year',
                'posting_hour',
                'posting_minute',

                'reception_day',
                'reception_year',
                'reception_hour',
                'reception_minute',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('couriers', function (Blueprint $table) {

            // Restore old columns
            $table->tinyInteger('posting_day');
            $table->year('posting_year');
            $table->tinyInteger('posting_hour');
            $table->tinyInteger('posting_minute');

            $table->tinyInteger('reception_day');
            $table->year('reception_year');
            $table->tinyInteger('reception_hour');
            $table->tinyInteger('reception_minute');

            // Remove new columns
            $table->dropColumn([
                'posting_at',
                'reception_at',
            ]);
        });
    }
};

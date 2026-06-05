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
        Schema::table('senders', function (Blueprint $table) {
            $table->decimal('posting_latitude', 10, 7)->nullable();

            $table->decimal('posting_longitude', 10, 7)->nullable();

            $table->decimal('reception_latitude', 10, 7)->nullable();

            $table->decimal('reception_longitude', 10, 7)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('senders', function (Blueprint $table) {
            $table->dropColumn([
                'posting_latitude',
                'posting_longitude',
                'reception_latitude',
                'reception_longitude',
            ]);
        });
    }
};

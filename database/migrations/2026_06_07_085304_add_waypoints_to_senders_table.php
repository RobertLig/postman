<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('senders', function (Blueprint $table) {
            $table->json('waypoints')->nullable()
                ->after('reception_longitude');
        });
    }

    public function down(): void
    {
        Schema::table('senders', function (Blueprint $table) {
            $table->dropColumn('waypoints');
        });
    }
};

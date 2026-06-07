<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courier_translations', function (Blueprint $table) {
            $table->json('waypoints')
                ->nullable()
                ->after('reception_place');
        });
    }

    public function down(): void
    {
        Schema::table('courier_translations', function (Blueprint $table) {
            $table->dropColumn('waypoints');
        });
    }
};

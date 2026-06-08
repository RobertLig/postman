<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sender_translations', function (Blueprint $table) {
            $table->renameColumn('waypoints', 'waypoint_labels');
        });
    }

    public function down(): void
    {
        Schema::table('sender_translations', function (Blueprint $table) {
            $table->renameColumn('waypoint_labels', 'waypoints');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Normalize existing data (VERY IMPORTANT)
        DB::table('senders')
            ->whereNull('library')
            ->update(['library' => json_encode([])]);

        // 2. Change column definition
        Schema::table('senders', function (Blueprint $table) {
            $table->json('library')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('senders', function (Blueprint $table) {
            $table->json('library')->nullable()->default(null)->change();
        });
    }
};

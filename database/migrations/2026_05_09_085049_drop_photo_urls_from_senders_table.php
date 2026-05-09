<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('senders', function (Blueprint $table) {
            $table->dropColumn([
                'photo_url_1',
                'photo_url_2',
                'photo_url_3',
                'photo_url_4',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('senders', function (Blueprint $table) {
            $table->text('photo_url_1')->nullable();
            $table->text('photo_url_2')->nullable();
            $table->text('photo_url_3')->nullable();
            $table->text('photo_url_4')->nullable();
        });
    }
};

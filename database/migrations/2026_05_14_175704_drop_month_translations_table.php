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
        Schema::dropIfExists('month_translations');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('month_translations', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('month_id');
            $table->string('month');
            $table->smallInteger('language_id');
            $table->timestamps();
        });
    }
};

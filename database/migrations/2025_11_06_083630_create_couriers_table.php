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
        Schema::create('couriers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('posting_day');
            $table->year('posting_year');
            $table->tinyInteger('posting_hour');
            $table->tinyInteger('posting_minute');
            $table->tinyInteger('reception_day');
            $table->year('reception_year');
            $table->tinyInteger('reception_hour');
            $table->tinyInteger('reception_minute');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('couriers');
    }
};

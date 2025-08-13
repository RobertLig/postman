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
        Schema::create('sender_announcement_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_announcement_id');
            $table->unsignedBigInteger('lang_id');
            $table->string('thing');
            $table->text('description')->nullable();
            $table->text('posting_place');
            $table->text('reception_place');
            $table->text('posting_month');
            $table->text('reception_month');
            $table->timestamps();

            $table->foreign('sender_announcement_id')->references('id')->on('sender_announcements')->onDelete('cascade');
            $table->foreign('lang_id')->references('id')->on('languages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sender_announcement_translations');
    }
};

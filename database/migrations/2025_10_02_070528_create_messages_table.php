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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_announcement_id')->nullable();
            //$table->unsignedBigInteger('courier_announcement_id')->nullable();
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('recipient_id');
            $table->text('message')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->foreign('sender_announcement_id')->references('id')->on('sender_announcements')->onDelete('cascade');
            //$table->foreign('courier_announcement_id')->references('id')->on('courier_announcements')->onDelete('cascade');
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};

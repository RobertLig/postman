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
        Schema::dropIfExists('messages');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sender_announcement_id')->nullable();
            $table->foreignId('courier_announcement_id')->nullable();

            $table->foreignId('sender_id');
            $table->foreignId('recipient_id')->nullable();

            $table->text('message')->nullable();

            $table->boolean('is_read')->default(false);
            $table->boolean('is_deleted')->default(false);

            $table->timestamps();
        });
    }
};

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
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            $table->string('role')
                ->nullable();

            $table->string('avatar')
                ->nullable();

            $table->tinyInteger('rating')
                ->default(5);

            $table->text('content');

            $table->boolean('is_featured')
                ->default(true);

            $table->boolean('is_active')
                ->default(true);

            $table->timestamp('published_at')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};

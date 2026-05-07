<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Rename tables
        |--------------------------------------------------------------------------
        */

        Schema::rename(
            'sender_announcements',
            'senders'
        );

        Schema::rename(
            'sender_announcement_translations',
            'sender_translations'
        );

        Schema::rename(
            'sender_announcement_weights',
            'sender_weights'
        );

        Schema::rename(
            'sender_announcement_dimensions',
            'sender_dimensions'
        );

        /*
        |--------------------------------------------------------------------------
        | Rename foreign key columns
        |--------------------------------------------------------------------------
        */

        Schema::table('sender_translations', function (Blueprint $table) {

            $table->renameColumn(
                'sender_announcement_id',
                'sender_id'
            );
        });

        Schema::table('sender_weights', function (Blueprint $table) {

            $table->renameColumn(
                'sender_announcement_id',
                'sender_id'
            );
        });

        Schema::table('sender_dimensions', function (Blueprint $table) {

            $table->renameColumn(
                'sender_announcement_id',
                'sender_id'
            );
        });
    }

    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Revert foreign key columns
        |--------------------------------------------------------------------------
        */

        Schema::table('sender_translations', function (Blueprint $table) {

            $table->renameColumn(
                'sender_id',
                'sender_announcement_id'
            );
        });

        Schema::table('sender_weights', function (Blueprint $table) {

            $table->renameColumn(
                'sender_id',
                'sender_announcement_id'
            );
        });

        Schema::table('sender_dimensions', function (Blueprint $table) {

            $table->renameColumn(
                'sender_id',
                'sender_announcement_id'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | Revert table names
        |--------------------------------------------------------------------------
        */

        Schema::rename(
            'senders',
            'sender_announcements'
        );

        Schema::rename(
            'sender_translations',
            'sender_announcement_translations'
        );

        Schema::rename(
            'sender_weights',
            'sender_announcement_weights'
        );

        Schema::rename(
            'sender_dimensions',
            'sender_announcement_dimensions'
        );
    }
};

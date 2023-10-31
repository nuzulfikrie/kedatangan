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
        //reminder send status
        Schema::create('reminder_send_status', function (Blueprint $table) {
            $table->id();

            $table->foreignId('reminder_id')->constrained(
                table: 'reminders',
                indexName: 'rms_reminder_id'
            );
            $table->foreignId('user_id')->constrained(
                table: 'users',
                indexName: 'rms_user_id'
            );
            $table->foreignId('school_id')->constrained(
                table: 'schools_institutions',
                indexName: 'rms_school_id'
            );

            $table->integer('status')->nullable(false)->length(11);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

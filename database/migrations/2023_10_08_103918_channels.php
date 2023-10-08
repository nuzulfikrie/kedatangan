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
        //table channels. stores channels for reminders - bot or email ., contain chatid and email , for a particular user id
        Schema::create('channels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(
                table: 'users',
                indexName: 'channelsuser_user_id'
            );
            $table->string('channel')->nullable(false)->length(10);
            $table->string('chatid')->nullable(true)->length(255);
            $table->string('email')->nullable(true)->length(255);
            //boolean active
            $table->boolean('active')->nullable(false)->default(1);
            //token for activation and deletion
            $table->string('token')->nullable(true)->length(64);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('channels');
    }
};

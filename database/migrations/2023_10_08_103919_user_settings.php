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
        //user settings table,
        Schema::create(
            'user_settings',
            function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained(
                    table: 'users',
                    indexName: 'channels_user_id'
                );
                $table->string('setting')->nullable(false)->length(255);
                $table->string('value')->nullable(false)->length(255);
                //boolean active
                $table->boolean('active')->nullable(false)->default(1);
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('user_settings');
    }
};

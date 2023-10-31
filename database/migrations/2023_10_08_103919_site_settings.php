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
        //site settings table,
        Schema::create(
            'site_settings',
            function (Blueprint $table) {
                $table->id();
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
        Schema::dropIfExists('site_settings');
    }
};

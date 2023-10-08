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
        Schema::create('schools_institutions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false)->length(255);
            $table->string('address')->nullable(false)->limit(500);
            $table->string('phone_number')->nullable(false)->limit(15);
            $table->string('school_email')->nullable(false)->limit(255);
            $table->string('school_website')->nullable(false)->limit(255);
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

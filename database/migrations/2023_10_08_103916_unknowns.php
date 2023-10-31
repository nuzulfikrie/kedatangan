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
        //table unknowns, stores date of date where child state was unknown both in attendance and nonattendance
        Schema::create('unknowns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained(
                table: 'childs',
                indexName: 'unknowns_child_id'
            );
            $table->date('date')->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('unknowns');
    }
};

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
        //reminders table
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained(
                table: 'childs',
                indexName: 'reminders_child_id'
            );
            $table->date('date')->nullable(false);
            $table->string('reminder')->nullable(false)->length(255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('reminders');
    }
};

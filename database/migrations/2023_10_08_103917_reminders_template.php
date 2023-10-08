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
        //table reminders_template
        Schema::create('reminders_template', function (Blueprint $table) {
            $table->id();
            $table->string('reminder')->nullable(false)->length(255);
            //active flag
            $table->boolean('active')->default(true);
            $table->foreignId('admin_id')->constrained(
                table: 'users',
                indexName: 'reminders_admin_id'
            );


            $table->foreignId('school_id')->constrained(
                table: 'schools_institutions',
                indexName: 'reminders_template_school_id'
            );
            //language
            $table->string('language')->nullable(false)->length(10);
            //channel - bot or email
            $table->string('channel')->nullable(false)->length(10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('reminders_template');
    }
};

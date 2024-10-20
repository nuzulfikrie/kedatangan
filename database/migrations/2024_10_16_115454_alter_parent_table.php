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
        //alter parents table , add column race
        Schema::table('parents', function (Blueprint $table) {
            $table->string('race')->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //reverse the  migration
        Schema::table('parents', function (Blueprint $table) {
            $table->dropColumn('race');
        });
    }
};

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
        //add flag boolean to table schools_institutions
        Schema::table('schools_institutions', function (Blueprint $table) {
            $table->boolean('record_active')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('schools_institutions', function (Blueprint $table) {
            $table->dropColumn('record_active');
        });
    }
};

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
        //table schools institutions add soft deletes column deleted_at
        Schema::table('schools_institutions', function (Blueprint $table) {
            $table->softDeletes('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //reverse
        Schema::table('schools_institutions', function (Blueprint $table) {
            $table->dropSoftDeletes('deleted_at');
        });
    }
};

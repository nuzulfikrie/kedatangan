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
        //use table SchoolsInstitutions, rename column user_id to school_admin_id
        Schema::table('schools_admin', function (Blueprint $table) {
            $table->renameColumn('user_id', 'school_admin_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //REVERSE ABOVE PROCESS
        Schema::table('schools_admin', function (Blueprint $table) {
            $table->renameColumn('school_admin_id', 'user_id');
        });
    }
};

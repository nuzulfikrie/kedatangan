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
        Schema::table('users', function (Blueprint $table) {
            //add column boolean mark as admin after password

            $table->boolean('is_admin')->default(false)->after('password');
            //add column role
            $table->string('role')->default('user')->after('is_admin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //drop column is_admin and role
            $table->dropColumn('is_admin');
            $table->dropColumn('role');
        });
    }
};

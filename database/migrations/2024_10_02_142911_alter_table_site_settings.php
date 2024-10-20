<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * alter table site_settings , drop column setting , add column key ,value , description, group
     */
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn('setting');
            $table->string('key')->nullable()->after('id');
            $table->string('description')->nullable()->after('value');
            $table->string('group')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('setting')->nullable()->after('id');
            $table->dropColumn('key');
            $table->dropColumn('description');
            $table->dropColumn('group');
        });
    }
};

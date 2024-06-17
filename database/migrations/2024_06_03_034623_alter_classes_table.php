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
        Schema::table('classes', function (Blueprint $table) {
            if (Schema::hasColumn('classes', 'child_id')) {
                Schema::disableForeignKeyConstraints();
                $table->dropForeign('classes_child_id');
                $table->dropColumn('child_id');
                Schema::enableForeignKeyConstraints();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            if (!Schema::hasColumn('classes', 'child_id')) {
                $table->foreignId('child_id')->constrained('childs')->onDelete('cascade');
            }
        });
    }
};

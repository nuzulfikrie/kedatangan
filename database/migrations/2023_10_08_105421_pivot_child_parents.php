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
        //pivot table, childs parents as a many to many relationship
        Schema::create(
            'child_parents',
            function (Blueprint $table) {
                $table->id();
                $table->foreignId('child_id')->constrained(
                    table: 'childs',
                    indexName: 'channels_child_id'
                );
                $table->foreignId('parent_id')->constrained(
                    table: 'parents',
                    indexName: 'channels_parent_id'
                );

                //boolean active
                $table->boolean('active')->nullable(false)->default(1);
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('child_parents');
    }
};

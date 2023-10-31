<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * - Class Table
CREATE TABLE class (
class_id INT AUTO_INCREMENT PRIMARY KEY,
class_name VARCHAR(255) NOT NULL,
school_id INT,
FOREIGN KEY (school_id) REFERENCES school_institution(school_id)
);
     */
    public function up(): void
    {
        //

        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained(
                table: 'schools_institutions',
                indexName: 'classes_school_id'
            );
            $table->foreignId('child_id')->constrained(
                table: 'childs',
                indexName: 'classes_child_id'
            );
            $table->string('class_name')->nullable(false)->length(255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('classes');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * - Children Table
CREATE TABLE child (
child_id INT AUTO_INCREMENT PRIMARY KEY,
child_name VARCHAR(255) NOT NULL,
child_date_of_birth DATE,
child_gender VARCHAR(10),
child_picture VARCHAR(255),
school_id INT,
FOREIGN KEY (school_id) REFERENCES school_institution(school_id)
);
     */
    public function up(): void
    {
        //a child, can have multiple parents
        //a child, can only have one school
        Schema::create('childs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained(
                table: 'schools_institutions',
                indexName: 'childs_school_id'
            );
            $table->string('child_name')->nullable(false)->length(255);
            $table->string('child_gender')->nullable(false)->length(15);
            $table->string('email')->nullable(false)->length(255)->unique();
            $table->string('picture_path')->nullable(false)->length(255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('childs');
    }
};

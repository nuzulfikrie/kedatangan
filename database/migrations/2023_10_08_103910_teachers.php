<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * - Teachers Table
CREATE TABLE teachers (
teacher_id INT AUTO_INCREMENT PRIMARY KEY,
teacher_name VARCHAR(255) NOT NULL,
teacher_specialization VARCHAR(255),
user_id INT,
school_id INT,
picture_path VARCHAR(255),
created DATETIME,
modified DATETIME,
FOREIGN KEY (user_id) REFERENCES users(user_id),
FOREIGN KEY (school_id) REFERENCES school_institution(school_id)
);
     */
    public function up(): void
    {
        //
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('teacher_name')->nullable(false)->length(255);
            $table->string('teacher_specialization')->nullable(false)->length(255);
            $table->foreignId('user_id')->constrained(
                table: 'users',
                indexName: 'teachers_user_id'
            );

            $table->foreignId('school_id')->constrained(
                table: 'schools_institutions',
                indexName: 'schools_institutions_id'
            );
            $table->string('picture_path')->nullable(false)->length(255);
            $table->timestamps();

            //foreign key
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('teachers');
    }
};

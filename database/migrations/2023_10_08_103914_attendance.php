<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * - Attendance Table
CREATE TABLE attendance (
attendance_id INT AUTO_INCREMENT PRIMARY KEY,
child_id INT,
date DATE,
status VARCHAR(50) NOT NULL,
FOREIGN KEY (child_id) REFERENCES child(child_id)
);
     */
    public function up(): void
    {
        //

        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained(
                table: 'childs',
                indexName: 'attendance_child_id'
            );
            $table->date('date')->nullable(false);
            $table->string('status')->nullable(false)->length(50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('attendance');
    }
};

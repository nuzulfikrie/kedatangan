<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * - Non-Attendance Table
CREATE TABLE nonattendance (
nonattendance_id INT AUTO_INCREMENT PRIMARY KEY,
child_id INT,
date DATE,
reason VARCHAR(255) NOT NULL,
FOREIGN KEY (child_id) REFERENCES child(child_id)
);
     */
    public function up(): void
    {
        //
        Schema::create('nonattendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained(
                table: 'childs',
                indexName: 'nonattendance_child_id'
            );
            $table->date('date')->nullable(false);
            $table->string('reason')->nullable(false)->length(255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('nonattendance');
    }
};

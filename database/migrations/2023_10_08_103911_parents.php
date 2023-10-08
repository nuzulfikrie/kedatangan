<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * - Parents Table
CREATE TABLE parents (
parent_id INT AUTO_INCREMENT PRIMARY KEY,
user_id INT,
parent_name VARCHAR(255) NOT NULL,
phone_number VARCHAR(15),
email VARCHAR(255) NOT NULL UNIQUE,
picture_path VARCHAR(255),
FOREIGN KEY (user_id) REFERENCES users(user_id)
);
     */
    public function up(): void
    {
        //
        Schema::create('parents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(
                table: 'users',
                indexName: 'parents_user_id'
            );
            $table->string('parent_name')->nullable(false)->length(255);
            $table->string('phone_number')->nullable(false)->length(15);
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
        Schema::dropIfExists('parents');
    }
};

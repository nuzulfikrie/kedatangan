<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * - Emergency Contacts Table for Parents
CREATE TABLE emergency_contacts (
emergency_contact_id INT AUTO_INCREMENT PRIMARY KEY,
emergency_contact_name VARCHAR(255) NOT NULL,
emergency_contact_phone_number VARCHAR(15),
emergency_contact_relationship VARCHAR(50),
parent_id INT,
picture VARCHAR(255),
FOREIGN KEY (parent_id) REFERENCES parents(parent_id)
);
     */
    public function up(): void
    {
        //
        Schema::create('emergency_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->constrained(
                table: 'parents',
                indexName: 'emergency_contact_parent_id'
            );


            $table->foreignId('child_id')->constrained(
                table: 'childs',
                indexName: 'classchild_child_id'
            );
            $table->string('name')->nullable(false)->length(255);
            $table->string('phone_number')->nullable(false)->length(15);
            $table->string('relationship')->nullable(false)->length(50);
            $table->string('picture_path')->nullable(false)->length(255);
            $table->string('email')->nullable(false)->length(255)->unique();
            //address
            $table->string('address')->nullable(false)->length(500);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('emergency_contacts');
    }
};

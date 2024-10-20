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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('storage_type')->default('s3'); // e.g., 's3', 'local', etc.
            $table->string('file_name');
            $table->string('file_extension', 10);
            $table->string('file_path');
            $table->unsignedBigInteger('uploader_id');
            $table->string('remark')->nullable();
            $table->timestamps();

            // Foreign key constraint for uploader
            $table->foreign('uploader_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};

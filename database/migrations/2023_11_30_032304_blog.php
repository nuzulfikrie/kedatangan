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
        //table blog
        Schema::create('blog', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100)->nullable(false);
            $table->string('slug', 100)->nullable(false);
            $table->string('description', 100)->nullable(false);
            $table->string('content', 100)->nullable(false);
            $table->string('image', 100)->nullable(false);
            $table->string('author', 100)->nullable(false);
            $table->string('status', 100)->nullable(false);
            $table->string('meta_title', 100)->nullable(false);
            $table->string('meta_description', 100)->nullable(false);
            $table->string('meta_keyword', 100)->nullable(false);
            $table->string('created_by', 100)->nullable(false);
            $table->string('updated_by', 100)->nullable(false);
            $table->string('deleted_by', 100)->nullable(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //rollback table blog
        Schema::dropIfExists('blog');
    }
};

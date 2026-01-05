<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Section types available in the system
        Schema::create('section_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('view_path'); // blade view path
            $table->json('fields')->nullable(); // JSON schema for fields
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Sections attached to pages
        Schema::create('page_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained()->onDelete('cascade');
            $table->foreignId('section_type_id')->constrained()->onDelete('cascade');
            $table->string('title')->nullable();
            $table->json('content')->nullable(); // Dynamic content based on section type
            $table->json('settings')->nullable(); // Background, padding, animations
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_sections');
        Schema::dropIfExists('section_types');
    }
};

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
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, boolean, integer, json, image
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('key');
        });

        // Create table for logo configurations
        Schema::create('logo_settings', function (Blueprint $table) {
            $table->id();
            $table->string('logo_type')->unique(); // header, footer, sidebar, login
            $table->string('name')->nullable(); // Display name
            $table->string('image_path')->nullable();
            $table->string('image_url')->nullable();
            $table->integer('width')->nullable(); // Custom width in pixels
            $table->integer('height')->nullable(); // Custom height in pixels
            $table->string('alignment')->default('left'); // left, center, right
            $table->string('position')->default('default'); // For footer/sidebar positioning
            $table->boolean('is_active')->default(true);
            $table->text('custom_css')->nullable(); // For additional styling
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logo_settings');
        Schema::dropIfExists('app_settings');
    }
};

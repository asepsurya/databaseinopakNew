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
        Schema::create('backup_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('auto_backup_enabled')->default(false);
            $table->enum('frequency', ['daily', 'monthly'])->default('daily');
            $table->time('daily_time')->nullable();
            $table->integer('monthly_day')->nullable(); // 1-31
            $table->time('monthly_time')->nullable();
            $table->string('backup_path')->default('backups');
            $table->boolean('encryption_enabled')->default(false);
            $table->string('encryption_password')->nullable();
            $table->integer('retention_days')->default(30); // Auto-delete after X days
            $table->boolean('auto_delete_old')->default(true);
            $table->enum('default_format', ['sql', 'csv', 'both'])->default('sql');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_settings');
    }
};

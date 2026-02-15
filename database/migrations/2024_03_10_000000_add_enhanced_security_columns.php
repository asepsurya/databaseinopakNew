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
        Schema::table('users', function (Blueprint $table) {
            // 2FA Fields
            $table->string('two_factor_secret', 255)->nullable()->after('password');
            $table->string('two_factor_recovery_codes', 500)->nullable()->after('two_factor_secret');
            $table->timestamp('two_factor_confirmed_at')->nullable()->after('two_factor_recovery_codes');
            $table->boolean('two_factor_enabled')->default(false)->after('two_factor_confirmed_at');

            // Security tracking
            $table->timestamp('last_login_at')->nullable()->after('two_factor_enabled');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            $table->string('last_login_user_agent', 500)->nullable()->after('last_login_ip');

            // Account security
            $table->timestamp('password_changed_at')->nullable()->after('last_login_user_agent');
            $table->integer('failed_login_attempts')->default(0)->after('password_changed_at');
            $table->timestamp('locked_until')->nullable()->after('failed_login_attempts');

            // Remember me token storage (enhanced)
            $table->string('remember_token_identifier', 255)->nullable()->after('locked_until');
            $table->string('remember_token_value', 255)->nullable()->after('remember_token_identifier');

            // Email verification
            $table->boolean('is_active')->default(true)->after('remember_token_value');
        });

        // Create login_devices table for new device tracking
        Schema::create('login_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('device_name', 255)->nullable();
            $table->string('device_type', 50)->nullable();
            $table->string('browser', 255)->nullable();
            $table->string('os', 100)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('fingerprint', 255)->nullable();
            $table->boolean('is_current')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'is_current']);
            $table->index('created_at');
        });

        // Create password_history table
        Schema::create('password_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('password');
            $table->timestamp('created_at')->useCurrent();

            $table->index('user_id');
        });

        // Create rate_limiting table for brute force protection
        Schema::create('rate_limitations', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('action', 100);
            $table->integer('attempts')->default(1);
            $table->timestamp('locked_until')->nullable();
            $table->timestamps();

            $table->index(['ip_address', 'action']);
            $table->index(['email', 'action']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rate_limitations');
        Schema::dropIfExists('password_history');
        Schema::dropIfExists('login_devices');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'two_factor_secret',
                'two_factor_recovery_codes',
                'two_factor_confirmed_at',
                'two_factor_enabled',
                'last_login_at',
                'last_login_ip',
                'last_login_user_agent',
                'password_changed_at',
                'failed_login_attempts',
                'locked_until',
                'remember_token_identifier',
                'remember_token_value',
                'is_active',
            ]);
        });
    }
};

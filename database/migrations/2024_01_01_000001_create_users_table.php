<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * মাইগ্রেশন চালান
     * 
     * ব্যবহারকারী টেবিল তৈরি করা
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            // প্রাথমিক কী
            $table->id();

            // প্রকাশ্য ব্যবহারকারী ID (API এর জন্য)
            $table->uuid('uuid')->unique()->index();

            // ব্যক্তিগত তথ্য
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique()->index();
            $table->string('phone', 20)->nullable()->unique();

            // অথেন্টিকেশন
            $table->string('password');
            $table->rememberToken();

            // যাচাইকরণ
            $table->timestamp('email_verified_at')->nullable();
            $table->string('email_verification_token')->nullable()->unique();
            $table->timestamp('email_verification_expires_at')->nullable();

            // অ্যাকাউন্ট স্ট্যাটাস
            $table->enum('status', ['active', 'inactive', 'suspended', 'banned'])->default('active')->index();
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('deactivated_at')->nullable();
            $table->string('deactivation_reason')->nullable();

            // লগইন ট্র্যাকিং
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip', 45)->nullable();
            $table->text('last_login_user_agent')->nullable();
            $table->integer('login_attempts')->default(0);
            $table->timestamp('locked_until')->nullable();

            // ২FA (Two Factor Authentication)
            $table->boolean('two_factor_enabled')->default(false);
            $table->string('two_factor_secret')->nullable();
            $table->timestamp('two_factor_enabled_at')->nullable();
            $table->json('two_factor_backup_codes')->nullable();

            // প্রোফাইল তথ্য
            $table->text('bio')->nullable();
            $table->string('avatar_url')->nullable();
            $table->string('country')->nullable();
            $table->string('timezone', 50)->default('UTC');
            $table->enum('language', ['bn', 'en'])->default('bn');

            // পছন্দ
            $table->enum('theme', ['light', 'dark', 'auto'])->default('auto');

            // রোল (অথরাইজেশনের জন্য)
            $table->enum('role', [
                'admin',
                'editor_in_chief',
                'editor',
                'author',
                'contributor',
                'subscriber'
            ])->default('subscriber')->index();

            // বৈশিষ্ট্য ফ্ল্যাগ
            $table->json('features')->nullable(); // সক্ষম বৈশিষ্ট্য

            // পাসওয়ার্ড ম্যানেজমেন্ট
            $table->string('password_reset_token')->nullable()->unique();
            $table->timestamp('password_reset_expires_at')->nullable();
            $table->timestamp('password_changed_at')->nullable();

            // ডিভাইস ট্র্যাকিং
            $table->json('trusted_devices')->nullable();
            $table->timestamp('device_verification_at')->nullable();

            // GDPR
            $table->timestamp('gdpr_accepted_at')->nullable();
            $table->boolean('marketing_consent')->default(false);
            $table->timestamp('marketing_consent_at')->nullable();

            // সিস্টেম
            $table->timestamps(); // created_at, updated_at
            $table->softDeletes(); // deleted_at

            // ইনডেক্স
            $table->index(['status', 'created_at']);
            $table->index(['role', 'status']);
            $table->index(['email_verified_at', 'status']);
        });

        // কমেন্ট টেবিল (লগইন ইতিহাস)
        Schema::create('login_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('email');
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->boolean('successful')->default(false);
            $table->string('failure_reason')->nullable();
            $table->timestamp('attempted_at')->useCurrent();

            $table->index(['user_id', 'attempted_at']);
            $table->index(['email', 'attempted_at']);
            $table->index(['ip_address', 'attempted_at']);
        });
    }

    /**
     * মাইগ্রেশন রোলব্যাক করুন
     */
    public function down(): void
    {
        Schema::dropIfExists('login_attempts');
        Schema::dropIfExists('users');
    }
};
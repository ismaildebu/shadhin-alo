<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('login_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->enum('action', ['login', 'logout'])->index();
            $table->string('email', 255)->nullable()->index();
            $table->ipAddress('ip_address')->nullable()->index();
            $table->text('user_agent')->nullable();
            $table->boolean('is_successful')->default(false)->index();
            $table->string('failure_reason', 100)->nullable(); // invalid_credentials, email_not_verified, account_locked
            $table->string('location', 255)->nullable();
            $table->string('device_info', 255)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['action', 'created_at']);
            $table->index(['is_successful', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_audits');
    }
};


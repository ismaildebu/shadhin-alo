<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('notification_type', 50)->index();
            $table->boolean('email_enabled')->default(true);
            $table->boolean('push_enabled')->default(true);
            $table->boolean('in_app_enabled')->default(true);
            $table->boolean('sms_enabled')->default(false);
            $table->enum('frequency', ['instant', 'daily', 'weekly', 'monthly'])->default('instant');
            $table->boolean('quiet_hours_enabled')->default(false);
            $table->time('quiet_hours_start')->nullable();
            $table->time('quiet_hours_end')->nullable();
            $table->string('timezone', 50)->default('UTC');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['user_id', 'notification_type']);
            $table->index(['user_id', 'notification_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_notification_preferences');
    }
};

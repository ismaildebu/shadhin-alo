<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('theme', ['light', 'dark', 'auto'])->default('auto');
            $table->string('language', 5)->default('en')->index();
            $table->string('timezone', 50)->default('UTC');
            $table->string('date_format', 20)->default('Y-m-d');
            $table->string('time_format', 20)->default('H:i:s');
            $table->smallInteger('items_per_page')->default(15);
            $table->boolean('notifications_enabled')->default(true)->index();
            $table->boolean('email_notifications')->default(true);
            $table->boolean('push_notifications')->default(true);
            $table->boolean('in_app_notifications')->default(true);
            $table->boolean('marketing_emails')->default(false);
            $table->boolean('newsletter_subscription')->default(false);
            $table->enum('privacy_level', ['public', 'friends', 'private'])->default('private')->index();
            $table->boolean('show_online_status')->default(false);
            $table->boolean('show_profile_activity')->default(false);
            $table->boolean('data_collection_allowed')->default(true);
            $table->enum('allow_messages_from', ['everyone', 'friends', 'none'])->default('everyone');
            $table->enum('content_filter_level', ['none', 'moderate', 'strict'])->default('moderate');
            $table->json('custom_settings')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_preferences');
    }
};
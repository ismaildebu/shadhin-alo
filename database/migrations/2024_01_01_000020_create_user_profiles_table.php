<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->text('bio')->nullable();
            $table->string('avatar_url')->nullable();
            $table->string('cover_image_url')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other', 'prefer_not_to_say'])->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('website')->nullable();
            $table->string('twitter_handle', 50)->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('github_username', 100)->nullable();
            $table->string('instagram_handle', 50)->nullable();
            $table->boolean('is_public_profile')->default(false)->index();
            $table->timestamp('last_profile_update')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index(['is_public_profile', 'updated_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};

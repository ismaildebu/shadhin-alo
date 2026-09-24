<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feature_flags', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->text('description')->nullable();
            $table->boolean('is_enabled')->default(false)->index();
            $table->tinyInteger('rollout_percentage')->default(0)->comment('0-100');
            $table->json('target_users')->nullable()->comment('User IDs');
            $table->json('target_roles')->nullable()->comment('Role slugs');
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feature_flags');
    }
};

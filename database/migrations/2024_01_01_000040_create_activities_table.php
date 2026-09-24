<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('action', 50)->index(); // created, updated, deleted, viewed
            $table->string('model_type', 255)->nullable()->index();
            $table->unsignedBigInteger('model_id')->nullable()->index();
            $table->string('model_name', 100)->nullable();
            $table->text('description')->nullable();
            $table->json('changes')->nullable(); // {field: {old: val, new: val}}
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->ipAddress('ip_address')->nullable()->index();
            $table->text('user_agent')->nullable();
            $table->string('method', 10)->nullable(); // GET, POST, PUT, DELETE
            $table->string('url', 500)->nullable();
            $table->unsignedSmallInteger('status_code')->nullable();
            $table->unsignedInteger('response_time_ms')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['model_type', 'model_id']);
            $table->index(['action', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};


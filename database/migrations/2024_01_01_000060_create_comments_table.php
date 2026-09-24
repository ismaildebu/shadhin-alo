<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->longText('content');
            $table->enum('status', ['pending', 'approved', 'rejected', 'spam', 'deleted'])->default('pending')->index();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('parent_id')->nullable()->index();
            $table->string('commentable_type')->index();
            $table->unsignedBigInteger('commentable_id')->index();
            $table->unsignedBigInteger('helpful_count')->default(0);
            $table->unsignedBigInteger('unhelpful_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['commentable_type', 'commentable_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index(['parent_id', 'status']);
            $table->index(['created_at']);
        });
    }

    public function down(): void { Schema::dropIfExists('comments'); }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comment_flags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comment_id')->constrained('comments')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('reason', ['spam', 'offensive', 'irrelevant', 'misinformation', 'other']);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->index(['comment_id', 'reason']);
            $table->index(['created_at']);
        });
    }

    public function down(): void { Schema::dropIfExists('comment_flags'); }
};

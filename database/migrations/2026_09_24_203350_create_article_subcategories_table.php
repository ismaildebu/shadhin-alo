<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article_subcategories', function (Blueprint $table) {
            $table->foreignId('article_id')
                ->constrained('articles')
                ->cascadeOnDelete();

            $table->foreignId('subcategory_id')
                ->constrained('subcategories')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->primary(['article_id', 'subcategory_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_subcategories');
    }
};
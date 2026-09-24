<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->longText('value')->nullable();
            $table->enum('type', ['string', 'integer', 'boolean', 'array', 'json'])->default('string');
            $table->text('description')->nullable();
            $table->string('module', 50)->default('system')->index();
            $table->boolean('is_public')->default(true)->index();
            $table->boolean('is_encrypted')->default(false);
            $table->string('validation_rule')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['module', 'key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};

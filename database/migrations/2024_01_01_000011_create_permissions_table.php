<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 100)->unique()->index();
            $table->text('description')->nullable();
            $table->string('module', 50)->index();
            $table->boolean('is_system')->default(false)->index();
            $table->string('guard_name', 50)->default('web')->index();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['slug', 'guard_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};

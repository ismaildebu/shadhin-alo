<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->foreignId('series_id')->nullable()->after('author_id')
                ->constrained('series')->nullOnDelete();

            $table->enum('content_type', ['news', 'opinion', 'investigation', 'data'])
                ->default('news')->after('status')->index();
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropConstrainedForeignId('series_id');
            $table->dropColumn('content_type');
        });
    }
};
<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\Article\Policies\ArticlePolicy;
use App\Modules\Article\Models\Article;

class ArticleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Register policies
        $this->registerPolicies();
    }

    public function registerPolicies(): void
    {
        \Gate::policy(Article::class, ArticlePolicy::class);
    }
}


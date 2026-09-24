<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Modules\Article\Enums\ArticleStatus;
use App\Modules\Article\Models\Article;
use Illuminate\Console\Command;

class PublishScheduledArticles extends Command
{
    protected $signature = 'articles:publish-scheduled';

    protected $description = 'Publish articles whose scheduled publish time has arrived';

    public function handle(): int
    {
        $articles = Article::query()
            ->where('status', ArticleStatus::DRAFT)
            ->whereNotNull('scheduled_publish_at')
            ->where('scheduled_publish_at', '<=', now())
            ->get();

        foreach ($articles as $article) {
            $article->publish();
        }

        $this->info("Published {$articles->count()} scheduled article(s).");

        return self::SUCCESS;
    }
}
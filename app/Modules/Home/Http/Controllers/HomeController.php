<?php

declare(strict_types=1);

namespace App\Modules\Home\Http\Controllers;

use App\Modules\Home\Services\HomePageService;
use Illuminate\Contracts\View\View;

class HomeController
{
    public function __construct(
        private readonly HomePageService $homePageService
    ) {
    }

    public function index(): View
    {
        return view('public.index', [
            'featuredArticles' => $this->homePageService->getFeaturedArticles(),
            'latestArticles' => $this->homePageService->getLatestArticles(),
            'trendingArticles' => $this->homePageService->getTrendingArticles(),
            'categories' => $this->homePageService->getCategories(),
            'breakingNews' => $this->homePageService->getBreakingNews(),
            'leadStory' => $this->homePageService->getLeadStory(),
            'recentUpdates' => $this->homePageService->getRecentUpdates(),
            'districtsWithNews' => $this->homePageService->getDistrictsWithNews(),
            'investigationArticles' => $this->homePageService->getInvestigationArticles(),
            'opinionArticles' => $this->homePageService->getOpinionArticles(),
            'activeSeries' => $this->homePageService->getActiveSeries(),
            'marketPrices' => $this->homePageService->getLatestMarketPrices(),
            'videos' => $this->homePageService->getLatestVideos(),
            'mostReadArticles' => $this->homePageService->getMostReadArticles(),
        ]);
    }
}
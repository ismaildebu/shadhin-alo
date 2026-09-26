<?php

declare(strict_types=1);

namespace App\Modules\Dashboard\Http\Controllers;

use App\Modules\Dashboard\Services\DashboardService;
use Illuminate\Contracts\View\View;

class DashboardController
{
    public function __construct(
        private readonly DashboardService $dashboardService
    ) {
    }

    public function index(): View
    {
        return view('dashboard.index', [
            'statistics' => $this->dashboardService->getStatistics(),
            'recentArticles' => $this->dashboardService->getRecentArticles(),
            'popularArticles' => $this->dashboardService->getPopularArticles(),
        ]);
    }
}
@extends('layouts.public')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

            <div class="mb-10 flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-widest text-indigo-500">
                        Administration
                    </p>

                    <h1 class="mt-2 text-4xl font-extrabold tracking-tight text-slate-900">
                        Dashboard
                    </h1>

                    <p class="mt-2 text-lg text-slate-500">
                        Overview of your newspaper platform.
                    </p>
                </div>

                <a href="{{ route('articles.create') }}"
                    class="flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-indigo-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    নতুন আর্টিকেল
                </a>
            </div>

            @if (session('success'))
                <div class="mb-6 rounded-xl bg-emerald-50 p-4 text-sm font-medium text-emerald-700 ring-1 ring-emerald-100">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @php
                    $cards = [
                        'total_articles'     => ['label' => 'Total Articles',  'color' => 'rose',    'path' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z', 'status' => null],
                        'published_articles' => ['label' => 'Published',       'color' => 'emerald', 'path' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'status' => 'published'],
                        'draft_articles'     => ['label' => 'Drafts',          'color' => 'amber',   'path' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z', 'status' => 'draft'],
                        'pending_articles'   => ['label' => 'Pending Review',  'color' => 'orange',  'path' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'status' => 'pending_review'],
                        'total_categories'   => ['label' => 'Categories',      'color' => 'sky',     'path' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10', 'status' => false],
                        'total_users'        => ['label' => 'Users',           'color' => 'violet',  'path' => 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-1-7.87', 'status' => false],
                        'total_views'        => ['label' => 'Total Views',     'color' => 'cyan',    'path' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z', 'status' => false],
                    ];

                    $colorMap = [
                        'rose'    => ['bg' => 'bg-rose-50',    'text' => 'text-rose-600',    'ring' => 'ring-rose-100',    'shadow' => 'hover:shadow-rose-200'],
                        'emerald' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'ring' => 'ring-emerald-100', 'shadow' => 'hover:shadow-emerald-200'],
                        'amber'   => ['bg' => 'bg-amber-50',   'text' => 'text-amber-600',   'ring' => 'ring-amber-100',   'shadow' => 'hover:shadow-amber-200'],
                        'orange'  => ['bg' => 'bg-orange-50',  'text' => 'text-orange-600',  'ring' => 'ring-orange-100',  'shadow' => 'hover:shadow-orange-200'],
                        'sky'     => ['bg' => 'bg-sky-50',     'text' => 'text-sky-600',     'ring' => 'ring-sky-100',     'shadow' => 'hover:shadow-sky-200'],
                        'violet'  => ['bg' => 'bg-violet-50',  'text' => 'text-violet-600',  'ring' => 'ring-violet-100',  'shadow' => 'hover:shadow-violet-200'],
                        'cyan'    => ['bg' => 'bg-cyan-50',    'text' => 'text-cyan-600',    'ring' => 'ring-cyan-100',    'shadow' => 'hover:shadow-cyan-200'],
                    ];
                @endphp

                @foreach ($cards as $key => $card)
                    @php
                        $c = $colorMap[$card['color']];
                        $isClickable = $card['status'] !== false;
                        $href = $isClickable
                            ? route('articles.manage', $card['status'] ? ['status' => $card['status']] : [])
                            : null;
                        $tag = $isClickable ? 'a' : 'div';
                    @endphp

                    <{{ $tag }} @if($href) href="{{ $href }}" @endif
                        class="group block rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg {{ $c['shadow'] }}">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl {{ $c['bg'] }} ring-4 {{ $c['ring'] }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 {{ $c['text'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['path'] }}" />
                            </svg>
                        </div>

                        <p class="mt-4 text-sm font-medium text-slate-500">
                            {{ $card['label'] }}
                        </p>

                        <p class="mt-1 text-3xl font-extrabold tracking-tight text-slate-900">
                            {{ number_format($statistics[$key]) }}
                        </p>
                    </{{ $tag }}>
                @endforeach
            </div>

            <div class="mt-10 grid gap-6 lg:grid-cols-2">

                <section class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-100">
                    <div class="flex items-center gap-3 px-6 py-5">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-rose-50 ring-4 ring-rose-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                        <h2 class="text-lg font-bold text-slate-900">
                            Recent Articles
                        </h2>
                    </div>

                    <div class="divide-y divide-slate-50">
                        @forelse ($recentArticles as $article)
                            <div class="px-6 py-4 transition-colors hover:bg-slate-50">
                                <h3 class="text-base font-semibold text-slate-900">
                                    {{ $article->title }}
                                </h3>

                                <div class="mt-1 text-sm text-slate-500">
                                    {{ ucfirst(str_replace('_', ' ', $article->status->value)) }}
                                    ·
                                    {{ $article->created_at?->format('d M Y H:i') }}
                                </div>
                            </div>
                        @empty
                            <p class="px-6 py-8 text-center text-sm text-slate-400">
                                No articles available.
                            </p>
                        @endforelse
                    </div>
                </section>

                <section class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-100">
                    <div class="flex items-center gap-3 px-6 py-5">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-50 ring-4 ring-emerald-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </span>
                        <h2 class="text-lg font-bold text-slate-900">
                            Popular Articles
                        </h2>
                    </div>

                    <div class="divide-y divide-slate-50">
                        @forelse ($popularArticles as $article)
                            <div class="flex items-center justify-between gap-4 px-6 py-4 transition-colors hover:bg-slate-50">
                                <h3 class="text-base font-semibold text-slate-900">
                                    {{ $article->title }}
                                </h3>

                                <span class="shrink-0 text-sm font-semibold text-emerald-600">
                                    {{ number_format($article->views_count ?? 0) }} views
                                </span>
                            </div>
                        @empty
                            <p class="px-6 py-8 text-center text-sm text-slate-400">
                                No published articles available.
                            </p>
                        @endforelse
                    </div>
                </section>

            </div>

        </div>
    </div>
@endsection
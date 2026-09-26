@extends('layouts.public')

@section('content')

    <x-breaking-news-bar :articles="$breakingNews" />

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

        {{-- প্রধান সংবাদ অঞ্চল --}}
        <section>
            <div class="mb-6">
                <p class="text-sm font-semibold uppercase tracking-widest text-indigo-500">
                    প্রধান সংবাদ
                </p>
            </div>

            @if ($leadStory)
                <div class="grid gap-6 lg:grid-cols-3">
                    {{-- বড় প্রধান খবর --}}
                    <a href="{{ url('/articles/' . $leadStory->slug) }}"
                        class="group relative col-span-1 overflow-hidden rounded-2xl shadow-sm lg:col-span-2">
                        <div class="aspect-[16/9] overflow-hidden bg-slate-200 lg:aspect-[16/8]">
                            @if ($leadStory->featured_image)
                                <img
                                    src="{{ \Illuminate\Support\Str::startsWith($leadStory->featured_image, ['http://', 'https://'])
                                        ? $leadStory->featured_image
                                        : asset('storage/' . ltrim($leadStory->featured_image, '/')) }}"
                                    alt="{{ $leadStory->title }}"
                                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                >
                            @else
                                <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-indigo-100 to-slate-200"></div>
                            @endif
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                        <div class="absolute inset-x-0 bottom-0 p-6 sm:p-8">
                            @if ($leadStory->categories->isNotEmpty())
                                <span class="rounded-full bg-indigo-600 px-3 py-1 text-xs font-bold uppercase tracking-wide text-white">
                                    {{ $leadStory->categories->first()->name }}
                                </span>
                            @endif
                            <h1 class="mt-3 text-2xl font-extrabold leading-tight text-white sm:text-3xl">
                                {{ $leadStory->title }}
                            </h1>
                            @if ($leadStory->excerpt)
                                <p class="mt-2 hidden text-sm text-slate-200 sm:block">
                                    {{ \Illuminate\Support\Str::limit($leadStory->excerpt, 140) }}
                                </p>
                            @endif
                        </div>
                    </a>

                    {{-- গুরুত্বপূর্ণ খবর --}}
                    <div class="flex flex-col gap-4">
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-400">
                            গুরুত্বপূর্ণ খবর
                        </p>

                        @forelse ($featuredArticles->take(4) as $article)
                            <a href="{{ url('/articles/' . $article->slug) }}"
                                class="group flex gap-4 rounded-2xl bg-white p-3 shadow-sm ring-1 ring-slate-100 transition-shadow hover:shadow-md">
                                <div class="h-16 w-20 shrink-0 overflow-hidden rounded-xl bg-slate-100">
                                    @if ($article->featured_image)
                                        <img
                                            src="{{ \Illuminate\Support\Str::startsWith($article->featured_image, ['http://', 'https://'])
                                                ? $article->featured_image
                                                : asset('storage/' . ltrim($article->featured_image, '/')) }}"
                                            alt="{{ $article->title }}"
                                            class="h-full w-full object-cover"
                                        >
                                    @endif
                                </div>
                                <h3 class="line-clamp-3 text-sm font-bold leading-snug text-slate-900 transition-colors group-hover:text-indigo-600">
                                    {{ $article->title }}
                                </h3>
                            </a>
                        @empty
                            <p class="text-sm text-slate-400">কোনো গুরুত্বপূর্ণ খবর নেই।</p>
                        @endforelse
                    </div>
                </div>
            @else
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center">
                    <p class="text-slate-500">
                        কোনো প্রধান সংবাদ পাওয়া যায়নি।
                    </p>
                </div>
            @endif
        </section>

        <x-recent-updates-timeline :articles="$recentUpdates" />

        {{-- Latest Articles --}}
        <section class="mt-16">
            <div class="mb-6 flex items-end justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-widest text-indigo-500">
                        News
                    </p>
                    <h2 class="mt-2 text-3xl font-extrabold text-slate-900">
                        Latest Articles
                    </h2>
                </div>
                <a href="{{ url('/articles') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700">
                    সব দেখুন →
                </a>
            </div>

            @if ($latestArticles->isNotEmpty())
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($latestArticles as $article)
                        <x-article-card :article="$article" />
                    @endforeach
                </div>
            @else
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center">
                    <p class="text-slate-500">
                        No published articles are available.
                    </p>
                </div>
            @endif
        </section>

        {{-- Trending Articles --}}
        <section class="mt-16">
            <div class="mb-6">
                <p class="text-sm font-semibold uppercase tracking-widest text-indigo-500">
                    Trending
                </p>
                <h2 class="mt-2 text-3xl font-extrabold text-slate-900">
                    Trending This Week
                </h2>
            </div>

            @if ($trendingArticles->isNotEmpty())
                <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-100">
                    <div class="divide-y divide-slate-50">
                        @foreach ($trendingArticles as $index => $article)
                            <a href="{{ url('/articles/' . $article->slug) }}"
                                class="group flex items-center gap-5 px-6 py-4 transition-colors hover:bg-slate-50">
                                <span class="w-8 shrink-0 text-2xl font-extrabold text-slate-200 group-hover:text-indigo-200">
                                    {{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}
                                </span>
                                <div class="min-w-0 flex-1">
                                    <h3 class="truncate text-base font-semibold text-slate-900 group-hover:text-indigo-600">
                                        {{ $article->title }}
                                    </h3>
                                    <span class="text-sm text-slate-400">
                                        {{ $article->published_at?->format('d M Y') }}
                                    </span>
                                </div>
                                <span class="shrink-0 text-sm font-semibold text-slate-500">
                                    {{ number_format($article->views_count ?? 0) }} views
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center">
                    <p class="text-slate-500">
                        No trending articles are available.
                    </p>
                </div>
            @endif
        </section>

        {{-- Categories --}}
        <section class="mt-16">
            <div class="mb-6">
                <p class="text-sm font-semibold uppercase tracking-widest text-indigo-500">
                    Browse
                </p>
                <h2 class="mt-2 text-3xl font-extrabold text-slate-900">
                    Categories
                </h2>
            </div>

            @if ($categories->isNotEmpty())
                @php
                    $catColors = ['bg-rose-50 text-rose-600', 'bg-emerald-50 text-emerald-600', 'bg-sky-50 text-sky-600', 'bg-amber-50 text-amber-600', 'bg-violet-50 text-violet-600', 'bg-cyan-50 text-cyan-600'];
                @endphp
                <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    @foreach ($categories as $i => $category)
                        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-100 transition-shadow hover:shadow-md">
                            <span class="inline-block rounded-lg px-2.5 py-1 text-xs font-bold uppercase tracking-wide {{ $catColors[$i % count($catColors)] }}">
                                {{ $category->name }}
                            </span>
                            <p class="mt-3 text-sm text-slate-500">
                                {{ $category->published_articles_count }}
                                {{ \Illuminate\Support\Str::plural('article', $category->published_articles_count) }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center">
                    <p class="text-slate-500">
                        No categories are available.
                    </p>
                </div>
            @endif
        </section>

    </div>
@endsection
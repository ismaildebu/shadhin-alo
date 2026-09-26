@props(['article'])

<article class="group overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
    <a href="{{ url('/articles/' . $article->slug) }}" class="block">
        <div class="aspect-[16/9] overflow-hidden bg-slate-100">
            @if ($article->featured_image)
                <img
                    src="{{ \Illuminate\Support\Str::startsWith($article->featured_image, ['http://', 'https://'])
                        ? $article->featured_image
                        : asset('storage/' . ltrim($article->featured_image, '/')) }}"
                    alt="{{ $article->title }}"
                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                    loading="lazy"
                >
            @else
                <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-indigo-50 to-slate-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M4 8h16M4 4h16v16H4V4z" />
                    </svg>
                </div>
            @endif
        </div>
    </a>

    <div class="p-5">
        @if ($article->categories->isNotEmpty())
            <div class="mb-2 flex flex-wrap gap-2">
                @foreach ($article->categories->take(2) as $category)
                    <span class="text-xs font-bold uppercase tracking-wide text-indigo-600">
                        {{ $category->name }}
                    </span>
                @endforeach
            </div>
        @endif

        <a href="{{ url('/articles/' . $article->slug) }}">
            <h3 class="text-xl font-bold leading-tight text-slate-900 transition-colors group-hover:text-indigo-600">
                {{ $article->title }}
            </h3>
        </a>

        @if ($article->excerpt)
            <p class="mt-3 line-clamp-3 text-sm leading-6 text-slate-500">
                {{ $article->excerpt }}
            </p>
        @endif

        <div class="mt-4 flex items-center justify-between border-t border-slate-50 pt-4 text-xs font-medium text-slate-400">
            <span>
                {{ $article->published_at?->format('d M Y') }}
            </span>

            <span class="flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                {{ number_format($article->views_count ?? 0) }}
            </span>
        </div>
    </div>
</article>
@props(['articles'])

@if ($articles->isNotEmpty())
    <div class="overflow-hidden bg-rose-600">
        <div class="mx-auto flex max-w-7xl items-center gap-4 px-4 py-2.5 sm:px-6 lg:px-8">
            <span class="flex shrink-0 items-center gap-1.5 rounded bg-white/20 px-2.5 py-1 text-xs font-extrabold uppercase tracking-wide text-white">
                <span class="h-2 w-2 animate-pulse rounded-full bg-white"></span>
                ব্রেকিং
            </span>

            <div class="flex min-w-0 flex-1 items-center gap-8 overflow-x-auto text-sm font-semibold text-white [scrollbar-width:none]">
                @foreach ($articles as $article)
                    <a href="{{ url('/articles/' . $article->slug) }}" class="shrink-0 hover:underline">
                        {{ $article->title }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endif
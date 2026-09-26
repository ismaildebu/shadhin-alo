@props(['articles'])

<section class="mt-14">
    <div class="mb-6">
        <p class="text-sm font-semibold uppercase tracking-widest text-indigo-500">
            আপডেট
        </p>
        <h2 class="mt-2 text-2xl font-extrabold text-slate-900">
            এই মুহূর্তে
        </h2>
    </div>

    @if ($articles->isNotEmpty())
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-100">
            <div class="divide-y divide-slate-50">
                @foreach ($articles as $article)
                    <a href="{{ url('/articles/' . $article->slug) }}"
                        class="group flex items-center gap-4 px-6 py-4 transition-colors hover:bg-slate-50">
                        <span class="shrink-0 text-sm font-semibold text-indigo-500">
                            {{ $article->published_at?->format('h:i A') }}
                        </span>
                        <span class="hidden h-1 w-1 shrink-0 rounded-full bg-slate-300 sm:block"></span>
                        <h3 class="min-w-0 truncate text-sm font-semibold text-slate-800 group-hover:text-indigo-600">
                            {{ $article->title }}
                        </h3>
                    </a>
                @endforeach
            </div>
        </div>
    @else
        <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center">
            <p class="text-sm text-slate-500">
                এই মুহূর্তে কোনো আপডেট নেই।
            </p>
        </div>
    @endif
</section>
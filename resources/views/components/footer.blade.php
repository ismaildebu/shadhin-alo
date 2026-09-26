<footer class="mt-20 border-t border-slate-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
            <a href="{{ url('/') }}" class="flex items-center gap-2 text-lg font-extrabold text-slate-900">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-600 text-sm text-white">
                    {{ \Illuminate\Support\Str::substr(config('app.name', 'Shadhin Alo'), 0, 1) }}
                </span>
                {{ config('app.name', 'Shadhin Alo') }}
            </a>

            <div class="flex gap-6 text-sm font-medium text-slate-500">
                <a href="{{ url('/') }}" class="transition-colors hover:text-indigo-600">Home</a>
                <a href="{{ url('/articles') }}" class="transition-colors hover:text-indigo-600">Articles</a>
                <a href="{{ url('/categories') }}" class="transition-colors hover:text-indigo-600">Categories</a>
            </div>
        </div>

        <div class="mt-8 border-t border-slate-100 pt-6 text-center text-sm text-slate-400">
            &copy; {{ now()->year }} {{ config('app.name', 'Shadhin Alo') }}. All rights reserved.
        </div>
    </div>
</footer>
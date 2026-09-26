<nav class="sticky top-0 z-40 border-b border-slate-200 bg-white/90 backdrop-blur">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
        <a href="{{ url('/') }}" class="flex items-center gap-2 text-2xl font-extrabold tracking-tight text-slate-900">
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-600 text-lg text-white">
                {{ \Illuminate\Support\Str::substr(config('app.name', 'Shadhin Alo'), 0, 1) }}
            </span>
            {{ config('app.name', 'Shadhin Alo') }}
        </a>

        <div class="hidden items-center gap-8 md:flex">
            <a href="{{ url('/') }}" class="text-sm font-semibold text-slate-600 transition-colors hover:text-indigo-600">
                Home
            </a>

            <a href="{{ url('/articles') }}" class="text-sm font-semibold text-slate-600 transition-colors hover:text-indigo-600">
                Articles
            </a>

            <a href="{{ url('/categories') }}" class="text-sm font-semibold text-slate-600 transition-colors hover:text-indigo-600">
                Categories
            </a>

            @auth
                <a href="{{ route('dashboard') }}"
                    class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-indigo-700">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}"
                    class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-indigo-700">
                    Login
                </a>
            @endauth
        </div>
    </div>
</nav>
@extends('layouts.public')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100">
        <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">

            <div class="mb-8 flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-widest text-indigo-500">
                        Articles
                    </p>
                    <h1 class="mt-2 text-4xl font-extrabold tracking-tight text-slate-900">
                        আর্টিকেল তালিকা
                    </h1>
                </div>

                <a href="{{ route('articles.create') }}"
                    class="flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">
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

            <div class="mb-6 flex flex-wrap gap-2">
                @php
                    $tabs = [
                        null              => 'সব',
                        'published'       => 'Published',
                        'draft'           => 'Draft',
                        'pending_review'  => 'Pending Review',
                        'archived'        => 'Archived',
                    ];
                @endphp

                @foreach ($tabs as $value => $label)
                    <a href="{{ route('articles.manage', $value ? ['status' => $value] : []) }}"
                        class="rounded-lg px-4 py-2 text-sm font-semibold transition-colors
                            {{ $currentStatus === $value
                                ? 'bg-indigo-600 text-white'
                                : 'bg-white text-slate-600 ring-1 ring-slate-200 hover:bg-slate-50' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <section class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-100">
                <div class="divide-y divide-slate-50">
                    @forelse ($articles as $article)
                        <div class="flex items-center justify-between gap-4 px-6 py-4 transition-colors hover:bg-slate-50">
                            <div>
                                <h3 class="text-base font-semibold text-slate-900">
                                    {{ $article->title }}
                                </h3>
                                <div class="mt-1 text-sm text-slate-500">
                                    {{ $article->author->full_name ?? $article->author->email ?? '—' }}
                                    ·
                                    {{ $article->created_at?->format('d M Y H:i') }}
                                </div>
                            </div>

                            <div class="flex shrink-0 items-center gap-4">
                                <span class="text-sm font-semibold text-slate-600">
                                    {{ ucfirst(str_replace('_', ' ', $article->status->value)) }}
                                </span>

                                <a href="{{ route('articles.edit', $article) }}"
                                    class="rounded-lg bg-indigo-50 px-3 py-1.5 text-sm font-semibold text-indigo-600 hover:bg-indigo-100">
                                    Edit
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="px-6 py-10 text-center text-sm text-slate-400">
                            কোনো আর্টিকেল পাওয়া যায়নি।
                        </p>
                    @endforelse
                </div>
            </section>

            <div class="mt-6">
                {{ $articles->links() }}
            </div>

        </div>
    </div>
@endsection
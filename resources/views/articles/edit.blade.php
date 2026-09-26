@extends('layouts.public')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100">
        <div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">

            <div class="mb-8">
                <p class="text-sm font-semibold uppercase tracking-widest text-indigo-500">
                    Articles
                </p>
                <h1 class="mt-2 text-4xl font-extrabold tracking-tight text-slate-900">
                    আর্টিকেল এডিট করুন
                </h1>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-xl bg-rose-50 p-4 ring-1 ring-rose-100">
                    <ul class="list-inside list-disc text-sm text-rose-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('articles.update.web', $article) }}" class="space-y-6 rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-100">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-semibold text-slate-700">শিরোনাম (Title)</label>
                    <input type="text" name="title" value="{{ old('title', $article->title) }}" required
                        class="mt-2 w-full rounded-lg border-slate-300 text-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700">সংক্ষিপ্তসার (Excerpt)</label>
                    <textarea name="excerpt" rows="2" maxlength="500" required
                        class="mt-2 w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('excerpt', $article->excerpt) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700">মূল কনটেন্ট (Content) — কমপক্ষে ১০০ অক্ষর</label>
                    <textarea name="content" rows="10" required
                        class="mt-2 w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('content', $article->content) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700">ফিচার্ড ইমেজ URL (ঐচ্ছিক)</label>
                    <input type="url" name="featured_image" value="{{ old('featured_image', $article->featured_image) }}"
                        class="mt-2 w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Status</label>
                        <select name="status" required
                            class="mt-2 w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach (['draft' => 'Draft', 'pending_review' => 'Pending Review', 'published' => 'Published', 'archived' => 'Archived'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('status', $article->status->value) === $value)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex flex-wrap gap-6">
                    <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                        <input type="checkbox" name="featured" value="1" class="rounded border-slate-300 text-indigo-600"
                            @checked(old('featured', $article->featured))>
                        Featured
                    </label>
                    <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                        <input type="checkbox" name="breaking" value="1" class="rounded border-slate-300 text-indigo-600"
                            @checked(old('breaking', $article->breaking))>
                        Breaking News
                    </label>
                    <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                        <input type="checkbox" name="lead" value="1" class="rounded border-slate-300 text-indigo-600"
                            @checked(old('lead', $article->lead))>
                        Lead Article
                    </label>
                </div>

                <div class="grid gap-4 sm:grid-cols-3">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Categories</label>
                        <select name="categories[]" multiple size="4"
                            class="mt-2 w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(in_array($category->id, old('categories', $selectedCategoryIds)))>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Subcategories</label>
                        <select name="subcategories[]" multiple size="4"
                            class="mt-2 w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach ($subcategories as $subcategory)
                                <option value="{{ $subcategory->id }}" @selected(in_array($subcategory->id, old('subcategories', $selectedSubcategoryIds)))>
                                    {{ $subcategory->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Tags</label>
                        <select name="tags[]" multiple size="4"
                            class="mt-2 w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach ($tags as $tag)
                                <option value="{{ $tag->id }}" @selected(in_array($tag->id, old('tags', $selectedTagIds)))>
                                    {{ $tag->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('articles.manage') }}"
                        class="rounded-lg px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-100">
                        বাতিল
                    </a>
                    <button type="submit"
                        class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">
                        আপডেট করুন
                    </button>
                </div>
            </form>

        </div>
    </div>
@endsection
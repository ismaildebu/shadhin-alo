@extends('layouts.public')
@php
    $errors = $errors ?? session('errors', new \Illuminate\Support\ViewErrorBag());
@endphp

@section('content')
    <div class="relative min-h-[calc(100vh-160px)] overflow-hidden bg-gradient-to-br from-green-950 via-green-800 to-red-950">

        {{-- Red / Green Background --}}
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_15%_20%,rgba(220,38,38,0.35),transparent_30%),radial-gradient(circle_at_85%_80%,rgba(22,163,74,0.4),transparent_35%)]"></div>

        <div class="absolute -left-32 -top-32 h-[32rem] w-[32rem] rounded-full bg-red-600/20 blur-3xl"></div>
        <div class="absolute -bottom-32 -right-32 h-[32rem] w-[32rem] rounded-full bg-green-400/20 blur-3xl"></div>

        <div class="relative mx-auto flex min-h-[calc(100vh-160px)] max-w-7xl items-center justify-center px-4 py-12 sm:px-6 lg:px-8">

            {{-- Main Card --}}
            <div class="grid w-full max-w-6xl overflow-hidden rounded-[2.5rem] border border-white/20 bg-white shadow-[0_30px_100px_rgba(0,0,0,0.35)] lg:grid-cols-2">

                {{-- LEFT: RED + GREEN BRAND AREA --}}
                <div class="relative min-h-[720px] overflow-hidden bg-green-700 p-10 text-white sm:p-14 lg:p-16">

                    {{-- Red diagonal section --}}
                    <div class="absolute -right-40 -top-40 h-[650px] w-[650px] rotate-12 rounded-[8rem] bg-red-600"></div>

                    {{-- Green overlay --}}
                    <div class="absolute bottom-[-180px] left-[-180px] h-[500px] w-[500px] rounded-full bg-green-900/60"></div>

                    {{-- Decorative circles --}}
                    <div class="absolute right-20 top-24 h-36 w-36 rounded-full border-[28px] border-white/10"></div>
                    <div class="absolute right-32 top-36 h-16 w-16 rounded-full bg-red-700"></div>

                    <div class="relative z-10 flex h-full flex-col justify-between">

                        {{-- Brand --}}
                        <div>
                            <div class="flex items-center gap-4">

                                <div class="flex h-20 w-20 items-center justify-center rounded-3xl bg-white text-3xl font-black text-green-700 shadow-2xl">
                                    সা
                                </div>

                                <div>
                                    <p class="text-lg font-black tracking-wide">
                                        স্বাধীন আলো
                                    </p>

                                    <p class="text-xs font-bold uppercase tracking-[0.3em] text-green-100">
                                        SHADHIN ALO
                                    </p>
                                </div>

                            </div>


                            {{-- Hero --}}
                            <div class="mt-24 max-w-xl">

                                <div class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-black/10 px-4 py-2 text-xs font-bold uppercase tracking-widest backdrop-blur">
                                    <span class="h-2.5 w-2.5 rounded-full bg-red-400"></span>
                                    Digital Newsroom
                                </div>

                                <h2 class="mt-7 text-5xl font-black leading-[1.05] tracking-tight xl:text-6xl">
                                    সত্যের সাথে
                                    <span class="text-red-200">স্বাধীন আলো</span>
                                </h2>

                                <p class="mt-7 max-w-lg text-lg leading-8 text-green-50">
                                    সংবাদ সংগ্রহ থেকে প্রকাশনা—
                                    আপনার সম্পূর্ণ নিউজরুম ব্যবস্থাপনা
                                    এখন এক জায়গায়।
                                </p>

                            </div>


                            {{-- Features --}}
                            <div class="mt-12 grid gap-4 sm:grid-cols-2">

                                <div class="rounded-2xl border border-white/15 bg-white/10 p-5 backdrop-blur-md">
                                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-red-600">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v12a2 2 0 01-2 2zM7 8h10M7 12h7M7 16h5" />
                                        </svg>
                                    </div>

                                    <h3 class="mt-4 font-bold">
                                        সংবাদ ব্যবস্থাপনা
                                    </h3>

                                    <p class="mt-2 text-sm leading-6 text-green-100/80">
                                        সংবাদ তৈরি, সম্পাদনা ও প্রকাশনা।
                                    </p>
                                </div>


                                <div class="rounded-2xl border border-white/15 bg-white/10 p-5 backdrop-blur-md">
                                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-900">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                d="M12 15v2m-6 3h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-8V7a4 4 0 10-8 0v3h8z" />
                                        </svg>
                                    </div>

                                    <h3 class="mt-4 font-bold">
                                        নিরাপদ অ্যাক্সেস
                                    </h3>

                                    <p class="mt-2 text-sm leading-6 text-green-100/80">
                                        অনুমোদিত ব্যবহারকারীদের সুরক্ষিত প্রবেশ।
                                    </p>
                                </div>

                            </div>

                        </div>


                        {{-- Bottom --}}
                        <div class="mt-12 border-t border-white/20 pt-6">
                            <div class="flex items-center justify-between text-sm">
                                <span class="font-semibold text-green-100">
                                    Independent Journalism
                                </span>

                                <span class="font-bold text-red-200">
                                    সত্য • ন্যায় • স্বাধীনতা
                                </span>
                            </div>
                        </div>

                    </div>
                </div>


                {{-- RIGHT: LOGIN --}}
                <div class="flex min-h-[720px] items-center bg-white px-6 py-12 sm:px-12 lg:px-16 xl:px-20">

                    <div class="mx-auto w-full max-w-lg">

                        {{-- Mobile Brand --}}
                        <div class="mb-10 flex items-center gap-4 lg:hidden">

                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-green-700 text-2xl font-black text-white shadow-lg">
                                সা
                            </div>

                            <div>
                                <p class="text-lg font-black text-green-800">
                                    স্বাধীন আলো
                                </p>

                                <p class="text-xs font-bold uppercase tracking-widest text-red-600">
                                    SHADHIN ALO
                                </p>
                            </div>

                        </div>


                        {{-- Heading --}}
                        <div>

                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-red-600 text-2xl font-black text-white shadow-xl shadow-red-600/20">
                                সা
                            </div>

                            <p class="mt-8 text-sm font-black uppercase tracking-[0.25em] text-green-700">
                                Welcome Back
                            </p>

                            <h1 class="mt-3 text-4xl font-black tracking-tight text-gray-950 sm:text-5xl">
                                আবার স্বাগতম
                            </h1>

                            <p class="mt-4 text-base leading-7 text-gray-500">
                                আপনার Shadhin Alo নিউজরুমে প্রবেশ করতে
                                আপনার অ্যাকাউন্টে সাইন ইন করুন।
                            </p>

                        </div>


                        {{-- Errors --}}
                        @if ($errors->any())
                            <div class="mt-8 rounded-2xl border-2 border-red-100 bg-red-50 p-5">

                                <div class="flex gap-4">

                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-red-600 text-sm font-black text-white">
                                        !
                                    </div>

                                    <div>
                                        <p class="font-black text-red-800">
                                            Login failed
                                        </p>

                                        <p class="mt-1 text-sm leading-6 text-red-700">
                                            {{ $errors->first() }}
                                        </p>
                                    </div>

                                </div>

                            </div>
                        @endif


                        {{-- Form --}}
                        <form method="POST" action="{{ route('login.store') }}" class="mt-9 space-y-6">
                            @csrf

                            {{-- Email --}}
                            <div>

                                <label for="email" class="mb-2.5 block text-sm font-black text-gray-800">
                                    Email address
                                </label>

                                <div class="relative">

                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-5 text-green-700">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                d="M3 8l9 6 9-6M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                                        </svg>
                                    </div>

                                    <input
                                        id="email"
                                        name="email"
                                        type="email"
                                        value="{{ old('email') }}"
                                        required
                                        autofocus
                                        autocomplete="email"
                                        placeholder="you@example.com"
                                        class="block w-full rounded-2xl border-2 border-gray-100 bg-gray-50 py-4 pl-14 pr-5 text-base text-gray-900 outline-none transition duration-200 placeholder:text-gray-400 hover:border-green-200 focus:border-green-600 focus:bg-white focus:ring-4 focus:ring-green-600/10"
                                    >

                                </div>

                            </div>


                            {{-- Password --}}
                            <div>

                                <div class="mb-2.5 flex items-center justify-between">

                                    <label for="password" class="block text-sm font-black text-gray-800">
                                        Password
                                    </label>

                                    <span class="text-xs font-bold text-red-600">
                                        Secure access
                                    </span>

                                </div>

                                <div class="relative">

                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-5 text-red-600">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                d="M15 11V8a3 3 0 10-6 0v3m-3 0h12a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6a2 2 0 012-2z" />
                                        </svg>
                                    </div>

                                    <input
                                        id="password"
                                        name="password"
                                        type="password"
                                        required
                                        autocomplete="current-password"
                                        placeholder="আপনার password লিখুন"
                                        class="block w-full rounded-2xl border-2 border-gray-100 bg-gray-50 py-4 pl-14 pr-5 text-base text-gray-900 outline-none transition duration-200 placeholder:text-gray-400 hover:border-red-200 focus:border-red-600 focus:bg-white focus:ring-4 focus:ring-red-600/10"
                                    >

                                </div>

                            </div>


                            {{-- Remember --}}
                            <div class="flex items-center justify-between">

                                <label class="flex cursor-pointer items-center gap-3 text-sm font-semibold text-gray-600">

                                    <input
                                        type="checkbox"
                                        name="remember_me"
                                        value="1"
                                        class="h-5 w-5 rounded border-gray-300 text-green-600 focus:ring-green-500"
                                    >

                                    <span>
                                        Remember me
                                    </span>

                                </label>

                                <div class="flex items-center gap-2 text-xs font-bold text-gray-400">
                                    <span class="h-2.5 w-2.5 rounded-full bg-red-600"></span>
                                    Protected
                                </div>

                            </div>


                            {{-- Login Button --}}
                            <button
                                type="submit"
                                class="group relative flex w-full items-center justify-center gap-3 overflow-hidden rounded-2xl bg-gradient-to-r from-green-700 via-green-600 to-red-600 px-6 py-5 text-base font-black text-white shadow-xl shadow-green-700/20 transition duration-300 hover:-translate-y-1 hover:shadow-2xl hover:shadow-red-600/20 focus:outline-none focus:ring-4 focus:ring-green-500/20"
                            >

                                <span>
                                    Sign in to Dashboard
                                </span>

                                <svg
                                    class="h-5 w-5 transition-transform duration-300 group-hover:translate-x-1"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6"
                                    />
                                </svg>

                            </button>

                        </form>


                        {{-- Security Box --}}
                        <div class="mt-10 rounded-2xl border border-green-100 bg-gradient-to-r from-green-50 to-red-50 p-5">

                            <div class="flex gap-4">

                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-green-700 text-white">

                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                            d="M12 3l7 4v5c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V7l7-4z" />
                                    </svg>

                                </div>

                                <div>

                                    <p class="text-sm font-black text-green-900">
                                        নিরাপদ প্রবেশাধিকার
                                    </p>

                                    <p class="mt-1 text-xs leading-5 text-gray-600">
                                        শুধুমাত্র অনুমোদিত ব্যবহারকারীরা
                                        এই নিউজরুমে প্রবেশ করতে পারবেন।
                                    </p>

                                </div>

                            </div>

                        </div>


                        {{-- Footer --}}
                        <div class="mt-8 flex items-center justify-center gap-3 text-xs font-semibold text-gray-400">

                            <span class="h-1.5 w-1.5 rounded-full bg-green-600"></span>

                            <span>
                                স্বাধীন আলো
                            </span>

                            <span class="h-1.5 w-1.5 rounded-full bg-red-600"></span>

                            <span>
                                Independent Journalism
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>
@endsection
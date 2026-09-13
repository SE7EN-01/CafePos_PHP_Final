<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#F9F6F0]">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Bong Heng Cafe') }} - Premium Specialty Cafe</title>

        <!-- Fonts Preconnect -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-full font-sans antialiased text-stone-800 bg-[#F9F6F0] selection:bg-amber-500 selection:text-white">
        <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">
            <!-- Sidebar Navigation -->
            @include('layouts.navigation')

            <!-- Main Application Viewport -->
            <div class="flex flex-1 flex-col overflow-hidden min-w-0">
                <!-- Top Header for Mobile & Quick Status Bar -->
                <header class="flex h-16 shrink-0 items-center justify-between border-b border-stone-200/80 bg-white/80 px-4 sm:px-6 backdrop-blur-md lg:hidden">
                    <div class="flex items-center gap-3">
                        <button type="button" @click="sidebarOpen = true" class="inline-flex items-center justify-center rounded-xl p-2 text-stone-600 hover:bg-stone-100 hover:text-stone-900 focus:outline-none ring-1 ring-stone-200">
                            <span class="sr-only">Open sidebar</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </button>
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-500 text-stone-950 font-bold shadow-sm">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0 1 12 21 8.25 8.25 0 0 1 6.038 7.047 8.287 8.287 0 0 0 9 9.601a8.983 8.983 0 0 1 3.361-6.867 8.21 8.21 0 0 0 3 2.48Z" />
                                </svg>
                            </div>
                            <span class="font-display font-bold text-stone-900 tracking-tight">Bong Heng Cafe</span>
                        </a>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('pos.index') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-amber-500/10 px-3 py-1.5 text-xs font-semibold text-amber-700 ring-1 ring-inset ring-amber-500/20 hover:bg-amber-500/20 transition">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            POS
                        </a>
                        <a href="{{ route('profile.edit') }}" class="flex h-8 w-8 items-center justify-center rounded-full bg-stone-900 text-xs font-bold text-amber-300">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </a>
                    </div>
                </header>

                <!-- Page Header (if provided by view) -->
                @if (isset($header))
                    <header class="bg-white/80 backdrop-blur-md border-b border-stone-200/80 px-4 py-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </header>
                @endif

                <!-- Notification Toast (Flash Messages) -->
                @if (session('status'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition class="fixed top-5 right-5 z-50 flex items-center gap-3 rounded-2xl bg-emerald-900/90 text-white px-5 py-3.5 shadow-2xl backdrop-blur-md border border-emerald-500/30">
                        <div class="flex h-7 w-7 items-center justify-center rounded-full bg-emerald-500/20 text-emerald-300">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                        </div>
                        <p class="text-xs font-semibold tracking-wide">{{ session('status') }}</p>
                        <button @click="show = false" class="ml-2 text-emerald-300/60 hover:text-white">&times;</button>
                    </div>
                @endif

                @if (session('error'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition class="fixed top-5 right-5 z-50 flex items-center gap-3 rounded-2xl bg-rose-900/90 text-white px-5 py-3.5 shadow-2xl backdrop-blur-md border border-rose-500/30">
                        <div class="flex h-7 w-7 items-center justify-center rounded-full bg-rose-500/20 text-rose-300">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" /></svg>
                        </div>
                        <p class="text-xs font-semibold tracking-wide">{{ session('error') }}</p>
                        <button @click="show = false" class="ml-2 text-rose-300/60 hover:text-white">&times;</button>
                    </div>
                @endif

                <!-- Main Scrollable Content Area -->
                <main class="flex-1 overflow-y-auto custom-scrollbar">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>

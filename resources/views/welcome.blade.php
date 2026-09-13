<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#120F0D]">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Bong Heng Cafe') }} - Artisanal Cafe &amp; Modern POS System</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-full font-sans antialiased text-stone-200 bg-[#120F0D] selection:bg-amber-500 selection:text-stone-950">
        <!-- Ambient Warm Lighting Glows -->
        <div class="pointer-events-none fixed -top-40 -right-40 h-[600px] w-[600px] rounded-full bg-gradient-to-br from-amber-500/20 via-amber-700/10 to-transparent blur-3xl"></div>
        <div class="pointer-events-none fixed -bottom-40 -left-40 h-[600px] w-[600px] rounded-full bg-gradient-to-tr from-amber-800/15 via-stone-800/20 to-transparent blur-3xl"></div>

        <div class="relative flex min-h-screen flex-col justify-between">
            <!-- Navbar -->
            <header class="sticky top-0 z-40 w-full border-b border-white/5 bg-[#120F0D]/80 backdrop-blur-xl">
                <div class="mx-auto flex h-20 max-w-7xl items-center justify-between px-6 lg:px-8">
                    <a href="/" class="flex items-center gap-3 group">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-400 to-amber-600 text-stone-950 font-bold shadow-lg shadow-amber-500/25 transition group-hover:scale-105">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0 1 12 21 8.25 8.25 0 0 1 6.038 7.047 8.287 8.287 0 0 0 9 9.601a8.983 8.983 0 0 1 3.361-6.867 8.21 8.21 0 0 0 3 2.48Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18a3.75 3.75 0 0 0 .495-7.468 5.99 5.99 0 0 0-1.925 3.547 5.975 5.975 0 0 1-2.133-1.001A3.75 3.75 0 0 0 12 18Z" />
                            </svg>
                        </div>
                        <div>
                            <span class="block text-xl font-bold font-display tracking-tight text-white leading-tight">Bong Heng Cafe</span>
                            <span class="block text-[11px] font-semibold text-amber-400 tracking-widest uppercase">Specialty Coffee</span>
                        </div>
                    </a>

                    <nav class="flex items-center gap-3">
                        @auth
                            <a 
                                href="{{ route('pos.index') }}" 
                                class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-amber-400 to-amber-600 px-5 py-2.5 text-xs font-bold text-stone-950 shadow-md shadow-amber-500/20 hover:brightness-110 transition"
                            >
                                <span>Point of Sale (POS)</span>
                                <span class="h-1.5 w-1.5 rounded-full bg-stone-950 animate-pulse"></span>
                            </a>
                            <a 
                                href="{{ route('dashboard') }}" 
                                class="rounded-2xl bg-white/10 px-4 py-2.5 text-xs font-semibold text-white hover:bg-white/15 transition border border-white/10"
                            >
                                Dashboard
                            </a>
                        @else
                            <a 
                                href="{{ route('login') }}" 
                                class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-amber-400 to-amber-600 px-6 py-2.5 text-xs font-bold text-stone-950 shadow-lg shadow-amber-500/25 hover:brightness-110 active:scale-95 transition"
                            >
                                <span>Staff Portal Sign In</span>
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                            </a>
                        @endauth
                    </nav>
                </div>
            </header>

            <!-- Hero Section -->
            <main class="mx-auto max-w-7xl px-6 py-16 lg:px-8 lg:py-24">
                <div class="grid gap-12 lg:grid-cols-12 lg:items-center">
                    <!-- Left Hero Content (7 cols) -->
                    <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                        <div class="inline-flex items-center gap-2 rounded-full bg-amber-500/10 px-4 py-1.5 text-xs font-semibold text-amber-400 border border-amber-500/20 backdrop-blur-md">
                            <span class="h-2 w-2 rounded-full bg-amber-400 animate-pulse"></span>
                            Handcrafted Brews &bull; High-Performance POS
                        </div>

                        <h1 class="text-4xl sm:text-6xl font-extrabold font-display tracking-tight text-white leading-tight">
                            Elevate Every Cup with <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 via-amber-500 to-amber-200">Artisan Speed</span>
                        </h1>

                        <p class="text-sm sm:text-base text-stone-400 max-w-2xl leading-relaxed">
                            A tailor-made cafe management &amp; point-of-sale platform. Crafted for baristas to ring up drinks in seconds, manage recipe ingredients, track stock in real time, and delight coffee lovers.
                        </p>

                        <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 pt-4">
                            @auth
                                <a 
                                    href="{{ route('pos.index') }}" 
                                    class="inline-flex items-center gap-2.5 rounded-2xl bg-gradient-to-r from-amber-400 via-amber-500 to-amber-600 px-8 py-4 text-sm font-bold text-stone-950 shadow-xl shadow-amber-500/30 hover:brightness-110 active:scale-[0.98] transition"
                                >
                                    <span>Open Point of Sale (POS)</span>
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                                </a>
                            @else
                                <a 
                                    href="{{ route('login') }}" 
                                    class="inline-flex items-center gap-2.5 rounded-2xl bg-gradient-to-r from-amber-400 via-amber-500 to-amber-600 px-8 py-4 text-sm font-bold text-stone-950 shadow-xl shadow-amber-500/30 hover:brightness-110 active:scale-[0.98] transition"
                                >
                                    <span>Open Staff Register</span>
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                                </a>
                            @endauth

                            <div class="flex items-center gap-2 rounded-2xl bg-white/5 px-5 py-4 border border-white/10 text-xs text-stone-300">
                                <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                                <span>Demo: <strong>admin@cafe.com</strong> / admin</span>
                            </div>
                        </div>

                        <!-- Highlights Badge Row -->
                        <div class="grid grid-cols-3 gap-4 pt-6 border-t border-white/10 max-w-xl mx-auto lg:mx-0">
                            <div>
                                <p class="text-2xl font-extrabold text-amber-400 font-display">&lt; 3s</p>
                                <p class="text-[11px] text-stone-400 mt-0.5">Rapid Order Checkout</p>
                            </div>
                            <div>
                                <p class="text-2xl font-extrabold text-white font-display">100%</p>
                                <p class="text-[11px] text-stone-400 mt-0.5">Bilingual (English &amp; Khmer)</p>
                            </div>
                            <div>
                                <p class="text-2xl font-extrabold text-white font-display">Real-Time</p>
                                <p class="text-[11px] text-stone-400 mt-0.5">Ingredient Inventory</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Hero Showcase Visual (5 cols) -->
                    <div class="lg:col-span-5">
                        <div class="relative rounded-3xl bg-gradient-to-b from-[#241F1C] to-[#171412] p-6 sm:p-8 shadow-2xl border border-stone-800 backdrop-blur-xl">
                            <div class="flex items-center justify-between pb-4 border-b border-stone-800">
                                <div class="flex items-center gap-2">
                                    <span class="h-3 w-3 rounded-full bg-rose-500/80"></span>
                                    <span class="h-3 w-3 rounded-full bg-amber-500/80"></span>
                                    <span class="h-3 w-3 rounded-full bg-emerald-500/80"></span>
                                </div>
                                <span class="text-xs font-bold text-amber-400 font-display">Live Register View</span>
                            </div>

                            <!-- Showcase Beverage Cards -->
                            <div class="mt-5 space-y-3">
                                <div class="flex items-center justify-between rounded-2xl bg-white/5 p-3.5 border border-white/5 hover:border-amber-500/30 transition">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-500/20 text-amber-400 font-bold text-sm">
                                            ☕
                                        </div>
                                        <div>
                                            <p class="font-bold text-sm text-white">Cappuccino</p>
                                            <p class="text-[11px] text-stone-400">Regular &bull; Steamed whole milk foam</p>
                                        </div>
                                    </div>
                                    <span class="font-extrabold text-amber-400 text-sm">$3.50</span>
                                </div>

                                <div class="flex items-center justify-between rounded-2xl bg-white/5 p-3.5 border border-white/5 hover:border-amber-500/30 transition">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-500/20 text-amber-400 font-bold text-sm">
                                            🧊
                                        </div>
                                        <div>
                                            <p class="font-bold text-sm text-white">Iced Latte</p>
                                            <p class="text-[11px] text-stone-400">Regular &bull; Espresso &amp; chilled milk</p>
                                        </div>
                                    </div>
                                    <span class="font-extrabold text-amber-400 text-sm">$3.75</span>
                                </div>

                                <div class="flex items-center justify-between rounded-2xl bg-white/5 p-3.5 border border-white/5 hover:border-amber-500/30 transition">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-500/20 text-emerald-400 font-bold text-sm">
                                            🍵
                                        </div>
                                        <div>
                                            <p class="font-bold text-sm text-white">Matcha Tonic</p>
                                            <p class="text-[11px] text-stone-400">Regular &bull; Ceremonial matcha &amp; citrus</p>
                                        </div>
                                    </div>
                                    <span class="font-extrabold text-amber-400 text-sm">$4.25</span>
                                </div>

                                <div class="flex items-center justify-between rounded-2xl bg-white/5 p-3.5 border border-white/5 hover:border-amber-500/30 transition">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-500/20 text-amber-400 font-bold text-sm">
                                            🥐
                                        </div>
                                        <div>
                                            <p class="font-bold text-sm text-white">Butter Croissant</p>
                                            <p class="text-[11px] text-stone-400">Standard &bull; Baked fresh daily</p>
                                        </div>
                                    </div>
                                    <span class="font-extrabold text-amber-400 text-sm">$2.50</span>
                                </div>
                            </div>

                            <div class="mt-5 pt-4 border-t border-stone-800 flex items-center justify-between text-xs">
                                <span class="text-stone-400">Sample Order Subtotal</span>
                                <span class="text-base font-extrabold text-amber-400 font-display">$14.00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="border-t border-white/5 py-8 text-center text-xs text-stone-500">
                <p>&copy; {{ date('Y') }} Bong Heng Cafe. Handcrafted for specialty coffee roasters and cafe teams.</p>
            </footer>
        </div>
    </body>
</html>

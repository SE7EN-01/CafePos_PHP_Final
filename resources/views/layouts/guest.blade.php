<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Bong Heng Cafe') }} - Staff Portal</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-full font-sans antialiased text-stone-900 bg-[#171412] selection:bg-amber-500 selection:text-white">
        <div class="relative min-h-screen flex items-center justify-center px-4 py-12 sm:px-6 lg:px-8 overflow-hidden">
            <!-- Warm Coffee Atmosphere Ambient Glows -->
            <div class="pointer-events-none absolute -top-40 -right-40 h-[500px] w-[500px] rounded-full bg-gradient-to-br from-amber-500/20 to-transparent blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-40 -left-40 h-[500px] w-[500px] rounded-full bg-gradient-to-tr from-amber-700/20 to-transparent blur-3xl"></div>
            
            <div class="relative w-full max-w-md">
                <!-- Branding -->
                <div class="mb-8 text-center">
                    <a href="/" class="inline-flex items-center gap-3 group">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-400 to-amber-600 shadow-xl shadow-amber-500/25 text-stone-950 transition group-hover:scale-105">
                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0 1 12 21 8.25 8.25 0 0 1 6.038 7.047 8.287 8.287 0 0 0 9 9.601a8.983 8.983 0 0 1 3.361-6.867 8.21 8.21 0 0 0 3 2.48Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18a3.75 3.75 0 0 0 .495-7.468 5.99 5.99 0 0 0-1.925 3.547 5.975 5.975 0 0 1-2.133-1.001A3.75 3.75 0 0 0 12 18Z" />
                            </svg>
                        </div>
                        <div class="text-left">
                            <span class="block text-2xl font-bold font-display tracking-tight text-white leading-tight">Bong Heng Cafe</span>
                            <span class="block text-xs font-medium text-amber-400 uppercase tracking-widest">Specialty Cafe</span>
                        </div>
                    </a>
                </div>

                <!-- Glass Container Card -->
                <div class="overflow-hidden rounded-3xl bg-stone-900/80 border border-stone-800 p-8 shadow-2xl shadow-black/50 backdrop-blur-xl">
                    {{ $slot }}
                </div>

                <div class="mt-8 text-center">
                    <p class="text-xs text-stone-500">
                        &copy; {{ date('Y') }} Bong Heng Cafe. Crafted for specialty cafes.
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>

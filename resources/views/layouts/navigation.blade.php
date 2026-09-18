<!-- Desktop Sidebar -->
<aside class="hidden lg:flex lg:w-64 lg:flex-col shrink-0 bg-[#1C1917] text-stone-300 border-r border-stone-800/80 select-none">
    <!-- Brand Header -->
    <div class="flex h-18 items-center gap-3 px-6 border-b border-stone-800/80 bg-[#171412]">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 shadow-md shadow-amber-500/20 text-stone-950">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0 1 12 21 8.25 8.25 0 0 1 6.038 7.047 8.287 8.287 0 0 0 9 9.601a8.983 8.983 0 0 1 3.361-6.867 8.21 8.21 0 0 0 3 2.48Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18a3.75 3.75 0 0 0 .495-7.468 5.99 5.99 0 0 0-1.925 3.547 5.975 5.975 0 0 1-2.133-1.001A3.75 3.75 0 0 0 12 18Z" />
            </svg>
        </div>
        <div class="min-w-0 flex-1">
            <span class="block text-base font-bold font-display tracking-tight text-white leading-tight">Bong Heng Cafe</span>
            <span class="block text-[11px] text-amber-400/80 font-medium uppercase tracking-wider">Specialty Cafe</span>
        </div>
    </div>

    <!-- Navigation Links -->
    <div class="flex flex-1 flex-col justify-between overflow-y-auto custom-scrollbar px-3 py-4">
        <nav class="space-y-1.5" aria-label="Sidebar Navigation">
            @if (!Auth::user()->hasRole('barista'))
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-amber-500/20 to-amber-500/5 text-amber-300 border-l-2 border-amber-400 font-semibold shadow-inner' : 'text-stone-300 hover:text-white hover:bg-white/5' }}">
                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('dashboard') ? 'text-amber-400' : 'text-stone-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    <span>Dashboard</span>
                </a>
            @endif

            <a href="{{ route('pos.index') }}" class="flex items-center justify-between rounded-xl px-3.5 py-2.5 text-sm font-medium transition-all duration-150 {{ request()->routeIs('pos.*') ? 'bg-gradient-to-r from-amber-500/20 to-amber-500/5 text-amber-300 border-l-2 border-amber-400 font-semibold shadow-inner' : 'text-stone-300 hover:text-white hover:bg-white/5' }}">
                <div class="flex items-center gap-3">
                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('pos.*') ? 'text-amber-400' : 'text-stone-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015A3.001 3.001 0 0 0 21 9.349m-7.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-3.75c-.621 0-1.125.504-1.125 1.125v6" />
                    </svg>
                    <span>Point of Sale (POS)</span>
                </div>
                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-500/15 px-2 py-0.5 text-[11px] font-semibold text-emerald-400 ring-1 ring-inset ring-emerald-500/30">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    Live
                </span>
            </a>


            @if (Auth::user()->hasRole('admin'))
                <div class="pt-5 pb-2">
                    <p class="px-3.5 text-[10px] font-bold uppercase tracking-widest text-amber-500/70">Management</p>
                </div>

                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium transition-all duration-150 {{ request()->routeIs('admin.categories.*') ? 'bg-gradient-to-r from-amber-500/20 to-amber-500/5 text-amber-300 border-l-2 border-amber-400 font-semibold shadow-inner' : 'text-stone-300 hover:text-white hover:bg-white/5' }}">
                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('admin.categories.*') ? 'text-amber-400' : 'text-stone-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                    </svg>
                    <span>Categories</span>
                </a>

                <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium transition-all duration-150 {{ request()->routeIs('admin.products.*') ? 'bg-gradient-to-r from-amber-500/20 to-amber-500/5 text-amber-300 border-l-2 border-amber-400 font-semibold shadow-inner' : 'text-stone-300 hover:text-white hover:bg-white/5' }}">
                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('admin.products.*') ? 'text-amber-400' : 'text-stone-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m6 4.125l2.25 2.25m0 0l2.25-2.25M12 13.875V7.5" />
                    </svg>
                    <span>Products</span>
                </a>

                @php
                    $isStockActive = request()->routeIs([
                        'admin.stock.*',
                        'admin.purchases.*',
                        'admin.suppliers.*',
                        'admin.recipes.*',
                        'admin.ingredients.*',
                    ]);
                @endphp

                <!-- Stock & Inventory Dropdown -->
                <div x-data="{ open: {{ $isStockActive ? 'true' : 'false' }} }" class="space-y-1">
                    <button 
                        type="button" 
                        @click="open = !open" 
                        class="flex w-full items-center justify-between rounded-xl px-3.5 py-2.5 text-sm font-medium transition-all duration-150 {{ $isStockActive ? 'bg-gradient-to-r from-amber-500/20 to-amber-500/5 text-amber-300 border-l-2 border-amber-400 font-semibold shadow-inner' : 'text-stone-300 hover:text-white hover:bg-white/5' }}"
                    >
                        <div class="flex items-center gap-3">
                            <svg class="h-5 w-5 shrink-0 {{ $isStockActive ? 'text-amber-400' : 'text-stone-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                            </svg>
                            <span>Stock &amp; Inventory</span>
                        </div>
                        <svg class="h-4 w-4 shrink-0 text-stone-500 transition-transform duration-200" :class="open ? 'rotate-180 text-amber-400' : ''" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>

                    <!-- Dropdown Sub-Items -->
                    <div 
                        x-show="open" 
                        x-cloak
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 -translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-1"
                        class="pl-9 pr-2 space-y-1 pt-0.5"
                    >
                        <a href="{{ route('admin.stock.index') }}" class="flex items-center justify-between rounded-lg px-2.5 py-1.5 text-xs font-medium {{ request()->fullUrlIs(route('admin.stock.index')) ? 'text-amber-300 font-semibold bg-white/5' : 'text-stone-400 hover:text-white hover:bg-white/5' }} transition">
                            <span>Stock Overview</span>
                        </a>
                        <a href="{{ route('admin.ingredients.index') }}" class="flex items-center justify-between rounded-lg px-2.5 py-1.5 text-xs font-medium {{ request()->routeIs('admin.ingredients.*') ? 'text-amber-300 font-semibold bg-white/5' : 'text-stone-400 hover:text-white hover:bg-white/5' }} transition">
                            <span>Raw Ingredients</span>
                        </a>
                        <a href="{{ route('admin.purchases.index') }}" class="flex items-center justify-between rounded-lg px-2.5 py-1.5 text-xs font-medium {{ request()->routeIs('admin.purchases.*') ? 'text-amber-300 font-semibold bg-white/5' : 'text-stone-400 hover:text-white hover:bg-white/5' }} transition">
                            <span>Purchases (GRN)</span>
                        </a>
                        <a href="{{ route('admin.suppliers.index') }}" class="flex items-center justify-between rounded-lg px-2.5 py-1.5 text-xs font-medium {{ request()->routeIs('admin.suppliers.*') ? 'text-amber-300 font-semibold bg-white/5' : 'text-stone-400 hover:text-white hover:bg-white/5' }} transition">
                            <span>Suppliers</span>
                        </a>
                        <a href="{{ route('admin.recipes.index') }}" class="flex items-center justify-between rounded-lg px-2.5 py-1.5 text-xs font-medium {{ request()->routeIs('admin.recipes.*') ? 'text-amber-300 font-semibold bg-white/5' : 'text-stone-400 hover:text-white hover:bg-white/5' }} transition">
                            <span>Recipes &amp; Costing</span>
                        </a>
                        <a href="{{ route('admin.stock.reports') }}" class="flex items-center justify-between rounded-lg px-2.5 py-1.5 text-xs font-medium {{ request()->routeIs('admin.stock.reports') ? 'text-amber-300 font-semibold bg-white/5' : 'text-stone-400 hover:text-white hover:bg-white/5' }} transition">
                            <span>Inventory Reports</span>
                        </a>
                        <a href="{{ route('admin.stock.index', ['filter' => 'low_stock']) }}" class="flex items-center justify-between rounded-lg px-2.5 py-1.5 text-xs font-medium {{ request()->input('filter') === 'low_stock' ? 'text-amber-300 font-semibold bg-white/5' : 'text-stone-400 hover:text-white hover:bg-white/5' }} transition">
                            <span>Low Stock Alerts</span>
                        </a>
                    </div>
                </div>

                <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium transition-all duration-150 {{ request()->routeIs('admin.orders.*') ? 'bg-gradient-to-r from-amber-500/20 to-amber-500/5 text-amber-300 border-l-2 border-amber-400 font-semibold shadow-inner' : 'text-stone-300 hover:text-white hover:bg-white/5' }}">
                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('admin.orders.*') ? 'text-amber-400' : 'text-stone-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                    <span>Orders</span>
                </a>

                <a href="{{ route('admin.tables.index') }}" class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium transition-all duration-150 {{ request()->routeIs('admin.tables.*') ? 'bg-gradient-to-r from-amber-500/20 to-amber-500/5 text-amber-300 border-l-2 border-amber-400 font-semibold shadow-inner' : 'text-stone-300 hover:text-white hover:bg-white/5' }}">
                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('admin.tables.*') ? 'text-amber-400' : 'text-stone-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                    </svg>
                    <span>Tables</span>
                </a>

                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium transition-all duration-150 {{ request()->routeIs('admin.users.*') ? 'bg-gradient-to-r from-amber-500/20 to-amber-500/5 text-amber-300 border-l-2 border-amber-400 font-semibold shadow-inner' : 'text-stone-300 hover:text-white hover:bg-white/5' }}">
                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('admin.users.*') ? 'text-amber-400' : 'text-stone-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                    <span>Team</span>
                </a>

                <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium transition-all duration-150 {{ request()->routeIs('admin.settings.*') ? 'bg-gradient-to-r from-amber-500/20 to-amber-500/5 text-amber-300 border-l-2 border-amber-400 font-semibold shadow-inner' : 'text-stone-300 hover:text-white hover:bg-white/5' }}">
                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('admin.settings.*') ? 'text-amber-400' : 'text-stone-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.6 6.6 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <span>Store Settings</span>
                </a>
            @endif
        </nav>

        <!-- User Profile Dropup -->
        <div class="border-t border-stone-800/80 pt-4 mt-2">
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex w-full items-center gap-3 rounded-xl p-2 text-stone-300 transition-all hover:bg-white/5 hover:text-white">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-amber-500/20 text-sm font-bold text-amber-300 border border-amber-500/30">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1 text-left">
                        <p class="truncate text-xs font-semibold text-white leading-tight">{{ Auth::user()->name }}</p>
                        <p class="truncate text-[11px] text-amber-400/80 capitalize">{{ Auth::user()->roles->first()?->name ?? 'Staff' }}</p>
                    </div>
                    <svg class="h-4 w-4 shrink-0 text-stone-500 transition-transform duration-200" :class="open ? 'rotate-180 text-amber-400' : ''" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>

                <!-- Dropup Menu -->
                <div x-show="open" @click.away="open = false" x-cloak
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                    class="absolute bottom-full left-0 mb-2 w-full rounded-2xl bg-[#24201D] p-2 shadow-2xl ring-1 ring-white/10 z-50">
                    <div class="px-3 py-2 border-b border-stone-700/60 mb-1">
                        <p class="text-xs font-semibold text-white">{{ Auth::user()->name }}</p>
                        <p class="text-[11px] text-stone-400 truncate">{{ Auth::user()->email }}</p>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-medium text-stone-300 hover:bg-white/5 hover:text-white transition">
                        <svg class="h-4 w-4 text-stone-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0" /></svg>
                        Profile Settings
                    </a>
                    @if (Auth::user()->hasRole('admin'))
                        <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-medium text-stone-300 hover:bg-white/5 hover:text-white transition">
                            <svg class="h-4 w-4 text-stone-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.6 6.6 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            Store &amp; Payment Settings
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex w-full items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-medium text-rose-400 hover:bg-rose-500/10 transition">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" /></svg>
                            Sign out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</aside>

<!-- Mobile Slide-over Drawer -->
<div x-show="sidebarOpen" x-cloak class="relative z-50 lg:hidden" role="dialog" aria-modal="true">
    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="sidebarOpen = false" class="fixed inset-0 bg-stone-950/70 backdrop-blur-sm"></div>

    <div class="fixed inset-0 flex">
        <div x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-200 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-150 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="relative mr-16 flex w-full max-w-xs flex-1 flex-col bg-[#1C1917] text-stone-300">
            <!-- Close Button -->
            <div class="flex h-16 items-center justify-between px-6 border-b border-stone-800/80">
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-500 text-stone-950 font-bold">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0 1 12 21 8.25 8.25 0 0 1 6.038 7.047 8.287 8.287 0 0 0 9 9.601a8.983 8.983 0 0 1 3.361-6.867 8.21 8.21 0 0 0 3 2.48Z" /></svg>
                    </div>
                    <span class="text-base font-bold font-display text-white">Bong Heng Cafe</span>
                </div>
                <button type="button" @click="sidebarOpen = false" class="rounded-lg p-1.5 text-stone-400 hover:bg-white/10 hover:text-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <!-- Mobile Nav Links -->
            <div class="flex flex-1 flex-col justify-between overflow-y-auto px-4 py-4 space-y-2">
                <div class="space-y-1">
                    @if (!Auth::user()->hasRole('barista'))
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-amber-500/20 text-amber-300 font-semibold' : 'text-stone-300 hover:bg-white/5' }}">
                            Dashboard
                        </a>
                    @endif
                    <a href="{{ route('pos.index') }}" class="flex items-center justify-between rounded-xl px-3.5 py-2.5 text-sm font-medium {{ request()->routeIs('pos.*') ? 'bg-amber-500/20 text-amber-300 font-semibold' : 'text-stone-300 hover:bg-white/5' }}">
                        <span>Point of Sale (POS)</span>
                        <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    </a>
                    @if (Auth::user()->hasRole('admin'))
                        <p class="pt-4 pb-1 px-3 text-[10px] font-bold uppercase tracking-widest text-amber-500/70">Management</p>
                        <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium {{ request()->routeIs('admin.categories.*') ? 'bg-amber-500/20 text-amber-300 font-semibold' : 'text-stone-300 hover:bg-white/5' }}">Categories</a>
                        <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium {{ request()->routeIs('admin.products.*') ? 'bg-amber-500/20 text-amber-300 font-semibold' : 'text-stone-300 hover:bg-white/5' }}">Products</a>
                        <!-- Mobile Stock & Inventory Dropdown -->
                        <div x-data="{ open: {{ $isStockActive ? 'true' : 'false' }} }" class="space-y-1">
                            <button 
                                type="button" 
                                @click="open = !open" 
                                class="flex w-full items-center justify-between rounded-xl px-3.5 py-2.5 text-sm font-medium text-stone-300 hover:bg-white/5 {{ $isStockActive ? 'bg-amber-500/20 text-amber-300 font-semibold' : '' }}"
                            >
                                <span class="flex items-center gap-2">
                                    <span>Stock &amp; Inventory</span>
                                </span>
                                <svg class="h-4 w-4 text-stone-500 transition-transform duration-200" :class="open ? 'rotate-180 text-amber-400' : ''" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <div x-show="open" x-cloak class="pl-6 space-y-1">
                                <a href="{{ route('admin.stock.index') }}" class="flex items-center justify-between rounded-lg px-3 py-1.5 text-xs transition {{ request()->fullUrlIs(route('admin.stock.index')) ? 'text-amber-300 font-semibold bg-white/5' : 'text-stone-400 hover:text-white hover:bg-white/5' }}">
                                    <span>Stock Overview</span>
                                </a>
                                <a href="{{ route('admin.ingredients.index') }}" class="flex items-center justify-between rounded-lg px-3 py-1.5 text-xs transition {{ request()->routeIs('admin.ingredients.*') ? 'text-amber-300 font-semibold bg-white/5' : 'text-stone-400 hover:text-white hover:bg-white/5' }}">
                                    <span>Raw Ingredients</span>
                                </a>
                                <a href="{{ route('admin.purchases.index') }}" class="flex items-center justify-between rounded-lg px-3 py-1.5 text-xs transition {{ request()->routeIs('admin.purchases.*') ? 'text-amber-300 font-semibold bg-white/5' : 'text-stone-400 hover:text-white hover:bg-white/5' }}">
                                    <span>Purchases (GRN)</span>
                                </a>
                                <a href="{{ route('admin.suppliers.index') }}" class="flex items-center justify-between rounded-lg px-3 py-1.5 text-xs transition {{ request()->routeIs('admin.suppliers.*') ? 'text-amber-300 font-semibold bg-white/5' : 'text-stone-400 hover:text-white hover:bg-white/5' }}">
                                    <span>Suppliers</span>
                                </a>
                                <a href="{{ route('admin.recipes.index') }}" class="flex items-center justify-between rounded-lg px-3 py-1.5 text-xs transition {{ request()->routeIs('admin.recipes.*') ? 'text-amber-300 font-semibold bg-white/5' : 'text-stone-400 hover:text-white hover:bg-white/5' }}">
                                    <span>Recipes &amp; Costing</span>
                                </a>
                                <a href="{{ route('admin.stock.reports') }}" class="flex items-center justify-between rounded-lg px-3 py-1.5 text-xs transition {{ request()->routeIs('admin.stock.reports') ? 'text-amber-300 font-semibold bg-white/5' : 'text-stone-400 hover:text-white hover:bg-white/5' }}">
                                    <span>Inventory Reports</span>
                                </a>
                                <a href="{{ route('admin.stock.index', ['filter' => 'low_stock']) }}" class="flex items-center justify-between rounded-lg px-3 py-1.5 text-xs transition {{ request()->input('filter') === 'low_stock' ? 'text-amber-300 font-semibold bg-white/5' : 'text-stone-400 hover:text-white hover:bg-white/5' }}">
                                    <span>Low Stock Alerts</span>
                                </a>
                            </div>
                        </div>

                        <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium {{ request()->routeIs('admin.orders.*') ? 'bg-amber-500/20 text-amber-300 font-semibold' : 'text-stone-300 hover:bg-white/5' }}">Orders</a>
                        <a href="{{ route('admin.tables.index') }}" class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium {{ request()->routeIs('admin.tables.*') ? 'bg-amber-500/20 text-amber-300 font-semibold' : 'text-stone-300 hover:bg-white/5' }}">Tables</a>
                        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'bg-amber-500/20 text-amber-300 font-semibold' : 'text-stone-300 hover:bg-white/5' }}">Team</a>
                        <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium {{ request()->routeIs('admin.settings.*') ? 'bg-amber-500/20 text-amber-300 font-semibold' : 'text-stone-300 hover:bg-white/5' }}">Store Settings</a>
                    @endif
                </div>

                <div class="border-t border-stone-800 pt-4">
                    <div class="flex items-center gap-3 px-2 mb-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-500/20 font-bold text-amber-300">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-semibold text-white">{{ Auth::user()->name }}</p>
                            <p class="truncate text-xs text-stone-400">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <a href="{{ route('profile.edit') }}" class="block rounded-xl px-3 py-2 text-xs font-medium text-stone-300 hover:bg-white/5">Profile Settings</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left rounded-xl px-3 py-2 text-xs font-medium text-rose-400 hover:bg-rose-500/10">Sign out</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-app-layout>
    <div x-data="{
        stockInModal: false,
        adjustModal: false,
        stockInType: 'ingredient',
        stockInIngredientId: '{{ $allIngredients->first()?->id ?? '' }}',
        stockInVariantId: '{{ $allVariants->first()?->id ?? '' }}',
        adjustType: 'ingredient',
        adjustIngredientId: '{{ $allIngredients->first()?->id ?? '' }}',
        adjustVariantId: '{{ $allVariants->first()?->id ?? '' }}',
        adjustMode: 'waste',
        openQuickIn(type, id) {
            this.stockInType = type;
            if (type === 'ingredient') {
                this.stockInIngredientId = String(id);
            } else {
                this.stockInVariantId = String(id);
            }
            this.stockInModal = true;
        },
        openQuickAdjust(type, id) {
            this.adjustType = type;
            if (type === 'ingredient') {
                this.adjustIngredientId = String(id);
            } else {
                this.adjustVariantId = String(id);
            }
            this.adjustModal = true;
        }
    }" class="min-h-full bg-[#F9F6F0] p-4 sm:p-6 lg:p-8">
        <div class="w-full space-y-6">

            <!-- Success Alerts -->
            @if (session('status') === 'stock-received')
                <div x-data="{ show: true }" x-show="show" class="flex items-center justify-between rounded-2xl bg-emerald-500/10 border border-emerald-500/20 p-4 text-emerald-800">
                    <div class="flex items-center gap-3">
                        <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <span class="text-sm font-semibold">Stock received and inventory balance updated successfully! (បញ្ចូលស្តុកដោយជោគជ័យ)</span>
                    </div>
                    <button @click="show = false" class="text-emerald-600 hover:text-emerald-800">&times;</button>
                </div>
            @endif

            @if (session('status') === 'stock-adjusted')
                <div x-data="{ show: true }" x-show="show" class="flex items-center justify-between rounded-2xl bg-amber-500/10 border border-amber-500/20 p-4 text-amber-900">
                    <div class="flex items-center gap-3">
                        <svg class="h-5 w-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                        </svg>
                        <span class="text-sm font-semibold">Stock adjustment / waste logged successfully! (កែសម្រួលស្តុកដោយជោគជ័យ)</span>
                    </div>
                    <button @click="show = false" class="text-amber-700 hover:text-amber-900">&times;</button>
                </div>
            @endif

            <!-- Header Panel -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-stone-200/80">
                <div class="flex items-center gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-500/15 text-amber-700 border border-amber-500/30">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-3">
                            <h1 class="text-2xl sm:text-3xl font-bold font-display tracking-tight text-stone-900">Stock &amp; Inventory</h1>
                            <span class="inline-flex items-center rounded-full bg-emerald-500/10 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 border border-emerald-500/20">
                                Live Active
                            </span>
                        </div>
                        <p class="text-xs sm:text-sm text-stone-500 mt-1">Real-time inventory levels, goods receiving, waste logging, and recipe usage audit</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center gap-2.5">
                    <button 
                        type="button" 
                        @click="stockInModal = true"
                        class="inline-flex items-center gap-2 rounded-2xl bg-amber-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-amber-700 transition"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>Stock In (Receive Goods)</span>
                    </button>

                    <button 
                        type="button" 
                        @click="adjustModal = true"
                        class="inline-flex items-center gap-2 rounded-2xl bg-stone-100 px-4 py-2.5 text-xs font-bold text-stone-800 hover:bg-stone-200 transition border border-stone-200"
                    >
                        <svg class="h-4 w-4 text-stone-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                        <span>Adjust / Waste</span>
                    </button>

                    <a 
                        href="{{ route('admin.ingredients.index') }}" 
                        class="inline-flex items-center gap-1.5 rounded-2xl bg-white px-3.5 py-2.5 text-xs font-semibold text-stone-600 hover:bg-stone-50 transition border border-stone-200"
                        title="Manage Raw Ingredient Definitions"
                    >
                        <span>Raw Items</span>
                        <svg class="h-3.5 w-3.5 text-stone-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- KPI Summary Cards -->
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Total Items -->
                <div class="rounded-3xl bg-white p-5 shadow-sm border border-stone-200/80">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-stone-400">Total Tracked</span>
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-stone-100 text-stone-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline gap-2">
                        <span class="text-2xl sm:text-3xl font-bold font-display text-stone-900">{{ $totalItems }}</span>
                        <span class="text-xs text-stone-500">items</span>
                    </div>
                    <p class="mt-1 text-[11px] text-stone-400">Ingredients + Retail snacks</p>
                </div>

                <!-- Low Stock Alert -->
                <div class="rounded-3xl bg-white p-5 shadow-sm border border-stone-200/80">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-stone-400">Low Stock Alert</span>
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl {{ $lowStockCount > 0 ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline gap-2">
                        <span class="text-2xl sm:text-3xl font-bold font-display {{ $lowStockCount > 0 ? 'text-amber-600' : 'text-stone-900' }}">{{ $lowStockCount }}</span>
                        <span class="text-xs text-stone-500">needs reorder</span>
                    </div>
                    <p class="mt-1 text-[11px] text-stone-400">At or below reorder threshold</p>
                </div>

                <!-- Out of Stock -->
                <div class="rounded-3xl bg-white p-5 shadow-sm border border-stone-200/80">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-stone-400">Out of Stock</span>
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl {{ $outOfStockCount > 0 ? 'bg-rose-100 text-rose-700' : 'bg-stone-100 text-stone-600' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline gap-2">
                        <span class="text-2xl sm:text-3xl font-bold font-display {{ $outOfStockCount > 0 ? 'text-rose-600' : 'text-stone-900' }}">{{ $outOfStockCount }}</span>
                        <span class="text-xs text-stone-500">depleted</span>
                    </div>
                    <p class="mt-1 text-[11px] text-stone-400">Inventory balance at 0</p>
                </div>

                <!-- Activity Today -->
                <div class="rounded-3xl bg-white p-5 shadow-sm border border-stone-200/80">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-stone-400">Activity Today</span>
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-sky-100 text-sky-700">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline gap-2">
                        <span class="text-2xl sm:text-3xl font-bold font-display text-stone-900">{{ $todayMovementsCount }}</span>
                        <span class="text-xs text-stone-500">movements</span>
                    </div>
                    <p class="mt-1 text-[11px] text-stone-400">Stock In, waste &amp; sales deductions</p>
                </div>
            </div>

            <!-- Filter Tabs & Search Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white rounded-3xl p-4 shadow-sm border border-stone-200/80">
                <!-- Tabs -->
                <div class="flex flex-wrap items-center gap-1.5">
                    <a 
                        href="{{ route('admin.stock.index', ['filter' => 'all', 'search' => $search]) }}"
                        class="rounded-xl px-3.5 py-2 text-xs font-semibold transition {{ $filter === 'all' ? 'bg-stone-900 text-white shadow-sm' : 'text-stone-600 hover:bg-stone-100' }}"
                    >
                        All Stock ({{ $totalItems }})
                    </a>

                    <a 
                        href="{{ route('admin.stock.index', ['filter' => 'low_stock', 'search' => $search]) }}"
                        class="inline-flex items-center gap-1.5 rounded-xl px-3.5 py-2 text-xs font-semibold transition {{ $filter === 'low_stock' ? 'bg-amber-600 text-white shadow-sm' : 'text-stone-600 hover:bg-stone-100' }}"
                    >
                        <span>Low Stock</span>
                        @if ($lowStockCount > 0)
                            <span class="rounded-full bg-white/20 px-1.5 py-0.2 text-[10px]">{{ $lowStockCount }}</span>
                        @endif
                    </a>

                    <a 
                        href="{{ route('admin.stock.index', ['filter' => 'ingredients', 'search' => $search]) }}"
                        class="rounded-xl px-3.5 py-2 text-xs font-semibold transition {{ $filter === 'ingredients' ? 'bg-stone-900 text-white shadow-sm' : 'text-stone-600 hover:bg-stone-100' }}"
                    >
                        Raw Ingredients
                    </a>

                    <a 
                        href="{{ route('admin.stock.index', ['filter' => 'variants', 'search' => $search]) }}"
                        class="rounded-xl px-3.5 py-2 text-xs font-semibold transition {{ $filter === 'variants' ? 'bg-stone-900 text-white shadow-sm' : 'text-stone-600 hover:bg-stone-100' }}"
                    >
                        Packaged Goods
                    </a>
                </div>

                <!-- Search Input -->
                <form method="GET" action="{{ route('admin.stock.index') }}" class="flex items-center gap-2">
                    <input type="hidden" name="filter" value="{{ $filter }}">
                    <div class="relative">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ $search }}" 
                            placeholder="Search stock item..." 
                            class="w-56 sm:w-64 rounded-xl border border-stone-200 bg-stone-50 px-3 py-2 text-xs text-stone-800 placeholder-stone-400 focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-amber-500"
                        />
                        @if ($search)
                            <a href="{{ route('admin.stock.index', ['filter' => $filter]) }}" class="absolute right-2.5 top-2 text-stone-400 hover:text-stone-600 text-xs">&times;</a>
                        @endif
                    </div>
                    <button type="submit" class="rounded-xl bg-stone-100 px-3 py-2 text-xs font-semibold text-stone-700 hover:bg-stone-200 transition">
                        Search
                    </button>
                </form>
            </div>

            <!-- Inventory Balance Table -->
            <div class="bg-white rounded-3xl shadow-sm border border-stone-200/80 overflow-hidden">
                <div class="px-6 py-4 border-b border-stone-200/80 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold font-display text-stone-900">Current Inventory Balances</h2>
                        <p class="text-xs text-stone-500">Live physical stock quantity versus safety reorder point</p>
                    </div>
                    <span class="text-xs font-medium text-stone-400">Showing {{ count($stockItems) }} items</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-stone-200/80 bg-stone-50/50 text-[11px] font-bold uppercase tracking-wider text-stone-400">
                                <th class="px-6 py-3.5">Item Name</th>
                                <th class="px-6 py-3.5">Category / Type</th>
                                <th class="px-6 py-3.5">Current Stock</th>
                                <th class="px-6 py-3.5">Safety Level</th>
                                <th class="px-6 py-3.5">Status</th>
                                <th class="px-6 py-3.5 text-right">Quick Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 text-xs">
                            @forelse ($stockItems as $item)
                                <tr class="hover:bg-amber-500/[0.02] transition">
                                    <!-- Name -->
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-stone-900 text-sm">
                                            {{ $item->name }}
                                        </div>
                                        @php
                                            $secondaryName = (app()->getLocale() === 'km') 
                                                ? ($item->name_translations['en'] ?? '') 
                                                : ($item->name_translations['km'] ?? '');
                                        @endphp
                                        @if ($secondaryName && $secondaryName !== $item->name)
                                            <div class="text-[11px] text-stone-400 mt-0.5">
                                                {{ $secondaryName }}
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Category -->
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center rounded-lg bg-stone-100 px-2.5 py-1 text-[11px] font-medium text-stone-700">
                                            {{ $item->category }}
                                        </span>
                                    </td>

                                    <!-- Stock Balance -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-baseline gap-1.5 font-mono font-bold text-stone-900 text-sm">
                                            <span>{{ number_format($item->current_stock, $item->unit === 'pcs' ? 0 : 1) }}</span>
                                            <span class="text-xs text-stone-500 font-normal font-sans">{{ $item->unit }}</span>
                                        </div>
                                    </td>

                                    <!-- Reorder Level -->
                                    <td class="px-6 py-4 text-stone-500">
                                        <span class="font-mono text-xs">{{ number_format($item->reorder_level, $item->unit === 'pcs' ? 0 : 1) }} {{ $item->unit }}</span>
                                    </td>

                                    <!-- Status Badge -->
                                    <td class="px-6 py-4">
                                        @if ($item->is_out_of_stock)
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-2.5 py-1 text-xs font-bold text-rose-700 border border-rose-200">
                                                <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                                                Out of Stock
                                            </span>
                                        @elseif ($item->is_low_stock)
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-700 border border-amber-200">
                                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                Low Stock
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700 border border-emerald-200">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                                In Stock
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Quick Actions -->
                                    <td class="px-6 py-4 text-right">
                                        <div class="inline-flex items-center gap-1.5">
                                            <button 
                                                type="button" 
                                                @click="openQuickIn('{{ $item->type }}', {{ $item->id }})"
                                                class="rounded-lg bg-emerald-50 px-2.5 py-1.5 text-xs font-bold text-emerald-700 hover:bg-emerald-100 transition border border-emerald-200"
                                                title="Receive Stock"
                                            >
                                                + In
                                            </button>
                                            <button 
                                                type="button" 
                                                @click="openQuickAdjust('{{ $item->type }}', {{ $item->id }})"
                                                class="rounded-lg bg-stone-100 px-2.5 py-1.5 text-xs font-bold text-stone-700 hover:bg-stone-200 transition border border-stone-200"
                                                title="Adjust or Record Waste"
                                            >
                                                Adjust
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-stone-400">
                                        <svg class="mx-auto h-8 w-8 text-stone-300 mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                        </svg>
                                        <p class="text-sm font-semibold">No stock items match your search or filter.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Stock Movement Audit Ledger -->
            <div class="bg-white rounded-3xl shadow-sm border border-stone-200/80 overflow-hidden">
                <div class="px-6 py-4 border-b border-stone-200/80 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold font-display text-stone-900">Recent Stock Activity &amp; Audit Log</h2>
                        <p class="text-xs text-stone-500">Historical trail of supplier restocks, waste losses, and order deductions</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-stone-200/80 bg-stone-50/50 text-[11px] font-bold uppercase tracking-wider text-stone-400">
                                <th class="px-6 py-3.5">Date &amp; Time</th>
                                <th class="px-6 py-3.5">Item</th>
                                <th class="px-6 py-3.5">Type</th>
                                <th class="px-6 py-3.5">Quantity Change</th>
                                <th class="px-6 py-3.5">Logged By</th>
                                <th class="px-6 py-3.5">Reason &amp; Notes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 text-xs">
                            @forelse ($movements as $m)
                                <tr class="hover:bg-stone-50/50 transition">
                                    <td class="px-6 py-3.5 text-stone-500 font-mono">
                                        {{ $m->created_at->format('M d, Y H:i') }}
                                    </td>
                                    <td class="px-6 py-3.5 font-semibold text-stone-900">
                                        {{ $m->stockable?->name ?? 'Unknown Item' }}
                                    </td>
                                    <td class="px-6 py-3.5">
                                        @if ($m->type === 'in')
                                            <span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-bold text-emerald-700 border border-emerald-200">
                                                Stock In
                                            </span>
                                        @elseif ($m->type === 'waste')
                                            <span class="inline-flex items-center rounded-full bg-rose-50 px-2 py-0.5 text-[11px] font-bold text-rose-700 border border-rose-200">
                                                Waste / Spoilage
                                            </span>
                                        @elseif ($m->type === 'sale')
                                            <span class="inline-flex items-center rounded-full bg-sky-50 px-2 py-0.5 text-[11px] font-bold text-sky-700 border border-sky-200">
                                                POS Sale
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-amber-50 px-2 py-0.5 text-[11px] font-bold text-amber-700 border border-amber-200">
                                                Adjustment
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3.5 font-mono font-bold {{ $m->quantity > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ $m->quantity > 0 ? '+' : '' }}{{ number_format($m->quantity, 2) }}
                                        <span class="text-[11px] font-normal text-stone-400 font-sans">{{ $m->stockable?->unit ?? '' }}</span>
                                    </td>
                                    <td class="px-6 py-3.5 text-stone-600">
                                        {{ $m->user?->name ?? 'System' }}
                                    </td>
                                    <td class="px-6 py-3.5 text-stone-500">
                                        <span class="font-medium text-stone-700">{{ $m->reason }}</span>
                                        @if ($m->notes)
                                            <span class="text-stone-400 text-[11px]">({{ $m->notes }})</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-stone-400">
                                        No stock movements recorded yet. Receive stock or place orders to build history.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($movements->hasPages())
                    <div class="px-6 py-4 border-t border-stone-200/80">
                        {{ $movements->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- MODAL 1: STOCK IN (RECEIVE GOODS) -->
        <!-- ========================================================================= -->
        <div 
            x-show="stockInModal" 
            x-cloak 
            class="fixed inset-0 z-50 overflow-y-auto bg-stone-900/60 backdrop-blur-sm flex items-center justify-center p-4"
        >
            <div 
                @click.outside="stockInModal = false"
                class="w-full max-w-lg rounded-3xl bg-white p-6 sm:p-8 shadow-2xl border border-stone-200 space-y-6"
            >
                <div class="flex items-center justify-between border-b border-stone-100 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold font-display text-stone-900">Stock In (Receive Delivery)</h3>
                            <p class="text-xs text-stone-500">Record incoming supplies into physical inventory</p>
                        </div>
                    </div>
                    <button @click="stockInModal = false" class="text-stone-400 hover:text-stone-600 text-xl font-bold">&times;</button>
                </div>

                <form method="POST" action="{{ route('admin.stock.in') }}" class="space-y-4">
                    @csrf

                    <!-- Item Type Selector -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-500 mb-1.5">Item Classification</label>
                        <div class="grid grid-cols-2 gap-2">
                            <button 
                                type="button" 
                                @click="stockInType = 'ingredient'"
                                :class="stockInType === 'ingredient' ? 'bg-amber-600 text-white font-bold' : 'bg-stone-100 text-stone-600 hover:bg-stone-200'"
                                class="rounded-xl py-2 text-xs transition"
                            >
                                Raw Ingredient
                            </button>
                            <button 
                                type="button" 
                                @click="stockInType = 'variant'"
                                :class="stockInType === 'variant' ? 'bg-amber-600 text-white font-bold' : 'bg-stone-100 text-stone-600 hover:bg-stone-200'"
                                class="rounded-xl py-2 text-xs transition"
                            >
                                Packaged Retail Variant
                            </button>
                        </div>
                        <input type="hidden" name="item_type" :value="stockInType">
                    </div>

                    <!-- Item Dropdown -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-500 mb-1.5">Select Item to Restock</label>
                        
                        <!-- Raw Ingredients Dropdown -->
                        <div x-show="stockInType === 'ingredient'">
                            <select 
                                name="item_id" 
                                :disabled="stockInType !== 'ingredient'"
                                x-model="stockInIngredientId"
                                required 
                                class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3.5 py-2.5 text-xs text-stone-900 focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-amber-500"
                            >
                                @forelse ($allIngredients as $ing)
                                    <option value="{{ $ing->id }}">{{ $ing->name }} (Current: {{ number_format($ing->current_stock, 1) }} {{ $ing->unit }})</option>
                                @empty
                                    <option value="" disabled selected>No raw ingredients found</option>
                                @endforelse
                            </select>
                        </div>

                        <!-- Packaged Retail Variants Dropdown -->
                        <div x-show="stockInType === 'variant'">
                            <select 
                                name="item_id" 
                                :disabled="stockInType !== 'variant'"
                                x-model="stockInVariantId"
                                required 
                                class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3.5 py-2.5 text-xs text-stone-900 focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-amber-500"
                            >
                                @forelse ($allVariants as $var)
                                    <option value="{{ $var->id }}">{{ $var->product->name }} - {{ $var->name }} (Current: {{ $var->stock_quantity }} pcs)</option>
                                @empty
                                    <option value="" disabled selected>No packaged retail products found</option>
                                @endforelse
                            </select>
                            @if ($allVariants->isEmpty())
                                <p class="text-[11px] text-amber-600 mt-1">No retail products have "Track Stock" enabled yet.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Quantity Added -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-stone-500 mb-1.5">Quantity Received</label>
                            <input 
                                type="number" 
                                name="quantity" 
                                step="0.01" 
                                min="0.01" 
                                required 
                                placeholder="e.g. 5000" 
                                class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3.5 py-2.5 text-xs text-stone-900 font-mono focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-amber-500"
                            />
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-stone-500 mb-1.5">Unit Cost ($ optional)</label>
                            <input 
                                type="number" 
                                name="unit_cost" 
                                step="0.01" 
                                min="0" 
                                placeholder="e.g. 12.50" 
                                class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3.5 py-2.5 text-xs text-stone-900 font-mono focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-amber-500"
                            />
                        </div>
                    </div>

                    <!-- Reason / Reference -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-500 mb-1.5">Supplier / Delivery Reference</label>
                        <input 
                            type="text" 
                            name="reason" 
                            placeholder="e.g. Weekly delivery from Phnom Penh Roastery" 
                            class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3.5 py-2.5 text-xs text-stone-900 focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-amber-500"
                        />
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-500 mb-1.5">Internal Notes (Optional)</label>
                        <textarea 
                            name="notes" 
                            rows="2" 
                            placeholder="Invoice #, batch code, expiry notes..." 
                            class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3.5 py-2 text-xs text-stone-900 focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-amber-500"
                        ></textarea>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-stone-100">
                        <button 
                            type="button" 
                            @click="stockInModal = false"
                            class="rounded-xl px-4 py-2 text-xs font-semibold text-stone-500 hover:bg-stone-100 transition"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            class="rounded-xl bg-emerald-600 px-5 py-2 text-xs font-bold text-white shadow-sm hover:bg-emerald-700 transition"
                        >
                            Confirm Stock In
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- MODAL 2: STOCK ADJUSTMENT / WASTE -->
        <!-- ========================================================================= -->
        <div 
            x-show="adjustModal" 
            x-cloak 
            class="fixed inset-0 z-50 overflow-y-auto bg-stone-900/60 backdrop-blur-sm flex items-center justify-center p-4"
        >
            <div 
                @click.outside="adjustModal = false"
                class="w-full max-w-lg rounded-3xl bg-white p-6 sm:p-8 shadow-2xl border border-stone-200 space-y-6"
            >
                <div class="flex items-center justify-between border-b border-stone-100 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold font-display text-stone-900">Stock Adjustment &amp; Waste</h3>
                            <p class="text-xs text-stone-500">Record spoiled items, calibration loss, or stocktake recount</p>
                        </div>
                    </div>
                    <button @click="adjustModal = false" class="text-stone-400 hover:text-stone-600 text-xl font-bold">&times;</button>
                </div>

                <form method="POST" action="{{ route('admin.stock.adjust') }}" class="space-y-4">
                    @csrf

                    <!-- Mode Selector -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-500 mb-1.5">Adjustment Type</label>
                        <div class="grid grid-cols-3 gap-2">
                            <button 
                                type="button" 
                                @click="adjustMode = 'waste'"
                                :class="adjustMode === 'waste' ? 'bg-rose-600 text-white font-bold' : 'bg-stone-100 text-stone-600 hover:bg-stone-200'"
                                class="rounded-xl py-2 text-xs transition"
                            >
                                Waste / Spoilage
                            </button>
                            <button 
                                type="button" 
                                @click="adjustMode = 'calibration'"
                                :class="adjustMode === 'calibration' ? 'bg-amber-600 text-white font-bold' : 'bg-stone-100 text-stone-600 hover:bg-stone-200'"
                                class="rounded-xl py-2 text-xs transition"
                            >
                                Calibration Loss
                            </button>
                            <button 
                                type="button" 
                                @click="adjustMode = 'recount'"
                                :class="adjustMode === 'recount' ? 'bg-stone-800 text-white font-bold' : 'bg-stone-100 text-stone-600 hover:bg-stone-200'"
                                class="rounded-xl py-2 text-xs transition"
                            >
                                Stocktake Recount
                            </button>
                        </div>
                        <input type="hidden" name="adjustment_type" :value="adjustMode">
                    </div>

                    <!-- Item Type Selector -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-500 mb-1.5">Item Classification</label>
                        <div class="grid grid-cols-2 gap-2">
                            <button 
                                type="button" 
                                @click="adjustType = 'ingredient'"
                                :class="adjustType === 'ingredient' ? 'bg-amber-600 text-white font-bold' : 'bg-stone-100 text-stone-600 hover:bg-stone-200'"
                                class="rounded-xl py-2 text-xs transition"
                            >
                                Raw Ingredient
                            </button>
                            <button 
                                type="button" 
                                @click="adjustType = 'variant'"
                                :class="adjustType === 'variant' ? 'bg-amber-600 text-white font-bold' : 'bg-stone-100 text-stone-600 hover:bg-stone-200'"
                                class="rounded-xl py-2 text-xs transition"
                            >
                                Packaged Retail Variant
                            </button>
                        </div>
                        <input type="hidden" name="item_type" :value="adjustType">
                    </div>

                    <!-- Item Dropdown -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-500 mb-1.5">Select Item</label>
                        
                        <!-- Raw Ingredients Dropdown -->
                        <div x-show="adjustType === 'ingredient'">
                            <select 
                                name="item_id" 
                                :disabled="adjustType !== 'ingredient'"
                                x-model="adjustIngredientId"
                                required 
                                class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3.5 py-2.5 text-xs text-stone-900 focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-amber-500"
                            >
                                @forelse ($allIngredients as $ing)
                                    <option value="{{ $ing->id }}">{{ $ing->name }} (Current: {{ number_format($ing->current_stock, 1) }} {{ $ing->unit }})</option>
                                @empty
                                    <option value="" disabled selected>No raw ingredients found</option>
                                @endforelse
                            </select>
                        </div>

                        <!-- Packaged Retail Variants Dropdown -->
                        <div x-show="adjustType === 'variant'">
                            <select 
                                name="item_id" 
                                :disabled="adjustType !== 'variant'"
                                x-model="adjustVariantId"
                                required 
                                class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3.5 py-2.5 text-xs text-stone-900 focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-amber-500"
                            >
                                @forelse ($allVariants as $var)
                                    <option value="{{ $var->id }}">{{ $var->product->name }} - {{ $var->name }} (Current: {{ $var->stock_quantity }} pcs)</option>
                                @empty
                                    <option value="" disabled selected>No packaged retail products found</option>
                                @endforelse
                            </select>
                            @if ($allVariants->isEmpty())
                                <p class="text-[11px] text-amber-600 mt-1">No retail products have "Track Stock" enabled yet.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Quantity Field (dynamic label based on mode) -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-500 mb-1.5">
                            <span x-show="adjustMode !== 'recount'">Quantity Wasted / Deducted</span>
                            <span x-show="adjustMode === 'recount'">New Actual Count On Hand</span>
                        </label>
                        <input 
                            type="number" 
                            name="quantity" 
                            step="0.01" 
                            required 
                            placeholder="e.g. 250" 
                            class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3.5 py-2.5 text-xs text-stone-900 font-mono focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-amber-500"
                        />
                        <p class="text-[11px] text-stone-400 mt-1">
                            <span x-show="adjustMode !== 'recount'">This amount will be deducted from current stock balance.</span>
                            <span x-show="adjustMode === 'recount'">The stock balance will be directly updated to this counted figure.</span>
                        </p>
                    </div>

                    <!-- Reason -->
                    <div x-data="{ quickReason: '' }">
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-500 mb-1.5">Reason For Adjustment</label>
                        <input 
                            type="text" 
                            name="reason" 
                            x-model="quickReason"
                            list="adjustment-reasons"
                            required 
                            placeholder="Type or pick a preset reason..." 
                            class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3.5 py-2.5 text-xs text-stone-900 focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-amber-500"
                        />
                        <datalist id="adjustment-reasons">
                            <option value="Expired / Past shelf life">
                            <option value="Damaged / Spilled container">
                            <option value="Tasting / Quality check">
                            <option value="Grinder calibration loss">
                            <option value="Physical stocktake recount variance">
                            <option value="Preparation mistake / Spoiled drink">
                        </datalist>
                        <!-- Quick Click Presets -->
                        <div class="flex flex-wrap gap-1.5 mt-2">
                            <button type="button" @click="quickReason = 'Expired / Past shelf life'" class="rounded-lg bg-stone-100 px-2 py-1 text-[11px] text-stone-600 hover:bg-stone-200 transition">
                                Expired
                            </button>
                            <button type="button" @click="quickReason = 'Damaged / Spilled container'" class="rounded-lg bg-stone-100 px-2 py-1 text-[11px] text-stone-600 hover:bg-stone-200 transition">
                                Spilled / Broken
                            </button>
                            <button type="button" @click="quickReason = 'Tasting / Quality check'" class="rounded-lg bg-stone-100 px-2 py-1 text-[11px] text-stone-600 hover:bg-stone-200 transition">
                                Tasting Test
                            </button>
                            <button type="button" @click="quickReason = 'Grinder calibration loss'" class="rounded-lg bg-stone-100 px-2 py-1 text-[11px] text-stone-600 hover:bg-stone-200 transition">
                                Calibration
                            </button>
                            <button type="button" @click="quickReason = 'Physical stocktake recount variance'" class="rounded-lg bg-stone-100 px-2 py-1 text-[11px] text-stone-600 hover:bg-stone-200 transition">
                                Stocktake Recount
                            </button>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-500 mb-1.5">Additional Notes (Optional)</label>
                        <textarea 
                            name="notes" 
                            rows="2" 
                            placeholder="Specific circumstances or manager authorization..." 
                            class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3.5 py-2 text-xs text-stone-900 focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-amber-500"
                        ></textarea>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-stone-100">
                        <button 
                            type="button" 
                            @click="adjustModal = false"
                            class="rounded-xl px-4 py-2 text-xs font-semibold text-stone-500 hover:bg-stone-100 transition"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            class="rounded-xl bg-amber-600 px-5 py-2 text-xs font-bold text-white shadow-sm hover:bg-amber-700 transition"
                        >
                            Confirm Adjustment
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>

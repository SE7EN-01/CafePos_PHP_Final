<x-app-layout>
    <div class="min-h-full bg-[#F9F6F0] p-4 sm:p-6 lg:p-8 space-y-8 font-sans">

        <!-- 1. HERO WELCOME & QUICK ACTION BAR -->
        <div
            class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#1C1917] via-[#262220] to-[#151210] p-6 sm:p-8 text-white shadow-xl border border-white/10">
            <!-- Ambient Coffee Glow Effects -->
            <div
                class="pointer-events-none absolute -top-24 -right-24 h-80 w-80 rounded-full bg-gradient-to-br from-amber-500/25 via-amber-700/15 to-transparent blur-3xl">
            </div>
            <div
                class="pointer-events-none absolute -bottom-24 -left-24 h-80 w-80 rounded-full bg-gradient-to-tr from-amber-800/20 via-stone-800/30 to-transparent blur-3xl">
            </div>

            <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <!-- Welcome Title & Live Badge -->
                <div class="space-y-2">
                    <div class="flex flex-wrap items-center gap-3">
                        <span
                            class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/15 px-3 py-1 text-xs font-semibold text-emerald-400 ring-1 ring-inset ring-emerald-500/30">
                            <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            Live POS Active
                        </span>
                        <span class="text-xs font-medium text-stone-400">
                            {{ now()->format('l, F j, Y') }} &bull; {{ now()->format('h:i A') }}
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-extrabold font-display tracking-tight text-white">
                        សួស្តី, {{ auth()->user()->name }}! ☕
                    </h1>
                    <p class="text-xs sm:text-sm text-stone-300 max-w-xl leading-relaxed">
                        Welcome to <strong class="text-amber-400">Bong Heng Cafe</strong> live control center. Monitor
                        sales revenue, table seating, inventory health, and staff performance in real-time.
                    </p>
                </div>

                <!-- Quick Action Shortcuts -->
                <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                    <a href="{{ route('pos.index') }}"
                        class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-amber-400 via-amber-500 to-amber-600 px-5 py-3 text-xs sm:text-sm font-bold text-stone-950 shadow-lg shadow-amber-500/25 hover:brightness-110 active:scale-95 transition transform">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015A3.001 3.001 0 0 0 21 9.349m-7.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-3.75c-.621 0-1.125.504-1.125 1.125v6" />
                        </svg>
                        <span>POS</span>
                    </a>

                    @if (auth()->user()->hasRole('admin'))
                        <a href="{{ route('admin.stock.index') }}"
                            class="inline-flex items-center gap-2 rounded-2xl bg-stone-800/80 hover:bg-stone-700/80 px-4 py-3 text-xs sm:text-sm font-semibold text-stone-200 border border-white/10 shadow-sm transition active:scale-95">
                            <svg class="h-4 w-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.75 7.5h16.5m-16.5 0-1.5-3.75h19.5l-1.5 3.75" />
                            </svg>
                            <span>Stock In</span>
                        </a>

                        <a href="{{ route('admin.tables.index') }}"
                            class="inline-flex items-center gap-2 rounded-2xl bg-stone-800/80 hover:bg-stone-700/80 px-4 py-3 text-xs sm:text-sm font-semibold text-stone-200 border border-white/10 shadow-sm transition active:scale-95">
                            <svg class="h-4 w-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6Zm0 9.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25Zm9.75 0A2.25 2.25 0 0 1 16 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H16a2.25 2.25 0 0 1-2.25-2.25v-2.25Zm0-9.75A2.25 2.25 0 0 1 16 3.75h2.25a2.25 2.25 0 0 1 2.25 2.25V6a2.25 2.25 0 0 1-2.25 2.25H16a2.25 2.25 0 0 1-2.25-2.25V6Z" />
                            </svg>
                            <span>Tables</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- 2. INVENTORY ALERT BANNER (IF LOW STOCK OR EXPIRING ITEMS) -->
        @if ($lowStockIngredients->isNotEmpty() || $expiringIngredients->isNotEmpty())
            <div
                class="rounded-3xl bg-amber-50 border border-amber-200/80 p-4 sm:p-5 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-amber-500/20 text-amber-800 border border-amber-500/30">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-amber-950 font-display">
                            ការជូនដំណឹងស្តុក និងទំនិញ / Inventory Alert
                        </h4>
                        <p class="text-xs text-amber-800 mt-0.5">
                            @if ($lowStockIngredients->isNotEmpty())
                                <span class="font-bold">{{ $lowStockIngredients->count() }}</span> ingredient(s) are below
                                reorder level.
                            @endif
                            @if ($expiringIngredients->isNotEmpty())
                                <span class="font-bold">{{ $expiringIngredients->count() }}</span> ingredient(s) expiring within
                                7 days.
                            @endif
                        </p>
                    </div>
                </div>

                @if (auth()->user()->hasRole('admin'))
                    <a href="{{ route('admin.stock.reports') }}"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-amber-600 hover:bg-amber-700 px-4 py-2 text-xs font-bold text-white shadow-sm transition">
                        <span>Check Stock &amp; Reports</span>
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                @endif
            </div>
        @endif

        <!-- 3. TOP 4 METRIC STATS CARDS -->
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Card 1: Today's Revenue -->
            <div
                class="rounded-3xl bg-white p-6 shadow-sm border border-stone-200/80 hover:border-amber-400/60 hover:shadow-md transition card-hover group">
                <div class="flex items-center justify-between">
                    <div class="min-w-0">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-stone-400">Today's Revenue /
                            ចំណូលថ្ងៃនេះ</span>
                        <p
                            class="mt-2 text-3xl font-extrabold text-stone-900 font-display tracking-tight group-hover:text-amber-600 transition">
                            ${{ number_format($todayRevenue, 2) }}
                        </p>
                    </div>
                    <div
                        class="flex h-13 w-13 shrink-0 items-center justify-center rounded-2xl bg-amber-500/15 text-amber-700 border border-amber-500/20 shadow-inner">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v12m-3-2.818.879.659 1.171-1.671.659-1.171m-1.171 1.671L6.293 17.4l-.659-1.171m6.541-2.061.659 1.171M12 6V2m0 4a2.5 2.5 0 0 0-2.5 2.5v.5a3.5 3.5 0 0 0 7 0v-.5A2.5 2.5 0 0 0 12 6Z" />
                        </svg>
                    </div>
                </div>
                <div
                    class="mt-4 pt-3 border-t border-stone-100 flex items-center justify-between text-[11px] font-medium text-stone-500">
                    <span class="inline-flex items-center gap-1 text-emerald-700 font-semibold">
                        <span>💵 Cash: ${{ number_format($cashRevenue, 2) }}</span>
                    </span>
                    <span class="inline-flex items-center gap-1 text-rose-700 font-semibold">
                        <span>📱 KHQR: ${{ number_format($khqrRevenue, 2) }}</span>
                    </span>
                </div>
            </div>

            <!-- Card 2: Orders Today -->
            <div
                class="rounded-3xl bg-white p-6 shadow-sm border border-stone-200/80 hover:border-emerald-400/60 hover:shadow-md transition card-hover group">
                <div class="flex items-center justify-between">
                    <div class="min-w-0">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-stone-400">Orders Today /
                            ការកុម្ម៉ង់</span>
                        <p
                            class="mt-2 text-3xl font-extrabold text-stone-900 font-display tracking-tight group-hover:text-emerald-600 transition">
                            {{ $ordersToday }}
                        </p>
                    </div>
                    <div
                        class="flex h-13 w-13 shrink-0 items-center justify-center rounded-2xl bg-emerald-500/15 text-emerald-700 border border-emerald-500/20 shadow-inner">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                        </svg>
                    </div>
                </div>
                <div
                    class="mt-4 pt-3 border-t border-stone-100 flex items-center justify-between text-[11px] font-medium text-stone-500">
                    <span>Average Ticket</span>
                    <span class="font-bold text-stone-900">${{ number_format($avgOrderValue, 2) }} / order</span>
                </div>
            </div>

            <!-- Card 3: Items Sold -->
            <div
                class="rounded-3xl bg-white p-6 shadow-sm border border-stone-200/80 hover:border-sky-400/60 hover:shadow-md transition card-hover group">
                <div class="flex items-center justify-between">
                    <div class="min-w-0">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-stone-400">Cups &amp; Treats /
                            ចំនួនលក់</span>
                        <p
                            class="mt-2 text-3xl font-extrabold text-stone-900 font-display tracking-tight group-hover:text-sky-600 transition">
                            {{ $itemsSold }} <span class="text-sm font-semibold text-stone-500">units</span>
                        </p>
                    </div>
                    <div
                        class="flex h-13 w-13 shrink-0 items-center justify-center rounded-2xl bg-sky-500/15 text-sky-700 border border-sky-500/20 shadow-inner">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0 1 12 15a9.065 9.065 0 0 1-6.23-.693L5 14.5m14.8.8 1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0 1 12 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" />
                        </svg>
                    </div>
                </div>
                <div
                    class="mt-4 pt-3 border-t border-stone-100 flex items-center justify-between text-[11px] font-medium text-stone-500">
                    <span>Staff On Duty</span>
                    <span class="font-bold text-stone-900">{{ $teamMembers }} active accounts</span>
                </div>
            </div>

            <!-- Card 4: Dine-In Tables Status -->
            <div
                class="rounded-3xl bg-white p-6 shadow-sm border border-stone-200/80 hover:border-purple-400/60 hover:shadow-md transition card-hover group">
                <div class="flex items-center justify-between">
                    <div class="min-w-0">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-stone-400">Tables Seated /
                            តុភ្ញៀវ</span>
                        <p
                            class="mt-2 text-3xl font-extrabold text-stone-900 font-display tracking-tight group-hover:text-purple-600 transition">
                            {{ $occupiedTables }} <span class="text-sm font-semibold text-stone-400">/
                                {{ $totalTables }}</span>
                        </p>
                    </div>
                    <div
                        class="flex h-13 w-13 shrink-0 items-center justify-center rounded-2xl bg-purple-500/15 text-purple-700 border border-purple-500/20 shadow-inner">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6Zm0 9.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25Zm9.75 0A2.25 2.25 0 0 1 16 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H16a2.25 2.25 0 0 1-2.25-2.25v-2.25Zm0-9.75A2.25 2.25 0 0 1 16 3.75h2.25a2.25 2.25 0 0 1 2.25 2.25V6a2.25 2.25 0 0 1-2.25 2.25H16a2.25 2.25 0 0 1-2.25-2.25V6Z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-stone-100 flex items-center justify-between text-[11px]">
                    <span class="text-stone-500">Available: <strong
                            class="text-emerald-700">{{ $availableTables }}</strong></span>
                    <span class="text-stone-500">Occupied: <strong
                            class="text-amber-700">{{ $occupiedTables }}</strong></span>
                </div>
            </div>
        </div>

        <!-- 4. MAIN TWO-COLUMN DASHBOARD GRID -->
        <div class="grid gap-8 lg:grid-cols-12">

            <!-- LEFT COLUMN: Sales Chart + Recent Orders (8 Cols) -->
            <div class="lg:col-span-8 space-y-8">

                <!-- 7-Day Revenue Sparkline / Bar Graph -->
                <div class="rounded-3xl bg-white p-6 sm:p-7 shadow-sm border border-stone-200/80">
                    <div
                        class="flex flex-col sm:flex-row sm:items-center justify-between pb-5 border-b border-stone-100 gap-2">
                        <div>
                            <h2 class="text-base sm:text-lg font-bold font-display tracking-tight text-stone-900">
                                7-Day Revenue Trend / និន្នាការចំណូល ៧ ថ្ងៃចុងក្រោយ
                            </h2>
                            <p class="text-xs text-stone-500 mt-0.5">
                                Daily turnover comparison over the current week
                            </p>
                        </div>
                        <span
                            class="self-start sm:self-auto rounded-full bg-amber-500/10 px-3 py-1 text-xs font-bold text-amber-800 border border-amber-500/20">
                            7-Day Total: ${{ number_format($salesLast7Days->sum('total'), 2) }}
                        </span>
                    </div>

                    <!-- Visual Bar Graph -->
                    <div class="mt-6 pt-2">
                        <div class="grid grid-cols-7 gap-2 sm:gap-4 items-end h-44 sm:h-52 px-2 pb-2">
                            @foreach ($salesLast7Days as $day)
                                @php
                                    $heightPercent = $maxDaySale > 0 ? max(round(($day['total'] / $maxDaySale) * 100), 8) : 8;
                                    $isToday = $loop->last;
                                @endphp
                                <div class="flex flex-col items-center h-full justify-end group">
                                    <!-- Tooltip / Total Value on hover -->
                                    <span
                                        class="mb-2 text-[11px] font-bold {{ $isToday ? 'text-amber-700' : 'text-stone-700' }} transition">
                                        ${{ number_format($day['total'], 0) }}
                                    </span>

                                    <!-- Bar Pillar -->
                                    <div class="w-full max-w-[48px] rounded-2xl transition-all duration-300 transform group-hover:scale-y-105 {{ $isToday ? 'bg-gradient-to-t from-amber-600 via-amber-500 to-amber-400 shadow-md shadow-amber-500/30 ring-2 ring-amber-400/40' : 'bg-gradient-to-t from-stone-200 via-stone-300 to-amber-200/80 group-hover:from-amber-400 group-hover:to-amber-500' }}"
                                        style="height: {{ $heightPercent }}%;">
                                    </div>

                                    <!-- Label Date & Day -->
                                    <div class="mt-3 text-center">
                                        <span
                                            class="block text-[11px] font-bold {{ $isToday ? 'text-amber-700' : 'text-stone-800' }}">
                                            {{ $day['day'] }}
                                        </span>
                                        <span class="block text-[10px] text-stone-400">
                                            {{ $day['date'] }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Live Recent Orders Table -->
                <div class="rounded-3xl bg-white p-6 sm:p-7 shadow-sm border border-stone-200/80">
                    <div class="flex items-center justify-between pb-5 border-b border-stone-100">
                        <div>
                            <h2 class="text-base sm:text-lg font-bold font-display tracking-tight text-stone-900">
                                Live Recent Orders / ការកុម្ម៉ង់ចុងក្រោយ
                            </h2>
                            <p class="text-xs text-stone-500 mt-0.5">Real-time tickets placed by cashiers &amp;
                                customers</p>
                        </div>
                        @if (auth()->user()->hasRole('admin'))
                            <a href="{{ route('admin.orders.index') }}"
                                class="inline-flex items-center gap-1 text-xs font-bold text-amber-700 hover:text-amber-800 transition">
                                <span>View all tickets</span>
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>
                        @endif
                    </div>

                    <div class="overflow-x-auto mt-3">
                        <table class="w-full text-left text-xs text-stone-600">
                            <thead>
                                <tr
                                    class="border-b border-stone-100 text-[11px] font-bold uppercase tracking-wider text-stone-400">
                                    <th class="py-3 px-2">Order #</th>
                                    <th class="py-3 px-2">Seating / Type</th>
                                    <th class="py-3 px-2">Payment</th>
                                    <th class="py-3 px-2 text-right">Total</th>
                                    <th class="py-3 px-2 text-right">Time</th>
                                    <th class="py-3 px-2 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-100">
                                @forelse ($recentOrders as $order)
                                    <tr class="hover:bg-stone-50/70 transition">
                                        <!-- Order Number -->
                                        <td class="py-3.5 px-2 font-bold text-stone-900 font-mono">
                                            {{ $order->order_number }}
                                        </td>

                                        <!-- Type / Seating -->
                                        <td class="py-3.5 px-2">
                                            @if ($order->order_type === 'dine_in')
                                                <span
                                                    class="inline-flex items-center gap-1 rounded-lg bg-purple-50 px-2 py-0.5 text-[11px] font-semibold text-purple-700 border border-purple-200">
                                                    <span>🪑 {{ $order->cafeTable->name ?? 'Dine-In' }}</span>
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-1 rounded-lg bg-amber-50 px-2 py-0.5 text-[11px] font-semibold text-amber-800 border border-amber-200">
                                                    <span>🛍️ Takeaway</span>
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Payment Method -->
                                        <td class="py-3.5 px-2">
                                            @if ($order->payment_method === 'khqr')
                                                <span
                                                    class="inline-flex items-center gap-1 rounded-full bg-rose-50 px-2.5 py-0.5 text-[10px] font-bold text-rose-700 border border-rose-200">
                                                    <span>Bakong KHQR</span>
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-0.5 text-[10px] font-bold text-emerald-700 border border-emerald-200">
                                                    <span>Cash</span>
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Total -->
                                        <td class="py-3.5 px-2 text-right font-extrabold text-stone-900 font-display">
                                            ${{ number_format($order->total_amount, 2) }}
                                        </td>

                                        <!-- Time -->
                                        <td class="py-3.5 px-2 text-right text-stone-400 text-[11px]">
                                            {{ $order->created_at->diffForHumans() }}
                                        </td>

                                        <!-- Action Receipt -->
                                        <td class="py-3.5 px-2 text-center">
                                            <a href="{{ route('pos.receipt', $order) }}" target="_blank"
                                                class="inline-flex items-center gap-1 rounded-xl bg-stone-100 hover:bg-amber-100 text-stone-700 hover:text-amber-900 px-2.5 py-1 text-[11px] font-semibold transition border border-stone-200/80">
                                                <span>Receipt</span>
                                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-12 text-center">
                                            <div
                                                class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-stone-100 text-stone-400 mb-2">
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg>
                                            </div>
                                            <p class="text-sm font-semibold text-stone-800">No orders recorded yet today</p>
                                            <p class="text-xs text-stone-400 mt-0.5">Start ringing up drinks from the POS
                                                register.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN: Top Drinks & Floor Plan Overview (4 Cols) -->
            <div class="lg:col-span-4 space-y-8">

                <!-- Popular Drinks Leaderboard -->
                <div class="rounded-3xl bg-white p-6 shadow-sm border border-stone-200/80">
                    <div class="flex items-center justify-between pb-4 border-b border-stone-100">
                        <div>
                            <h3 class="text-base font-bold font-display tracking-tight text-stone-900">
                                Top Sellers / ភេសជ្ជៈពេញនិយម
                            </h3>
                            <p class="text-xs text-stone-500">Most ordered drinks today</p>
                        </div>
                        <span
                            class="flex h-8 w-8 items-center justify-center rounded-xl bg-amber-500/15 text-amber-800 font-bold text-sm">
                            ★
                        </span>
                    </div>

                    <div class="divide-y divide-stone-100 mt-2">
                        @forelse ($popularProducts as $index => $variant)
                            <div class="flex items-center justify-between py-3.5 group">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div
                                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-2xl text-xs font-black {{ $index === 0 ? 'bg-gradient-to-br from-amber-400 to-amber-600 text-stone-950 shadow-md shadow-amber-500/20' : ($index === 1 ? 'bg-stone-300 text-stone-800' : ($index === 2 ? 'bg-amber-800/20 text-amber-900' : 'bg-stone-100 text-stone-500')) }}">
                                        #{{ $index + 1 }}
                                    </div>
                                    <div class="min-w-0">
                                        <p
                                            class="truncate text-xs font-bold text-stone-900 leading-tight group-hover:text-amber-700 transition">
                                            {{ $variant->product->name ?? 'Drink' }}
                                        </p>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            <span class="truncate text-[10px] text-stone-500">{{ $variant->name }}</span>
                                            <span class="text-[10px] text-stone-300">&bull;</span>
                                            <span
                                                class="truncate text-[10px] text-amber-700 font-medium">${{ number_format($variant->price, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <span
                                    class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-900 border border-amber-200/80 shrink-0">
                                    {{ $variant->total_sold }} sold
                                </span>
                            </div>
                        @empty
                            <div class="py-10 text-center text-xs text-stone-400">
                                No sales data recorded yet today.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Table Seating Floor Plan Overview -->
                <div class="rounded-3xl bg-white p-6 shadow-sm border border-stone-200/80">
                    <div class="flex items-center justify-between pb-4 border-b border-stone-100">
                        <div>
                            <h3 class="text-base font-bold font-display tracking-tight text-stone-900">
                                Table Floor Plan / តុភ្ញៀវ
                            </h3>
                            <p class="text-xs text-stone-500">Live cafe seating status</p>
                        </div>
                        @if (auth()->user()->hasRole('admin'))
                            <a href="{{ route('admin.tables.index') }}"
                                class="text-xs font-bold text-amber-700 hover:text-amber-800 transition">
                                Manage &rarr;
                            </a>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-3 mt-4">
                        @forelse ($activeTablesList as $table)
                            <div
                                class="rounded-2xl p-3 border transition {{ $table->status === 'occupied' ? 'bg-amber-50/70 border-amber-300 text-amber-950' : ($table->status === 'reserved' ? 'bg-purple-50/70 border-purple-200 text-purple-950' : 'bg-emerald-50/50 border-emerald-200 text-emerald-950') }}">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-xs">{{ $table->name }}</span>
                                    <span
                                        class="h-2 w-2 rounded-full {{ $table->status === 'occupied' ? 'bg-amber-500 animate-ping' : ($table->status === 'reserved' ? 'bg-purple-500' : 'bg-emerald-500') }}"></span>
                                </div>
                                <div class="mt-2 flex items-center justify-between text-[11px]">
                                    <span class="text-stone-500 capitalize">{{ $table->capacity }} seats</span>
                                    <span
                                        class="font-bold text-[10px] uppercase {{ $table->status === 'occupied' ? 'text-amber-700' : ($table->status === 'reserved' ? 'text-purple-700' : 'text-emerald-700') }}">
                                        {{ $table->status }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-2 py-6 text-center text-xs text-stone-400">
                                No tables configured.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Professional Cafe Barista Tip Card -->
                <div
                    class="rounded-3xl bg-gradient-to-br from-[#1C1917] to-[#2D241E] p-6 text-white shadow-md border border-white/10">
                    <div class="flex items-center gap-2 text-amber-400 text-xs font-bold mb-2">
                        <span>☕ Espresso Quality Standard</span>
                    </div>
                    <p class="text-xs text-stone-300 leading-relaxed">
                        Extract espresso at 9 bars of pressure between 25-30 seconds. Steam milk to silky micro-foam
                        between 60°C - 65°C for optimal latte sweetness!
                    </p>
                    <div
                        class="mt-4 pt-3 border-t border-white/10 flex items-center justify-between text-[11px] text-stone-400">
                        <span>Bong Heng Specialty Blend</span>
                        <span class="text-amber-400 font-semibold">100% Arabica</span>
                    </div>
                </div>

            </div>

        </div>

    </div>
</x-app-layout>
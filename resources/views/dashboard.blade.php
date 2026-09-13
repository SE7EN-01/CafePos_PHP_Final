<x-app-layout>
    <div class="min-h-full bg-[#F9F6F0] p-4 sm:p-6 lg:p-8">
        <div class="w-full space-y-8">
            <!-- Stats Metric Cards -->
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Today's Revenue -->
                <div class="rounded-3xl bg-white p-6 shadow-sm border border-stone-200/80 hover:border-amber-400/50 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-stone-400">Today's Revenue</p>
                            <p class="mt-2 text-3xl font-extrabold text-stone-900 font-display tracking-tight">${{ number_format($todayRevenue, 2) }}</p>
                            <p class="mt-1 text-[11px] font-semibold text-emerald-600 flex items-center gap-1">
                                <span>&uarr; Live sales</span>
                            </p>
                        </div>
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-500/15 text-amber-700 border border-amber-500/20">
                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659 1.171-1.671.659-1.171m-1.171 1.671L6.293 17.4l-.659-1.171m6.541-2.061.659 1.171M12 6V2m0 4a2.5 2.5 0 0 0-2.5 2.5v.5a3.5 3.5 0 0 0 7 0v-.5A2.5 2.5 0 0 0 12 6Z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Orders Today -->
                <div class="rounded-3xl bg-white p-6 shadow-sm border border-stone-200/80 hover:border-emerald-400/50 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-stone-400">Orders Today</p>
                            <p class="mt-2 text-3xl font-extrabold text-stone-900 font-display tracking-tight">{{ $ordersToday }}</p>
                            <p class="mt-1 text-[11px] font-semibold text-stone-500">Tickets processed</p>
                        </div>
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-500/15 text-emerald-700 border border-emerald-500/20">
                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Items Sold -->
                <div class="rounded-3xl bg-white p-6 shadow-sm border border-stone-200/80 hover:border-sky-400/50 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-stone-400">Items Sold</p>
                            <p class="mt-2 text-3xl font-extrabold text-stone-900 font-display tracking-tight">{{ $itemsSold }}</p>
                            <p class="mt-1 text-[11px] font-semibold text-stone-500">Cups &amp; treats</p>
                        </div>
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-sky-500/15 text-sky-700 border border-sky-500/20">
                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6Zm0 9.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25Zm9.75 0A2.25 2.25 0 0 1 16 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H16a2.25 2.25 0 0 1-2.25-2.25v-2.25Zm0-9.75A2.25 2.25 0 0 1 16 3.75h2.25a2.25 2.25 0 0 1 2.25 2.25V6a2.25 2.25 0 0 1-2.25 2.25H16a2.25 2.25 0 0 1-2.25-2.25V6Z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Team Members -->
                <div class="rounded-3xl bg-white p-6 shadow-sm border border-stone-200/80 hover:border-purple-400/50 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-stone-400">Team Staff</p>
                            <p class="mt-2 text-3xl font-extrabold text-stone-900 font-display tracking-tight">{{ $teamMembers }}</p>
                            <p class="mt-1 text-[11px] font-semibold text-stone-500">Active accounts</p>
                        </div>
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-purple-500/15 text-purple-700 border border-purple-500/20">
                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Two Columns: Recent Orders + Popular Products -->
            <div class="grid gap-6 lg:grid-cols-3">
                <!-- Recent Orders (2 columns on large screen) -->
                <div class="lg:col-span-2 rounded-3xl bg-white p-6 shadow-sm border border-stone-200/80">
                    <div class="flex items-center justify-between pb-4 border-b border-stone-100">
                        <div>
                            <h2 class="text-lg font-bold font-display tracking-tight text-stone-900">Recent Orders</h2>
                            <p class="text-xs text-stone-500">Live feed of orders placed today</p>
                        </div>
                        @if (Auth::user()->hasRole('admin'))
                            <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-amber-700 hover:text-amber-800 transition">
                                View all &rarr;
                            </a>
                        @endif
                    </div>

                    <div class="divide-y divide-stone-100 mt-2">
                        @forelse ($recentOrders as $order)
                            <div class="flex items-center justify-between py-3.5">
                                <div class="flex items-center gap-3.5">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-stone-100 text-stone-700 font-bold text-xs">
                                        {{ substr($order->number, -4) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-sm text-stone-900 leading-tight">{{ $order->number }}</p>
                                        <p class="text-xs text-stone-500 capitalize">{{ $order->type }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4">
                                    <span class="font-bold text-sm text-stone-900 font-display">${{ $order->total }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="py-12 text-center">
                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-stone-100 text-stone-400 mb-2">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                </div>
                                <p class="text-sm font-semibold text-stone-800">No orders today yet</p>
                                <p class="text-xs text-stone-400 mt-0.5">Start serving customers from the POS Register.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Popular Products Leaderboard -->
                <div class="rounded-3xl bg-white p-6 shadow-sm border border-stone-200/80 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between pb-4 border-b border-stone-100">
                            <div>
                                <h2 class="text-lg font-bold font-display tracking-tight text-stone-900">Top Sellers</h2>
                                <p class="text-xs text-stone-500">Most popular beverages &amp; treats</p>
                            </div>
                            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-100 text-amber-700 text-xs font-bold">★</span>
                        </div>

                        <div class="divide-y divide-stone-100 mt-2">
                            @forelse ($popularProducts as $index => $variant)
                                <div class="flex items-center justify-between py-3">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl {{ $index === 0 ? 'bg-amber-500 text-stone-950 font-extrabold' : 'bg-stone-100 text-stone-600 font-bold' }} text-xs">
                                            #{{ $index + 1 }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="truncate text-xs font-bold text-stone-900 leading-tight">{{ $variant->product->name ?? 'N/A' }}</p>
                                            <p class="truncate text-[11px] text-stone-500">{{ $variant->name }}</p>
                                        </div>
                                    </div>
                                    <span class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-800 border border-amber-200 shrink-0">
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

                    <!-- Bottom Coffee Tip Pill -->
                    <div class="mt-6 rounded-2xl bg-[#1C1917] p-4 text-white">
                        <div class="flex items-center gap-2 text-amber-400 text-xs font-bold mb-1">
                            <span>☕ Cafe Barista Tip</span>
                        </div>
                        <p class="text-[11px] text-stone-300 leading-relaxed">
                            Keep eye on ingredient stocks like Espresso beans and Fresh Milk before peak morning rush!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <div class="min-h-full bg-[#F9F6F0] p-4 sm:p-6 lg:p-8">
        <div class="w-full space-y-6">

            <!-- Header -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-stone-200/80">
                <div class="flex items-center gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-500/15 text-amber-700 border border-amber-500/30">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold font-display tracking-tight text-stone-900">Inventory Reports &amp; Analytics</h1>
                        <p class="text-xs sm:text-sm text-stone-500 mt-1">Valuation, audit movements, waste &amp; spoilage analysis, and batch expiry tracking</p>
                    </div>
                </div>

                <div class="flex items-center gap-2.5">
                    <a href="{{ route('admin.stock.index') }}" class="rounded-2xl bg-stone-100 px-4 py-2.5 text-xs font-semibold text-stone-700 hover:bg-stone-200 transition border border-stone-200">
                        &larr; Stock Hub
                    </a>
                </div>
            </div>

            <!-- Report Navigation Tabs -->
            <div class="flex flex-wrap items-center gap-2 bg-white rounded-3xl p-3 shadow-sm border border-stone-200/80">
                <a 
                    href="{{ route('admin.stock.reports', ['tab' => 'valuation']) }}"
                    class="rounded-xl px-4 py-2 text-xs font-semibold transition {{ $tab === 'valuation' ? 'bg-stone-900 text-white shadow-sm' : 'text-stone-600 hover:bg-stone-100' }}"
                >
                    Inventory Valuation (${{ number_format($totalValuation, 2) }})
                </a>

                <a 
                    href="{{ route('admin.stock.reports', ['tab' => 'movements']) }}"
                    class="rounded-xl px-4 py-2 text-xs font-semibold transition {{ $tab === 'movements' ? 'bg-stone-900 text-white shadow-sm' : 'text-stone-600 hover:bg-stone-100' }}"
                >
                    Stock Movements
                </a>

                <a 
                    href="{{ route('admin.stock.reports', ['tab' => 'waste']) }}"
                    class="inline-flex items-center gap-1.5 rounded-xl px-4 py-2 text-xs font-semibold transition {{ $tab === 'waste' ? 'bg-rose-600 text-white shadow-sm' : 'text-stone-600 hover:bg-stone-100' }}"
                >
                    <span>Waste &amp; Loss</span>
                    @if ($totalWasteCount > 0)
                        <span class="rounded-full bg-white/20 px-1.5 py-0.2 text-[10px]">{{ $totalWasteCount }}</span>
                    @endif
                </a>

                <a 
                    href="{{ route('admin.stock.reports', ['tab' => 'expiry']) }}"
                    class="inline-flex items-center gap-1.5 rounded-xl px-4 py-2 text-xs font-semibold transition {{ $tab === 'expiry' ? 'bg-amber-600 text-white shadow-sm' : 'text-stone-600 hover:bg-stone-100' }}"
                >
                    <span>Batch Expiry</span>
                    @if (count($expiringSoon) > 0 || count($expiredItems) > 0)
                        <span class="rounded-full bg-white/20 px-1.5 py-0.2 text-[10px]">{{ count($expiringSoon) + count($expiredItems) }}</span>
                    @endif
                </a>
            </div>

            <!-- TAB 1: VALUATION -->
            @if ($tab === 'valuation')
                <div class="space-y-6">
                    <div class="grid gap-4 sm:grid-cols-3">
                        <div class="rounded-3xl bg-white p-6 shadow-sm border border-stone-200/80">
                            <span class="text-xs font-bold uppercase tracking-wider text-stone-400">Total Inventory Asset Value</span>
                            <div class="mt-2 text-3xl font-bold font-mono text-emerald-700">
                                ${{ number_format($totalValuation, 2) }}
                            </div>
                            <p class="text-xs text-stone-400 mt-1">Calculated via weighted average purchase cost</p>
                        </div>
                        <div class="rounded-3xl bg-white p-6 shadow-sm border border-stone-200/80">
                            <span class="text-xs font-bold uppercase tracking-wider text-stone-400">Total Raw Ingredients</span>
                            <div class="mt-2 text-3xl font-bold font-mono text-stone-900">
                                {{ count($ingredients) }}
                            </div>
                            <p class="text-xs text-stone-400 mt-1">Managed in inventory catalog</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl shadow-sm border border-stone-200/80 overflow-hidden">
                        <div class="px-6 py-4 border-b border-stone-200/80">
                            <h2 class="text-base font-bold font-display text-stone-900">Ingredient Valuation Ledger</h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-stone-200/80 bg-stone-50/50 text-[11px] font-bold uppercase tracking-wider text-stone-400">
                                        <th class="px-6 py-3.5">Ingredient Name</th>
                                        <th class="px-6 py-3.5">Current Balance</th>
                                        <th class="px-6 py-3.5">Unit Average Cost</th>
                                        <th class="px-6 py-3.5">Asset Valuation</th>
                                        <th class="px-6 py-3.5">Supplier</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-stone-100 text-xs">
                                    @foreach ($ingredients as $ing)
                                        @php
                                            $avgCost = (float) ($ing->average_cost > 0 ? $ing->average_cost : $ing->purchase_cost);
                                            $val = round((float) $ing->current_stock * $avgCost, 2);
                                        @endphp
                                        <tr class="hover:bg-amber-500/[0.02] transition">
                                            <td class="px-6 py-4 font-bold text-stone-900">
                                                {{ $ing->name }}
                                            </td>
                                            <td class="px-6 py-4 font-mono font-bold text-stone-900">
                                                {{ number_format($ing->current_stock, 1) }} {{ $ing->unit }}
                                            </td>
                                            <td class="px-6 py-4 font-mono text-stone-600">
                                                ${{ number_format($avgCost, 4) }} / {{ $ing->unit }}
                                            </td>
                                            <td class="px-6 py-4 font-mono font-bold text-emerald-700 text-sm">
                                                ${{ number_format($val, 2) }}
                                            </td>
                                            <td class="px-6 py-4 text-stone-500">
                                                {{ $ing->supplier?->name ?: '—' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- TAB 2: MOVEMENTS -->
            @if ($tab === 'movements')
                <div class="space-y-4">
                    <!-- Date Filter Bar -->
                    <form method="GET" action="{{ route('admin.stock.reports') }}" class="flex flex-wrap items-center gap-3 bg-white rounded-3xl p-4 shadow-sm border border-stone-200/80">
                        <input type="hidden" name="tab" value="movements">
                        <div class="flex items-center gap-2 text-xs">
                            <span class="font-bold text-stone-500">From:</span>
                            <input type="date" name="from" value="{{ $dateFrom }}" class="rounded-xl border border-stone-200 bg-stone-50 px-3 py-1.5 text-xs text-stone-900 focus:border-amber-500 focus:outline-none">
                        </div>
                        <div class="flex items-center gap-2 text-xs">
                            <span class="font-bold text-stone-500">To:</span>
                            <input type="date" name="to" value="{{ $dateTo }}" class="rounded-xl border border-stone-200 bg-stone-50 px-3 py-1.5 text-xs text-stone-900 focus:border-amber-500 focus:outline-none">
                        </div>
                        <div class="flex items-center gap-2 text-xs">
                            <span class="font-bold text-stone-500">Type:</span>
                            <select name="type" class="rounded-xl border border-stone-200 bg-stone-50 px-3 py-1.5 text-xs text-stone-900 focus:border-amber-500 focus:outline-none">
                                <option value="">All Types</option>
                                <option value="in" {{ request('type') === 'in' ? 'selected' : '' }}>Stock In</option>
                                <option value="sale" {{ request('type') === 'sale' ? 'selected' : '' }}>POS Sale</option>
                                <option value="waste" {{ request('type') === 'waste' ? 'selected' : '' }}>Waste / Spoilage</option>
                                <option value="adjustment" {{ request('type') === 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                            </select>
                        </div>
                        <button type="submit" class="rounded-xl bg-stone-900 px-4 py-1.5 text-xs font-bold text-white hover:bg-stone-800 transition">Filter</button>
                    </form>

                    <div class="bg-white rounded-3xl shadow-sm border border-stone-200/80 overflow-hidden">
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
                                            <td class="px-6 py-3.5 font-mono text-stone-500">{{ $m->created_at->format('M d, Y H:i') }}</td>
                                            <td class="px-6 py-3.5 font-bold text-stone-900">{{ $m->stockable?->name ?? 'Unknown' }}</td>
                                            <td class="px-6 py-3.5">
                                                <span class="rounded-full px-2 py-0.5 text-[11px] font-bold {{ $m->type === 'in' ? 'bg-emerald-50 text-emerald-700' : ($m->type === 'sale' ? 'bg-sky-50 text-sky-700' : 'bg-rose-50 text-rose-700') }}">
                                                    {{ strtoupper($m->type) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-3.5 font-mono font-bold {{ $m->quantity > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                                {{ $m->quantity > 0 ? '+' : '' }}{{ number_format($m->quantity, 2) }}
                                            </td>
                                            <td class="px-6 py-3.5 text-stone-600">{{ $m->user?->name ?? 'System' }}</td>
                                            <td class="px-6 py-3.5 text-stone-500">{{ $m->reason }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-8 text-center text-stone-400">No stock movements found for the selected period.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if ($movements->hasPages())
                            <div class="px-6 py-4 border-t border-stone-200/80">{{ $movements->links() }}</div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- TAB 3: WASTE & LOSS -->
            @if ($tab === 'waste')
                <div class="bg-white rounded-3xl shadow-sm border border-stone-200/80 overflow-hidden">
                    <div class="px-6 py-4 border-b border-stone-200/80">
                        <h2 class="text-base font-bold font-display text-stone-900">Waste &amp; Spoilage Log</h2>
                        <p class="text-xs text-stone-500">Record of spilled milk, grinder dial-in waste, and damaged inventory</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-stone-200/80 bg-stone-50/50 text-[11px] font-bold uppercase tracking-wider text-stone-400">
                                    <th class="px-6 py-3.5">Date &amp; Time</th>
                                    <th class="px-6 py-3.5">Wasted Item</th>
                                    <th class="px-6 py-3.5">Quantity Lost</th>
                                    <th class="px-6 py-3.5">Reason</th>
                                    <th class="px-6 py-3.5">Logged By</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-100 text-xs">
                                @forelse ($wasteMovements as $wm)
                                    <tr class="hover:bg-rose-500/[0.02] transition">
                                        <td class="px-6 py-3.5 font-mono text-stone-500">{{ $wm->created_at->format('M d, Y H:i') }}</td>
                                        <td class="px-6 py-3.5 font-bold text-stone-900">{{ $wm->stockable?->name }}</td>
                                        <td class="px-6 py-3.5 font-mono font-bold text-rose-600">{{ number_format(abs($wm->quantity), 2) }} {{ $wm->stockable?->unit }}</td>
                                        <td class="px-6 py-3.5 text-stone-700 font-medium">{{ $wm->reason }}</td>
                                        <td class="px-6 py-3.5 text-stone-500">{{ $wm->user?->name ?? 'Staff' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-stone-400">No waste recorded in this period.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- TAB 4: EXPIRY MANAGEMENT -->
            @if ($tab === 'expiry')
                <div class="space-y-6">
                    <!-- Expired Items Card -->
                    <div class="bg-white rounded-3xl shadow-sm border border-stone-200/80 overflow-hidden">
                        <div class="px-6 py-4 border-b border-stone-200/80 flex items-center justify-between">
                            <h2 class="text-base font-bold font-display text-rose-700">Expired Ingredients (Immediate Removal)</h2>
                            <span class="rounded-full bg-rose-50 px-2.5 py-0.5 text-xs font-bold text-rose-700 border border-rose-200">{{ count($expiredItems) }} Items</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-stone-200/80 bg-stone-50/50 text-[11px] font-bold uppercase tracking-wider text-stone-400">
                                        <th class="px-6 py-3.5">Ingredient</th>
                                        <th class="px-6 py-3.5">Current Stock</th>
                                        <th class="px-6 py-3.5">Batch #</th>
                                        <th class="px-6 py-3.5">Expiry Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-stone-100 text-xs">
                                    @forelse ($expiredItems as $item)
                                        <tr>
                                            <td class="px-6 py-3.5 font-bold text-stone-900">{{ $item->name }}</td>
                                            <td class="px-6 py-3.5 font-mono font-bold text-rose-600">{{ number_format($item->current_stock, 1) }} {{ $item->unit }}</td>
                                            <td class="px-6 py-3.5 font-mono text-stone-500">{{ $item->batch_number ?: '—' }}</td>
                                            <td class="px-6 py-3.5 font-mono font-bold text-rose-700">{{ $item->expiry_date->format('M d, Y') }} (Expired)</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="px-6 py-6 text-center text-stone-400">No expired items found. Good job!</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Expiring Soon Items Card -->
                    <div class="bg-white rounded-3xl shadow-sm border border-stone-200/80 overflow-hidden">
                        <div class="px-6 py-4 border-b border-stone-200/80 flex items-center justify-between">
                            <h2 class="text-base font-bold font-display text-amber-700">Expiring Soon (Within 7 Days)</h2>
                            <span class="rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-bold text-amber-700 border border-amber-200">{{ count($expiringSoon) }} Items</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-stone-200/80 bg-stone-50/50 text-[11px] font-bold uppercase tracking-wider text-stone-400">
                                        <th class="px-6 py-3.5">Ingredient</th>
                                        <th class="px-6 py-3.5">Current Stock</th>
                                        <th class="px-6 py-3.5">Batch #</th>
                                        <th class="px-6 py-3.5">Expiry Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-stone-100 text-xs">
                                    @forelse ($expiringSoon as $item)
                                        <tr>
                                            <td class="px-6 py-3.5 font-bold text-stone-900">{{ $item->name }}</td>
                                            <td class="px-6 py-3.5 font-mono font-bold text-amber-700">{{ number_format($item->current_stock, 1) }} {{ $item->unit }}</td>
                                            <td class="px-6 py-3.5 font-mono text-stone-500">{{ $item->batch_number ?: '—' }}</td>
                                            <td class="px-6 py-3.5 font-mono text-amber-700">{{ $item->expiry_date->format('M d, Y') }} ({{ $item->expiry_date->diffForHumans() }})</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="px-6 py-6 text-center text-stone-400">No items expiring within the next 7 days.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>

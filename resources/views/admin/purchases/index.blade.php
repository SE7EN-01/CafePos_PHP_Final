<x-app-layout>
    <div class="min-h-full bg-[#F9F6F0] p-4 sm:p-6 lg:p-8">
        <div class="w-full space-y-6">

            @if (session('status') === 'purchase-received')
                <div x-data="{ show: true }" x-show="show" class="flex items-center justify-between rounded-2xl bg-emerald-500/10 border border-emerald-500/20 p-4 text-emerald-800">
                    <span class="text-sm font-semibold">Purchase received successfully! Stock balances and moving average costs updated.</span>
                    <button @click="show = false" class="text-emerald-600 hover:text-emerald-800">&times;</button>
                </div>
            @endif

            <!-- Header -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-stone-200/80">
                <div class="flex items-center gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-500/15 text-amber-700 border border-amber-500/30">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold font-display tracking-tight text-stone-900">Purchases &amp; Receiving (GRN)</h1>
                        <p class="text-xs sm:text-sm text-stone-500 mt-1">Record supplier shipments, invoice costs, batch tracking, and automatically update stock</p>
                    </div>
                </div>

                <div class="flex items-center gap-2.5">
                    <a 
                        href="{{ route('admin.purchases.create') }}"
                        class="inline-flex items-center gap-2 rounded-2xl bg-amber-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-amber-700 transition"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>+ Receive Goods / Purchase</span>
                    </a>
                    <a href="{{ route('admin.stock.index') }}" class="rounded-2xl bg-stone-100 px-4 py-2.5 text-xs font-semibold text-stone-700 hover:bg-stone-200 transition border border-stone-200">
                        &larr; Stock Hub
                    </a>
                </div>
            </div>

            <!-- Purchases Table -->
            <div class="bg-white rounded-3xl shadow-sm border border-stone-200/80 overflow-hidden">
                <div class="px-6 py-4 border-b border-stone-200/80 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold font-display text-stone-900">Purchase Invoices &amp; Deliveries</h2>
                        <p class="text-xs text-stone-500">Historical delivery invoices</p>
                    </div>
                    <span class="text-xs font-semibold text-stone-400">{{ $purchases->total() }} Records</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-stone-200/80 bg-stone-50/50 text-[11px] font-bold uppercase tracking-wider text-stone-400">
                                <th class="px-6 py-3.5">Invoice #</th>
                                <th class="px-6 py-3.5">Supplier</th>
                                <th class="px-6 py-3.5">Date Received</th>
                                <th class="px-6 py-3.5">Items Received</th>
                                <th class="px-6 py-3.5">Total Cost</th>
                                <th class="px-6 py-3.5">Status</th>
                                <th class="px-6 py-3.5">Received By</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 text-xs">
                            @forelse ($purchases as $p)
                                <tr class="hover:bg-amber-500/[0.02] transition">
                                    <td class="px-6 py-4 font-mono font-bold text-stone-900">
                                        {{ $p->invoice_number ?: 'PO-'.str_pad($p->id, 5, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-stone-800">
                                        {{ $p->supplier?->name ?? 'Direct Purchase' }}
                                    </td>
                                    <td class="px-6 py-4 font-mono text-stone-600">
                                        {{ $p->purchase_date->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-0.5">
                                            @foreach ($p->items as $item)
                                                <div class="text-[11px] text-stone-600">
                                                    &bull; {{ $item->ingredient?->name }}: <span class="font-mono font-bold">{{ number_format($item->quantity, 1) }} {{ $item->unit }}</span>
                                                    @if ($item->batch_number)
                                                        <span class="text-stone-400 font-mono">({{ $item->batch_number }})</span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-mono font-bold text-stone-900 text-sm">
                                        ${{ number_format($p->total_amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-bold text-emerald-700 border border-emerald-200">
                                            Received
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-stone-500">
                                        {{ $p->user?->name ?? 'Admin' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-stone-400">
                                        No purchase orders recorded yet. Click "+ Receive Goods / Purchase" to log a delivery.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($purchases->hasPages())
                    <div class="px-6 py-4 border-t border-stone-200/80">
                        {{ $purchases->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>

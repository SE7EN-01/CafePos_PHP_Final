<x-app-layout>
    <div class="min-h-full bg-[#F9F6F0] p-4 sm:p-6 lg:p-8">
        <div class="w-full space-y-6">
            <!-- Header with Search & Filter -->
            <header class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between bg-white rounded-3xl p-6 shadow-sm border border-stone-200/80">
                <div class="flex items-center gap-3.5">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-500/15 text-amber-700 border border-amber-500/30">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-2xl font-bold font-display tracking-tight text-stone-900">Order Management</h1>
                            <span class="rounded-full bg-stone-100 px-3 py-0.5 text-xs font-bold text-stone-600 border border-stone-200">
                                {{ $orders->total() }} Total
                            </span>
                        </div>
                        <p class="text-xs text-stone-500 mt-0.5">Track POS counter receipts, kitchen preparation, and order fulfillment</p>
                    </div>
                </div>

                <!-- Filter Controls Form -->
                <form method="GET" class="flex flex-wrap items-center gap-2.5">
                    <div class="relative">
                        <input 
                            name="search" 
                            type="text" 
                            value="{{ request('search') }}" 
                            placeholder="Search order #..." 
                            class="rounded-2xl border-stone-200 bg-stone-50 px-4 py-2.5 text-xs text-stone-900 placeholder-stone-400 focus:border-amber-500 focus:bg-white focus:ring-2 focus:ring-amber-500/20 transition"
                        >
                    </div>

                    @if (request('search'))
                        <a href="{{ route('admin.orders.index') }}" class="rounded-2xl bg-stone-100 px-3.5 py-2.5 text-xs font-semibold text-stone-600 hover:bg-stone-200 transition">
                            Clear
                        </a>
                    @endif
                </form>
            </header>

            <!-- Orders Table Card -->
            <div class="overflow-hidden rounded-3xl bg-white shadow-sm border border-stone-200/80">
                <div class="border-b border-stone-100 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-stone-900">Order Records</h3>
                    <span class="text-xs text-stone-400">Page {{ $orders->currentPage() }}</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-stone-100 bg-stone-50/50 text-stone-400 uppercase tracking-wider font-bold">
                                <th class="px-6 py-3.5">Order Number</th>
                                <th class="px-6 py-3.5">Cashier / Staff</th>
                                <th class="px-6 py-3.5">Type</th>
                                <th class="px-6 py-3.5">Total</th>
                                <th class="px-6 py-3.5">Placed At</th>
                                <th class="px-6 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @forelse ($orders as $order)
                                <tr class="hover:bg-stone-50/50 transition">
                                    <td class="px-6 py-4 font-bold text-stone-900 text-sm">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="hover:text-amber-700 transition">
                                            {{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-stone-600 font-medium">
                                        {{ $order->user->name ?? 'Walk-in Cashier' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center rounded-xl bg-stone-100 px-2.5 py-1 text-xs font-bold text-stone-700 capitalize">
                                            {{ str_replace('_', ' ', $order->order_type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-extrabold text-stone-900 text-sm font-display">
                                        ${{ number_format($order->total_amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-stone-500">
                                        {{ $order->created_at->format('M j, Y g:i A') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="inline-block rounded-xl px-3.5 py-1.5 text-xs font-semibold text-amber-800 bg-amber-50 hover:bg-amber-100 transition border border-amber-200">
                                            View Order
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-stone-400">
                                        No orders found matching the filter criteria.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($orders->hasPages())
                    <div class="border-t border-stone-100 px-6 py-4">{{ $orders->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

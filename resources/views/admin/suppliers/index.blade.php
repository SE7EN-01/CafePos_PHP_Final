<x-app-layout>
    <div x-data="{ createModal: false }" class="min-h-full bg-[#F9F6F0] p-4 sm:p-6 lg:p-8">
        <div class="w-full space-y-6">

            @if (session('status'))
                <div x-data="{ show: true }" x-show="show" class="flex items-center justify-between rounded-2xl bg-emerald-500/10 border border-emerald-500/20 p-4 text-emerald-800">
                    <span class="text-sm font-semibold">Supplier saved successfully!</span>
                    <button @click="show = false" class="text-emerald-600 hover:text-emerald-800">&times;</button>
                </div>
            @endif

            <!-- Header -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-stone-200/80">
                <div class="flex items-center gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-500/15 text-amber-700 border border-amber-500/30">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold font-display tracking-tight text-stone-900">Cafe Suppliers</h1>
                        <p class="text-xs sm:text-sm text-stone-500 mt-1">Manage vendor contacts, coffee bean roasters, and raw supply distributors</p>
                    </div>
                </div>

                <div class="flex items-center gap-2.5">
                    <button 
                        type="button" 
                        @click="createModal = true"
                        class="inline-flex items-center gap-2 rounded-2xl bg-amber-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-amber-700 transition"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>+ Add Supplier</span>
                    </button>
                    <a href="{{ route('admin.stock.index') }}" class="rounded-2xl bg-stone-100 px-4 py-2.5 text-xs font-semibold text-stone-700 hover:bg-stone-200 transition border border-stone-200">
                        &larr; Stock Hub
                    </a>
                </div>
            </div>

            <!-- Suppliers Table -->
            <div class="bg-white rounded-3xl shadow-sm border border-stone-200/80 overflow-hidden">
                <div class="px-6 py-4 border-b border-stone-200/80 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold font-display text-stone-900">Active Vendor Accounts</h2>
                        <p class="text-xs text-stone-500">Contact details and historical purchase totals</p>
                    </div>
                    <span class="text-xs font-semibold text-stone-400">{{ $suppliers->total() }} Total</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-stone-200/80 bg-stone-50/50 text-[11px] font-bold uppercase tracking-wider text-stone-400">
                                <th class="px-6 py-3.5">Supplier Name</th>
                                <th class="px-6 py-3.5">Contact Person</th>
                                <th class="px-6 py-3.5">Phone &amp; Email</th>
                                <th class="px-6 py-3.5">Orders Received</th>
                                <th class="px-6 py-3.5">Total Spend</th>
                                <th class="px-6 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 text-xs">
                            @forelse ($suppliers as $s)
                                <tr class="hover:bg-amber-500/[0.02] transition">
                                    <td class="px-6 py-4 font-bold text-stone-900">
                                        <div class="text-sm">{{ $s->name }}</div>
                                        @if ($s->address)
                                            <div class="text-[11px] text-stone-400 mt-0.5 font-normal">{{ $s->address }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-stone-600">
                                        {{ $s->contact_person ?: '—' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-mono text-stone-900">{{ $s->phone ?: '—' }}</div>
                                        <div class="text-[11px] text-stone-400">{{ $s->email }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="rounded-full bg-stone-100 px-2.5 py-0.5 text-xs font-semibold text-stone-700">
                                            {{ $s->purchases_count }} purchases
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-mono font-bold text-stone-900 text-sm">
                                        ${{ number_format($s->purchases_sum_total_amount ?? 0, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <form method="POST" action="{{ route('admin.suppliers.destroy', $s) }}" onsubmit="return confirm('Delete this supplier?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 hover:bg-rose-100 transition border border-rose-200">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-stone-400">
                                        No suppliers registered yet. Click "+ Add Supplier" to create your first vendor.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($suppliers->hasPages())
                    <div class="px-6 py-4 border-t border-stone-200/80">
                        {{ $suppliers->links() }}
                    </div>
                @endif
            </div>

        </div>

        <!-- Add Supplier Modal -->
        <div x-show="createModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-stone-900/60 backdrop-blur-sm flex items-center justify-center p-4">
            <div @click.outside="createModal = false" class="w-full max-w-lg rounded-3xl bg-white p-6 sm:p-8 shadow-2xl border border-stone-200 space-y-6">
                <div class="flex items-center justify-between border-b border-stone-100 pb-4">
                    <h3 class="text-lg font-bold font-display text-stone-900">+ Register New Supplier</h3>
                    <button @click="createModal = false" class="text-stone-400 hover:text-stone-600 text-xl font-bold">&times;</button>
                </div>

                <form method="POST" action="{{ route('admin.suppliers.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-500 mb-1.5">Supplier / Company Name</label>
                        <input type="text" name="name" required placeholder="e.g. Phnom Penh Coffee Roasters" class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3.5 py-2.5 text-xs text-stone-900 focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-amber-500" />
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-stone-500 mb-1.5">Contact Person</label>
                            <input type="text" name="contact_person" placeholder="e.g. Mr. Sokha" class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3.5 py-2.5 text-xs text-stone-900 focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-amber-500" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-stone-500 mb-1.5">Phone</label>
                            <input type="text" name="phone" placeholder="012 345 678" class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3.5 py-2.5 text-xs text-stone-900 focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-amber-500" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-500 mb-1.5">Email Address</label>
                        <input type="email" name="email" placeholder="orders@roasters.com" class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3.5 py-2.5 text-xs text-stone-900 focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-amber-500" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-500 mb-1.5">Office / Warehouse Address</label>
                        <textarea name="address" rows="2" placeholder="Street #, Sangkat, Khan, Phnom Penh" class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3.5 py-2 text-xs text-stone-900 focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-amber-500"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-stone-100">
                        <button type="button" @click="createModal = false" class="rounded-xl px-4 py-2 text-xs font-semibold text-stone-500 hover:bg-stone-100 transition">Cancel</button>
                        <button type="submit" class="rounded-xl bg-amber-600 px-5 py-2 text-xs font-bold text-white shadow-sm hover:bg-amber-700 transition">Save Supplier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

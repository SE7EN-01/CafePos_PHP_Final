<x-app-layout>
    <div class="min-h-full bg-[#F9F6F0] p-4 sm:p-6 lg:p-8" x-data="{ activeTab: 'payment' }">
        <div class="mx-auto max-w-5xl space-y-6">
            <!-- Header -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-stone-200/80 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-500/15 text-amber-700 border border-amber-500/30 shadow-inner">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 0 1 0 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 0 1 0-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-2xl font-bold font-display tracking-tight text-stone-900">ការកំណត់ប្រព័ន្ធ (Settings)</h1>
                            <span class="rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-bold text-amber-800">Admin</span>
                        </div>
                        <p class="text-xs text-stone-500 mt-0.5">Customize payment options, exchange rates, Bakong KHQR, taxes, and receipt headers.</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('pos.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-stone-100 px-4 py-2 text-xs font-bold text-stone-700 hover:bg-stone-200 transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015A3.001 3.001 0 0 0 21 9.349m-7.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-3.75c-.621 0-1.125.504-1.125 1.125v6" /></svg>
                        <span>Open POS</span>
                    </a>
                </div>
            </div>

            <!-- Flash Success Message -->
            @if (session('status') === 'settings-updated')
                <div class="rounded-2xl bg-emerald-50 border border-emerald-200 p-4 flex items-center gap-3 text-emerald-800 shadow-sm" x-data="{ show: true }" x-show="show">
                    <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <p class="text-xs sm:text-sm font-bold">ការកំណត់ត្រូវបានរក្សាទុកដោយជោគជ័យ! (Settings saved successfully!)</p>
                </div>
            @endif

            <!-- Navigation Tabs -->
            <div class="flex gap-2 border-b border-stone-200 pb-2 overflow-x-auto">
                <button 
                    type="button" 
                    @click="activeTab = 'payment'" 
                    :class="activeTab === 'payment' ? 'bg-[#1C1917] text-white shadow-md font-bold' : 'bg-white text-stone-600 hover:bg-stone-50 font-semibold'"
                    class="rounded-2xl px-5 py-3 text-xs transition flex items-center gap-2 whitespace-nowrap border border-stone-200/80"
                >
                    <svg class="h-4 w-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" /></svg>
                    <span>ការទូទាត់ និងរូបិយប័ណ្ណ (Payment &amp; Currency)</span>
                </button>

                <button 
                    type="button" 
                    @click="activeTab = 'store'" 
                    :class="activeTab === 'store' ? 'bg-[#1C1917] text-white shadow-md font-bold' : 'bg-white text-stone-600 hover:bg-stone-50 font-semibold'"
                    class="rounded-2xl px-5 py-3 text-xs transition flex items-center gap-2 whitespace-nowrap border border-stone-200/80"
                >
                    <svg class="h-4 w-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                    <span>ហាង និងវិក្កយបត្រ (Store &amp; Receipt)</span>
                </button>

                <button 
                    type="button" 
                    @click="activeTab = 'pos'" 
                    :class="activeTab === 'pos' ? 'bg-[#1C1917] text-white shadow-md font-bold' : 'bg-white text-stone-600 hover:bg-stone-50 font-semibold'"
                    class="rounded-2xl px-5 py-3 text-xs transition flex items-center gap-2 whitespace-nowrap border border-stone-200/80"
                >
                    <svg class="h-4 w-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" /></svg>
                    <span>ប្រព័ន្ធ POS (POS Preferences)</span>
                </button>
            </div>

            <!-- Settings Form -->
            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf

                <!-- TAB 1: Payment & Currencies -->
                <div x-show="activeTab === 'payment'" class="space-y-6">
                    <!-- Cash & Exchange Rate Card -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-stone-200/80 space-y-6">
                        <div class="flex items-center justify-between border-b border-stone-100 pb-4">
                            <div>
                                <h2 class="text-base font-bold text-stone-900 flex items-center gap-2">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-100 text-emerald-800 text-xs font-bold">💵</span>
                                    <span>ការទូទាត់សាច់ប្រាក់ (Cash Payment &amp; KHR Rate)</span>
                                </h2>
                                <p class="text-xs text-stone-500 mt-0.5">Configure Khmer Riel exchange rate, cash currency toggle, and acceptance.</p>
                            </div>
                            <!-- Cash toggle -->
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="payment_cash_enabled" value="1" {{ ($settings['payment_cash_enabled'] ?? '1') === '1' ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-stone-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-stone-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                            </label>
                        </div>

                        <div class="grid gap-6 sm:grid-cols-2">
                            <!-- Exchange Rate KHR -->
                            <div>
                                <label class="block text-xs font-bold text-stone-700 mb-1.5">
                                    <span>អត្រាប្តូរប្រាក់រៀល (KHR Exchange Rate)</span>
                                    <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <input 
                                        type="number" 
                                        name="exchange_rate_khr" 
                                        id="input_exchange_rate"
                                        value="{{ old('exchange_rate_khr', $settings['exchange_rate_khr'] ?? 4100) }}" 
                                        min="1000" 
                                        max="10000" 
                                        step="10"
                                        required 
                                        class="block w-full rounded-2xl border-stone-200 bg-stone-50/70 py-3 pl-4 pr-16 text-sm font-bold text-stone-900 focus:border-amber-500 focus:bg-white font-mono"
                                    >
                                    <span class="absolute inset-y-0 right-0 flex items-center pr-4 text-xs font-bold text-amber-700 font-mono">៛ / 1$</span>
                                </div>
                                <p class="text-[11px] text-stone-400 mt-1">Common Cambodian café rates: 4,000 ៛, 4,100 ៛, 4,150 ៛, or 4,200 ៛.</p>
                                
                                <!-- Quick Rate Preset Buttons -->
                                <div class="flex items-center gap-2 mt-2">
                                    <button type="button" onclick="document.getElementById('input_exchange_rate').value = 4000" class="rounded-lg bg-stone-100 px-2.5 py-1 text-[11px] font-bold text-stone-700 hover:bg-stone-200 transition font-mono">4,000 ៛</button>
                                    <button type="button" onclick="document.getElementById('input_exchange_rate').value = 4100" class="rounded-lg bg-amber-100 px-2.5 py-1 text-[11px] font-bold text-amber-800 hover:bg-amber-200 transition font-mono">4,100 ៛</button>
                                    <button type="button" onclick="document.getElementById('input_exchange_rate').value = 4150" class="rounded-lg bg-stone-100 px-2.5 py-1 text-[11px] font-bold text-stone-700 hover:bg-stone-200 transition font-mono">4,150 ៛</button>
                                    <button type="button" onclick="document.getElementById('input_exchange_rate').value = 4200" class="rounded-lg bg-stone-100 px-2.5 py-1 text-[11px] font-bold text-stone-700 hover:bg-stone-200 transition font-mono">4,200 ៛</button>
                                </div>
                            </div>

                            <!-- Default Cash Currency -->
                            <div>
                                <label class="block text-xs font-bold text-stone-700 mb-1.5">
                                    <span>រូបិយប័ណ្ណសាច់ប្រាក់ដើម (Default Cash Currency)</span>
                                </label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="flex items-center gap-3 rounded-2xl border-2 p-3.5 cursor-pointer transition {{ ($settings['default_currency'] ?? 'khr') === 'khr' ? 'border-amber-500 bg-amber-50/60 font-bold text-stone-900' : 'border-stone-200 text-stone-600 hover:border-stone-300' }}">
                                        <input type="radio" name="default_currency" value="khr" {{ ($settings['default_currency'] ?? 'khr') === 'khr' ? 'checked' : '' }} class="text-amber-600 focus:ring-amber-500">
                                        <div>
                                            <p class="text-xs font-bold">🇰🇭 លុយរៀល (KHR ៛)</p>
                                            <p class="text-[10px] text-stone-500">Khmer Riel (Default)</p>
                                        </div>
                                    </label>

                                    <label class="flex items-center gap-3 rounded-2xl border-2 p-3.5 cursor-pointer transition {{ ($settings['default_currency'] ?? 'khr') === 'usd' ? 'border-amber-500 bg-amber-50/60 font-bold text-stone-900' : 'border-stone-200 text-stone-600 hover:border-stone-300' }}">
                                        <input type="radio" name="default_currency" value="usd" {{ ($settings['default_currency'] ?? 'khr') === 'usd' ? 'checked' : '' }} class="text-amber-600 focus:ring-amber-500">
                                        <div>
                                            <p class="text-xs font-bold">🇺🇸 ដុល្លារ (USD $)</p>
                                            <p class="text-[10px] text-stone-500">US Dollar</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bakong KHQR Card -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-stone-200/80 space-y-6">
                        <div class="flex items-center justify-between border-b border-stone-100 pb-4">
                            <div>
                                <h2 class="text-base font-bold text-stone-900 flex items-center gap-2">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-rose-100 text-rose-800 text-xs font-bold">📱</span>
                                    <span>ការទូទាត់ Bakong KHQR (ABA &amp; All Cambodian Banks)</span>
                                </h2>
                                <p class="text-xs text-stone-500 mt-0.5">Customize your Bakong merchant account ID, shop label, and payment details.</p>
                            </div>
                            <!-- KHQR toggle -->
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="payment_khqr_enabled" value="1" {{ ($settings['payment_khqr_enabled'] ?? '1') === '1' ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-stone-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-stone-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rose-500"></div>
                            </label>
                        </div>

                        <div class="grid gap-6 sm:grid-cols-3">
                            <!-- Bakong Account ID -->
                            <div>
                                <label class="block text-xs font-bold text-stone-700 mb-1.5">
                                    <span>គណនីបាគង (Bakong Account ID)</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="bakong_account_id" 
                                    value="{{ old('bakong_account_id', $settings['bakong_account_id'] ?? 'bongheng@aba') }}" 
                                    placeholder="yourname@aba or account ID"
                                    class="block w-full rounded-2xl border-stone-200 bg-stone-50/70 py-3 px-4 text-sm text-stone-900 focus:border-amber-500 focus:bg-white font-mono"
                                >
                                <p class="text-[11px] text-stone-400 mt-1">e.g. `bongheng@aba` or `cafe@acleda`</p>
                            </div>

                            <!-- Merchant Name -->
                            <div>
                                <label class="block text-xs font-bold text-stone-700 mb-1.5">
                                    <span>ឈ្មោះអាជីវករលើ KHQR (Merchant Display Name)</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="bakong_merchant_name" 
                                    value="{{ old('bakong_merchant_name', $settings['bakong_merchant_name'] ?? 'Bong Heng Cafe') }}" 
                                    class="block w-full rounded-2xl border-stone-200 bg-stone-50/70 py-3 px-4 text-sm text-stone-900 focus:border-amber-500 focus:bg-white"
                                >
                            </div>

                            <!-- Merchant City -->
                            <div>
                                <label class="block text-xs font-bold text-stone-700 mb-1.5">
                                    <span>ទីក្រុង (Merchant City)</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="bakong_merchant_city" 
                                    value="{{ old('bakong_merchant_city', $settings['bakong_merchant_city'] ?? 'Phnom Penh') }}" 
                                    class="block w-full rounded-2xl border-stone-200 bg-stone-50/70 py-3 px-4 text-sm text-stone-900 focus:border-amber-500 focus:bg-white"
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 2: Store & Receipt -->
                <div x-show="activeTab === 'store'" class="space-y-6">
                    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-stone-200/80 space-y-6">
                        <div class="border-b border-stone-100 pb-4">
                            <h2 class="text-base font-bold text-stone-900 flex items-center gap-2">
                                <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-amber-100 text-amber-800 text-xs font-bold">☕</span>
                                <span>ព័ត៌មានហាង និងវិក្កយបត្រ (Store &amp; Receipt Header)</span>
                            </h2>
                            <p class="text-xs text-stone-500 mt-0.5">Customize information printed on thermal receipts and displayed on customer order summaries.</p>
                        </div>

                        <div class="grid gap-6 sm:grid-cols-2">
                            <!-- Cafe Name -->
                            <div>
                                <label class="block text-xs font-bold text-stone-700 mb-1.5">
                                    <span>ឈ្មោះហាងកាហ្វេ (Café Brand Name)</span>
                                    <span class="text-rose-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="cafe_name" 
                                    value="{{ old('cafe_name', $settings['cafe_name'] ?? 'Bong Heng Cafe') }}" 
                                    required
                                    class="block w-full rounded-2xl border-stone-200 bg-stone-50/70 py-3 px-4 text-sm font-bold text-stone-900 focus:border-amber-500 focus:bg-white"
                                >
                            </div>

                            <!-- Tagline -->
                            <div>
                                <label class="block text-xs font-bold text-stone-700 mb-1.5">
                                    <span>ពាក្យស្លោកហាង (Café Tagline)</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="cafe_tagline" 
                                    value="{{ old('cafe_tagline', $settings['cafe_tagline'] ?? 'Specialty Cafe & Roastery') }}" 
                                    class="block w-full rounded-2xl border-stone-200 bg-stone-50/70 py-3 px-4 text-sm text-stone-900 focus:border-amber-500 focus:bg-white"
                                >
                            </div>

                            <!-- Contact Phone -->
                            <div>
                                <label class="block text-xs font-bold text-stone-700 mb-1.5">
                                    <span>លេខទូរស័ព្ទទំនាក់ទំនង (Contact Phone)</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="cafe_phone" 
                                    value="{{ old('cafe_phone', $settings['cafe_phone'] ?? '+855 12 345 678') }}" 
                                    class="block w-full rounded-2xl border-stone-200 bg-stone-50/70 py-3 px-4 text-sm text-stone-900 focus:border-amber-500 focus:bg-white"
                                >
                            </div>

                            <!-- VAT / Tax % -->
                            <div>
                                <label class="block text-xs font-bold text-stone-700 mb-1.5">
                                    <span>អត្រាពន្ធ VAT (%)</span>
                                </label>
                                <div class="relative">
                                    <input 
                                        type="number" 
                                        name="tax_rate_percent" 
                                        value="{{ old('tax_rate_percent', $settings['tax_rate_percent'] ?? 0) }}" 
                                        min="0" 
                                        max="100" 
                                        step="0.5"
                                        class="block w-full rounded-2xl border-stone-200 bg-stone-50/70 py-3 pl-4 pr-12 text-sm font-bold text-stone-900 focus:border-amber-500 focus:bg-white font-mono"
                                    >
                                    <span class="absolute inset-y-0 right-0 flex items-center pr-4 text-xs font-bold text-stone-400">%</span>
                                </div>
                                <p class="text-[11px] text-stone-400 mt-1">Set to 0 if tax is included in item prices or not applicable.</p>
                            </div>

                            <!-- Address -->
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-stone-700 mb-1.5">
                                    <span>អាសយដ្ឋានហាង (Store Address)</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="cafe_address" 
                                    value="{{ old('cafe_address', $settings['cafe_address'] ?? '#123 Street 214, Daun Penh, Phnom Penh') }}" 
                                    class="block w-full rounded-2xl border-stone-200 bg-stone-50/70 py-3 px-4 text-sm text-stone-900 focus:border-amber-500 focus:bg-white"
                                >
                            </div>

                            <!-- Receipt Footer Text -->
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-stone-700 mb-1.5">
                                    <span>សារបាតវិក្កយបត្រ (Receipt Footer Message)</span>
                                </label>
                                <textarea 
                                    name="receipt_footer_text" 
                                    rows="2" 
                                    class="block w-full rounded-2xl border-stone-200 bg-stone-50/70 py-3 px-4 text-sm text-stone-900 focus:border-amber-500 focus:bg-white"
                                >{{ old('receipt_footer_text', $settings['receipt_footer_text'] ?? 'សូមអរគុណចំពោះការគាំទ្រ! សូមអញ្ជើញមកម្តងទៀត។') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 3: POS Preferences -->
                <div x-show="activeTab === 'pos'" class="space-y-6">
                    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-stone-200/80 space-y-6">
                        <div class="border-b border-stone-100 pb-4">
                            <h2 class="text-base font-bold text-stone-900 flex items-center gap-2">
                                <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-amber-100 text-amber-800 text-xs font-bold">🖥️</span>
                                <span>ការកំណត់ប្រព័ន្ធចុះបញ្ជីលក់ (POS Preferences)</span>
                            </h2>
                            <p class="text-xs text-stone-500 mt-0.5">Control default language, stock threshold badges, and register operational options.</p>
                        </div>

                        <div class="grid gap-6 sm:grid-cols-2">
                            <!-- Default Language -->
                            <div>
                                <label class="block text-xs font-bold text-stone-700 mb-1.5">
                                    <span>ភាសាដើមនៅពេលបើក POS (Default Register Language)</span>
                                </label>
                                <select 
                                    name="pos_default_language" 
                                    class="block w-full rounded-2xl border-stone-200 bg-stone-50/70 py-3 px-4 text-sm font-semibold text-stone-900 focus:border-amber-500 focus:bg-white"
                                >
                                    <option value="km" {{ ($settings['pos_default_language'] ?? 'km') === 'km' ? 'selected' : '' }}>🇰🇭 ភាសាខ្មែរ (Khmer - Recommended)</option>
                                    <option value="en" {{ ($settings['pos_default_language'] ?? 'km') === 'en' ? 'selected' : '' }}>🇺🇸 English (Default)</option>
                                </select>
                            </div>

                            <!-- Low Stock Alert Threshold -->
                            <div>
                                <label class="block text-xs font-bold text-stone-700 mb-1.5">
                                    <span>កម្រិតជូនដំណឹងស្តុកទាប (Low Stock Alert Threshold)</span>
                                </label>
                                <div class="relative">
                                    <input 
                                        type="number" 
                                        name="low_stock_threshold" 
                                        value="{{ old('low_stock_threshold', $settings['low_stock_threshold'] ?? 5) }}" 
                                        min="1" 
                                        max="100" 
                                        required 
                                        class="block w-full rounded-2xl border-stone-200 bg-stone-50/70 py-3 pl-4 pr-16 text-sm font-bold text-stone-900 focus:border-amber-500 focus:bg-white font-mono"
                                    >
                                    <span class="absolute inset-y-0 right-0 flex items-center pr-4 text-xs font-bold text-stone-400">units</span>
                                </div>
                                <p class="text-[11px] text-stone-400 mt-1">Products with stock at or below this amount will display an amber warning badge.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button Bar -->
                <div class="mt-6 flex items-center justify-end gap-3 bg-white rounded-3xl p-5 shadow-sm border border-stone-200/80">
                    <button 
                        type="submit" 
                        class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-amber-500 to-amber-600 px-6 py-3.5 text-sm font-bold text-stone-950 shadow-lg shadow-amber-500/25 hover:brightness-110 active:scale-[0.99] transition duration-150"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        <span>រក្សាទុកការកំណត់ (Save Settings)</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

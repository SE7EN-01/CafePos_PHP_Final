<x-app-layout>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('posRegister', () => ({
                lang: localStorage.getItem('pos_lang') || '{{ $default_language ?? 'km' }}',
                exchangeRate: {{ $exchange_rate ?? 4100 }},
                activeCategory: 'all',
                search: '',
                cart: [],
                categories: @json($categories, JSON_UNESCAPED_UNICODE),
                products: @json($products, JSON_UNESCAPED_UNICODE),
                showMobileCart: false,
                orderType: 'dine_in',
                showQuickPayModal: false,
                cashGiven: 0,
                selectedPaymentMethod: 'cash',
                quickBills: [5, 10, 20, 50, 100],

                translations: {
                    en: {
                        pos_title: 'Point of Sale (POS)',
                        pos_subtitle: 'Espresso & Specialty Cafe Order Register',
                        online: 'Online',
                        dine_in: 'Dine In',
                        takeaway: 'Takeaway',
                        search_placeholder: 'Search drinks, pastries, espresso...',
                        all_items: 'All items',
                        out_of_stock: 'Out of stock',
                        left: 'left',
                        no_items_found: 'No menu items found',
                        no_items_desc: 'No products match your current search query or active category filter.',
                        reset_filters: 'Reset Filters',
                        current_order: 'Current Order',
                        items: 'items',
                        cart_empty: 'Your cart is empty',
                        cart_empty_desc: 'Select drinks and pastries to build a coffee order.',
                        subtotal: 'Subtotal',
                        total_amount: 'Total Amount',
                        checkout: 'Checkout',
                        clear_order: 'Clear Order',
                        view_cart: 'View Cart',
                        default_desc: 'Specialty roasted cafe selection.',
                    },
                    km: {
                        pos_title: 'ប្រព័ន្ធលក់ទំនិញ (POS)',
                        pos_subtitle: 'បញ្ជរបញ្ជាទិញកាហ្វេ និងភេសជ្ជៈពិសេស',
                        online: 'ដំណើរការ',
                        dine_in: 'ញ៉ាំនៅហាង',
                        takeaway: 'ខ្ចប់ទៅផ្ទះ',
                        search_placeholder: 'ស្វែងរកភេសជ្ជៈ នំ កាហ្វេ...',
                        all_items: 'មុខទំនិញទាំងអស់',
                        out_of_stock: 'អស់ពីស្តុក',
                        left: 'នៅសល់',
                        no_items_found: 'រកមិនឃើញទំនិញទេ',
                        no_items_desc: 'មិនមានទំនិញត្រូវនឹងពាក្យស្វែងរក ឬប្រភេទដែលបានជ្រើសរើសឡើយ។',
                        reset_filters: 'កំណត់ឡើងវិញ',
                        current_order: 'ការបញ្ជាទិញបច្ចុប្បន្ន',
                        items: 'មុខ',
                        cart_empty: 'កន្ត្រករបស់អ្នកទទេ',
                        cart_empty_desc: 'ជ្រើសរើសភេសជ្ជៈ និងនំ ដើម្បីបង្កើតការបញ្ជាទិញ។',
                        subtotal: 'សរុបរង',
                        total_amount: 'ទឹកប្រាក់សរុប',
                        checkout: 'ទូទាត់ប្រាក់ (Checkout)',
                        clear_order: 'សម្អាតកន្ត្រក',
                        view_cart: 'មើលកន្ត្រក',
                        default_desc: 'ការជ្រើសរើសកាហ្វេពិសេសប្រចាំហាង។',
                    }
                },

                t(key) {
                    return this.translations[this.lang]?.[key] || this.translations['en']?.[key] || key;
                },

                setLanguage(newLang) {
                    this.lang = newLang;
                    localStorage.setItem('pos_lang', newLang);
                },

                get filteredProducts() {
                    return this.products.filter((product) => {
                        const matchesCategory = this.activeCategory === 'all' || 
                            product.category_id === String(this.activeCategory) ||
                            product.category_en === this.activeCategory ||
                            product.category_km === this.activeCategory;

                        const query = this.search.trim().toLowerCase();
                        if (!query) return matchesCategory;

                        const matchesSearch = 
                            (product.name && product.name.toLowerCase().includes(query)) || 
                            (product.product_name && product.product_name.toLowerCase().includes(query)) ||
                            (product.product_name_en && product.product_name_en.toLowerCase().includes(query)) ||
                            (product.product_name_km && product.product_name_km.toLowerCase().includes(query)) ||
                            (product.variant_name_en && product.variant_name_en.toLowerCase().includes(query)) ||
                            (product.variant_name_km && product.variant_name_km.toLowerCase().includes(query)) ||
                            (product.category_en && product.category_en.toLowerCase().includes(query)) ||
                            (product.category_km && product.category_km.toLowerCase().includes(query)) ||
                            (product.description_en && product.description_en.toLowerCase().includes(query)) ||
                            (product.description_km && product.description_km.toLowerCase().includes(query));

                        return matchesCategory && matchesSearch;
                    });
                },

                get itemCount() {
                    return this.cart.reduce((total, item) => total + item.quantity, 0);
                },

                get subtotal() {
                    return this.cart.reduce((total, item) => total + (item.price * item.quantity), 0);
                },

                get total() {
                    return Math.round(this.subtotal * 100) / 100;
                },

                get changeAmount() {
                    return Math.max(0, Math.round((this.cashGiven - this.total) * 100) / 100);
                },

                addToCart(product) {
                    if (product.track_stock && product.stock_quantity <= 0) return;
                    const existing = this.cart.find((item) => item.id === product.id);
                    if (existing) {
                        if (product.track_stock && existing.quantity >= product.stock_quantity) return;
                        existing.quantity++;
                        return;
                    }
                    this.cart.push({ ...product, quantity: 1 });
                },

                decreaseQuantity(item) {
                    if (item.quantity <= 1) {
                        this.cart = this.cart.filter((c) => c.id !== item.id);
                        return;
                    }
                    item.quantity--;
                },

                removeFromCart(item) {
                    this.cart = this.cart.filter((c) => c.id !== item.id);
                },

                clearCart() {
                    this.cart = [];
                    this.cashGiven = 0;
                },

                formatCurrency(value) {
                    return '$' + Number(value).toFixed(2);
                },

                formatKhr(value) {
                    return Number(Math.round((value * this.exchangeRate) / 100) * 100).toLocaleString('en-US');
                },

                setExactCash() {
                    this.cashGiven = Math.ceil(this.total);
                },

                addCashBill(amount) {
                    this.cashGiven = (Number(this.cashGiven) || 0) + amount;
                },

                goToCheckout() {
                    if (this.cart.length === 0) return;
                    const params = new URLSearchParams();
                    params.append('order_type', this.orderType);
                    this.cart.forEach((item, i) => {
                        params.append('cart[' + i + '][id]', item.id);
                        params.append('cart[' + i + '][quantity]', item.quantity);
                    });
                    window.location.href = '{{ route("pos.checkout.index") }}?' + params.toString();
                }
            }));
        });
    </script>

    <div class="min-h-full bg-[#F9F6F0] p-4 sm:p-6 lg:p-8" x-data="posRegister">
        <div class="w-full flex flex-col gap-6">
            <!-- Top Control Bar -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between bg-white rounded-3xl p-5 sm:p-6 shadow-sm border border-stone-200/80">
                <div class="flex items-center gap-3.5">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-500/15 text-amber-700 border border-amber-500/30 shadow-inner">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015A3.001 3.001 0 0 0 21 9.349m-7.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-3.75c-.621 0-1.125.504-1.125 1.125v6" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-2xl font-bold font-display tracking-tight text-stone-900" x-text="t('pos_title')">Point of Sale (POS)</h1>
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 border border-emerald-200">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span x-text="t('online')">Online</span>
                            </span>
                        </div>
                        <p class="text-xs text-stone-500 mt-0.5" x-text="t('pos_subtitle')">Espresso &amp; Specialty Cafe Order Register</p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <!-- Language Selector (English & Khmer) -->
                    <div class="inline-flex rounded-2xl bg-stone-100 p-1 border border-stone-200 shadow-inner">
                        <button 
                            type="button" 
                            @click="setLanguage('en')" 
                            :class="lang === 'en' ? 'bg-white text-stone-950 font-bold shadow-sm' : 'text-stone-600 hover:text-stone-900 font-medium'" 
                            class="flex items-center gap-1.5 rounded-xl px-3 py-1.5 text-xs transition"
                            title="English language"
                        >
                            <span class="text-sm leading-none">🇺🇸</span>
                            <span>English</span>
                        </button>
                        <button 
                            type="button" 
                            @click="setLanguage('km')" 
                            :class="lang === 'km' ? 'bg-white text-stone-950 font-bold shadow-sm' : 'text-stone-600 hover:text-stone-900 font-medium'" 
                            class="flex items-center gap-1.5 rounded-xl px-3 py-1.5 text-xs transition font-khmer"
                            title="ភាសាខ្មែរ"
                        >
                            <span class="text-sm leading-none">🇰🇭</span>
                            <span>ខ្មែរ</span>
                        </button>
                    </div>

                    <!-- Date Pill -->
                    <div class="hidden sm:flex items-center gap-2 rounded-2xl bg-stone-100/80 px-3.5 py-2 text-xs font-semibold text-stone-600 border border-stone-200">

                        <svg class="h-4 w-4 text-stone-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
                        <span>{{ now()->format('D, M j, Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Main Workspace: Catalog + Cart -->
            <div class="grid items-start gap-6 xl:grid-cols-[minmax(0,1fr)_420px]">
                <!-- Catalog Section -->
                <section class="flex flex-col gap-6">
                    <!-- Search & Filter Controls -->
                    <div class="flex flex-col gap-4 rounded-3xl bg-white p-5 shadow-sm border border-stone-200/80 md:flex-row md:items-center md:justify-between">
                        <!-- Search Bar -->
                        <div class="relative w-full md:max-w-md">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                <svg class="h-5 w-5 text-stone-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m1.35-5.4a6.75 6.75 0 1 1-13.5 0 6.75 6.75 0 0 1 13.5 0Z" /></svg>
                            </div>
                            <input 
                                id="pos-search-input"
                                x-model="search" 
                                type="search" 
                                :placeholder="t('search_placeholder')"
                                placeholder="Search drinks, pastries, espresso..." 
                                class="w-full rounded-2xl border-stone-200 bg-stone-50/70 py-3 pl-11 pr-4 text-sm text-stone-900 placeholder-stone-400 focus:border-amber-500 focus:bg-white focus:ring-2 focus:ring-amber-500/20 transition"
                            >
                        </div>

                        <!-- Category Pills -->
                        <div class="flex gap-2 overflow-x-auto pb-1 custom-scrollbar">
                            <!-- All Items Pill -->
                            <button 
                                type="button" 
                                @click="activeCategory = 'all'" 
                                :class="activeCategory === 'all' ? 'bg-[#1C1917] text-white shadow-md shadow-stone-900/20 font-semibold' : 'bg-stone-100 text-stone-700 hover:bg-stone-200 hover:text-stone-900 font-medium'" 
                                class="whitespace-nowrap rounded-xl px-4 py-2.5 text-xs transition flex items-center gap-1.5"
                            >
                                <svg class="h-3.5 w-3.5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6Z" /></svg>
                                <span x-text="t('all_items')">All items</span>
                            </button>

                            @foreach ($categories as $cat)
                                <button 
                                    type="button" 
                                    @click="activeCategory = '{{ $cat['id'] }}'" 
                                    :class="activeCategory === '{{ $cat['id'] }}' ? 'bg-[#1C1917] text-white shadow-md shadow-stone-900/20 font-semibold' : 'bg-stone-100 text-stone-700 hover:bg-stone-200 hover:text-stone-900 font-medium'" 
                                    class="whitespace-nowrap rounded-xl px-4 py-2.5 text-xs transition flex items-center gap-1.5"
                                >
                                    <span class="h-1.5 w-1.5 rounded-full" :class="activeCategory === '{{ $cat['id'] }}' ? 'bg-amber-400' : 'bg-stone-400'"></span>
                                    <span x-text="lang === 'km' ? '{{ $cat['name_km'] }}' : '{{ $cat['name_en'] }}'">{{ $cat['name_en'] }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 2xl:grid-cols-4">
                        <template x-for="product in filteredProducts" :key="product.id">
                            <button 
                                type="button" 
                                @click="addToCart(product)" 
                                :disabled="product.track_stock && product.stock_quantity <= 0" 
                                class="group relative flex flex-col overflow-hidden rounded-3xl bg-white border border-stone-200/80 text-left transition-all duration-200 hover:-translate-y-1 hover:border-amber-400/60 hover:shadow-xl hover:shadow-amber-500/10 active:scale-[0.98] disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                <!-- Top Accent Banner with Category & Stock Status -->
                                <div class="relative h-24 w-full bg-gradient-to-br from-[#2D241E] via-[#1E1915] to-[#120F0D] p-4 flex flex-col justify-between overflow-hidden">
                                    <div class="pointer-events-none absolute -right-6 -bottom-6 h-24 w-24 rounded-full bg-amber-500/10 blur-xl group-hover:bg-amber-500/25 transition"></div>
                                    
                                    <div class="flex items-center justify-between z-10">
                                        <span class="inline-flex items-center rounded-full bg-white/10 px-2.5 py-0.5 text-[11px] font-semibold text-amber-300 backdrop-blur-md border border-white/10" x-text="lang === 'km' ? product.category_km : product.category_en"></span>
                                        <template x-if="product.track_stock">
                                            <span 
                                                class="rounded-full px-2 py-0.5 text-[10px] font-bold"
                                                :class="product.stock_quantity <= 0 ? 'bg-rose-500/20 text-rose-300 border border-rose-500/30' : (product.stock_quantity <= 5 ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30')"
                                                x-text="product.stock_quantity <= 0 ? t('out_of_stock') : product.stock_quantity + ' ' + t('left')"
                                            ></span>
                                        </template>
                                    </div>

                                    <!-- Coffee Cup / Drink Graphic Icon -->
                                    <div class="flex items-end justify-between z-10">
                                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/10 text-amber-300 backdrop-blur-md">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0 1 12 21 8.25 8.25 0 0 1 6.038 7.047 8.287 8.287 0 0 0 9 9.601a8.983 8.983 0 0 1 3.361-6.867 8.21 8.21 0 0 0 3 2.48Z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card Content -->
                                <div class="flex flex-1 flex-col justify-between p-4 sm:p-5">
                                    <div>
                                        <!-- Primary Product Title in Selected Language -->
                                        <h3 class="font-bold text-stone-900 leading-snug group-hover:text-amber-700 transition" x-text="lang === 'km' ? product.product_name_km : product.product_name_en"></h3>
                                        
                                        <!-- Secondary Subtitle (Alternative Language) -->
                                        <p class="text-[11px] text-stone-400 mt-0.5" x-text="lang === 'km' ? product.product_name_en : product.product_name_km"></p>
                                        
                                        <!-- Variant Pill & Details -->
                                        <p class="text-xs text-stone-500 font-medium mt-1.5 flex items-center gap-1.5">
                                            <span class="inline-block h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                            <span x-text="lang === 'km' ? product.variant_name_km : product.variant_name_en"></span>
                                        </p>
                                        
                                        <p class="text-xs text-stone-400 mt-2 line-clamp-2" x-text="(lang === 'km' ? product.description_km : product.description_en) || t('default_desc')"></p>
                                    </div>

                                    <div class="mt-4 flex items-center justify-between pt-3 border-t border-stone-100">
                                        <span class="text-lg font-extrabold text-stone-900 font-display" x-text="formatCurrency(product.price)"></span>
                                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-amber-500/10 text-amber-700 font-bold group-hover:bg-amber-500 group-hover:text-stone-950 transition duration-150">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                            </button>
                        </template>
                    </div>

                    <!-- Empty Search State -->
                    <div x-show="filteredProducts.length === 0" x-cloak class="rounded-3xl bg-white p-12 text-center border border-dashed border-stone-300">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-50 text-amber-700 mb-3">
                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                        </div>
                        <h4 class="text-base font-bold text-stone-900" x-text="t('no_items_found')">No menu items found</h4>
                        <p class="text-xs text-stone-500 mt-1 max-w-sm mx-auto" x-text="t('no_items_desc')">No products match your current search query or active category filter.</p>
                        <button type="button" @click="search = ''; activeCategory = 'all'" class="mt-4 inline-flex items-center gap-2 rounded-xl bg-stone-900 px-4 py-2 text-xs font-semibold text-white hover:bg-stone-800 transition">
                            <span x-text="t('reset_filters')">Reset Filters</span>
                        </button>
                    </div>
                </section>

                <!-- Order Cart Sidebar (Desktop Sticky) -->
                <aside 
                    class="sticky top-6 rounded-3xl bg-[#1C1917] text-white p-6 shadow-2xl border border-stone-800 xl:block select-none"
                    :class="showMobileCart ? 'fixed inset-0 z-50 rounded-none p-6 overflow-y-auto flex flex-col justify-between' : 'hidden xl:block'"
                >
                    <!-- Mobile Close Button & Header -->
                    <div class="flex items-center justify-between border-b border-stone-800 pb-5">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-400 to-amber-600 text-stone-950 font-bold shadow-md shadow-amber-500/20">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold font-display tracking-tight text-white leading-tight" x-text="t('current_order')">Current Order</h2>
                                <p class="text-[11px] text-amber-400/80 font-medium" x-text="lang === 'km' ? 'បញ្ជីទំនិញជ្រើសរើស' : 'Selected Items'">Selected Items</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center justify-center rounded-full bg-amber-500/20 px-3 py-1 text-xs font-bold text-amber-300 border border-amber-500/30" x-text="itemCount + ' ' + t('items')"></span>
                            <button type="button" @click="showMobileCart = false" class="xl:hidden rounded-xl p-2 text-stone-400 hover:bg-white/10 hover:text-white">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Cart Item List -->
                    <div class="flex min-h-[220px] max-h-[360px] flex-col gap-2.5 py-4 overflow-y-auto custom-scrollbar">
                        <template x-for="item in cart" :key="item.id">
                            <div class="flex items-center justify-between gap-3 rounded-2xl bg-[#25211E] p-3.5 border border-stone-800 hover:border-stone-700 transition">
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-semibold text-white leading-tight" x-text="lang === 'km' ? item.product_name_km : item.product_name_en"></p>
                                    <p class="text-[10px] text-stone-400 truncate" x-text="lang === 'km' ? item.product_name_en : item.product_name_km"></p>
                                    <p class="text-xs text-amber-300/90 font-medium mt-0.5" x-text="lang === 'km' ? item.variant_name_km : item.variant_name_en"></p>
                                    <p class="text-xs font-bold text-amber-400 mt-1" x-text="formatCurrency(item.price)"></p>
                                </div>

                                <div class="flex items-center gap-2">
                                    <!-- Quantity Stepper -->
                                    <div class="inline-flex items-center rounded-xl bg-black/40 p-1 border border-white/5">
                                        <button type="button" @click="decreaseQuantity(item)" class="flex h-6 w-6 items-center justify-center rounded-lg bg-white/10 text-stone-300 hover:bg-white/20 hover:text-white transition font-bold text-xs">-</button>
                                        <span class="w-7 text-center text-xs font-bold text-white" x-text="item.quantity"></span>
                                        <button type="button" @click="addToCart(item)" class="flex h-6 w-6 items-center justify-center rounded-lg bg-white/10 text-stone-300 hover:bg-white/20 hover:text-white transition font-bold text-xs">+</button>
                                    </div>
                                    <button type="button" @click="removeFromCart(item)" class="rounded-lg p-1 text-stone-500 hover:bg-rose-500/20 hover:text-rose-400 transition" title="Remove item">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                    </button>
                                </div>
                            </div>
                        </template>

                        <!-- Empty Cart Illustration -->
                        <div x-show="cart.length === 0" class="flex flex-1 flex-col items-center justify-center py-10 text-center">
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/5 text-amber-400/80 border border-white/5 mb-3">
                                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                            </div>
                            <p class="text-sm font-bold text-stone-200" x-text="t('cart_empty')">Your cart is empty</p>
                            <p class="mt-1 max-w-[220px] text-xs text-stone-400" x-text="t('cart_empty_desc')">Select drinks and pastries to build a coffee order.</p>
                        </div>
                    </div>

                    <!-- Bill Totals Breakdown -->
                    <div class="space-y-2.5 border-t border-stone-800 pt-4 text-xs">
                        <div class="flex justify-between text-stone-400">
                            <span x-text="t('subtotal')">Subtotal</span>
                            <span class="font-semibold text-stone-200" x-text="formatCurrency(subtotal)"></span>
                        </div>
                        <div class="flex items-baseline justify-between pt-2 border-t border-stone-800/80">
                            <span class="text-sm font-bold text-white" x-text="t('total_amount')">Total Amount</span>
                            <div class="text-right">
                                <span class="text-2xl font-extrabold text-amber-400 font-display tracking-tight" x-text="formatCurrency(total)"></span>
                                <p class="text-[11px] text-amber-300/80 font-mono mt-0.5" x-text="'(' + formatKhr(total) + ' ៛)'"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-5 space-y-2.5">
                        <button 
                            type="button" 
                            @click="goToCheckout()" 
                            :disabled="cart.length === 0" 
                            class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-amber-400 via-amber-500 to-amber-600 px-5 py-4 text-sm font-bold text-stone-950 shadow-lg shadow-amber-500/25 transition duration-150 hover:brightness-110 active:scale-[0.99] disabled:cursor-not-allowed disabled:opacity-40"
                        >
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                            </svg>
                            <span><span x-text="t('checkout')">Checkout</span> (<span x-text="formatCurrency(total)"></span> &bull; <span x-text="formatKhr(total) + ' ៛'"></span>)</span>
                        </button>

                        <button 
                            type="button" 
                            @click="clearCart()" 
                            :disabled="cart.length === 0" 
                            class="w-full rounded-2xl border border-stone-800 bg-white/5 px-4 py-3 text-xs font-semibold text-stone-400 transition hover:bg-white/10 hover:text-white disabled:cursor-not-allowed disabled:opacity-30"
                        >
                            <span x-text="t('clear_order')">Clear Order</span>
                        </button>
                    </div>
                </aside>
            </div>

            <!-- Floating Mobile Cart Trigger Button -->
            <button 
                type="button" 
                @click="showMobileCart = true" 
                x-show="cart.length > 0" 
                x-cloak
                class="fixed bottom-6 right-6 z-40 xl:hidden inline-flex items-center gap-3 rounded-full bg-gradient-to-r from-amber-400 to-amber-600 px-6 py-3.5 text-sm font-bold text-stone-950 shadow-2xl shadow-amber-500/40"
            >
                <div class="flex h-7 w-7 items-center justify-center rounded-full bg-stone-950 text-amber-400 text-xs font-bold" x-text="itemCount"></div>
                <span><span x-text="t('view_cart')">View Cart</span> &bull; <span x-text="formatCurrency(total)"></span></span>
            </button>
        </div>
    </div>
</x-app-layout>

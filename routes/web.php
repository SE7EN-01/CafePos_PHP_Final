<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\IngredientController;
use App\Http\Controllers\Admin\InventoryReportController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Controllers\Admin\RecipeController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\KhqrController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\PreventBaristaAccess;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/slides', function () {
    return response()->file(public_path('slides.html'));
})->name('slides');

Route::get('/canva', function () {
    return response()->file(public_path('canva.html'));
})->name('canva');

Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->hasRole('barista')
            ? redirect()->route('pos.index')
            : redirect()->route('dashboard');
    }

    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    if (auth()->user()->hasRole('barista')) {
        return redirect()->route('pos.index');
    }

    $today = now()->startOfDay();

    $todayRevenue = Order::where('created_at', '>=', $today)->sum('total_amount');
    $ordersToday = Order::where('created_at', '>=', $today)->count();
    $itemsSold = OrderItem::whereHas('order', fn ($q) => $q->where('created_at', '>=', $today))->sum('quantity');
    $teamMembers = User::count();

    $recentOrders = Order::with('user')
        ->latest()
        ->limit(5)
        ->get()
        ->map(fn ($order) => (object) [
            'number' => $order->order_number,
            'type' => str_replace('_', ' ', $order->order_type),
            'total' => number_format($order->total_amount, 2),
        ]);

    $popularProducts = ProductVariant::select('product_variants.*')
        ->selectRaw('(SELECT SUM(quantity) FROM order_items WHERE product_variant_id = product_variants.id) as total_sold')
        ->with('product')
        ->orderByDesc('total_sold')
        ->limit(4)
        ->get()
        ->filter(fn ($v) => $v->total_sold > 0);

    return view('dashboard', compact(
        'todayRevenue',
        'ordersToday',
        'itemsSold',
        'teamMembers',
        'recentOrders',
        'popularProducts',
    ));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/pos', [PosController::class, 'index'])
    ->middleware('auth')
    ->name('pos.index');

Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('pos.checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('pos.checkout.store');
    Route::get('/receipt/{order}', [CheckoutController::class, 'receipt'])->name('pos.receipt');

    // KHQR Payment Routes
    Route::post('/khqr/generate', [KhqrController::class, 'generate'])->name('khqr.generate');
    Route::post('/khqr/generate-for-checkout', [KhqrController::class, 'generateForCheckout'])->name('khqr.generate-for-checkout');
    Route::post('/khqr/check-status', [KhqrController::class, 'checkStatus'])->name('khqr.check-status');
});

Route::middleware(['auth', PreventBaristaAccess::class, 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::patch('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::patch('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    Route::post('/products/{product}/variants', [ProductController::class, 'storeVariant'])->name('products.variants.store');
    Route::patch('/products/{product}/variants/{variant}', [ProductController::class, 'updateVariant'])->name('products.variants.update');
    Route::delete('/products/{product}/variants/{variant}', [ProductController::class, 'destroyVariant'])->name('products.variants.destroy');

    Route::get('/ingredients', [IngredientController::class, 'index'])->name('ingredients.index');
    Route::post('/ingredients', [IngredientController::class, 'store'])->name('ingredients.store');
    Route::get('/ingredients/{ingredient}/edit', [IngredientController::class, 'edit'])->name('ingredients.edit');
    Route::patch('/ingredients/{ingredient}', [IngredientController::class, 'update'])->name('ingredients.update');
    Route::delete('/ingredients/{ingredient}', [IngredientController::class, 'destroy'])->name('ingredients.destroy');

    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
    Route::post('/stock/in', [StockController::class, 'stockIn'])->name('stock.in');
    Route::post('/stock/adjust', [StockController::class, 'adjust'])->name('stock.adjust');
    Route::get('/stock/reports', [InventoryReportController::class, 'index'])->name('stock.reports');

    Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');
    Route::get('/recipes/{variant}/edit', [RecipeController::class, 'edit'])->name('recipes.edit');
    Route::post('/recipes/{variant}', [RecipeController::class, 'store'])->name('recipes.store');
    Route::delete('/recipes/{recipe}', [RecipeController::class, 'destroy'])->name('recipes.destroy');

    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::patch('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');

    Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
    Route::get('/purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
    Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');

    Route::get('/tables', [TableController::class, 'index'])->name('tables.index');
    Route::post('/tables', [TableController::class, 'store'])->name('tables.store');
    Route::get('/tables/{table}/edit', [TableController::class, 'edit'])->name('tables.edit');
    Route::patch('/tables/{table}', [TableController::class, 'update'])->name('tables.update');
    Route::patch('/tables/{table}/status', [TableController::class, 'toggleStatus'])->name('tables.status');
    Route::delete('/tables/{table}', [TableController::class, 'destroy'])->name('tables.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

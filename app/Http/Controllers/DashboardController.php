<?php

namespace App\Http\Controllers;

use App\Models\CafeTable;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        if ($request->user()?->hasRole('barista')) {
            return redirect()->route('pos.index');
        }

        $today = now()->startOfDay();

        // 1. Core KPIs
        $todayRevenue = (float) Order::where('created_at', '>=', $today)
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');
        $ordersToday = Order::where('created_at', '>=', $today)->count();
        $itemsSold = (int) OrderItem::whereHas('order', fn ($q) => $q->where('created_at', '>=', $today)->where('status', '!=', 'cancelled'))->sum('quantity');
        $teamMembers = User::count();
        $avgOrderValue = $ordersToday > 0 ? ($todayRevenue / $ordersToday) : 0;

        // 2. Payment breakdown (Cash vs KHQR)
        $cashOrdersCount = Order::where('created_at', '>=', $today)->where('payment_method', 'cash')->count();
        $khqrOrdersCount = Order::where('created_at', '>=', $today)->where('payment_method', 'khqr')->count();
        $cashRevenue = (float) Order::where('created_at', '>=', $today)->where('payment_method', 'cash')->sum('total_amount');
        $khqrRevenue = (float) Order::where('created_at', '>=', $today)->where('payment_method', 'khqr')->sum('total_amount');

        // 3. Dine-in Tables Status
        $totalTables = CafeTable::where('is_active', true)->count();
        $occupiedTables = CafeTable::where('is_active', true)->where('status', 'occupied')->count();
        $availableTables = CafeTable::where('is_active', true)->where('status', 'available')->count();
        $activeTablesList = CafeTable::where('is_active', true)->orderBy('name')->take(6)->get();

        // 4. Low stock & Expiry warnings
        $allIngredients = Ingredient::all();
        $lowStockIngredients = $allIngredients->filter(fn ($ing) => $ing->isLowStock());
        $expiringIngredients = $allIngredients->filter(fn ($ing) => $ing->isExpiringSoon(7));

        // 5. Recent 6 Orders
        $recentOrders = Order::with(['user', 'cafeTable', 'items'])
            ->latest()
            ->limit(6)
            ->get();

        // 6. Top 5 Popular Products with variant and category
        $popularProducts = ProductVariant::select('product_variants.*')
            ->selectRaw('(SELECT COALESCE(SUM(quantity), 0) FROM order_items WHERE product_variant_id = product_variants.id) as total_sold')
            ->with('product.category')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get()
            ->filter(fn ($v) => (int) $v->total_sold > 0);

        // 7. Last 7 days sales trend
        $salesLast7Days = collect(range(6, 0))->map(function (int $daysAgo): array {
            $date = now()->subDays($daysAgo);
            $total = (float) Order::whereDate('created_at', $date->toDateString())
                ->where('status', '!=', 'cancelled')
                ->sum('total_amount');

            return [
                'day' => $date->format('D'),
                'date' => $date->format('M d'),
                'total' => $total,
            ];
        });
        $maxDaySale = max($salesLast7Days->max('total') ?: 1, 10);

        return view('dashboard', compact(
            'todayRevenue',
            'ordersToday',
            'itemsSold',
            'teamMembers',
            'avgOrderValue',
            'cashOrdersCount',
            'khqrOrdersCount',
            'cashRevenue',
            'khqrRevenue',
            'totalTables',
            'occupiedTables',
            'availableTables',
            'activeTablesList',
            'lowStockIngredients',
            'expiringIngredients',
            'recentOrders',
            'popularProducts',
            'salesLast7Days',
            'maxDaySale',
        ));
    }
}

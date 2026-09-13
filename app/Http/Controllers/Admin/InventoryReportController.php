<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryReportController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        $tab = $request->query('tab', 'valuation'); // valuation, movements, waste, expiry
        $dateFrom = $request->query('from', now()->subDays(30)->toDateString());
        $dateTo = $request->query('to', now()->toDateString());

        // 1. Valuation & Stock Balance
        $ingredients = Ingredient::with('supplier')->orderByRaw("name->>'en' ASC")->get();
        $totalValuation = $ingredients->sum(function ($ing) {
            $cost = (float) ($ing->average_cost > 0 ? $ing->average_cost : $ing->purchase_cost);

            return (float) $ing->current_stock * $cost;
        });

        // 2. Stock Movements Ledger (filtered)
        $movementsQuery = StockMovement::with(['user', 'stockable'])
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo);

        if ($request->filled('type')) {
            $movementsQuery->where('type', $request->query('type'));
        }

        $movements = $movementsQuery->latest()->paginate(15)->withQueryString();

        // 3. Waste Report
        $wasteMovements = StockMovement::with(['user', 'stockable'])
            ->where('type', 'waste')
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->latest()
            ->get();

        $totalWasteCount = $wasteMovements->count();

        // 4. Expiry Report
        $expiredItems = Ingredient::whereNotNull('expiry_date')->where('expiry_date', '<', now())->get();
        $expiringSoon = Ingredient::whereNotNull('expiry_date')
            ->where('expiry_date', '>=', now())
            ->where('expiry_date', '<=', now()->addDays(7))
            ->get();

        return view('admin.stock.reports', [
            'tab' => $tab,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'ingredients' => $ingredients,
            'totalValuation' => $totalValuation,
            'movements' => $movements,
            'wasteMovements' => $wasteMovements,
            'totalWasteCount' => $totalWasteCount,
            'expiredItems' => $expiredItems,
            'expiringSoon' => $expiringSoon,
        ]);
    }
}

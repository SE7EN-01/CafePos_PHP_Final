<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\ProductVariant;
use App\Models\StockMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StockController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        $filter = $request->query('filter', 'all'); // all, low_stock, ingredients, variants
        $search = $request->query('search', '');

        // Fetch Ingredients
        $ingredientsQuery = Ingredient::query();
        if ($search) {
            $ingredientsQuery->where(function ($q) use ($search) {
                $q->whereRaw("name->>'en' ILIKE ?", ["%{$search}%"])
                    ->orWhereRaw("name->>'km' ILIKE ?", ["%{$search}%"]);
            });
        }
        $ingredients = $ingredientsQuery->orderByRaw("name->>'en' ASC")->get();

        // Fetch Tracked Variants
        $variantsQuery = ProductVariant::query()
            ->where('track_stock', true)
            ->with(['product.category']);

        if ($search) {
            $variantsQuery->where(function ($q) use ($search) {
                $q->whereRaw("name->>'en' ILIKE ?", ["%{$search}%"])
                    ->orWhereRaw("name->>'km' ILIKE ?", ["%{$search}%"])
                    ->orWhereHas('product', function ($pq) use ($search) {
                        $pq->whereRaw("name->>'en' ILIKE ?", ["%{$search}%"])
                            ->orWhereRaw("name->>'km' ILIKE ?", ["%{$search}%"]);
                    });
            });
        }
        $variants = $variantsQuery->get();

        // Map into unified stock items
        $stockItems = collect();

        foreach ($ingredients as $ing) {
            $stockItems->push((object) [
                'id' => $ing->id,
                'type' => 'ingredient',
                'name' => $ing->name,
                'name_translations' => $ing->name_translations,
                'category' => 'Raw Ingredient',
                'unit' => $ing->unit,
                'current_stock' => (float) $ing->current_stock,
                'reorder_level' => (float) $ing->reorder_level,
                'is_low_stock' => $ing->isLowStock(),
                'is_out_of_stock' => (float) $ing->current_stock <= 0,
                'raw_model' => $ing,
            ]);
        }

        foreach ($variants as $var) {
            $productName = $var->product->name ?? 'Product';
            $stockItems->push((object) [
                'id' => $var->id,
                'type' => 'variant',
                'name' => "{$productName} - {$var->name}",
                'name_translations' => $var->name_translations,
                'category' => $var->product->category->name ?? 'Packaged Retail',
                'unit' => 'pcs',
                'current_stock' => (float) $var->stock_quantity,
                'reorder_level' => 10.0,
                'is_low_stock' => (float) $var->stock_quantity <= 10.0,
                'is_out_of_stock' => (float) $var->stock_quantity <= 0,
                'raw_model' => $var,
            ]);
        }

        // Apply tab filters
        $filteredItems = match ($filter) {
            'low_stock' => $stockItems->filter(fn ($i) => $i->is_low_stock || $i->is_out_of_stock),
            'ingredients' => $stockItems->where('type', 'ingredient'),
            'variants' => $stockItems->where('type', 'variant'),
            default => $stockItems,
        };

        // Statistics
        $totalItems = $stockItems->count();
        $lowStockCount = $stockItems->where('is_low_stock', true)->count();
        $outOfStockCount = $stockItems->where('is_out_of_stock', true)->count();
        $todayMovementsCount = StockMovement::whereDate('created_at', today())->count();

        // Recent Movements Ledger
        $movements = StockMovement::with(['user', 'stockable'])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // All ingredients & variants for dropdown selections in modals
        $allIngredients = Ingredient::orderByRaw("name->>'en' ASC")->get();
        $allVariants = ProductVariant::where('track_stock', true)->with('product')->get();

        return view('admin.stock.index', [
            'stockItems' => $filteredItems,
            'totalItems' => $totalItems,
            'lowStockCount' => $lowStockCount,
            'outOfStockCount' => $outOfStockCount,
            'todayMovementsCount' => $todayMovementsCount,
            'movements' => $movements,
            'filter' => $filter,
            'search' => $search,
            'allIngredients' => $allIngredients,
            'allVariants' => $allVariants,
        ]);
    }

    public function stockIn(Request $request): RedirectResponse
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        $validated = $request->validate([
            'item_type' => ['required', 'string', 'in:ingredient,variant'],
            'item_id' => ['required', 'integer'],
            'quantity' => ['required', 'numeric', 'min:0.01'],
            'unit_cost' => ['nullable', 'numeric', 'min:0'],
            'reason' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($validated, $request) {
            $stockable = null;

            if ($validated['item_type'] === 'ingredient') {
                $stockable = Ingredient::lockForUpdate()->findOrFail($validated['item_id']);
                $stockable->increment('current_stock', $validated['quantity']);
            } else {
                $stockable = ProductVariant::lockForUpdate()->findOrFail($validated['item_id']);
                $stockable->increment('stock_quantity', $validated['quantity']);
            }

            StockMovement::create([
                'user_id' => $request->user()->id,
                'stockable_type' => get_class($stockable),
                'stockable_id' => $stockable->id,
                'type' => 'in',
                'quantity' => $validated['quantity'],
                'unit_cost' => $validated['unit_cost'] ?? null,
                'reason' => $validated['reason'] ?: 'Supplier Delivery',
                'notes' => $validated['notes'] ?? null,
            ]);
        });

        return redirect()->route('admin.stock.index')->with('status', 'stock-received');
    }

    public function adjust(Request $request): RedirectResponse
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        $validated = $request->validate([
            'item_type' => ['required', 'string', 'in:ingredient,variant'],
            'item_id' => ['required', 'integer'],
            'adjustment_type' => ['required', 'string', 'in:waste,calibration,recount'],
            'quantity' => ['required', 'numeric'],
            'reason' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($validated, $request) {
            $stockable = null;
            $delta = 0;

            if ($validated['item_type'] === 'ingredient') {
                $stockable = Ingredient::lockForUpdate()->findOrFail($validated['item_id']);
                $current = (float) $stockable->current_stock;
            } else {
                $stockable = ProductVariant::lockForUpdate()->findOrFail($validated['item_id']);
                $current = (float) $stockable->stock_quantity;
            }

            if ($validated['adjustment_type'] === 'recount') {
                // For recount, user inputs the actual count on hand
                $newBalance = max(0, (float) $validated['quantity']);
                $delta = $newBalance - $current;
            } else {
                // For waste or calibration, user inputs quantity wasted (positive number), so delta is negative
                $wastedQty = abs((float) $validated['quantity']);
                $newBalance = max(0, $current - $wastedQty);
                $delta = -$wastedQty;
            }

            if ($validated['item_type'] === 'ingredient') {
                $stockable->update(['current_stock' => $newBalance]);
            } else {
                $stockable->update(['stock_quantity' => $newBalance]);
            }

            StockMovement::create([
                'user_id' => $request->user()->id,
                'stockable_type' => get_class($stockable),
                'stockable_id' => $stockable->id,
                'type' => $validated['adjustment_type'] === 'waste' ? 'waste' : 'adjustment',
                'quantity' => $delta,
                'reason' => $validated['reason'],
                'notes' => $validated['notes'] ?? null,
            ]);
        });

        return redirect()->route('admin.stock.index')->with('status', 'stock-adjusted');
    }
}

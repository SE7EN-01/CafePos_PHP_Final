<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PurchaseController extends Controller
{
    public function __construct(protected InventoryService $inventoryService) {}

    public function index(Request $request): View
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        $purchases = Purchase::with(['supplier', 'user', 'items.ingredient'])
            ->latest()
            ->paginate(12);

        return view('admin.purchases.index', [
            'purchases' => $purchases,
        ]);
    }

    public function create(Request $request): View
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        $ingredients = Ingredient::orderByRaw("name->>'en' ASC")->get();

        return view('admin.purchases.create', [
            'suppliers' => $suppliers,
            'ingredients' => $ingredients,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        $validated = $request->validate([
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'invoice_number' => ['nullable', 'string', 'max:100'],
            'purchase_date' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.ingredient_id' => ['required', 'exists:ingredients,id'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.01'],
            'items.*.unit_cost' => ['required', 'numeric', 'min:0'],
            'items.*.batch_number' => ['nullable', 'string', 'max:100'],
            'items.*.expiry_date' => ['nullable', 'date'],
        ]);

        $purchase = DB::transaction(function () use ($validated, $request) {
            $totalAmount = 0.0;

            $purchase = Purchase::create([
                'supplier_id' => $validated['supplier_id'] ?? null,
                'user_id' => $request->user()->id,
                'invoice_number' => $validated['invoice_number'] ?? null,
                'purchase_date' => $validated['purchase_date'],
                'status' => 'pending',
                'total_amount' => 0.0,
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($validated['items'] as $itemData) {
                $ingredient = Ingredient::findOrFail($itemData['ingredient_id']);
                $subtotal = round((float) $itemData['quantity'] * (float) $itemData['unit_cost'], 2);
                $totalAmount += $subtotal;

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'ingredient_id' => $ingredient->id,
                    'quantity' => $itemData['quantity'],
                    'unit' => $ingredient->unit,
                    'unit_cost' => $itemData['unit_cost'],
                    'subtotal' => $subtotal,
                    'batch_number' => $itemData['batch_number'] ?? null,
                    'expiry_date' => $itemData['expiry_date'] ?? null,
                ]);
            }

            $purchase->update(['total_amount' => $totalAmount]);

            // Automatically receive the purchase and update inventory stock + moving average cost (Step 12)
            $this->inventoryService->receivePurchase($purchase);

            return $purchase;
        });

        return redirect()->route('admin.purchases.index')->with('status', 'purchase-received');
    }
}

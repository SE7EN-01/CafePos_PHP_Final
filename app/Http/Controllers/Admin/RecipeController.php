<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\ProductVariant;
use App\Models\Recipe;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RecipeController extends Controller
{
    public function __construct(protected InventoryService $inventoryService) {}

    public function index(Request $request): View
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        $variants = ProductVariant::with(['product.category', 'recipes.ingredient'])
            ->where('is_active', true)
            ->get()
            ->map(function (ProductVariant $variant) {
                $costData = $this->inventoryService->calculateRecipeCost($variant);

                return (object) [
                    'variant' => $variant,
                    'product_name' => $variant->product->name,
                    'variant_name' => $variant->name,
                    'category' => $variant->product->category->name ?? 'Drinks',
                    'selling_price' => $costData['selling_price'],
                    'recipe_cost' => $costData['recipe_cost'],
                    'gross_margin' => $costData['gross_margin'],
                    'margin_percentage' => $costData['margin_percentage'],
                    'ingredients_count' => $variant->recipes->count(),
                ];
            });

        return view('admin.recipes.index', [
            'variants' => $variants,
        ]);
    }

    public function edit(Request $request, ProductVariant $variant): View
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        $variant->load(['product', 'recipes.ingredient']);
        $costData = $this->inventoryService->calculateRecipeCost($variant);
        $allIngredients = Ingredient::orderByRaw("name->>'en' ASC")->get();

        return view('admin.recipes.edit', [
            'variant' => $variant,
            'costData' => $costData,
            'allIngredients' => $allIngredients,
        ]);
    }

    public function store(Request $request, ProductVariant $variant): RedirectResponse
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        $validated = $request->validate([
            'ingredient_id' => ['required', 'exists:ingredients,id'],
            'quantity_required' => ['required', 'numeric', 'min:0.01'],
        ]);

        Recipe::updateOrCreate(
            [
                'product_variant_id' => $variant->id,
                'ingredient_id' => $validated['ingredient_id'],
            ],
            [
                'quantity_required' => $validated['quantity_required'],
            ]
        );

        return redirect()->route('admin.recipes.edit', $variant)->with('status', 'recipe-updated');
    }

    public function destroy(Request $request, Recipe $recipe): RedirectResponse
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        $variantId = $recipe->product_variant_id;
        $recipe->delete();

        return redirect()->route('admin.recipes.edit', $variantId)->with('status', 'recipe-item-deleted');
    }
}

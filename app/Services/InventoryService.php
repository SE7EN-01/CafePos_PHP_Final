<?php

namespace App\Services;

use App\Models\Ingredient;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\Purchase;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class InventoryService
{
    /**
     * Deduct inventory for an order atomically with duplicate deduction protection.
     *
     * @throws \Exception
     */
    public function deductForOrder(Order $order): bool
    {
        // Step 9: Prevent duplicate deductions
        if ($order->inventory_deducted_at !== null) {
            return false;
        }

        $order->loadMissing('items.productVariant.recipes.ingredient');

        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                $variant = $item->productVariant;
                if (! $variant) {
                    continue;
                }

                // 1. Packaged item stock deduction
                if ($variant->track_stock) {
                    if ($variant->stock_quantity < $item->quantity) {
                        throw new \RuntimeException("Insufficient stock for {$variant->name}. Available: {$variant->stock_quantity}");
                    }

                    $variant->decrement('stock_quantity', $item->quantity);

                    StockMovement::create([
                        'user_id' => $order->user_id,
                        'stockable_type' => ProductVariant::class,
                        'stockable_id' => $variant->id,
                        'type' => 'sale',
                        'quantity' => -(float) $item->quantity,
                        'reason' => "POS Order #{$order->order_number}",
                        'notes' => 'Packaged item sale',
                    ]);
                }

                // 2. Recipe ingredient deductions
                foreach ($variant->recipes as $recipe) {
                    $ingredient = $recipe->ingredient;
                    if (! $ingredient) {
                        continue;
                    }

                    $requiredAmount = (float) $recipe->quantity_required * (int) $item->quantity;

                    // Deduct ingredient
                    $ingredient->decrement('current_stock', $requiredAmount);

                    StockMovement::create([
                        'user_id' => $order->user_id,
                        'stockable_type' => Ingredient::class,
                        'stockable_id' => $ingredient->id,
                        'type' => 'sale',
                        'quantity' => -$requiredAmount,
                        'reason' => "POS Order #{$order->order_number}",
                        'notes' => "Recipe deduction for {$variant->name} (x{$item->quantity})",
                    ]);
                }
            }

            // Step 9: Mark order as processed
            $order->update(['inventory_deducted_at' => now()]);
        });

        return true;
    }

    /**
     * Convert compatible units safely (Step 5).
     */
    public function convertUnits(float $quantity, string $fromUnit, string $toUnit): float
    {
        $from = strtolower(trim($fromUnit));
        $to = strtolower(trim($toUnit));

        if ($from === $to) {
            return $quantity;
        }

        // Weight conversions
        $weightUnits = ['g' => 1.0, 'grams' => 1.0, 'kg' => 1000.0, 'kilograms' => 1000.0];
        if (isset($weightUnits[$from], $weightUnits[$to])) {
            $inGrams = $quantity * $weightUnits[$from];

            return $inGrams / $weightUnits[$to];
        }

        // Volume conversions
        $volumeUnits = ['ml' => 1.0, 'milliliters' => 1.0, 'l' => 1000.0, 'liters' => 1000.0];
        if (isset($volumeUnits[$from], $volumeUnits[$to])) {
            $inMl = $quantity * $volumeUnits[$from];

            return $inMl / $volumeUnits[$to];
        }

        // Count conversions (boxes/packs to pcs if 1:1 fallback)
        if (in_array($from, ['pcs', 'pieces']) && in_array($to, ['pcs', 'pieces'])) {
            return $quantity;
        }

        throw new InvalidArgumentException("Incompatible unit conversion from {$fromUnit} to {$toUnit}");
    }

    /**
     * Calculate recipe cost and gross margin for a variant (Step 7).
     *
     * @return array{recipe_cost: float, selling_price: float, gross_margin: float, margin_percentage: float, items: array<int, array<string, mixed>>}
     */
    public function calculateRecipeCost(ProductVariant $variant): array
    {
        $variant->loadMissing('recipes.ingredient');

        $totalCost = 0.0;
        $costItems = [];

        foreach ($variant->recipes as $recipe) {
            $ingredient = $recipe->ingredient;
            if (! $ingredient) {
                continue;
            }

            // Determine unit cost per unit
            $costPerBaseUnit = (float) ($ingredient->average_cost > 0 ? $ingredient->average_cost : $ingredient->purchase_cost);
            $qty = (float) $recipe->quantity_required;
            $itemCost = round($qty * $costPerBaseUnit, 4);

            $totalCost += $itemCost;

            $costItems[] = [
                'ingredient_name' => $ingredient->name,
                'quantity' => $qty,
                'unit' => $ingredient->unit,
                'unit_cost' => $costPerBaseUnit,
                'line_cost' => $itemCost,
            ];
        }

        $sellingPrice = (float) $variant->price;
        $grossMargin = $sellingPrice - $totalCost;
        $marginPercentage = $sellingPrice > 0 ? round(($grossMargin / $sellingPrice) * 100, 2) : 0.0;

        return [
            'recipe_cost' => round($totalCost, 2),
            'selling_price' => round($sellingPrice, 2),
            'gross_margin' => round($grossMargin, 2),
            'margin_percentage' => $marginPercentage,
            'items' => $costItems,
        ];
    }

    /**
     * Receive a purchase and update stock balances with moving average cost (Step 12).
     */
    public function receivePurchase(Purchase $purchase): void
    {
        if ($purchase->status === 'received') {
            return;
        }

        $purchase->loadMissing('items.ingredient');

        DB::transaction(function () use ($purchase) {
            foreach ($purchase->items as $item) {
                $ingredient = $item->ingredient;
                if (! $ingredient) {
                    continue;
                }

                $currentStock = (float) $ingredient->current_stock;
                $currentAvgCost = (float) ($ingredient->average_cost > 0 ? $ingredient->average_cost : $ingredient->purchase_cost);

                $receivedQty = (float) $item->quantity;
                $unitCost = (float) $item->unit_cost;

                // Calculate moving average cost
                $newTotalStock = $currentStock + $receivedQty;
                $newAvgCost = $newTotalStock > 0
                    ? (($currentStock * $currentAvgCost) + ($receivedQty * $unitCost)) / $newTotalStock
                    : $unitCost;

                // Update ingredient stock, costs, batch, and expiry
                $ingredient->update([
                    'current_stock' => $newTotalStock,
                    'average_cost' => round($newAvgCost, 2),
                    'purchase_cost' => $unitCost,
                    'batch_number' => $item->batch_number ?: $ingredient->batch_number,
                    'expiry_date' => $item->expiry_date ?: $ingredient->expiry_date,
                    'supplier_id' => $purchase->supplier_id ?: $ingredient->supplier_id,
                ]);

                // Record Stock Movement
                StockMovement::create([
                    'user_id' => $purchase->user_id,
                    'stockable_type' => Ingredient::class,
                    'stockable_id' => $ingredient->id,
                    'type' => 'in',
                    'quantity' => $receivedQty,
                    'unit_cost' => $unitCost,
                    'reason' => "Purchase Invoice #{$purchase->invoice_number}",
                    'notes' => $item->batch_number ? "Batch: {$item->batch_number}" : null,
                ]);
            }

            $purchase->update(['status' => 'received']);
        });
    }
}

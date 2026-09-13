<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PosController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->with(['products' => function ($q) {
                $q->where('is_active', true)
                    ->with(['variants' => function ($vq) {
                        $vq->where('is_active', true);
                    }]);
            }])
            ->orderByRaw("name->>'en' ASC")
            ->get();

        $products = ProductVariant::query()
            ->where('is_active', true)
            ->whereHas('product', fn ($q) => $q->where('is_active', true))
            ->with('product.category')
            ->get()
            ->map(function ($variant) {
                $enName = $variant->product->name_translations['en'] ?? $variant->product->name;
                $kmName = $variant->product->name_translations['km'] ?? '';
                $productTitle = ($kmName && $kmName !== $enName) ? "{$enName} ({$kmName})" : $enName;

                return [
                    'id' => $variant->id,
                    'name' => $productTitle.' - '.$variant->name,
                    'product_name' => $productTitle,
                    'variant_name' => $variant->name,
                    'category' => $variant->product->category->name ?? 'Other',
                    'category_id' => $variant->product->category_id,
                    'description' => $variant->product->description_translations['en'] ?? ($variant->product->description['en'] ?? ''),
                    'price' => (float) $variant->price,
                    'stock_quantity' => (float) $variant->stock_quantity,
                    'track_stock' => $variant->track_stock,
                ];
            });

        $categoryNames = $categories->pluck('name')->map(fn ($name) => is_array($name) ? ($name['en'] ?? reset($name)) : $name);

        return view('pos.index', [
            'categories' => $categoryNames->prepend('All items')->values(),
            'products' => $products,
        ]);
    }
}

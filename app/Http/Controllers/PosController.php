<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ProductVariant;
use App\Models\Setting;
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
            ->get()
            ->map(function ($category) {
                $trans = $category->name_translations;
                $nameEn = $trans['en'] ?? (is_string($category->name) ? $category->name : (is_array($category->name) ? reset($category->name) : ''));
                $nameKm = $trans['km'] ?? $nameEn;

                return [
                    'id' => (string) $category->id,
                    'slug' => $category->slug,
                    'name_en' => $nameEn,
                    'name_km' => $nameKm,
                ];
            });

        $products = ProductVariant::query()
            ->where('is_active', true)
            ->whereHas('product', fn ($q) => $q->where('is_active', true))
            ->with('product.category')
            ->get()
            ->map(function ($variant) {
                $prodTrans = $variant->product->name_translations;
                $enProdName = $prodTrans['en'] ?? $variant->product->name;
                $kmProdName = $prodTrans['km'] ?? $enProdName;

                $varTrans = $variant->name_translations;
                $rawVarName = is_string($variant->name) ? $variant->name : ($varTrans['en'] ?? 'Regular');

                $enVarName = match ($rawVarName) {
                    'ធម្មតា' => 'Regular',
                    'ធំ' => 'Large',
                    default => $varTrans['en'] ?? $rawVarName,
                };
                $kmVarName = match ($rawVarName) {
                    'Regular' => 'ធម្មតា',
                    'Large' => 'ធំ',
                    default => $varTrans['km'] ?? ($varTrans['en'] ?? $rawVarName),
                };

                $catTrans = $variant->product->category?->name_translations ?? [];
                $catEn = $catTrans['en'] ?? ($variant->product->category?->name ?? 'Other');
                $catKm = $catTrans['km'] ?? $catEn;

                $descTrans = $variant->product->description_translations ?? [];
                $descEn = $descTrans['en'] ?? ($variant->product->description['en'] ?? '');
                $descKm = $descTrans['km'] ?? ($variant->product->description['km'] ?? $descEn);

                $productTitle = ($kmProdName && $kmProdName !== $enProdName) ? "{$enProdName} ({$kmProdName})" : $enProdName;

                return [
                    'id' => $variant->id,
                    'name' => $productTitle.' - '.$enVarName,
                    'product_name' => $productTitle,
                    'product_name_en' => $enProdName,
                    'product_name_km' => $kmProdName,
                    'variant_name' => $enVarName,
                    'variant_name_en' => $enVarName,
                    'variant_name_km' => $kmVarName,
                    'category' => $catEn,
                    'category_en' => $catEn,
                    'category_km' => $catKm,
                    'category_id' => (string) $variant->product->category_id,
                    'description' => $descEn,
                    'description_en' => $descEn,
                    'description_km' => $descKm,
                    'price' => (float) $variant->price,
                    'stock_quantity' => (float) $variant->stock_quantity,
                    'track_stock' => $variant->track_stock,
                ];
            });

        return view('pos.index', [
            'categories' => $categories,
            'products' => $products,
            'exchange_rate' => (int) Setting::get('exchange_rate_khr', 4100),
            'default_language' => (string) Setting::get('pos_default_language', 'km'),
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        return view('admin.products.index', [
            'products' => Product::query()
                ->with('category', 'variants')
                ->latest()
                ->paginate(12),
            'categories' => Category::query()->orderByRaw("name->>'en' ASC")->get(),
        ]);
    }

    public function create(Request $request): View
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        return view('admin.products.create', [
            'categories' => Category::query()->where('is_active', true)->orderByRaw("name->>'en' ASC")->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name.en' => ['required', 'string', 'max:255'],
            'name.km' => ['nullable', 'string', 'max:255'],
            'description.en' => ['nullable', 'string', 'max:500'],
            'description.km' => ['nullable', 'string', 'max:500'],
        ]);

        Product::create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => true,
        ]);

        return redirect()->route('admin.products.index')->with('status', 'product-created');
    }

    public function edit(Request $request, Product $product): View
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        $product->load('variants', 'category');

        return view('admin.products.edit', [
            'product' => $product,
            'categories' => Category::query()->where('is_active', true)->orderByRaw("name->>'en' ASC")->get(),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name.en' => ['required', 'string', 'max:255'],
            'name.km' => ['nullable', 'string', 'max:255'],
            'description.en' => ['nullable', 'string', 'max:500'],
            'description.km' => ['nullable', 'string', 'max:500'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $product->update([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.products.edit', $product)->with('status', 'product-updated');
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        $product->delete();

        return redirect()->route('admin.products.index')->with('status', 'product-deleted');
    }

    public function storeVariant(Request $request, Product $product): RedirectResponse
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        $validated = $request->validate([
            'name.en' => ['required', 'string', 'max:255'],
            'name.km' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'track_stock' => ['sometimes', 'boolean'],
            'stock_quantity' => ['required_if:track_stock,true', 'numeric', 'min:0'],
        ]);

        $product->variants()->create([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'cost_price' => $validated['cost_price'],
            'track_stock' => $request->boolean('track_stock'),
            'stock_quantity' => $validated['stock_quantity'] ?? 0,
            'is_active' => true,
        ]);

        return redirect()->route('admin.products.edit', $product)->with('status', 'variant-created');
    }

    public function updateVariant(Request $request, Product $product, ProductVariant $variant): RedirectResponse
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        $validated = $request->validate([
            'name.en' => ['required', 'string', 'max:255'],
            'name.km' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'track_stock' => ['sometimes', 'boolean'],
            'stock_quantity' => ['required_if:track_stock,true', 'numeric', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $variant->update([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'cost_price' => $validated['cost_price'],
            'track_stock' => $request->boolean('track_stock'),
            'stock_quantity' => $validated['stock_quantity'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.products.edit', $product)->with('status', 'variant-updated');
    }

    public function destroyVariant(Request $request, Product $product, ProductVariant $variant): RedirectResponse
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        $variant->delete();

        return redirect()->route('admin.products.edit', $product)->with('status', 'variant-deleted');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IngredientController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        return view('admin.ingredients.index', [
            'ingredients' => Ingredient::query()->latest()->paginate(12),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        $validated = $request->validate([
            'name.en' => ['required', 'string', 'max:255'],
            'name.km' => ['nullable', 'string', 'max:255'],
            'unit' => ['required', 'string', 'in:grams,ml,pcs'],
            'current_stock' => ['required', 'numeric', 'min:0'],
            'reorder_level' => ['required', 'numeric', 'min:0'],
        ]);

        Ingredient::create([
            'name' => $validated['name'],
            'unit' => $validated['unit'],
            'current_stock' => $validated['current_stock'],
            'reorder_level' => $validated['reorder_level'],
        ]);

        return redirect()->route('admin.ingredients.index')->with('status', 'ingredient-created');
    }

    public function edit(Request $request, Ingredient $ingredient): View
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        return view('admin.ingredients.edit', [
            'ingredient' => $ingredient,
        ]);
    }

    public function update(Request $request, Ingredient $ingredient): RedirectResponse
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        $validated = $request->validate([
            'name.en' => ['required', 'string', 'max:255'],
            'name.km' => ['nullable', 'string', 'max:255'],
            'unit' => ['required', 'string', 'in:grams,ml,pcs'],
            'current_stock' => ['required', 'numeric', 'min:0'],
            'reorder_level' => ['required', 'numeric', 'min:0'],
        ]);

        $ingredient->update([
            'name' => $validated['name'],
            'unit' => $validated['unit'],
            'current_stock' => $validated['current_stock'],
            'reorder_level' => $validated['reorder_level'],
        ]);

        return redirect()->route('admin.ingredients.index')->with('status', 'ingredient-updated');
    }

    public function destroy(Request $request, Ingredient $ingredient): RedirectResponse
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        $ingredient->delete();

        return redirect()->route('admin.ingredients.index')->with('status', 'ingredient-deleted');
    }
}

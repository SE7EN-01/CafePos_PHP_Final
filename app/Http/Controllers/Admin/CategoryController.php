<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        return view('admin.categories.index', [
            'categories' => Category::query()->withCount('products')->orderByRaw("name->>'en' DESC")->paginate(12),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        $validated = $request->validate([
            'name.en' => ['required', 'string', 'max:255'],
            'name.km' => ['nullable', 'string', 'max:255'],
        ]);

        $slug = Str::slug($validated['name']['en']);

        if (Category::where('slug', $slug)->exists()) {
            $slug .= '-'.uniqid();
        }

        Category::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'is_active' => true,
        ]);

        return redirect()->route('admin.categories.index')->with('status', 'category-created');
    }

    public function edit(Request $request, Category $category): View
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        return view('admin.categories.edit', [
            'category' => $category,
        ]);
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        $validated = $request->validate([
            'name.en' => ['required', 'string', 'max:255'],
            'name.km' => ['nullable', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']['en']),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.categories.index')->with('status', 'category-updated');
    }

    public function destroy(Request $request, Category $category): RedirectResponse
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        $category->delete();

        return redirect()->route('admin.categories.index')->with('status', 'category-deleted');
    }
}

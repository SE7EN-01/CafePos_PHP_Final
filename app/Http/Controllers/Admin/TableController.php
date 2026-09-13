<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CafeTable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TableController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        $query = CafeTable::query()->with('activeOrder.user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('location')) {
            $query->where('location', $request->location);
        }

        $tables = $query->orderBy('name')->paginate(16)->withQueryString();

        $stats = [
            'total' => CafeTable::count(),
            'available' => CafeTable::where('status', 'available')->where('is_active', true)->count(),
            'occupied' => CafeTable::where('status', 'occupied')->count(),
            'reserved' => CafeTable::where('status', 'reserved')->count(),
        ];

        return view('admin.tables.index', [
            'tables' => $tables,
            'stats' => $stats,
            'locations' => CafeTable::whereNotNull('location')->distinct()->pluck('location'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:cafe_tables,name'],
            'capacity' => ['required', 'integer', 'min:1', 'max:50'],
            'location' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'string', 'in:available,occupied,reserved'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        CafeTable::create([
            'name' => $validated['name'],
            'capacity' => $validated['capacity'],
            'location' => $validated['location'] ?? null,
            'status' => $validated['status'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.tables.index')->with('status', 'table-created');
    }

    public function edit(Request $request, CafeTable $table): View
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        return view('admin.tables.edit', [
            'table' => $table,
        ]);
    }

    public function update(Request $request, CafeTable $table): RedirectResponse
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', Rule::unique('cafe_tables', 'name')->ignore($table->id)],
            'capacity' => ['required', 'integer', 'min:1', 'max:50'],
            'location' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'string', 'in:available,occupied,reserved'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $table->update([
            'name' => $validated['name'],
            'capacity' => $validated['capacity'],
            'location' => $validated['location'] ?? null,
            'status' => $validated['status'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.tables.index')->with('status', 'table-updated');
    }

    public function toggleStatus(Request $request, CafeTable $table): RedirectResponse
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:available,occupied,reserved'],
        ]);

        $table->update(['status' => $validated['status']]);

        return back()->with('status', 'table-status-updated');
    }

    public function destroy(Request $request, CafeTable $table): RedirectResponse
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        $table->delete();

        return redirect()->route('admin.tables.index')->with('status', 'table-deleted');
    }
}

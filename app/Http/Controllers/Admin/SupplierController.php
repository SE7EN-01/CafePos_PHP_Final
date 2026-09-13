<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        $suppliers = Supplier::withCount('purchases')
            ->withSum('purchases', 'total_amount')
            ->latest()
            ->paginate(10);

        return view('admin.suppliers.index', [
            'suppliers' => $suppliers,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        Supplier::create($validated);

        return redirect()->route('admin.suppliers.index')->with('status', 'supplier-created');
    }

    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['boolean'],
        ]);

        $supplier->update($validated);

        return redirect()->route('admin.suppliers.index')->with('status', 'supplier-updated');
    }

    public function destroy(Request $request, Supplier $supplier): RedirectResponse
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        $supplier->delete();

        return redirect()->route('admin.suppliers.index')->with('status', 'supplier-deleted');
    }
}

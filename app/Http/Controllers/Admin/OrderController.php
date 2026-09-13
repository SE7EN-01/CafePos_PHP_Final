<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        $query = Order::query()->with('user', 'cafeTable', 'items.productVariant');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('order_number', 'like', "%{$request->search}%");
        }

        return view('admin.orders.index', [
            'orders' => $query->latest()->paginate(15),
        ]);
    }

    public function show(Request $request, Order $order): View
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        $order->load('user', 'cafeTable', 'items.productVariant.product');

        return view('admin.orders.show', [
            'order' => $order,
        ]);
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,preparing,ready,completed,cancelled'],
        ]);

        $order->update(['status' => $validated['status']]);

        if (in_array($validated['status'], ['completed', 'cancelled']) && $order->cafe_table_id) {
            $order->cafeTable?->markAsAvailable();
        }

        return redirect()->route('admin.orders.show', $order)->with('status', 'order-updated');
    }
}

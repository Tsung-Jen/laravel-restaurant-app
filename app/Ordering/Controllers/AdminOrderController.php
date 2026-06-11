<?php

namespace App\Ordering\Controllers;

use App\Ordering\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class AdminOrderController
{
    public function index(Request $request): Response
    {
        $query = Order::withCount('items');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('pickup_date', $request->date);
        }

        return inertia('Admin/Orders/Index', [
            'orders' => $query->latest()->paginate(20),
            'filters' => $request->only(['status', 'date']),
        ]);
    }

    public function show(Order $order): Response
    {
        return inertia('Admin/Orders/Show', [
            'order' => $order->load('items'),
        ]);
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $order->update($validated);

        return redirect()->route('admin.ordering.orders.show', $order)
            ->with('success', __('messages.order_status_updated'));
    }
}

<?php

namespace App\Ordering\Controllers;

use App\Ordering\Models\Category;
use App\Ordering\Models\Order;
use App\Ordering\Requests\StoreOrderRequest;
use App\Ordering\Services\CartService;

class OrderController
{
    public function index()
    {
        $categories = Category::where('is_active', true)
            ->with('activeMenuItems')
            ->orderBy('sort_order')
            ->get();

        $cartCount = app(CartService::class)->count();

        return view('pages.order', [
            'categories' => $categories,
            'cartCount' => $cartCount,
        ]);
    }

    public function store(StoreOrderRequest $request, CartService $cart)
    {
        $order = Order::create([
            'pickup_date' => $request->pickup_date,
            'pickup_time' => $request->pickup_time,
            'phone' => $request->phone,
            'payment_method' => $request->payment_method,
            'subtotal' => $cart->total(),
            'status' => 'pending',
        ]);

        foreach ($cart->items() as $item) {
            $order->items()->create([
                'menu_item_id' => $item['menu_item_id'],
                'item_name' => $item['item_name'],
                'item_number' => $item['item_number'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
            ]);
        }

        $cart->clear();

        return redirect()->route('order.index')
            ->with('success', __('messages.order_placed'));
    }
}

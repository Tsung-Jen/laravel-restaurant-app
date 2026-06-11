<?php

namespace App\Ordering\Controllers;

use App\Ordering\Models\MenuItem;
use App\Ordering\Services\CartService;
use Illuminate\Http\Request;

class CartController
{
    public function show(CartService $cart)
    {
        return view('pages.cart', [
            'cartItems' => $cart->items(),
            'cartTotal' => $cart->total(),
            'cartCount' => $cart->count(),
            'cartEmpty' => $cart->isEmpty(),
            'minDate' => now()->format('Y-m-d'),
            'maxDate' => now()->addWeeks(2)->format('Y-m-d'),
        ]);
    }

    public function add(Request $request, CartService $cart)
    {
        $data = $request->validate([
            'item_id' => 'required|exists:menu_items,id',
            'quantity' => 'integer|min:1|max:99',
        ]);

        $item = MenuItem::findOrFail($data['item_id']);
        $cart->add($item, $data['quantity'] ?? 1);

        if ($request->wantsJson()) {
            return response()->json([
                'count' => $cart->count(),
                'total' => $cart->total(),
                'message' => __('messages.cart_added', ['item' => $item->name]),
            ]);
        }

        return redirect()->back()->with('success', __('messages.cart_added', ['item' => $item->name]));
    }

    public function update(Request $request, CartService $cart)
    {
        $data = $request->validate([
            'item_id' => 'required|integer',
            'quantity' => 'required|integer|min:0|max:99',
        ]);

        if ($data['quantity'] < 1) {
            $cart->remove($data['item_id']);
        } else {
            $cart->update($data['item_id'], $data['quantity']);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'count' => $cart->count(),
                'total' => $cart->total(),
                'items' => $cart->items(),
            ]);
        }

        return redirect()->back();
    }

    public function remove(Request $request, CartService $cart)
    {
        $data = $request->validate(['item_id' => 'required|integer']);
        $cart->remove($data['item_id']);

        if ($request->wantsJson()) {
            return response()->json([
                'count' => $cart->count(),
                'total' => $cart->total(),
                'items' => $cart->items(),
            ]);
        }

        return redirect()->back();
    }

    public function clear(CartService $cart)
    {
        $cart->clear();

        return redirect()->back()->with('success', __('messages.cart_cleared'));
    }
}

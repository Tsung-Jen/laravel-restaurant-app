<?php

namespace App\Ordering\Services;

use App\Ordering\Models\MenuItem;

class CartService
{
    private const SESSION_KEY = 'ordering_cart';

    public function add(MenuItem $item, int $quantity = 1): void
    {
        $cart = $this->getCart();
        $id = $item->id;

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $cart[$id] = [
                'menu_item_id' => $item->id,
                'item_name' => $item->name,
                'item_number' => $item->item_number,
                'price' => $item->price,
                'quantity' => $quantity,
            ];
        }

        session()->put(self::SESSION_KEY, $cart);
    }

    public function update(int $itemId, int $quantity): void
    {
        $cart = $this->getCart();

        if ($quantity < 1) {
            $this->remove($itemId);

            return;
        }

        if (isset($cart[$itemId])) {
            $cart[$itemId]['quantity'] = $quantity;
            session()->put(self::SESSION_KEY, $cart);
        }
    }

    public function remove(int $itemId): void
    {
        $cart = $this->getCart();
        unset($cart[$itemId]);
        session()->put(self::SESSION_KEY, $cart);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    public function items(): array
    {
        return array_values($this->getCart());
    }

    public function count(): int
    {
        return array_sum(array_column($this->getCart(), 'quantity'));
    }

    public function total(): float
    {
        return array_sum(array_map(fn ($item) => $item['price'] * $item['quantity'], $this->getCart()));
    }

    public function isEmpty(): bool
    {
        return empty($this->getCart());
    }

    public function getCart(): array
    {
        return session(self::SESSION_KEY, []);
    }
}

<?php

namespace App\Services;

use App\Models\Cart;

class CartService
{
    public function addProduct(string $sessionId, int $productId, int $quantity = 1): void
    {
        $cart = Cart::firstOrCreate(['session_id' => $sessionId]);
        $item = $cart->items()->firstOrCreate(
            ['product_id' => $productId],
            ['quantity' => 0]
        );
        $item->increment('quantity', $quantity);
    }

    public function getTotal(string $sessionId, float $vat = 0): float
    {
        $cart = Cart::where('session_id', $sessionId)->with('items.product')->firstOrFail();
        $total = $cart->items->sum(fn($item) => $item->quantity * $item->product->price);

        return $total * (1 + $vat);
    }
}

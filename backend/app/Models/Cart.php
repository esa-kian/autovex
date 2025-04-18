<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'session_id',
        'user_id',
    ];

    /**
     * Get the items in the cart.
     */
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the products in the cart through the cart items.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'cart_items')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    /**
     * Calculate the total price of all items in the cart excluding VAT.
     *
     * @return float
     */
    public function getTotalPriceAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
    }

    /**
     * Calculate the total price including VAT.
     *
     * @param float $vatRate The VAT rate as a percentage (e.g., 21 for 21%)
     * @return float
     */
    public function getTotalPriceWithVat($vatRate = 21)
    {
        $total = $this->total_price;
        return $total * (1 + ($vatRate / 100));
    }

    /**
     * Add a product to the cart.
     *
     * @param int $productId
     * @param int $quantity
     * @return CartItem
     */
    public function addProduct($productId, $quantity = 1)
    {
        $cartItem = $this->items()->where('product_id', $productId)->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            $cartItem = new CartItem([
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
            $this->items()->save($cartItem);
        }

        return $cartItem;
    }
}
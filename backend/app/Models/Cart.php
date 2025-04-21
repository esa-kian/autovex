<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use InvalidArgumentException;

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
    ];

    /**
     * Get the items in the cart.
     */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class)->with('product');
    }

    /**
     * Get the products in the cart through the cart items.
     */
    public function products(): BelongsToMany
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
    public function getTotalPriceAttribute(): float
    {
        return $this->items->sum(function ($item) {
            return optional($item->product)->price * $item->quantity;
        });
    }

    /**
     * Calculate the total price including VAT.
     *
     * @param float $vatRate The VAT rate as a percentage (e.g., 21 for 21%)
     * @return float
     */
    public function getTotalPriceWithVat($vatRate = 21): float
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
    public function addProduct($productId, $quantity = 1): CartItem
    {
        $quantity = (int) $quantity;

        // validations to check products in db!

        if ($quantity <= 0) {
            throw new InvalidArgumentException("Quantity must be greater than 0.");
        }

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

<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup test products
     */
    private function setupProducts()
    {
        return [
            Product::create([
                'title' => 'Test Product 1',
                'sku' => 'TEST-001',
                'description' => 'Test product description 1',
                'price' => 100.00,
            ]),
            Product::create([
                'title' => 'Test Product 2',
                'sku' => 'TEST-002',
                'description' => 'Test product description 2',
                'price' => 200.00,
            ]),
            Product::create([
                'title' => 'Test Product 3',
                'sku' => 'TEST-003',
                'description' => 'Test product description 3',
                'price' => 300.00,
            ]),
            Product::create([
                'title' => 'Test Product 4',
                'sku' => 'TEST-004',
                'description' => 'Test product description 4',
                'price' => 400.00,
            ]),
            Product::create([
                'title' => 'Test Product 5',
                'sku' => 'TEST-005',
                'description' => 'Test product description 5',
                'price' => 500.00,
            ]),
        ];
    }

    /**
     * Test adding a single product to the cart.
     */
    public function test_add_single_product_to_cart(): void
    {
        $products = $this->setupProducts();
        $cart = Cart::create(['session_id' => 'test-session-1']);

        $product = $products[0];
        $cart->addProduct($product->id);

        $this->assertEquals(1, $cart->items->count());
        $this->assertEquals($product->id, $cart->items->first()->product_id);
        $this->assertEquals(1, $cart->items->first()->quantity);
    }

    /**
     * Test adding multiple products to the cart.
     */
    public function test_add_multiple_products_to_cart(): void
    {
        $products = $this->setupProducts();
        $cart = Cart::create(['session_id' => 'test-session-2']);

        $cart->addProduct($products[0]->id);
        $cart->addProduct($products[1]->id, 2);
        $cart->addProduct($products[2]->id, 3);

        $this->assertEquals(3, $cart->items->count());
        $this->assertEquals(6, $cart->items->sum('quantity')); // 1 + 2 + 3 = 6 items total
    }

    /**
     * Test adding the same product multiple times updates quantity.
     */
    public function test_add_same_product_updates_quantity(): void
    {
        $products = $this->setupProducts();
        $cart = Cart::create(['session_id' => 'test-session-3']);

        $product = $products[0];
        $cart->addProduct($product->id);
        $cart->addProduct($product->id, 2);

        $this->assertEquals(1, $cart->items->count()); // Still only one unique product
        $this->assertEquals(3, $cart->items->first()->quantity); // But quantity is 3
    }

    /**
     * Test calculating total price for 1 product (no VAT).
     */
    public function test_calculate_total_price_one_product(): void
    {
        $products = $this->setupProducts();
        $cart = Cart::create(['session_id' => 'test-session-4']);

        $product = $products[0]; // Price: 100.00
        $cart->addProduct($product->id);

        $expectedTotal = 100.00;
        $this->assertEquals($expectedTotal, $cart->total_price);
    }

    /**
     * Test calculating total price for 3 products (no VAT).
     */
    public function test_calculate_total_price_three_products(): void
    {
        $products = $this->setupProducts();
        $cart = Cart::create(['session_id' => 'test-session-5']);

        $cart->addProduct($products[0]->id); // Price: 100.00
        $cart->addProduct($products[1]->id); // Price: 200.00
        $cart->addProduct($products[2]->id); // Price: 300.00

        $expectedTotal = 600.00; // 100 + 200 + 300 = 600
        $this->assertEquals($expectedTotal, $cart->total_price);
    }

    /**
     * Test calculating total price for 5 products (no VAT).
     */
    public function test_calculate_total_price_five_products(): void
    {
        $products = $this->setupProducts();
        $cart = Cart::create(['session_id' => 'test-session-6']);

        $cart->addProduct($products[0]->id); // Price: 100.00
        $cart->addProduct($products[1]->id); // Price: 200.00
        $cart->addProduct($products[2]->id); // Price: 300.00
        $cart->addProduct($products[3]->id); // Price: 400.00
        $cart->addProduct($products[4]->id); // Price: 500.00

        $expectedTotal = 1500.00; // 100 + 200 + 300 + 400 + 500 = 1500
        $this->assertEquals($expectedTotal, $cart->total_price);
    }

    /**
     * Test calculating total price with VAT for 1 product.
     */
    public function test_calculate_total_price_with_vat_one_product(): void
    {
        $products = $this->setupProducts();
        $cart = Cart::create(['session_id' => 'test-session-7']);

        $product = $products[0]; // Price: 100.00
        $cart->addProduct($product->id);

        $this->assertEquals(121.00, $cart->getTotalPriceWithVat(21)); // 21% VAT
        $this->assertEquals(105.00, $cart->getTotalPriceWithVat(5));  // 5% VAT 
        $this->assertEquals(103.00, $cart->getTotalPriceWithVat(3));  // 3% VAT
    }

    /**
     * Test calculating total price with VAT for 3 products.
     */
    public function test_calculate_total_price_with_vat_three_products(): void
    {
        $products = $this->setupProducts();
        $cart = Cart::create(['session_id' => 'test-session-8']);

        $cart->addProduct($products[0]->id); // Price: 100.00
        $cart->addProduct($products[1]->id); // Price: 200.00
        $cart->addProduct($products[2]->id); // Price: 300.00

        $this->assertEquals(726.00, $cart->getTotalPriceWithVat(21)); // 21% VAT
        $this->assertEquals(630.00, $cart->getTotalPriceWithVat(5));  // 5% VAT
        $this->assertEquals(618.00, $cart->getTotalPriceWithVat(3));  // 3% VAT
    }

    /**
     * Test calculating total price with VAT for 5 products.
     */
    public function test_calculate_total_price_with_vat_five_products(): void
    {
        $products = $this->setupProducts();
        $cart = Cart::create(['session_id' => 'test-session-9']);

        $cart->addProduct($products[0]->id); // Price: 100.00
        $cart->addProduct($products[1]->id); // Price: 200.00
        $cart->addProduct($products[2]->id); // Price: 300.00
        $cart->addProduct($products[3]->id); // Price: 400.00
        $cart->addProduct($products[4]->id); // Price: 500.00

        $this->assertEquals(1815.00, $cart->getTotalPriceWithVat(21)); // 21% VAT
        $this->assertEquals(1575.00, $cart->getTotalPriceWithVat(5));  // 5% VAT
        $this->assertEquals(1545.00, $cart->getTotalPriceWithVat(3));  // 3% VAT
    }
}
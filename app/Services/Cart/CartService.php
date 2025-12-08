<?php

namespace App\Services\Cart;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function App\Helpers\ensureStock;
use function App\Helpers\findCartItem;
use function App\Helpers\getUserCart;

class CartService
{
    public function getCart()
    {
        return getUserCart();
    }

    public function getCartItems(): Collection
    {
        $cart = $this->getCart();
        return $cart->items()->with('product')->get();
    }

    public function getCartTotal(): float
    {
        return $this->getCartItems()->sum(fn($item) =>
            $item->product->price * $item->quantity
        );
    }

    public function addItem(Product $product, array $data): CartItem
    {
        $cart = $this->getCart();
        $quantity = $data['quantity'];

        $cartItem = findCartItem($cart , $product , $data);

        // Update existing item
        if ($cartItem) {
            $newQty = $cartItem->quantity + $quantity;

            ensureStock($product, $newQty);

            $cartItem->update(['quantity' => $newQty]);
            return $cartItem;
        }

        // Create new item
        ensureStock($product, $quantity);

        return $cart->items()->create([
            'product_id' => $product->id,
            'quantity'   => $quantity,
            'size'       => $data['size'],
            'color'      => $data['color']
        ]);
    }

    public function updateItem(Product $product, array $data)
    {
        $cart = $this->getCart();
        $cartItem = $cart->items()->where('product_id', $product->id)->firstOrFail();

        $quantity = $data['quantity'];

        // Remove item if quantity < 1
        if ($quantity < 1) {
            $cartItem->delete();
            return ['deleted' => true];
        }

        ensureStock($product, $quantity);

        $cartItem->update([
            'quantity' => $quantity,
            'size'     => $data['size'],
            'color'    => $data['color']
        ]);

        return $cartItem;
    }

    public function removeItem(CartItem $item): void
    {
        $item->delete();
    }

    public function clearCart(): void
    {
        $cart = $this->getCart();
        $cart->items()->delete();
    }


   

    
}

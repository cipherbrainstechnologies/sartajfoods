<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Cart;
use App\Model\Product;

class CartController extends Controller
{
    public function listCarts()
    {
        // Retrieve the authenticated user
        $user = auth()->user();

        // Fetch cart products for the authenticated user
        $cartProducts = Cart::with('product')->where('user_id', $user->id)->get();

        return response()->json(['user' => $user, 'cartProducts' => $cartProducts]);
    }

    public function addToCart(Request $request)
    {
        
        $user = auth()->user();

        // Validate the request
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $productId = $request->product_id;
        $quantity = $request->quantity;

        // Check if the product exists
        $product = Product::find($productId);
        
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Add the product to the user's cart
        $cart = Cart::updateOrCreate(
            [
                'user_id' => $user->id,
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => ($product->price * $quantity)
            ],
        );

        return response()->json(['message' => 'Product added to cart', 'cart' => $cart]);
    }

    public function updateToCart( Request $request)
    {
        $user = auth()->user();

        // Validate the request
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required|integer|min:1',
        ]);
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        // Find the cart entry by id
        $cart = Cart::where(['user_id'=>  $user->id,"product_id" => $productId])->first();

        // Check if the cart entry exists
        if (!$cart) {
            return response()->json(['error' => 'Cart entry not found'], 404);
        }

        // Update the quantity
        $cart->update(['quantity' => $quantity]);

        return response()->json(['message' => 'Cart entry updated', 'cart' => $cart]);
    }

    public function removeToCart($productId,Request $request)
    {        

        $user = auth()->user();

        // Find the cart entry by id
        $cart =  Cart::where(['user_id'=>  $user->id,"product_id" => $productId])->first();
        // Check if the cart entry exists
        if (!$cart) {
            return response()->json(['error' => 'Cart entry not found'], 404);
        }

        // Delete the cart entry
        $cart->delete();

        return response()->json(['message' => 'Product removed from cart']);
    }

    public function clearCart(Request $request)
    {
        $user = auth()->user();
        $cart =   Cart::where('user_id', $user->id)->delete();

        return response()->json(['message' => 'Product removed from cart']);
    }
}

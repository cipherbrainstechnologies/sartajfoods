<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Cart;
use App\Model\Product;
use App\CentralLogics\Helpers;


class CartController extends Controller
{
    public function listCarts()
    {
        // Retrieve the authenticated user
        $user = auth()->user();
        // Fetch cart products for the authenticated user
        $cartProducts = Cart::with('product.rating')->where('user_id', $user->id)->get();
        $deliveryCharge = !empty(Helpers::get_business_settings('delivery_charge'))
                                    ? Helpers::get_business_settings('delivery_charge') : 0;

        $SubTotalAmt =  Cart::with('product')->where('user_id', $user->id)->sum('sub_total'); 
        $totalAmt = round($SubTotalAmt + $deliveryCharge,2);
        
        return response()->json(['user' => $user, 'cartProducts' => $cartProducts,'delivery_charge' =>$deliveryCharge,'total_sub_amt' => $SubTotalAmt,'total_amt' => $totalAmt]);
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

    public function addCartItems(Request $request){
        if(!empty($request->cart)){
            $user = auth()->user(); 
            $discount = 0;
            $discount_type = "amount";
            $subTotal = 0;
            foreach ($request->cart as $key => $data) {
                $productSalePrice = 0; 
                $product = Product::find($data['product_id']);
            
                if (!$product) {
                    return response()->json(['error' => 'Product not found'], 404);
                }

                if($data['qty'] > $product->maximum_order_quantity ){
                    return response()->json(['error' => 'maximum order quantity is '.$product->maximum_order_quantity]);
                }

                if(!empty($product->sale_price)){
                    $currentDate = new DateTime(); // Current date and time
                    $saleStartDate = new DateTime($product->sale_start_date);
                    $saleEndDate = new DateTime($product->sale_end_date);
                    if($currentDate >= $saleStartDate && $currentDate <= $saleEndDate){
                        $productPrice = $product->sale_price;
                        $discount = 0;
                        $subTotal = $product->sale_price * $data['qty'];
                    }
                    
                }else{
                    if($product->discount_type ="percent"){
                        $discount = ((($product->price * $product->discount) / 100) * $data['qty']);
                        $subTotal = (($product->price *  $data['qty']) - $discount);

                    }else{
                        
                        $discount = $product->discount;
                        $subTotal = (($product->price *  $data['qty']) - $discount);
                    }
                }
                
                $cart = Cart::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'product_id' => $data['product_id'],
                        'quantity' => $data['qty'],
                        'price' => (isset($productSalePrice) && !empty($productSalePrice)) ? 0 : $product->price,
                        'special_price' =>  (isset($productSalePrice) && !empty($productSalePrice)) ? $productSalePrice : '0',
                        'discount_type' => $discount_type,
                        'discount'      => $discount,
                        'sub_total'     => $subTotal
                    ],
                );
            }
        return response()->json(['message' => 'Product added to cart']);
        }
        
    }
}

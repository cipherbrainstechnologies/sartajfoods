<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Cart;
use App\Model\Product;
use App\CentralLogics\Helpers;
use App\Model\Regions;
use DateTime;


class CartController extends Controller
{
    public function listCarts(Request $request)
    {
        // Retrieve the authenticated user
        $region_id = !empty($request->region_id) ? $request->region_id : 1;
        $user = auth()->user();
        $eight_percent = 0;
        $ten_percent = 0;
        $FrozenWeight = 0;
        $DryProductAmount = 0;
        $deliveryCharge= 0;

        $regionDetails = Regions::find($region_id);

        // if(empty($regionDetails)){
        //     return response()->json(['errors' => 'no any regions found'], 403);
        // }

        // Fetch cart products for the authenticated user
        $cartProducts = Cart::with('product.rating')->where('user_id', $user->id)->get();
        
        $cartProducts->map(function ($cartProduct) use($eight_percent,$ten_percent,$FrozenWeight,$DryProductAmount){
            $cartData = $cartProduct;
            $product = $cartProduct->product; 
            
            if($cartProduct->product['product_type'] == 1){
                $FrozenWeight = Helpers::calculateWeight($cartProduct->product['weight'],$cartProduct->quantity,$cartProduct->product['weight_class']);
            }

            if($cartProduct->product['product_type'] != 1){
                $DryProductAmount = $cartProduct->product['actual_price'] * $cartProduct->quantity;
            }


            if($cartProduct->product['tax'] == 8){
                if(!empty($product->sale_price) && $product->sale_start_date <= now() && $product->sale_end_date >= now()){
                    $eight_percent += ((($product->actual_price * $cartProduct->product['tax']) / 100) * $cartProduct->quantity); 
                }else{
                    if($cartProduct->product['discount_type'] == "percent"){
                        $discount_price = Helpers::afterDiscountPrice($product,$product->actual_price);
                        $eight_percent += (((($product->actual_price - $discount_price['discount_amount']) * $cartProduct->product['tax']) / 100) * $cartProduct->quantity);   
                    }else{
                        $discount_price = Helpers::afterDiscountPrice($product,$product->actual_price);
                        if($product['discount_type']=="percent"){
                            $eight_percent += ((($discount_price['discount_amount'] * $cartProduct->product['tax']) / 100) * $cartProduct->quantity);   
                        }
                        if($product['discount_type']=="amount"){
                            $eight_percent += (((($product->actual_price -$discount_price['discount_amount']) * $cartProduct->product['tax']) / 100) * $cartProduct->quantity);   
                        }
                        
                    }
                   
                }
                
            }

            if($cartProduct->product['tax'] == 10){
                if(!empty($product->sale_price) && $product->sale_start_date <= now() && $product->sale_end_date >= now()){
                    $ten_percent += ((($product->actual_price * $cartProduct->product['tax']) / 100) * $cartProduct->quantity);   
                }else{
                    $discount_price = Helpers::afterDiscountPrice($product,$product->actual_price);
                    if($product['discount_type']=="percent"){
                        $ten_percent += ((($discount_price['discount_amount'] * $cartProduct->product['tax']) / 100) * $cartProduct->quantity);   
                    }
                    if($product['discount_type']=="amount"){
                        $ten_percent += (((($product->actual_price -$discount_price['discount_amount']) * $cartProduct->product['tax']) / 100) * $cartProduct->quantity);   
                    }
                } 
                
            }
            
            // Calculate overall rating
            $allOverRating = $product->rating->isNotEmpty()
                ? ($product->rating[0]->total / ($product->rating[0]->count * 5)) * 100
                : 0;

            // Add the overall_rating and total_reviews to each product
            $product->overall_rating = $allOverRating;
            $product->total_reviews = $product->rating->isNotEmpty()
                ? $product->rating[0]->count
                : 0;

            // Check if image is an array before using map function
            if (is_string($product->image)) {
                // Decode the JSON string to an array
                $imageArray = json_decode($product->image, true);
            
                // Check if decoding was successful and if the result is an array
                if (is_array($imageArray)) {
                    // Use array_map to create image URLs
                    $product->image = array_map(function ($imageName) {
                        return asset("storage/product/{$imageName}");
                    }, $imageArray);
                }
            }
            cart::where('id',$cartProduct->id)->update([
                "dry_product_amount" => $DryProductAmount,
                "frozen_weight"     =>$FrozenWeight
            ]);
            $cartData->dry_product_amount = $DryProductAmount;
            $cartData->frozen_weight = $FrozenWeight;
            $product->tax_eight_percent= $eight_percent;
            $product->tax_ten_percent = $ten_percent;
            
            return $cartProduct;
        });
        
        $totalFrozenWeight = ($cartProducts->sum('frozen_weight')/1000) ?? 0;
        $totalDryProductAmount = $cartProducts->sum('dry_product_amount');
        $totalDiscountAmount = $cartProducts->sum('total_discount');
        $totalEightPercentTax = $cartProducts->sum('eight_percent');
        $totalTenPercentTax = $cartProducts->sum('ten_percent');
        $subTotalAmt = $cartProducts->sum('sub_total');
        // if($subTotalAmt > 0 && $subTotalAmt < $regionDetails->maximum_order_amt){
        //     $deliveryCharge += $regionDetails->dry_delivery_charge;
        // }
        // if($totalFrozenWeight > 0 && $totalFrozenWeight < $regionDetails->frozen_weight){
            
        //     $deliveryCharge += $regionDetails->frozen_delivery_charge;
        // }
        if ($subTotalAmt > 0 && $subTotalAmt < $regionDetails->maximum_order_amt && $totalFrozenWeight > 0 && $totalFrozenWeight < $regionDetails->frozen_weight) {
            $deliveryCharge += $regionDetails->frozen_delivery_charge;
            if($totalDryProductAmount>0){
                 $deliveryCharge += $regionDetails->dry_delivery_charge;
            }
        }elseif($totalFrozenWeight > 0 && $totalFrozenWeight < $regionDetails->frozen_weight){
            
            $deliveryCharge += $regionDetails->frozen_delivery_charge;
        }elseif($subTotalAmt > 0 && $subTotalAmt < $regionDetails->maximum_order_amt){
            $deliveryCharge += $regionDetails->dry_delivery_charge;
        }
        
        // $deliveryCharge = Helpers::get_business_settings('delivery_charge', 0);
        // echo 'subTotal:'.$subTotalAmt . ' '.'deliveryCharge:'.$deliveryCharge.' '.$totalEightPercentTax.' '.'totalTenPercentTax'.$totalTenPercentTax;
        $totalAmt = $subTotalAmt + $deliveryCharge + round($totalEightPercentTax) + round($totalTenPercentTax) ;
        // Round Value
        $roundedFraction = round($totalAmt - floor($totalAmt), 2);
        if ($roundedFraction > 0.50) {
            // If yes, add 1
            $totalAmt = ceil($totalAmt);
        } elseif ($roundedFraction < 0.50) {
            // If no, subtract 1
            $totalAmt = floor($totalAmt);
        }
        $min_amount =  Helpers::get_business_settings('minimum_amount_for_cod_order');
        $max_amount =  Helpers::get_business_settings('maximum_amount_for_cod_order');
        return response()->json([
            'user' => $user,
            'cartProducts' => $cartProducts,
            'delivery_charge' => $deliveryCharge,
            'total_sub_amt' => round($subTotalAmt),
            'total_amt' => round($totalAmt),
            'eight_percent' => round($totalEightPercentTax),
            'ten_percent' => round($totalTenPercentTax),
            'totalDiscountAmount' => round($totalDiscountAmount),
            'minOrderAmount' => (Helpers::get_business_settings('minimum_amount_for_cod_order_status') == 1 && ( $totalAmt < $min_amount)) ? $min_amount : null,
            'maxOrderAmount' => (Helpers::get_business_settings('maximum_amount_for_cod_order_status') == 1 && ( $totalAmt < $max_amount)) ? $max_amount : null,
        ]);
    }
    
    public function addToCart(Request $request)
    {
        $user = auth()->user();
        $cart = [];
        $discount_type = "amount";
        $discount = 0;
        $subTotal = 0;
        // Validate the request
        // $request->validate([
        //     'product_id' => 'required|exists:products,id',
        //     'quantity' => 'required|integer|min:1',
        // ]);
        
        $productId = $request->product_id;
        $quantity = $request->quantity;
        $eight_percent = 0;
        $ten_percent = 0;

        // Check if the product exists
        $product = Product::find($productId);
        
        if($product->maximum_order_quantity >= $request->quantity){
            $quantity = $request->quantity;
        }elseif($product->stock < $product->maximum_order_quantity){
            $quantity = $product->stock;
        }else{
            $quantity = $product->maximum_order_quantity;
        }
        
        if($request->quantity > $product->maximum_order_quantity  && $product->maximum_order_quantity < $product->total_stock){
            return response()->json(['errors' => 'maximum order qty is '.$product->maximum_order_quantity], 403);
        }

        if($request->quantity > $product->total_stock){
            return response()->json(['errors' => 'maximum order qty is '.$product->total_stock], 403);   
        }

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        $discount_price = Helpers::afterDiscountPrice($product,$product->actual_price);
        // $productPrice = $product->price - $discount_price['discount_amount'];
        
        $discountPrice = $discount_price['discount_amount'];
        if(!empty($product->hotDeal) &&  $product->hotDeal['start_date'] <= now() && $product->hotDeal['end_date'] >= now()){
            $discount_type = "hot-deal";
            $productPrice = $product->actual_price;
            $discount = 0;
            $subTotal =  $subTotal + $product->actual_price * $quantity;
        }elseif(!empty($product->sale_price)){
            $currentDate = new DateTime(); // Current date and time

            $saleStartDate = new DateTime($product->sale_start_date);
            $saleEndDate = new DateTime($product->sale_end_date);
            
            if($currentDate >= $saleStartDate && $currentDate <= $saleEndDate){
                $discount_type = "special";
                $productPrice = $product->actual_price;
                $discount = 0;
                $subTotal =  $subTotal + $product->actual_price * $quantity;
            }else{
                if($product->discount_type =="percent"){
                    $discount_type = 'percent';
                    $productPrice = $product->actual_price - $discount_price['discount_amount'];
                    $discount = $discount_price['discount_amount'] * $quantity;
                    $subTotal =  $subTotal + (($productPrice  *  $quantity) );
                }else{
                    $discount_type = 'amount';
                    $productPrice = $product->actual_price - $discount_price['discount_amount'];
                    $discountPrice = $product->discount;
                    $discount = $product->discount;
                    $subTotal =   $subTotal  + (($productPrice  *  $quantity) );
                }
            }
            
        }else{
            if($product->discount_type =="percent"){
                $discount_type = 'percent';
                $productPrice = $product->actual_price - $discount_price['discount_amount'];
                $discount = $discount_price['discount_amount'] * $quantity;
                $subTotal =  $subTotal + (($productPrice  *  $quantity) );

            }else{
                $discount_type = 'amount';
                $productPrice = $product->actual_price - $discount_price['discount_amount'];
                $discountPrice = $product->discount;
                $discount = $product->discount;
                $subTotal =   $subTotal  + (($productPrice  *  $quantity) );
            }
        }
        
        if($product->tax == 8){

            if(!empty($product->sale_price) && $product->sale_start_date <= now() && $product->sale_end_date >= now()){
                $eight_percent += ((($product->actual_price * $product->tax) / 100) * $quantity); 
            }else{
                $discount_price = Helpers::afterDiscountPrice($product,$product->actual_price);
                $eight_percent += (((($product->actual_price - $discount_price['discount_amount']) * $product->tax) / 100) * $quantity);      
            }
       
        }
        if($product->tax == 10){
            if(!empty($product->sale_price) && $product->sale_start_date <= now() && $product->sale_end_date >= now()){
                $ten_percent += ((($product->actual_price * $product->tax) / 100) * $quantity);   
            }else{
                $discount_price = Helpers::afterDiscountPrice($product,$product->actual_price);
                $ten_percent += (((($product->actual_price - $discount_price['discount_amount']) * $product->tax) / 100) * $quantity);   
            }
            
        }

        if(Cart::where(['product_id' => $product->id, 'user_id' => $user->id])->exists()){
            $cartData = Cart::where(['product_id' => $product->id, 'user_id' => $user->id])->first();

            if($request->quantity > $product->maximum_order_quantity && $product->maximum_order_quantity < $product->total_stock){
                return response()->json(['errors' => 'maximum order qty is '.$product->maximum_order_quantity], 403);
            }

            if(($cartData->$quantity + $request->quantity) > $product->total_stock){
                return response()->json(['errors' => 'maximum order qty is '.$product->total_stock], 403);
            }

            $cart =  Cart::where(['product_id' => $product->id, 'user_id' => $user->id])
                            ->update(
                                [

                                    'quantity' => $quantity,
                                    'price' => $productPrice ,
                                    // 'special_price' =>  (isset($productSalePrice) && !empty($productSalePrice)) ? $productSalePrice : '0',
                                    'discount_type' => $discount_type,
                                    'discount'      => $discountPrice,
                                    'total_discount' => $discount,
                                    'sub_total'     => $subTotal ,
                                    'eight_percent' => $eight_percent,
                                    'ten_percent' => $ten_percent
                                ]
                            );
        }else{
            $cart = Cart::Create(
                [
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $productPrice,
                    'eight_percent' => $eight_percent,
                    'ten_percent' => $ten_percent,
                    // 'special_price' =>  (isset($productSalePrice) && !empty($productSalePrice)) ? $productSalePrice : '0',
                    'discount_type' => $discount_type,
                    'discount'      => $discountPrice,
                    'total_discount' => $discount,
                    'sub_total'     =>  $subTotal 
                ],
            );
        }       

        return response()->json(['status' => 200, 'message' => 'Product added to cart', 'cart' => $cart]);
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

        

        $eight_percent = 0;
        $ten_percent = 0;

        // Find the cart entry by id
        $cart = Cart::with('product')->where(['user_id'=>  $user->id,"product_id" => $productId])->first();

        if($request->input('quantity') > $cart->product['maximum_order_quantity'] && $cart->product['maximum_order_quantity'] < $cart->product['total_stock']){
            return response()->json(['errors' => 'maximum order qty is '.$cart->product['maximum_order_quantity']], 403);
        }
        
        if(($request->input('quantity')) > $cart->product['total_stock']){
            return response()->json(['errors' => 'maximum order qty is '.$cart->product['total_stock']], 403);
        }

        // Check if the cart entry exists
        if (!$cart) {
            return response()->json(['error' => 'Cart list is empty'], 404);
        }
        
        if($cart->product['tax'] == 8){
            if(!empty($cart->product['sale_price']) && $cart->product['sale_start_date'] <= now() && $cart->product['sale_end_date'] >= now()){
                $eight_percent += ((($cart->product['actual_price'] * $cart->product['tax']) / 100) *  $quantity);   
            }else{
                $product = collect($cart->product);
                $discount_price = Helpers::afterDiscountPrice($product,$product['actual_price']);                
                $eight_percent += (((($cart->product['actual_price'] - $discount_price['discount_amount']) * $cart->product['tax']) / 100) * $quantity);      
            }
       
        }
        if($cart->product['tax'] == 10){
            if(!empty($cart->product['sale_price']) && $cart->product['sale_start_date'] <= now() && $cart->product['sale_end_date'] >= now()){
                $ten_percent += ((($cart->product['actual_price'] * $cart->product['tax']) / 100) *  $quantity);   
            }else{
                $product = collect($cart->product);
                $discount_price = Helpers::afterDiscountPrice($product,$product['actual_price']);
                $ten_percent += (((($cart->product['actual_price'] - $discount_price['discount_amount']) * $cart->product['tax']) / 100) * $quantity);      
            }
       
        }
        $totalDiscount = round(($cart->discount *  $quantity));
        $subtotal = round(($cart->price * $quantity));

        // Update the quantity
        $cart->update([
                'total_discount'=>$totalDiscount,
                'sub_total'=>$subtotal,
                'quantity' => $quantity,
                'eight_percent' => $eight_percent,
                'ten_percent' => $ten_percent
            ]);

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
           
           
            foreach ($request->cart as $key => $data) {
                $subTotal = 0;
                $productSalePrice = 0; 
                $specialPrice = 0;
                $eight_percent = 0;
                $ten_percent = 0;
                $product = Product::find($data['product_id']);
            
                if (!$product) {
                    return response()->json(['error' => 'Product not found'], 404);
                }

                // if($data['qty'] > $product->maximum_order_quantity ){
                //     return response()->json(['status' => 403, 'error' => 'maximum order quantity is '.$product->maximum_order_quantity]);
                // }

                // add from date and end date condition
                if(!empty($product->hotDeal) &&  $product->hotDeal['start_date'] <= now() && $product->hotDeal['end_date'] >= now()){
                    $discount_type = "hot-deal";
                    $productPrice = $product->actual_price;
                    $discount = 0;
                    $subTotal =  $subTotal + $product->actual_price * $data['qty'];
                }elseif(!empty($product->sale_price) && $product->sale_start_date <= now() && $product->sale_end_date >= now()){
                    $currentDate = new DateTime(); // Current date and time
                    $saleStartDate = new DateTime($product->sale_start_date);
                    $saleEndDate = new DateTime($product->sale_end_date);
                    if($currentDate >= $saleStartDate && $currentDate <= $saleEndDate){
                        $productPrice = $product->actual_price;
                        
                        $discount = 0;
                        // $specialPrice = $product->sale_price;
                        $subTotal = $product->actual_price * $data['qty'];
                    }
                }else{
                    if($product->discount_type ="percent"){
                        if($product->discount != "0.00"){
                            $discount = ((($product->actual_price * $product->discount) / 100) * $data['qty']);
                            $subTotal = (($product->actual_price *  $data['qty']) - $discount);
                        }else{
                            $discount = 0;
                            $subTotal = ($product->actual_price *  $data['qty']);
                        }
                        
                    }else{
                        $discount = $product->discount;
                        $subTotal = (($product->actual_price *  $data['qty']) - $discount);
                    }
                }
              
                if($product->tax == 8){
                    if(!empty($product->sale_price) && $product->sale_start_date <= now() && $product->sale_end_date >= now()){
                        $eight_percent += ((($product->actual_price * $product->tax) / 100) * $data['qty']);   
                    }else{
                        $discount_price = Helpers::afterDiscountPrice($product,$product->actual_price);                  
                        $eight_percent += (((($product->actual_price - $discount_price['discount_amount']) * $product->tax) / 100) * $data['qty']);      
                    }
               
                }
                if($product->tax == 10){
                    if(!empty($product->sale_price) && $product->sale_start_date <= now() && $product->sale_end_date >= now()){
                        $ten_percent += ((($product->actual_price * $product->tax) / 100) * $data['qty']);   
                    }else{
                        $discount_price = Helpers::afterDiscountPrice($product,$product->actual_price);
                        $ten_percent += (((($product->actual_price - $discount_price['discount_amount']) * $product->tax) / 100) * $data['qty']);   
                    }
                    
                }
                
               
                $cart = Cart::updateOrCreate(
                    [
                        'user_id'       => $user->id,
                        'product_id'    => $data['product_id'],
                    ],
                    [
                        'quantity'      => $data['qty'],
                        'eight_percent' => round($eight_percent),
                        'ten_percent'   => round($ten_percent),
                        'price'         => round($product->actual_price),
                        // 'special_price' => (!empty($specialPrice)) ? $specialPrice : 0,
                        'discount_type' => $discount_type,
                        'discount'      => round($discount),
                        'sub_total'     => round($subTotal)
                    ]
                );
              
            }
            return response()->json(['message' => 'Product added to cart']);
        }else{
            return response()->json(['message' => 'cart is empty']);
        }
        
    }
}

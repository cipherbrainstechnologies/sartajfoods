<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\CentralLogics\ProductLogic;
use App\Http\Controllers\Controller;
use App\Model\Product;
use App\Model\HotDeals;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\CentralLogics\CategoryLogic;
use App\Model\Translation;

class HotDealsController extends Controller
{
    public function __construct(
    
        private Product $product,
        private Translation $translation,
        private HotDeals $HotDeals,
    ){}
    
    public function getHotDeals()
    {
        $HotDealsData = $this->HotDeals->with('product')->first();
        $product_price = 0;
        $product_discounted_price = 0;
        if(!empty($HotDealsData)) {
            $product = $this->product->find($HotDealsData->product_id);
            $product_price = !empty($product->price)? $product->price : 0;
            $product_discounted_price = $product_price - ($product_price * $HotDealsData->discount) / 100;
            $HotDealsData->product_price = $product_price;
            $HotDealsData->product_discounted_price = $product_discounted_price;
            $HotDealsData->image = $this->addImageUrl($HotDealsData->image);
        }
        return response()->json($HotDealsData, 200);
    }

    public function addImageUrl($image){
        $baseUrl = config('app.url');
        $imageUrl= $baseUrl . '/storage/deals/' . $image; 
        return $imageUrl;
    }
}

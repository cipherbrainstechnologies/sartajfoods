<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Model\Manufacturer;
use App\Model\Translation;
use App\CentralLogics\Helpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Model\Product;
use App\CentralLogics\CategoryLogic;


class ManufacturerController extends Controller
{
    public function __construct(
        private Manufacturer $manufacturer,
        private Translation $translation,
        private Product $product
    ){} 
    


    public function addImageUrl($manufacturers){
        $baseUrl = config('app.url');
        $response = [];
        if(!empty($manufacturers)){
            foreach($manufacturers->toArray() as $key => $manufacturer){
                $response[] = $manufacturer;
                $response[$key]['image'] = $baseUrl . '/storage/product/image/' . $manufacturer['image'];
            }
        }      
        return $response;
    }

    public function list() {
        try {
            $manufacturer_data = $this->manufacturer->with('products')->get();
            $Manufacturers = self::addImageUrl($manufacturer_data);
            return response()->json($Manufacturers, 200);
        } catch (\Exception $e) {
         return response()->json([], 200);
        }
    }

    public function search($id){
        try {
            $manufacturer_data = $this->manufacturer->with('products')->where('id', $id)->get();
            $Manufacturers = self::addImageUrl($manufacturer_data);
            return response()->json($Manufacturers, 200);
        } catch (\Exception $e) {
         return response()->json([], 200);
        }
    }

    public function get_seo_manufacturer($seo){
        try {
            $manufacturer_data = $this->manufacturer->with(['products.manufacturer' =>function($q) use($seo){
                return $q->Where('seo_en', 'like', "%{$seo}%")
                ->orWhere('seo_ja', 'like', "%{$seo}%");
            }])->get();
            $Manufacturers = self::addImageUrl($manufacturer_data);
            return response()->json($Manufacturers, 200);
        } catch (\Exception $e) {
         return response()->json([], 200);
        }
    }

    public function seo_type_test(Request $request){

        $type = Helpers::seo_type_test($request->seo);
        return response()->json(["type" =>$type], 200);
    }
    
    public function get_manufacturer():\Illuminate\Http\JsonResponse
    {
       try {
        // Fetch all manufacturers with related products
        $manufacturers = $this->manufacturer->orderBy('name')->get();
        // Debug the manufacturer data
        if ($manufacturers->isEmpty()) {
            return response()->json(['message' => 'No manufacturers found'], 404);
        }

        foreach ($manufacturers as $key => $manufacturer) {
            // Count total products for each manufacturer
            $manufacturers[$key]['total_products'] = Product::where('manufacturer_id', $manufacturer->id)->count();
            
            // You can include other information like translations if needed
            if ($manufacturer->translations) {
                $manufacturers[$key]['translated_name'] = $manufacturer->translations[0]->value ?? $manufacturer->name;
            }
        }

        // Return the manufacturers as JSON
        return response()->json($manufacturers, 200);
        } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
        }
    } 
    public function get_products($id): \Illuminate\Http\JsonResponse
    {
        return response()->json(Helpers::product_data_formatting(CategoryLogic::getProductsByManufacturer($id), true), 200);
    }
}  

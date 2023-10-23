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

class ManufacturerController extends Controller
{
    public function __construct(
        private Manufacturer   $manufacturer,
        private Translation $translation
    ){} 

    public function addImageUrl($manufacturers){
        $baseUrl = config('app.url');
        $response = [];
        if(!empty($manufacturers)){
            foreach($manufacturers->toArray() as $key => $manufacturer){
                $response[] = $manufacturer;
                $response[$key]['image'] = $baseUrl . '/storage/manufacturer/' . $manufacturer['image'];
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
}

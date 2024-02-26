<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Cities;
use App\CentralLogics\Helpers;

class CitiesController extends Controller
{
    //
    public function get_all_cities($region_id){
        $cityDetail = Cities::with('regions')->where('region_id',$region_id)->get();
        if(!empty($cityDetail)){
            return response()->json(['city' => $cityDetail->toArray()], 200);
        }
        return response()->json(['no any cities'], 200);
    }
}

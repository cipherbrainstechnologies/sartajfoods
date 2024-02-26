<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Regions;
use App\CentralLogics\Helpers;

class RegionsController extends Controller
{
    //
       
    public function get_all_regions(){
        $regions = Regions::all();
        if(!empty($regions)){
            return response()->json(['regions' => $regions->toArray()], 200);
        }  
        return response()->json(['no any regions'], 200);
    }
}

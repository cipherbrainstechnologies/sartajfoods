<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\BrowserHistory;

class BrowserHistoryController extends Controller
{
    // public function get(Request $request,$userId){
    //     $browserDetail = BrowserHistory::where('user_id',$userId)->first();
    //     if(!empty($browserDetail)){
    //         return response()->json($browserDetail, 200);
    //     }
    // }

    public function store(Request $request){
        $browserHistory = BrowserHistory::updateOrCreate(
            [
                "user_id" => !empty($request->user_id) ? $request->user_id : null
            ],
            [
                "ip_address" => !empty($request->ip_address) ? $request->ip_address : null,
                "forwarded_ip" => !empty($request->forwarded_ip) ? $request->forwarded_ip : null,
                "user_agent" => !empty($request->user_agent) ? $request->user_agent : null,
                "accept_language" => !empty($request->accept_language) ? $request->accept_language : null
            ]
        );
        return response()->json($browserHistory, 200);
    }
}

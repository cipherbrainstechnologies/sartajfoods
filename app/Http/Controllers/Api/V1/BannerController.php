<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\CategoryLogic;
use App\Http\Controllers\Controller;
use App\Model\Banner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function __construct(
        private Banner $banner
    ){}

    public function addImageUrl($banners){
        $baseUrl = config('app.url');
        $response = [];
        if(!empty($banners)){
            foreach($banners->toArray() as $key => $banner){
                $response[] = $banner;
                $response[$key]['image'] = $baseUrl . '/storage/banner/' . $banner['image'];
                if(!empty($banner['banner_logo'])){
                    $response[$key]['banner_logo'] = $baseUrl . '/storage/banner/logo/' . $banner['banner_logo'];
                }
            }
        }      
        return $response;
    }

    /**
     * @return JsonResponse
     */
    public function get_home_banners(): JsonResponse
    {
        try {
            $banners = $this->banner->whereNotNull('type')->active()->get();
            $Banner = self::addImageUrl($banners);
            return response()->json($Banner, 200);
        } catch (\Exception $e) {
            return response()->json([], 200);
        }
    }

    public function get_other_banners(): JsonResponse
    {
        try {
            $banners = $this->banner->whereNull('type')->active()->get();
            $Banner = self::addImageUrl($banners);
            return response()->json($Banner, 200);
        } catch (\Exception $e) {
            return response()->json([], 200);
        }
    }
}

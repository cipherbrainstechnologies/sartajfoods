<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\CategoryLogic;
use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\Product;

class CategoryController extends Controller
{
    public function __construct(
        private Category $category
    ){}


    public function get_categories(): \Illuminate\Http\JsonResponse
    {
        try {
            $Categories = $this->category->where(['position'=> 0,'status'=>1])->orderBy('name')->get();
            // $Categories = self::addImageUrl($categories);
            foreach($Categories as $key => $category) {
                // $Categories[$key]['total_produts'] = CategoryLogic::getProductCount($category["id"]);
                $Categories[$key]['total_produts'] =  Product::active()->whereJsonContains('category_ids', ['id' => "{$category->id}"])->count();
            }
            return response()->json($Categories, 200);
        } catch (\Exception $e) {
            return response()->json([], 200);
        }
    }

    public function get_childes($id): \Illuminate\Http\JsonResponse
    {
        try {
            $categories = $this->category->where(['parent_id' => $id,'status'=>1])->get();
            return response()->json($categories, 200);
        } catch (\Exception $e) {
            return response()->json([], 200);
        }
    }

    public function get_products($id): \Illuminate\Http\JsonResponse
    {
        return response()->json(Helpers::product_data_formatting(CategoryLogic::products($id), true), 200);
    }

    public function get_all_products($id): \Illuminate\Http\JsonResponse
    {
        try {
            return response()->json(Helpers::product_data_formatting(CategoryLogic::all_products($id), true), 200);
        } catch (\Exception $e) {
            return response()->json([], 200);
        }
    }

    public function getTags($id) : \Illuminate\Http\JsonResponse
    {
        try {
           $tags = CategoryLogic::all_tags($id);
           return response()->json($tags, 200);
        } catch (\Exception $e) {
            return response()->json([], 200);
        }
    }

    
}

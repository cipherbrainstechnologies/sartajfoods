<?php

namespace App\CentralLogics;

use App\Model\Category;
use App\Model\Product;

class CategoryLogic
{
    public static function parents()
    {
        return Category::where('position', 0)->get();
    }

    public static function child($parent_id)
    {
        return Category::where(['parent_id' => $parent_id])->get();
    }

    public static function products($category_id, $limit = 0, $offset =1, $manufacturer_id = null)
    {
        $products = Product::active()->get();
        $product_ids = [];
        foreach ($products as $product) {
            foreach (json_decode($product['category_ids'], true) as $category) {
                if ($category['id'] == $category_id) {
                    array_push($product_ids, $product['id']);
                }
            }
        }
        
        $product_data = Product::active()->withCount(['wishlist', 'active_reviews'])->with(['rating', 'active_reviews','manufacturer','soldProduct'])->whereIn('id', $product_ids);
        
        if(!empty($manufacturer_id)) {
            $product_data->whereHas('manufacturer', function ($query) use ($manufacturer_id) {
                $query->where('id', $manufacturer_id);
            });
        }

        if($limit !== 0) {
            return $product_data->paginate($limit, ['*'], 'page', $offset);
        } else {
            return $product_data->get();
        }
    }

    public static function all_products($id)
    {
        $cate_ids=[];
        array_push($cate_ids,(int)$id);
        foreach (CategoryLogic::child($id) as $ch1){
            array_push($cate_ids,$ch1['id']);
            foreach (CategoryLogic::child($ch1['id']) as $ch2){
                array_push($cate_ids,$ch2['id']);
            }
        }

        $products = Product::active()->with('rating', 'active_reviews')->get();
        $product_ids = [];
        foreach ($products as $product) {
            foreach (json_decode($product['category_ids'], true) as $category) {
                if (in_array($category['id'],$cate_ids)) {
                    array_push($product_ids, $product['id']);
                }
            }
        }

        return Product::active()->withCount(['wishlist'])->with('rating', 'active_reviews')->whereIn('id', $product_ids)->get();
    }

    public static function getProductCount($category_id) 
    {
        $products = Product::active()->get();
        $product_ids = [];
        if(!empty($products)){
            foreach ($products as $product) {
                // foreach (json_decode($product['category_ids'], true) as $category) {
                foreach ($product['category_ids'] as $category) {
                    
                    if ($category['id'] == $category_id) {
                        array_push($product_ids, $product['id']);
                    }
                }
            }
            return sizeof($product_ids);
        }
        return 0;
    }

    public static function productsSort($category_id, $limit = 0, $offset =1, $sort = '', $manufacturer_id = null)
    {
        $products = Product::active()->get();
        $product_ids = [];
        foreach ($products as $product) {
            foreach (json_decode($product['category_ids'], true) as $category) {
                if ($category['id'] == $category_id) {
                    array_push($product_ids, $product['id']);
                }
            }
        }
        $product_data = Product::active()->withCount(['wishlist', 'active_reviews'])->with(['rating', 'active_reviews','manufacturer', 'soldProduct'])->whereIn('id', $product_ids);

        if(!empty($manufacturer_id)) {
            $product_data->whereHas('manufacturer', function ($query) use ($manufacturer_id) {
                $query->where('id', $manufacturer_id);
            });
        }
        if($limit !== 0 && !empty($sort)) {
            if($sort === 'featured') {
                return $product_data->where(['is_featured' => 1])->paginate($limit, ['*'], 'page', $offset);
            } else if($sort === 'lowToHigh') {
                return $product_data->orderBy('price', 'ASC')->paginate($limit, ['*'], 'page', $offset);
            } else if($sort === 'highToLow') {
                return $product_data->orderBy('price', 'DESC')->paginate($limit, ['*'], 'page', $offset);
            } else if($sort === 'trending') {
                return $product_data->where('popularity_count', '<>' , 0)->orderBy('popularity_count', 'DESC')->paginate($limit, ['*'], 'page', $offset);
            } 
            else {
                return $product_data->paginate($limit, ['*'], 'page', $offset);
            }
        } else {
            return $product_data->get();
        }
    }

    public static function productsSortByPrice($category_id, $limit = 0, $offset =1, $max = 0 , $min = 0)
    {
        $products = Product::active()->get();
        $product_ids = [];
        foreach ($products as $product) {
            foreach (json_decode($product['category_ids'], true) as $category) {
                if ($category['id'] == $category_id) {
                    array_push($product_ids, $product['id']);
                }
            }
        }

        if($limit !== 0) {
            return Product::active()->withCount(['wishlist', 'active_reviews'])->with(['rating', 'active_reviews','manufacturer'])->whereIn('id', $product_ids)->whereBetween('price', [$min, $max])->paginate($limit, ['*'], 'page', $offset);
        } else {
            return Product::active()->withCount(['wishlist', 'active_reviews'])->with(['rating', 'active_reviews','manufacturer'])->whereIn('id', $product_ids)->get();
        }
    }

    public static function all_tags($id)
    {
        $cate_ids=[];
        array_push($cate_ids,(int)$id);
        foreach (CategoryLogic::child($id) as $ch1){
            array_push($cate_ids,$ch1['id']);
            foreach (CategoryLogic::child($ch1['id']) as $ch2){
                array_push($cate_ids,$ch2['id']);
            }
        }

        $products = Product::active()->get();
        $product_ids = [];
        foreach ($products as $product) {
            foreach (json_decode($product['category_ids'], true) as $category) {
                if (in_array($category['id'],$cate_ids)) {
                    array_push($product_ids, $product['id']);
                }
            }
        }
        
        $cat_products = Product::active()->select('product_tag')->whereIn('id', $product_ids)->get();
        
        $product_tags = $cat_products->pluck('product_tag')->toArray();
        return $product_tags;
    }
}

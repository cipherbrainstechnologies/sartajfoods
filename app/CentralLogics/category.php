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

    public static function products($category_id, $limit = 0, $offset =1)
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
            return Product::active()->withCount(['wishlist', 'active_reviews'])->with(['rating', 'active_reviews','manufacturer'])->whereIn('id', $product_ids)->paginate($limit, ['*'], 'page', $offset);
        } else {
            return Product::active()->withCount(['wishlist', 'active_reviews'])->with(['rating', 'active_reviews','manufacturer'])->whereIn('id', $product_ids)->get();
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
        foreach ($products as $product) {
            foreach (json_decode($product['category_ids'], true) as $category) {
                if ($category['id'] == $category_id) {
                    array_push($product_ids, $product['id']);
                }
            }
        }
        return sizeof($product_ids);
    }

    public static function productsSort($category_id, $limit = 0, $offset =1, $sort = '')
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
        if($limit !== 0 && !empty($sort)) {
            if($sort === 'featured') {
                return Product::active()->withCount(['wishlist', 'active_reviews'])->with(['rating', 'active_reviews','manufacturer'])->whereIn('id', $product_ids)->where(['is_featured' => 1])->paginate($limit, ['*'], 'page', $offset);
            } else if($sort === 'low_to_high') {
                return Product::active()->withCount(['wishlist', 'active_reviews'])->with(['rating', 'active_reviews','manufacturer'])->whereIn('id', $product_ids)->orderBy('price', 'ASC')->paginate($limit, ['*'], 'page', $offset);
            } else if($sort === 'high_to_low') {
                return Product::active()->withCount(['wishlist', 'active_reviews'])->with(['rating', 'active_reviews','manufacturer'])->whereIn('id', $product_ids)->orderBy('price', 'DESC')->paginate($limit, ['*'], 'page', $offset);
            } 
            else {
                return Product::active()->withCount(['wishlist', 'active_reviews'])->with(['rating', 'active_reviews','manufacturer'])->whereIn('id', $product_ids)->paginate($limit, ['*'], 'page', $offset);
            }
        } else {
            return Product::active()->withCount(['wishlist', 'active_reviews'])->with(['rating', 'active_reviews','manufacturer'])->whereIn('id', $product_ids)->get();
        }
    }

}

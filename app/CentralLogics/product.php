<?php

namespace App\CentralLogics;


use App\Model\CategoryDiscount;
use App\Model\FavoriteProduct;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\Product;
use App\Model\Review;
use App\Model\FlashDeal;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Model\HotDeals;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductLogic
{
    public static function get_product($id){
        return Product::active()->withCount(['wishlist','relatedProducts'])->with(['rating', 'active_reviews', 'active_reviews.customer', 'soldProduct','relatedProducts.relatedProduct','translation'])->where('id', $id)->first();
    }

    public static function get_seo_product($seo){
        return Product::active()->withCount(['wishlist','relatedProducts'])->with(['rating', 'active_reviews', 'active_reviews.customer', 'soldProduct','relatedProducts.relatedProduct','relatedProducts.relatedProduct.manufacturer','relatedProducts.relatedProduct.translations','translations'])
        ->Where('seo_en', 'like', "%{$seo}%")
        ->orWhere('seo_ja', 'like', "%{$seo}%")
        ->first();
    }

    // Get All the products
    public static function get_all_products($limit = 10, $offset = 1, $manufacturer_id = null){
        
        $currentDate = now();
        $twoWeeksAgo = $currentDate->subDays(14);

        $paginator = !empty($manufacturer_id) ?
                    Product::active()
                    ->withCount(['wishlist','order_details','relatedProducts'])
                    ->with(['rating', 'active_reviews','manufacturer', 'soldProduct','relatedProducts.relatedProduct','translations'])
                    ->whereHas('manufacturer', function ($query) use ($manufacturer_id) {
                        $query->Where('seo_en', 'like', "%{$manufacturer_id}%")
                        ->orWhere('seo_ja', 'like', "%{$manufacturer_id}%");
                    })
                    ->orderBy('id','desc')
                    ->paginate($limit, ['*'], 'page', $offset)
                    : Product::active()
                    ->withCount(['wishlist','order_details','relatedProducts'])
                    ->with(['rating', 'active_reviews','manufacturer', 'soldProduct','relatedProducts.relatedProduct','translations'])
                    ->orderBy('id','desc')
                    ->paginate($limit, ['*'], 'page', $offset);

        // $products = $paginator->getCollection()->map(function ($product) use($twoWeeksAgo){
        //     $badges = [];
        //     if ($product->created_at >= $twoWeeksAgo) {
        //         array_push($badges,'new');
        //     }
        //     // Check if the product is "hot"
        //     if ($product->order_details_count > 1) { // Change this condition as per your definition of "hot"                    
        //         array_push($badges,'hot');
        //     }
        //     // Add other conditions for badges here if needed
        //     $product['badges'] = $badges;
        //     return $product;
        // });

        return [
            'total_size' => $paginator->total(),
            'limit' => $limit,
            'offset' => $offset,
            'products' => $paginator->items()
        ];
    }

    // Get All the latest added products
    public static function get_latest_products($limit = 10, $offset = 1,$take = null){
        $currentDate = now();
        $twoWeeksAgo = $currentDate->subDays(14);

        $paginator = Product::active()
            ->withCount(['wishlist','relatedProducts'])
            ->with(['rating', 'active_reviews','manufacturer', 'soldProduct','relatedProducts.relatedProduct','translations'])
            ->where('created_at', '>=', $twoWeeksAgo)  // Filter for products created in the last two weeks
            ->latest();//;->paginate($limit, ['*'], 'page', $offset);
            if(!is_null($take)){
                $limit = $take;                    
            }
            $paginator = $paginator->paginate($limit, ['*'], 'page', $offset);
            $products = $paginator->getCollection()->map(function ($product) {
                $product['badges'] = ['new'];
                return $product;
            });
        return [
            'total_size' => $paginator->total(),
            'limit' => $limit,
            'offset' => $offset,
            'products' => $paginator->items()
        ];
    }

    // Get Favorite Products
    public static function get_favorite_products($limit, $offset, $user_id){
        $limit = is_null($limit) ? 10 : $limit;
        $offset = is_null($offset) ? 1 : $offset;

        $ids = User::with('favorite_products')->find($user_id)->favorite_products->pluck('product_id')->toArray();
        $favorite_products = Product::whereIn('id', $ids)->paginate($limit, ['*'], 'page', $offset);

        $formatted_products = Helpers::product_data_formatting($favorite_products, true);

        return [
            'total_size' => $favorite_products->total(),
            'limit' => $limit,
            'offset' => $offset,
            'products' => $formatted_products
        ];
    }

    //Get Related products 
    public static function get_related_products($product_id){
        // $product = Product::find($product_id);
        // return Product::active()->withCount(['wishlist'])->with(['rating', 'active_reviews','manufacturer', 'soldProduct'])
        //     ->where('category_ids', $product->category_ids)
        //     ->where('id', '!=', $product->id)
            // ->limit(10)
            // ->get();
        return Product::where('id',$product_id)->withCount(['wishlist','relatedProducts'])
                    ->with(['rating', 'active_reviews','manufacturer', 'soldProduct','relatedProducts.relatedProduct','translations'])
                    ->limit(10)
                    ->get();
        
    }

    // Search product get
    public static function search_products($name, $limit = 10, $offset = 1){
        $key = explode(' ', $name);
        $paginator = Product::active()->withCount(['wishlist','relatedProducts'])->with(['rating', 'active_reviews','manufacturer', 'soldProduct','relatedProducts.relatedProduct','translations'])->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('name', 'like', "%{$value}%");
            }
            $q->orWhereHas('tags',function($query) use ($key){
                $query->where(function($q) use ($key){
                    foreach ($key as $value) {
                        $q->where('tag', 'like', "%{$value}%");
                    };
                });
            });
        })->paginate($limit, ['*'], 'page', $offset);

        return [
            'total_size' => $paginator->total(),
            'limit' => $limit,
            'offset' => $offset,
            'products' => $paginator->items()
        ];
    }

    // review product get
    public static function get_product_review($id){
        $reviews = Review::active()->where('product_id', $id)->get();
        return $reviews;
    }



    //Get on rate based product
    public static function get_rating($reviews){
        $rating5 = 0;
        $rating4 = 0;
        $rating3 = 0;
        $rating2 = 0;
        $rating1 = 0;
        foreach ($reviews as $key => $review) {
            if ($review->rating == 5) {
                $rating5 += 1;
            }
            if ($review->rating == 4) {
                $rating4 += 1;
            }
            if ($review->rating == 3) {
                $rating3 += 1;
            }
            if ($review->rating == 2) {
                $rating2 += 1;
            }
            if ($review->rating == 1) {
                $rating1 += 1;
            }
        }
        return [$rating5, $rating4, $rating3, $rating2, $rating1];
    }

    public static function get_overall_rating($reviews){
        $totalRating = count($reviews);
        $rating = 0;
        foreach ($reviews as $key => $review) {
            $rating += $review->rating;
        }
        if ($totalRating == 0) {
            $overallRating = 0;
        } else {
            $overallRating = number_format($rating / $totalRating, 2);
        }

        return [$overallRating, $totalRating];
    }

    public static function get_popular_products($limit = 10, $offset = 1){
        $paginator = Product::active()->withCount(['wishlist','relatedProducts'])->with(['rating', 'active_reviews','manufacturer', 'soldProduct','relatedProducts.relatedProduct','translations'])->orderBy('popularity_count', 'desc')->paginate($limit, ['*'], 'page', $offset);
        return [
            'total_size' => $paginator->total(),
            'limit' => $limit,
            'offset' => $offset,
            'products' => $paginator->items()
        ];
    }

    public static function get_most_viewed_products($limit = 10, $offset = 1){
        $paginator = Product::active()
            ->withCount(['wishlist','relatedProducts'])
            ->with(['rating', 'active_reviews','manufacturer', 'soldProduct','relatedProducts.relatedProduct','translations'])
            ->orderBy('view_count', 'desc')
            ->paginate($limit, ['*'], 'page', $offset);

        return [
            'total_size' => $paginator->total(),
            'limit' => $limit,
            'offset' => $offset,
            'products' => $paginator->items()
        ];
    }

    public static function get_trending_products($limit = 10, $offset = 1,$take=null){
        if(OrderDetail::count() > 0) {
            $paginator = Product::active()
                ->with(['rating', 'active_reviews','manufacturer', 'soldProduct','relatedProducts.relatedProduct','translations'])
                // ->whereHas('order_details', function ($query) {
                //     $query->where('created_at', '<', now()->subDays(30)->endOfDay());
                // })
                ->withCount('order_details','relatedProducts')
                ->orderBy('order_details_count', 'desc');
                
                if(!is_null($take)){
                    $limit = $take;                    
                }
                $paginator = $paginator->paginate($limit, ['*'], 'page', $offset);
        } else {
            $paginator = Product::active()
                ->withCount('order_details','relatedProducts')
                ->with(['rating', 'active_reviews','manufacturer','relatedProducts.relatedProduct','translations'])
                ->inRandomOrder();
                // ->paginate($limit, ['*'], 'page', $offset);
                if(!is_null($take)){
                    $limit = $take;                    
                }
                $paginator = $paginator->paginate($limit, ['*'], 'page', $offset);
        }
        //Add the badges Hot
        $products = $paginator->getCollection()->map(function ($product) {
            $badges = ['hot'];
            $product['badges'] = $badges;
            return $product;
        });

        return [
            'total_size' => $paginator->total(),
            'limit' => $limit,
            'offset' => $offset,
            'products' => $paginator->items()
        ];
    }

    public static function get_recommended_products($user, $limit = 10, $offset = 1){
        if($user != null) {
            $order_ids = Order::where('user_id', $user->id)->pluck('id');
            $product_ids = OrderDetail::whereIn('order_id', $order_ids)->pluck('product_id')->toArray();
            $categoryIds = Product::whereIn('id', $product_ids)->pluck('category_ids')->toArray();

            $ids = [];
            foreach ($categoryIds as $value) {
                $items = json_decode($value);
                foreach ($items as $item) {
                    if ($item->position == 1) {
                        $ids[] = $item->id;
                    }
                }
            }
            $ids = array_unique($ids);

            $paginator = Product::active()
                ->withCount('order_details','relatedProducts')
                ->with(['rating', 'active_reviews','manufacturer', 'soldProduct','relatedProducts.relatedProduct','translations'])
                ->where(function ($query) use ($ids) {
                    foreach ($ids as $id) {
                        $query->orWhereJsonContains('category_ids', [['id' => $id, 'position' => 1]]);
                    }
                })
                ->paginate($limit, ['*'], 'page', $offset);

        } else {
            $paginator = Product::active()
                ->withCount('order_details','relatedProducts')
                ->with(['rating', 'active_reviews','manufacturer', 'soldProduct','relatedProducts.relatedProduct','translations'])
                ->inRandomOrder()
                ->paginate($limit, ['*'], 'page', $offset);
        }

        return [
            'total_size' => $paginator->total(),
            'limit' => $limit,
            'offset' => $offset,
            'products' => $paginator->items()
        ];
    }

    public static function get_most_reviewed_products($limit = 10, $offset = 1,$take=null){

        $paginator = Product::active()
            ->with(['rating', 'active_reviews', 'manufacturer', 'soldProduct','relatedProducts.relatedProduct','translations'])
            ->withCount('active_reviews','relatedProducts')
            ->get();

        // // Extract the rating values from the relationships
        // $paginator = $paginator->map(function ($product) {
        //     $product['rating'] = $product['rating']->avg('average');
        //     return $product;
        // });
        
        // Sort the products by rating in descending order
        $sortedProducts = $paginator->sortByDesc('rating')->values()->all();
        if(!is_null($take)){
            $limit = $take;                    
        }
        $sortedProducts = Helpers::product_data_formatting($sortedProducts, true);
        $paginator = new LengthAwarePaginator(
            array_slice($sortedProducts, ($offset - 1) * $limit, $limit),
            count($sortedProducts),
            $limit,
            $offset
        );
    
        return [
            'total_size' => $paginator->total(),
            'limit' => $limit,
            'offset' => $offset,
            'products' => $paginator->items()
        ];
    }

    // public static function get_most_reviewed_products($limit = 10, $offset = 1,$take=null){

    //     $paginator = Product::active()
        
    //     ->with(['rating', 'active_reviews','manufacturer', 'soldProduct'])
    //     ->withCount('active_reviews')
    //     ->get();
    //     $paginator = $paginator->map(function ($product) {
    //         $product['rating'] = $product['rating']->avg('average');
    //         $product['rating_count'] = $product['rating']->count();
    //         return $product;
    //     });
    //     // ->orderBy('active_reviews_count', 'desc');
    //     if(!is_null($take)){
    //         $limit = $take;                    
    //     }
    //     $paginator = new LengthAwarePaginator(
    //         array_slice($sortedProducts, ($offset - 1) * $limit, $limit),
    //         count($sortedProducts),
    //         $limit,
    //         $offset
    //     );
    //     // $paginator = $paginator->paginate($limit, ['*'], 'page', $offset);
    //     return [
    //         'total_size' => $paginator->total(),
    //         'limit' => $limit,
    //         'offset' => $offset,
    //         'products' => $paginator->items()
    //     ];
    // }

    public static function get_sale_products($limit = 10, $offset = 1,$take=null){
        
        $paginator = Product::active()
        ->withCount('active_reviews','relatedProducts')
        ->with(['rating', 'active_reviews','manufacturer', 'soldProduct','relatedProducts.relatedProduct','translations'])
        ->whereNotNull('sale_start_date')
        ->whereNotNull('sale_end_date')
        ->where('sale_start_date', '<=', now())  
        ->where('sale_end_date', '>=', now())
        ->paginate($limit, ['*'], 'page', $offset);        
        return [
                    'total_size' => $paginator->total(),
                    'limit' => $limit,
                    'offset' => $offset,
                    'products' => $paginator->items()
                ];
    }

    // public static function get_flash_sale_products($limit = 10, $offset = 1){
    //     $newArray = [];
    //     $flashDeals = FlashDeal::active()->get();
    //     if(!empty($flashDeals)){

    //     }
    //     // ->paginate($limit, ['*'], 'page', $offset);        
    //     return [
    //         'total_size' => $paginator->total(),
    //         'limit' => $limit,
    //         'offset' => $offset,
    //         'products' => $paginator->items()
    //     ];
    // }

    public static function cal_rating_and_review($products)
    {
        $products = array_key_exists("products",$products) ? $products['products'] : $products;
        if(!empty($products)) {
            foreach($products as $key => $product) {
                $all_over_rating = '';
                $total_reviews = '';
                if(!empty($product['rating'][0])) {
                    $all_over_rating = ($product['rating'][0]->total/($product['rating'][0]->count * 5)) * 100;
                    $total_reviews = $product['rating'][0]->count;
                }
    
                $product['overall_rating'] = $all_over_rating;
                $product['total_reviews'] = $total_reviews;
            }
        }
    }

    public static function search_products_all($name, $limit = 10, $offset = 1, $category_id = null){

        if(!empty($category_id)){
            $products = Product::active()->get();
            $product_ids = [];
            foreach ($products as $product) {
                foreach (json_decode($product['category_ids'], true) as $category) {
                    if ($category['id'] == $category_id) {
                        array_push($product_ids, $product['id']);
                    }
                }
            }

            $key = explode(' ', $name);
            $paginator = Product::active()->withCount(['wishlist'])->with(['rating', 'active_reviews','manufacturer', 'soldProduct','translations'])->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
                $q->orWhereHas('tags',function($query) use ($key){
                    $query->where(function($q) use ($key){
                        foreach ($key as $value) {
                            $q->where('tag', 'like', "%{$value}%");
                        };
                    });
                });
            })->whereIn('id', $product_ids)->paginate($limit, ['*'], 'page', $offset);
        } else {
            $key = explode(' ', $name);
            $paginator = Product::active()->withCount('wishlist','active_reviews','relatedProducts')->with(['rating', 'active_reviews','manufacturer', 'soldProduct','relatedProducts.relatedProduct','translations'])->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
                $q->orWhereHas('tags',function($query) use ($key){
                    $query->where(function($q) use ($key){
                        foreach ($key as $value) {
                            $q->where('tag', 'like', "%{$value}%");
                        };
                    });
                });
            })->paginate($limit, ['*'], 'page', $offset);
        }

        return $paginator->items();
    }

    public static function getSoldProducts($products)
    {
        $products = array_key_exists("products",$products) ? $products['products'] : $products;
        if(!empty($products)) {
            foreach($products as $product) {
                $sold = !empty($product["soldProduct"][0]["sold_products"]) ? $product["soldProduct"][0]["sold_products"] : 0;
                $product['sold_products'] = $sold;
                $product['total_product_count'] = $sold + $product['total_stock'];
            }
        }
    }

    public static function deal_of_month($products)
    {
        $deal = HotDeals::all()[0];
        foreach($products as $product) {
            if($product->id === $deal->product_id) {
                $product->price = $product->price - ($product->price * ($deal->discount / 100));
            }
        }   
    }
}

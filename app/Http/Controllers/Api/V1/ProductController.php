<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\CentralLogics\ProductLogic;
use App\Http\Controllers\Controller;
use App\Model\CategorySearchedByUser;
use App\Model\FavoriteProduct;
use App\Model\Product;
use App\Model\ProductSearchedByUser;
use App\Model\RecentSearch;
use App\Model\Review;
use App\Model\Category;
use App\Model\SearchedCategory;
use App\Model\SearchedKeywordCount;
use App\Model\SearchedKeywordUser;
use App\Model\SearchedProduct;
use App\Model\Translation;
use App\VisitedProduct;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\CentralLogics\CategoryLogic;

class ProductController extends Controller
{
    public function __construct(
        private CategorySearchedByUser $category_searched_by_user,
        private FavoriteProduct $favorite_product,
        private Product $product,
        private ProductSearchedByUser $product_searched_by_user,
        private RecentSearch $recent_search,
        private Review $review,
        private SearchedCategory $searched_category,
        private SearchedKeywordCount $searched_keyword_count,
        private SearchedKeywordUser $searched_keyword_user,
        private SearchedProduct $searched_product,
        private Translation $translation,
        private VisitedProduct $visited_product
    ){}

    
    /**
     * @param Request $request
     * @return JsonResponse
     */
    
     public function get_all_products(Request $request): \Illuminate\Http\JsonResponse
     {
        $orderByColumn = 'id';
        $orderBySort = 'desc';
        $Query = Product::active()
                    ->withCount(['wishlist','order_details','relatedProducts'])
                    ->with(['rating', 'active_reviews','manufacturer', 'soldProduct','relatedProducts.relatedProduct']);
        
                if(!empty($request->manufacturer_id)){
                    $Query->whereHas('manufacturer', function ($query) use ($request) {
                        $query->Where('seo_en', 'like', "%{$request->manufacturer_id}%")
                        ->orWhere('seo_ja', 'like', "%{$request->manufacturer_id}%");
                    });
                }
                if(!empty($request->category_id)){
                    $Query->byCategory($request->category_id);
                }
                if(!empty($request->max)){
                    $Query->whereBetween('price', [$request->min, $request->max]);
                }
                if(!empty($request['sort_by']) && $request['sort_by'] === 'lowToHigh') {
                    $orderByColumn= 'price';
                    $orderBySort = 'ASC';
                }
                if(!empty($request['sort_by']) && $request['sort_by'] === 'highToLow') {
                    $orderByColumn= 'price';
                    $orderBySort = 'DESC';
                }
                if(!empty($request['search'])){
                    $Query->Where('name', 'like', "%{$request['search']}%");
                }
            $products = $Query->orderBy($orderByColumn,$orderBySort)->paginate($request->limit, ['*'], 'page', $request->offset);
        // $products = !empty($request->manufacturer_id) ? ProductLogic::get_all_products($request['limit'], $request['offset'], $request->manufacturer_id) : ProductLogic::get_all_products($request['limit'], $request['offset']);
        // $products['products'] = Helpers::product_data_formatting($products['products'], true);
        // $product_fileter = array();
        // $manufacturer_id = $request->manufacturer_id;
        // if(!empty($request['category_id'])) {
        //     $product_fileter = Helpers::product_data_formatting(CategoryLogic::products($request['category_id'], $request['limit'], $request['offset'], !empty($request->manufacturer_id) ? $request->manufacturer_id : null), true);
        //     $size = CategoryLogic::getProductCount($request['category_id']);
        //     $products['total_size'] = $size;
        //     unset($products['products']);
        //     $products['products']= $product_fileter;
        // }

        // if(!empty($request['max'])) {
        //     $sort_by_fileter = $this->product->active()
        //    ->withCount(['wishlist'])
        //    ->with(['rating', 'active_reviews','manufacturer', 'soldProduct'])
        //    ->whereBetween('price', [$request['min'], $request['max']])
        //    ->orderBy('price', 'DESC');

        //    if(!empty($request->manufacturer_id) && empty($request['category_id'])) {
        //     $sort_by_fileter->whereHas('manufacturer', function ($query) use ($manufacturer_id) {
        //         // $query->where('id', $manufacturer_id);
        //         $query->Where('seo_en', 'like', "%{$manufacturer_id}%")
        //                 ->orWhere('seo_ja', 'like', "%{$manufacturer_id}%");
        //     });
        //    }
        //    $sort_by_fileter->paginate($request['limit'], ['*'], 'page', $request['offset']);

        //    $products['total_size'] = sizeof($sort_by_fileter->get()->toArray());

        //    if(!empty($request['category_id'])) {
        //     $product_fileter = array();
        //     $product_fileter = $product_categoty_fileter_with_sort_by = Helpers::product_data_formatting(CategoryLogic::productsSortByPrice($request['category_id'], $request['limit'], $request['offset'], $request['max'], $request['min'], !empty($request->manufacturer_id) ? $request->manufacturer_id : null), true);
        //    } else {
        //     $product_fileter = Helpers::product_data_formatting($sort_by_fileter->get(), true);
        //    }
           
        //    if(!empty($request['category_id'])) {
        //     $product_fileter = array();
        //     $product_fileter = $product_categoty_fileter_with_sort_by = Helpers::product_data_formatting(CategoryLogic::productsSortByPrice($request['category_id'], $request['limit'], $request['offset'], $request['max'], $request['min'], !empty($request->manufacturer_id) ? $request->manufacturer_id : null), true);
        //    }
           
        //    unset($products['products']);
        //    $products['products']= $product_fileter;
        // }
        // if(!empty($request['sort_by']) && $request['sort_by'] === 'lowToHigh') {
        //     $sort_by_fileter = $this->product->active()
        //    ->withCount(['wishlist'])
        //    ->with(['rating', 'active_reviews','manufacturer', 'soldProduct'])
        //    ->orderBy('price', 'ASC');

        //    if(!empty($request->manufacturer_id) && empty($request['category_id'])) {
        //     $sort_by_fileter->whereHas('manufacturer', function ($query) use ($manufacturer_id) {
        //         // $query->where('id', $manufacturer_id);
        //         $query->Where('seo_en', 'like', "%{$manufacturer_id}%")
        //                 ->orWhere('seo_ja', 'like', "%{$manufacturer_id}%");
        //     });
        //    }
        
        //    $sort_by_fileter->paginate($request['limit'], ['*'], 'page', $request['offset']);

        //    $products['total_size'] = sizeof($sort_by_fileter->get()->toArray());

        //    if(!empty($request['category_id'])) {
        //     $product_fileter = array();
        //     $product_fileter = $product_categoty_fileter_with_sort_by = Helpers::product_data_formatting(CategoryLogic::productsSort($request['category_id'], $request['limit'], $request['offset'], $request['sort_by'], !empty($request->manufacturer_id) ? $request->manufacturer_id : null), true);
        //    } else {
        //     $product_fileter = Helpers::product_data_formatting($sort_by_fileter->get(), true);
        //    }

        //    unset($products['products']);
        //    $products['products']= $product_fileter;
        // }

        // if(!empty($request['sort_by']) && $request['sort_by'] === 'highToLow') {
        //     $sort_by_fileter = $this->product->active()
        //    ->withCount(['wishlist'])
        //    ->with(['rating', 'active_reviews','manufacturer', 'soldProduct'])
        //    ->orderBy('price', 'DESC');
           
        //    if(!empty($request->manufacturer_id) && empty($request['category_id'])) {
        //     $sort_by_fileter->whereHas('manufacturer', function ($query) use ($manufacturer_id) {
        //         // $query->where('id', $manufacturer_id);
        //         $query->Where('seo_en', 'like', "%{$manufacturer_id}%")
        //                 ->orWhere('seo_ja', 'like', "%{$manufacturer_id}%");
        //     });
        //    }
        //    $sort_by_fileter->paginate($request['limit'], ['*'], 'page', $request['offset']);

        //    $products['total_size'] = sizeof($sort_by_fileter->get()->toArray());

        //    if(!empty($request['category_id'])) {
        //     $product_fileter = array();
        //     $product_fileter = $product_categoty_fileter_with_sort_by = Helpers::product_data_formatting(CategoryLogic::productsSort($request['category_id'], $request['limit'], $request['offset'], $request['sort_by'], !empty($request->manufacturer_id) ? $request->manufacturer_id : null), true);
        //    } else {
        //     $product_fileter = Helpers::product_data_formatting($sort_by_fileter->get(), true);
        //    }

        //    unset($products['products']);
        //    $products['products']= $product_fileter;
        // }
        // if(!empty($request['search'])) {
        //     if(!empty($request['category_id'])) {
        //         $sort_by_fileter = ProductLogic::search_products_all($request['search'], $request['limit'], $request['offset'], $request['category_id']);
        //     } else {
        //         $sort_by_fileter = ProductLogic::search_products_all($request['search'], $request['limit'], $request['offset']);
        //     }
            
        //     $products['total_size'] = sizeof($sort_by_fileter);
        //     $product_fileter =  Helpers::product_data_formatting($sort_by_fileter, true);
          
        //     unset($products['products']);
        //     $products['products'] = $product_fileter;
        // }
       
        ProductLogic::getSoldProducts($products->items());
        ProductLogic::cal_rating_and_review($products->items());
        // ProductLogic::deal_of_month($products->items()); //commmet temporarily
        
        return response()->json(['total_size' => $products->total(),
        'limit' => $request->limit,
        'offset' => $request->offset,
        'products' => $products->items()], 200);
     }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_latest_products(Request $request): \Illuminate\Http\JsonResponse
    {
        $products = ProductLogic::get_latest_products($request['limit'], $request['offset'],3);
        $products['products'] = Helpers::product_data_formatting($products['products'], true);
        ProductLogic::cal_rating_and_review($products);
        ProductLogic::getSoldProducts($products['products']);
        return response()->json($products, 200);
    }

    public function get_latest_three_products(Request $request) : \Illuminate\Http\JsonResponse
    {
        $products = ProductLogic::get_latest_products($request['limit'], $request['offset']);
        $products['products'] = Helpers::product_data_formatting($products['products'], true);
        ProductLogic::cal_rating_and_review($products);
        ProductLogic::getSoldProducts($products['products']);
        return response()->json($products, 200);
    }
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_searched_products(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $products = ProductLogic::search_products($request['name'], $request['limit'], $request['offset']);
        if (count($products['products']) == 0) {
            $key = explode(' ', $request['name']);
            $ids = $this->translation->where(['key' => 'name'])->where(function ($query) use ($key) {
                foreach ($key as $value) {
                    $query->orWhere('value', 'like', "%{$value}%");
                }
            })->pluck('translationable_id')->toArray();
            $paginator = $this->product->active()->whereIn('id', $ids)->withCount(['wishlist'])->with(['rating'])
                ->paginate($request['limit'], ['*'], 'page', $request['offset']);
            $products = [
                'total_size' => $paginator->total(),
                'limit' => $request['limit'],
                'offset' => $request['offset'],
                'products' => $paginator->items()
            ];
        }

        //search log
        $auth_user = auth('api')->user();
        $keyword = strtolower($request['name']);

        $recent_search = $this->recent_search->firstOrCreate(['keyword' => $keyword], [
            'keyword' => $keyword,
        ]);

        $recent_search_user = $this->searched_keyword_user;
        $recent_search_user->recent_search_id = $recent_search->id;
        $recent_search_user->user_id = $auth_user->id ?? null;
        $recent_search_user->save();

        $searched_count = $this->searched_keyword_count;
        $searched_count->recent_search_id = $recent_search->id;
        $searched_count->keyword_count = 1;
        $searched_count->save();

        $category_ids = [];
        foreach ($products['products'] as $searched_result){
            $categories =  json_decode($searched_result['category_ids']);
            if(!is_null($categories) && count($categories) > 0) {
                foreach ($categories as $value) {
                    if ($value->position == 1) {
                        $category_ids[] = $value->id;
                    }
                }
            }

            $searched_product_data = $this->searched_product->firstOrCreate([
                'recent_search_id' => $recent_search->id,
                'product_id' => $searched_result->id
            ], [
                'recent_search_id' => $recent_search->id,
                'product_id' => $searched_result->id
            ]);

            if (auth('api')->user()){
                $product_searched_by_user = $this->product_searched_by_user->firstOrCreate([
                    'user_id' => $auth_user->id,
                    'product_id' => $searched_result->id
                ], [
                    'user_id' => $auth_user->id,
                    'product_id' => $searched_result->id
                ]);
            }
        }

        $category_ids = array_unique($category_ids);
        foreach ($category_ids as $cat_id){
            $searched_category_data = $this->searched_category->firstOrCreate([
                'recent_search_id' => $recent_search->id,
                'category_id' => $cat_id,
            ], [
                'recent_search_id' => $recent_search->id,
                'category_id' => $cat_id,
            ]);

            if (auth('api')->user()){
                $category_searched_by_user = $this->category_searched_by_user->firstOrCreate([
                    'user_id' => $auth_user->id,
                    'category_id' => $cat_id
                ], [
                    'user_id' => $auth_user->id,
                    'category_id' => $cat_id
                ]);
            }

        }

        $products['products'] = Helpers::product_data_formatting($products['products'], true);
        ProductLogic::cal_rating_and_review($products);
        ProductLogic::getSoldProducts($products['products']);
        return response()->json($products, 200);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function get_product(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        try {
            $product = ProductLogic::get_product($id);
            if (!isset($product)) {
                return response()->json(['errors' => ['code' => 'product-001', 'message' => 'Product not found!']], 404);
            }

            $product = Helpers::product_data_formatting($product, false);

            $product->increment('view_count');

            if($request->has('attribute') && $request->attribute == 'product' && !is_null(auth('api')->user())) {

                $visited_product = $this->visited_product;
                $visited_product->user_id = auth('api')->user()->id ?? null;
                $visited_product->product_id = $product->id;
                $visited_product->save();
            }

            $all_over_rating = '';
            $total_reviews = '';
            if(!empty($product['rating'][0])) {
                $all_over_rating = ($product['rating'][0]->total/($product['rating'][0]->count * 5)) * 100;
                $total_reviews = $product['rating'][0]->count;
            }

            $product['overall_rating'] = $all_over_rating;
            $product['total_reviews'] = $total_reviews;

            $product['sold_products'] = !empty($product["soldProduct"][0]["sold_products"]) ? $product["soldProduct"][0]["sold_products"] : 0;
            $product['total_product_count'] = $product['sold_products'] + $product['total_stock'];
        
            return response()->json($product, 200);

        } catch (\Exception $e) {
            return response()->json(['errors' => ['code' => 'product-001', 'message' => 'Product not found!']], 404);
        }
    }

    public static function get_seo_product(Request $request, $seo): \Illuminate\Http\JsonResponse
    {
        try {
            $product = ProductLogic::get_seo_product($seo,$request->header('x-localization'));
            if (!isset($product)) {
                return response()->json(['errors' => ['code' => 'product-001', 'message' => 'Product not found!']], 404);
            }

            $product = Helpers::product_data_formatting($product, false);

            $product->increment('view_count');

            if($request->has('attribute') && $request->attribute == 'product' && !is_null(auth('api')->user())) {

                $visited_product = $this->visited_product;
                $visited_product->user_id = auth('api')->user()->id ?? null;
                $visited_product->product_id = $product->id;
                $visited_product->save();
            }

            $all_over_rating = '';
            $total_reviews = '';
            if(!empty($product['rating'][0])) {
                $all_over_rating = ($product['rating'][0]->total/($product['rating'][0]->count * 5)) * 100;
                $total_reviews = $product['rating'][0]->count;
            }

            $product['overall_rating'] = $all_over_rating;
            $product['total_reviews'] = $total_reviews;

            $product['sold_products'] = !empty($product["soldProduct"][0]["sold_products"]) ? $product["soldProduct"][0]["sold_products"] : 0;
            $product['total_product_count'] = $product['sold_products'] + $product['total_stock'];
            
            return response()->json($product, 200);

        } catch (\Exception $e) {
            return response()->json(['errors' => ['code' => 'product-001', 'message' => 'Product not found!']], 404);
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function get_related_products($id): \Illuminate\Http\JsonResponse
    {
        if ($this->product->find($id)) {
            $products = ProductLogic::get_related_products($id);
            $products = Helpers::product_data_formatting($products, true);
            ProductLogic::cal_rating_and_review($products);
            ProductLogic::getSoldProducts($products);
            return response()->json($products, 200);
        }
        return response()->json([
            'errors' => ['code' => 'product-001', 'message' => 'Product not found!'],
        ], 404);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function get_product_reviews($id): \Illuminate\Http\JsonResponse
    {
        $reviews = $this->review->with(['customer'])->where(['product_id' => $id])->get();
        $storage = [];
        foreach ($reviews as $item) {
            $item['attachment'] = json_decode($item['attachment']);
            $storage[] = $item;
        }

        return response()->json($storage, 200);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function get_product_rating($id): \Illuminate\Http\JsonResponse
    {
        try {
            $product = $this->product->find($id);
            $overallRating = ProductLogic::get_overall_rating($product->reviews);
            return response()->json(floatval($overallRating[0]), 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e], 403);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_rated_three_products(){ 
        $products = ProductLogic::get_most_reviewed_products(3);
        // $products = Helpers::product_data_formatting($products['products'], true);
        // ProductLogic::cal_rating_and_review($products);
        // ProductLogic::getSoldProducts($products);
        // usort($products, function ($a, $b) {
        //     return $b['overall_rating'] - $a['overall_rating'];
        // });
        return response()->json($products, 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function submit_product_review(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            // 'order_id' => 'required',
            'comment' => 'required',
            'rating' => 'required|numeric|max:5',
        ]);

        $product = $this->product->find($request->product_id);
        if (isset($product) == false) {
            $validator->errors()->add('product_id', 'There is no such product');
        }

        $multi_review = $this->review->where(['product_id' => $request->product_id, 'user_id' => $request->user()->id])->first();
        if (isset($multi_review)) {
            $review = $multi_review;
        } else {
            $review = $this->review;
        }

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $image_array = [];
        if (!empty($request->file('attachment'))) {
            foreach ($request->file('attachment') as $image) {
                if ($image != null) {
                    if (!Storage::disk('public')->exists('review')) {
                        Storage::disk('public')->makeDirectory('review');
                    }
                    $image_array[] = Storage::disk('public')->put('review', $image);
                }
            }
        }

        $review->user_id = $request->user()->id;
        $review->product_id = $request->product_id;
        $review->order_id = !empty($request->order_id) ? $request->order_id : null;
        $review->comment = $request->comment;
        $review->rating = $request->rating;
        $review->attachment = json_encode($image_array);
        $review->save();

        return response()->json(['message' => 'successfully review submitted!'], 200);
    }

    /**
     * @return JsonResponse
     */
    public function get_discounted_products(): \Illuminate\Http\JsonResponse
    {
        try {
            $products = Helpers::product_data_formatting($this->product->active()->withCount(['wishlist'])->with(['rating'])->where('discount', '>', 0)->get(), true);
            ProductLogic::cal_rating_and_review($products);
            ProductLogic::getSoldProducts($products['products']);
            return response()->json($products, 200);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => ['code' => 'product-001', 'message' => 'Set menu not found!'],
            ], 404);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_daily_need_products(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $paginator = $this->product->active()->withCount(['wishlist'])->with(['rating'])->where(['daily_needs' => 1])->orderBy('id', 'desc')->paginate($request['limit'], ['*'], 'page', $request['offset']);
            $products = [
                'total_size' => $paginator->total(),
                'limit' => $request['limit'],
                'offset' => $request['offset'],
                'products' => $paginator->items()
            ];
            $paginator = Helpers::product_data_formatting($products['products'], true);
            ProductLogic::cal_rating_and_review($products);
            ProductLogic::getSoldProducts($products['products']);
            return response()->json($products, 200);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => ['code' => 'product-001', 'message' => 'Products not found!'],
            ], 404);
        }
    }

    //favorite products

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_favorite_products(Request $request): \Illuminate\Http\JsonResponse
    {
        $products = ProductLogic::get_favorite_products($request['limit'], $request['offset'], $request->user()->id);
        ProductLogic::cal_rating_and_review($products);
        ProductLogic::getSoldProducts($products['products']);
        return response()->json($products, 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_popular_products(Request $request): \Illuminate\Http\JsonResponse
    {
        $products = ProductLogic::get_popular_products($request['limit'], $request['offset']);
        $products['products'] = Helpers::product_data_formatting($products['products'], true);
        ProductLogic::cal_rating_and_review($products);
        ProductLogic::getSoldProducts($products['products']);
        return response()->json($products, 200);

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function add_favorite_products(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'product_ids' => 'required|array',
        ],
            [
                'product_ids.required' => 'product_ids ' .translate('is required'),
                'product_ids.array' => 'product_ids ' .translate('must be an array')
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $favorite_ids = [];
        foreach ($request->product_ids as $id) {
            $values = [
                'user_id' => $request->user()->id,
                'product_id' => $id,
                'created_at' => now(),
                'updated_at' => now()
            ];
            $favorite_ids[] = $values;
        }
        $this->favorite_product->insert($favorite_ids);

        return response()->json(['message' => translate('Item added to favourite list!')], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function remove_favorite_products(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'product_ids' => 'required|array',
        ],
            [
                'product_ids.required' => 'product_ids ' .translate('is required'),
                'product_ids.array' => 'product_ids ' .translate('must be an array')
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $collection = $this->favorite_product->whereIn('product_id', $request->product_ids)->get(['id']);
        $this->favorite_product->destroy($collection->toArray());

        return response()->json(['message' => translate('Item removed from favourite list! ')], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function featured_products(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $paginator = $this->product->active()
                ->withCount(['wishlist'])
                ->with(['rating','manufacturer'])
                ->where(['is_featured' => 1])
                ->orderBy('id', 'desc')
                ->paginate($request['limit'], ['*'], 'page', $request['offset']);

            $products = [
                'total_size' => $paginator->total(),
                'limit' => $request['limit'],
                'offset' => $request['offset'],
                'products' => $paginator->items()
            ];
            $paginator = Helpers::product_data_formatting($products['products'], true);
            ProductLogic::cal_rating_and_review($products); 
            ProductLogic::getSoldProducts($products['products']);           
            return response()->json($products, 200);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => ['code' => 'product-001', 'message' => 'Products not found!'],
            ], 404);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_most_viewed_products(Request $request): \Illuminate\Http\JsonResponse
    {
        $products = ProductLogic::get_most_viewed_products($request['limit'], $request['offset']);
        $products['products'] = Helpers::product_data_formatting($products['products'], true);
        ProductLogic::cal_rating_and_review($products);
        ProductLogic::getSoldProducts($products['products']);
        return response()->json($products, 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_trending_products(Request $request): \Illuminate\Http\JsonResponse
    {
        $products = ProductLogic::get_trending_products($request['limit'], $request['offset']);
        $products['products'] = Helpers::product_data_formatting($products['products'], true);
        ProductLogic::cal_rating_and_review($products);
        ProductLogic::getSoldProducts($products['products']);
        return response()->json($products, 200);
    }

    public function get_trending_three_products(Request $request): \Illuminate\Http\JsonResponse
    {
        $products = ProductLogic::get_trending_products($request['limit'], $request['offset'],3);
        $products['products'] = Helpers::product_data_formatting($products['products'], true);
        ProductLogic::cal_rating_and_review($products);
        ProductLogic::getSoldProducts($products['products']);
        return response()->json($products, 200);
    }
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_recommended_products(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = $request->user();
        $products = ProductLogic::get_recommended_products($user, $request['limit'], $request['offset']);
        $products['products'] = Helpers::product_data_formatting($products['products'], true);
        ProductLogic::cal_rating_and_review($products);
        ProductLogic::getSoldProducts($products['products']);
        return response()->json($products, 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_most_reviewed_products(Request $request): \Illuminate\Http\JsonResponse
    {
        $products = ProductLogic::get_most_reviewed_products($request['limit'], $request['offset']);
        $products['products'] = Helpers::product_data_formatting($products['products'], true);
        ProductLogic::cal_rating_and_review($products);
        ProductLogic::getSoldProducts($products['products']);
        usort($products['products'], function ($a, $b) {
            return $b['overall_rating'] - $a['overall_rating'];
        });
        return response()->json($products, 200);
    }


    public function get_sale_products(Request $request): \Illuminate\Http\JsonResponse {
        $products = ProductLogic::get_sale_products($request['limit'], $request['offset']);
        $products['products'] = Helpers::product_data_formatting($products['products'], true);
        ProductLogic::cal_rating_and_review($products);
        return response()->json($products, 200);
    }

    // public function get_flash_sale_products(Request $request): \Illuminate\Http\JsonResponse
    // {
    //     $products = ProductLogic::get_flash_sale_products($request['limit'], $request['offset']);
    //     echo "<pre>";print_r($products);die;
    //     $products = Helpers::product_data_formatting($products, true);
    //     return response()->json($products, 200);
    // }

    public function get_max_price()
    {
        $max = Product::select('price')->max('price');
        return response()->json(array('max_price' => $max), 200);
    }

    public function restored_products()
    {
        $restoredProducts = $this->product->active()
        ->withCount(['wishlist'])
        ->with(['rating', 'active_reviews','manufacturer', 'soldProduct'])
        ->where('resotred_at', '<>' , null)
        ->orderBy('resotred_at', 'DESC')
        ->paginate(5, ['*'], 'page', 1);

        $products = Helpers::product_data_formatting($restoredProducts, true);
        ProductLogic::cal_rating_and_review($products);
        ProductLogic::getSoldProducts($products);
        
        return response()->json($products, 200);
    }

    public function get_seo_category($seo){
        // try {
            $data = [];
            $categoryData = Category::with('translations')->where(function ($q) use($seo){
                $q->where('seo_en',$seo)->orWhere('seo_ja',$seo);
            })->where('status',1)->first();
            if(!empty($categoryData)){
                $data['categories'] = $categoryData;
                $productData =Product::active()
                ->withCount(['wishlist','order_details','relatedProducts'])
                ->with(['rating', 'active_reviews','manufacturer', 'soldProduct','relatedProducts.relatedProduct'])
                ->byCategory("{$categoryData->id}")
                ->take(1)
                ->first();
                $data['products'] = $productData;
            }
            return response()->json([$data], 200);
        // } catch (\Exception $e) {
        //  return response()->json([], 200);
        // }
    }
}

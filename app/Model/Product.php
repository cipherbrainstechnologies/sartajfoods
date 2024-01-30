<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Model\FlashDeal;
use App\Model\HotDeals;
use App\Model\RelatedProducts;
use Illuminate\Support\Facades\Date;

class Product extends Model
{

    protected $appends = ['actual_price','overall_rating','total_reviews','badges'];
    protected $casts = [
        'tax'         => 'float',
        'price'       => 'float',
        'capacity'    => 'float',
        'status'      => 'integer',
        'discount'    => 'float',
        'total_stock' => 'integer',
        'set_menu'    => 'integer',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
        'is_featured'  => 'integer',
        'category_ids' => 'array',
        
    ];

    public function getImageAttribute()
    {
        $imageUrl = [];
        if(!empty($this->attributes['image'])){
            $images = json_decode($this->attributes['image'],true);
            foreach($images as $image){
                array_push($imageUrl,config('app.url').'/storage/product/'.$image);
            }
        }
        return $imageUrl;
    }

    public function relatedProducts()
    {
        return $this->hasMany(RelatedProducts::class, 'product_id');
    }

    public function hotDeal()
    {
        return $this->hasOne(HotDeals::class);
    }

    public function translations(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany('App\Model\Translation', 'translationable');
    }

    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }

    public function reviews(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Review::class)->latest();
    }

    public function active_reviews(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Review::class)->where(['is_active' => 1])->latest();
    }

    public function wishlist(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Wishlist::class)->latest();
    }

    public function rating(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Review::class)
            ->where('is_active', 1)
            ->select(DB::raw('avg(rating) average, sum(rating) total, product_id, count(product_id) count'))
            ->groupBy('product_id');
    }

    public function all_rating(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Review::class)
            ->select(DB::raw('avg(rating) average, sum(rating) total, product_id, count(product_id) count'))
            ->groupBy('product_id');
    }

    protected static function booted(): void
    {
        static::addGlobalScope('translate', function (Builder $builder) {
            $builder->with(['translations']);
        });
    }

    public function order_details(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function tags(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function manufacturer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Manufacturer::class);
    }

    // public function getImageAttribute($value)
    // {
    //     // Get the base URL for the images
    //     $baseUrl = config('app.url') . '/storage/product/';

    //     // Append the base URL to each image filename
    //     return array_map(function ($filename) use ($baseUrl) {
    //         return $baseUrl . $filename;
    //     }, json_decode($value));
    // }
    
    public function getOverallRatingAttribute()
    {
        
        if (!empty($this->rating[0])) {
            return ($this->rating[0]->total / ($this->rating[0]->count * 5)) * 100;
        }
        
        return 0;
    }

    public function getBadgesAttribute(){
        
        $badges = [];
        $currentDate = now();
        $twoWeeksAgo = $currentDate->subDays(14);
        
        if ($this->created_at >= $twoWeeksAgo) {
            array_push($badges,'new');
        }
        // Check if the product is "hot"
        if ($this->order_details()->count() > 1) { // Change this condition as per your definition of "hot"                    
            array_push($badges,'hot');
        }
        return $badges;
    }

    public function getActualPriceAttribute(){
        if(!empty($this->hotDeal) &&  $this->hotDeal['start_date'] <= now() && Date::parse($this->hotDeal['end_date'])->startOfDay() >= Date::parse(now())->startOfDay()){
            return $this->price - ($this->price * $this->hotDeal['discount'] / 100);
        }elseif(!empty($this->sale_price) && $this->sale_start_date <= now() && Date::parse($this->sale_end_date)->startOfDay() >= Date::parse(now())->startOfDay()){
            return $this->sale_price;
        }elseif(!empty($this->discount)){
            if($this->discount_type=="percent"){
                return $this->price - ($this->price * $this->discount / 100);
            }else{
                return $this->price - $this->discount;
            }
        }else{
            return $this->price;
        }
    }

    // public function getMaximumOrderQuantityAttribute(){
    //     if($this['total_stock'] > $this['maximum_order_quantity']){
    //         return $this['maximum_order_quantity'];
    //     }else{
    //         return $this['total_stock'];
    //     }
    // }

    public function getTotalReviewsAttribute()
    {
        if (!empty($this->rating[0])) {
            return $this->rating[0]->count;
        }

        return 0;
    }

    
    public function getManufacturerImageAttribute()
    {
        // $baseURL = 'http://192.168.1.30:8000';


        if (!empty($this->manufacturer->image)) {
            return config('app.url') . '/storage/manufacturer/' . $this->manufacturer->image;
        }
        return null;
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function soldProduct(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        $sold_product = $this->hasMany(OrderDetail::class)
        ->select(DB::raw('sum(quantity) sold_products, product_id'))
        ->groupBy('product_id');
        return $sold_product;
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->whereJsonContains('category_ids', ['id' =>$categoryId]);
    }

}

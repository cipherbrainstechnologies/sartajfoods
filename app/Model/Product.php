<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Model\FlashDeal;

class Product extends Model
{
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
    ];

    

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
            $builder->with(['translations' => function($query){
                return $query->where('locale', app()->getLocale());
            }]);
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
        return $this->BelongsTo(Manufacturer::class);
    }
    
    public function getOverallRatingAttribute()
    {
        if (!empty($this->rating[0])) {
            return ($this->rating[0]->total / ($this->rating[0]->count * 5)) * 100;
        }

        return 0;
    }

    public function getTotalReviewsAttribute()
    {
        if (!empty($this->rating[0])) {
            return $this->rating[0]->count;
        }

        return 0;
    }

    
    public function getManufacturerImageAttribute()
    {
        $baseURL = 'http://192.168.1.30:8000';

        if (!empty($this->manufacturer->image)) {
            return $baseURL . '/storage/manufacturer/' . $this->manufacturer->image;
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

}

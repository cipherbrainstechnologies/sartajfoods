<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'user_id', 'quantity','price','special_price','sub_total','discount','discount_type','total_discount','eight_percent','ten_percent'];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTotalEightPercentAttribute()
    {
        // Assuming your tax is stored in the 'tax' attribute
        $taxEightPercent = $this->tax ?? 0;

        // Assuming your product is related to the Cart model through a relationship named 'carts'
        $quantity = $this->carts->sum('quantity');

        return $taxEightPercent * $quantity;
    }
}

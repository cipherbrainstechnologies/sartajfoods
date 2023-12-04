<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotDeals extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'discount',
        'start_date',
        'end_date'
    ];
}

<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regions extends Model
{
    use HasFactory;

    protected $table="regions";
    protected $fillable = ['name','status','maximum_order_amt','dry_delivery_charge','frozen_weight','frozen_delivery_charge'];

    public function translations(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany('App\Model\Translation', 'translationable');
    }
    protected static function booted(): void
    {
        static::addGlobalScope('translate', function (Builder $builder) {
            $builder->with(['translations']);
        });
    }

    public function address_details(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Regions::class);
    }

}

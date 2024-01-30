<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Manufacturer extends Model
{
    public function translations()
    {
        return $this->morphMany('App\Model\Translation', 'translationable');
    }

    public function getNameAttribute($name)
    {
        if (auth('admin')->check() || auth('branch')->check()) {
            return $name;
        }
        return $this->translations[0]->value ?? $name;
    }

    protected static function booted(): void
    {
        static::addGlobalScope('translate', function (Builder $builder) {
            $builder->with(['translations']);
        });
    }

    public function products(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Product::class)->latest();
    }

    public function relatedProducts()
    {
        return $this->hasMany(Product::class, 'manufacturer_id');
    }
}

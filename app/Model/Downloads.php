<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Downloads extends Model
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
}

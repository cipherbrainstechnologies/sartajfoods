<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\CentralLogics\Helpers;

class Cities extends Model
{
    use HasFactory;

    protected  $table = 'cities';
    protected $fillable = ['name','status'];
}

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecentActivity extends Model
{
    protected $table = 'recent_activity';
    protected $fillable = [
        'user_id',
        'message',
    ];
}

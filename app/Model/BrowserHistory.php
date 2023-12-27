<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrowserHistory extends Model
{
    use HasFactory;

    protected $table = "browser_history";
    protected $fillable = ['user_id','ip_address','forwarded_ip','user_agent','accept_language'];
}

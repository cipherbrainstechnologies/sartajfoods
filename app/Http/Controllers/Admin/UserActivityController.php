<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\RecentActivity;

class UserActivityController extends Controller
{
    public function __construct(
        private RecentActivity $recent_activity,
    ){}
    public function list(Request $request){
        $query_param = [];
        $search = $request['search'];
        $query =RecentActivity::select('*');
        if ($request->has('search')){
            $key = explode(' ', $request['search']);
            $query = $this->recent_activity->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('user_id', 'like', "%{$value}%")
                        ->orWhere('message', 'like', "%{$value}%");
                }
            })->latest();
            $query_param = ['search' => $request['search']];
        }else{
            $query = $this->recent_activity->latest();
        }
        $activities = $query->paginate(Helpers::getPagination())->appends($query_param);
        return view('admin-views.user-activity.list', compact('activities','search'));
    }

    
}

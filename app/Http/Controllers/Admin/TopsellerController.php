<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Admin;
use App\Model\Branch;
use App\Model\Category;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\Product;
use App\Model\Review;
use App\Model\RecentActivity;
use App\User;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TopsellerController extends Controller
{
    public function __construct(
    private Order $order,
    private OrderDetail $order_detail,
    private Product $product,
    ){}
    public function list(Request $request){
        $query_param = [];
         $top_sell = $this->order_detail->with(['product'])
            ->whereHas('order', function ($query){
                $query->where('order_status', 'delivered');
            })
            ->select('product_id', DB::raw('SUM(quantity) as count'))
            ->groupBy('product_id')
            ->orderBy("count", 'desc')
             ->latest()
            ->paginate(Helpers::getPagination())
            ->appends($query_param);
        return view('admin-views.top-seller.list', compact('top_sell'));
    }

    
}

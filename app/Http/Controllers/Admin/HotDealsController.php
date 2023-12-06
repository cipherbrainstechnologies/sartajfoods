<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Model\HotDeals;
use App\Model\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Brian2694\Toastr\Facades\Toastr;
use App\CentralLogics\Helpers;

class HotDealsController extends Controller
{
    public function __construct(
        private HotDeals $hotDeals,
        private Product $product,
     ){}

    public function index()
    {
        $hotDeals = $this->hotDeals->first();
        $products =  $this->product->all();
        return view('admin-views.hot-deals.index', compact('hotDeals', 'products'));
    }

    public function store(Request $request)
    {
        $hotDeals = $this->hotDeals->first();
        $request->validate([
            'product' => 'required',
            'discount' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'image' => !empty($hotDeals) ? '' :'required',
        ], [
            'product.required' => translate('Product is required!'),
            'discount.required' => translate('Discount is required!'),
            'start_date.required' => translate('Start date is required!'),
            'end_date.required' => translate('End date is required!'),
            'image.required' => translate('Poster is required!'),
        ]);
       

       if(!empty($hotDeals)) {
        $hotDealsData = $this->hotDeals->find($hotDeals->id);
        $hotDealsData->image = $request->has('image') ? Helpers::update('deals/', $hotDealsData->image, 'png', $request->file('image')) : $hotDealsData->image;
        Toastr::success(translate('Deals updated successfully!'));
       } else {
        $hotDealsData = new HotDeals();
        $hotDealsData->image =  Helpers::upload('deals/', 'png', $request->file('image'));
        Toastr::success(translate('Deals added successfully!'));
       }

       $hotDealsData->title = $request->title;
       $hotDealsData->product_id = $request->product;
       $hotDealsData->discount = $request->discount;
       $hotDealsData->start_date = $request->start_date;
       $hotDealsData->end_date = $request->end_date;
       $hotDealsData->save();
       return back();
    }
     
}

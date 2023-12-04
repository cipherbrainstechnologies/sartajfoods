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
        $request->validate([
            'product' => 'required',
            'discount' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'image' => 'required',
        ], [
            'product.required' => translate('Product is required!'),
            'discount.required' => translate('Discount is required!'),
            'start_date.required' => translate('Start date is required!'),
            'end_date.required' => translate('End date is required!'),
            'image.required' => translate('Poster is required!'),
        ]);
       $hotDeals = $this->hotDeals->first();

       $data = [
        'product_id' => $request->product,
        'discount' => $request->discount,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
       ];

       if(!empty($hotDeals)) {
        $hotDealsUpadate = $this->hotDeals->find($hotDeals->id);
        $hotDealsUpadate->image = $request->has('image') ? Helpers::update('deals/', $hotDealsUpadate->image, 'png', $request->file('image')) : $hotDealsUpadate->image;
        $hotDealsUpadate->update($data);
        Toastr::success(translate('Deals updated successfully!'));
        return back();
       } else {
        $data['image'] =  Helpers::upload('deals/', 'png', $request->file('image'));
        $this->hotDeals->create($data);
        Toastr::success(translate('Deals added successfully!'));
        return back();
       }
    }
     
}

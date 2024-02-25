<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Model\Regions;
use Brian2694\Toastr\Facades\Toastr;
use App\Model\Translation;

class RegionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function __construct(
        private Regions $regions,
        private Translation $translation,
    ){}
    public function index(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $regions = $this->regions->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $regions = $this->regions;
        }
        $regions = $regions->orderBY('id', 'ASC')->paginate(Helpers::getPagination())->appends($query_param);
        return view('admin-views.regions.index', compact('regions', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:regions',
            'minimum_order_value' => 'numeric',
            'minimum_amt_delivery_charge' => 'numeric',
            'minimum_weight' => 'numeric',
            'minimum_weight_delivery_charge' => 'numeric',
        ],[
            'name.unique' => 'The region name has already been taken.',
            'minimum_order_value.numeric' => 'The minimum order value must be a numeric value.',
            'minimum_amt_delivery_charge.numeric' => 'The dry product delivery charge must be a numeric value.',
            'minimum_weight.numeric' => 'The frozen weigh must be a numeric value.',
            'minimum_weight_delivery_charge.numeric' => 'The frozen product delivery charge must be a numeric value.',
        ]);

        foreach ($request->name as $name) {
            if (strlen($name) > 255) {
                toastr::error(translate('Name is too long!'));
                return back();
            }
        }
        $ct = $this->regions;
        $ct->name = $request->name[array_search('en', $request->lang)];
        $ct->maximum_order_amt = $request->minimum_order_value;
        $ct->dry_delivery_charge = $request->minimum_amt_delivery_charge;
        $ct->frozen_weight = $request->minimum_weight;
        $ct->frozen_delivery_charge	 = $request->minimum_weight_delivery_charge;
        $ct->status = 1;//$request->status;
        $ct->save();
        foreach($request->lang as $index=>$key)
        {
            // if($request->name[$index] && $key != 'en')
            // {
                $data[] = array(
                    'translationable_type' => 'App\Model\Regions',
                    'translationable_id' => $ct->id,
                    'locale' => $key,
                    'key' => 'name',
                    'value' => $request->name[$index],
                );
            
        }
        $this->translation->insert($data);        
        Toastr::success(translate('Regions created successfully'));
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $regions = $this->regions->with('translations')->find($id);
        return view('admin-views.regions.edit', compact('regions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        
        //
        $request->validate([
            'name' => 'required|unique:regions,name,'.$id,
            'minimum_order_value' => 'numeric',
            'minimum_amt_delivery_charge' => 'numeric',
            'minimum_weight' => 'numeric',
            'minimum_weight_delivery_charge' => 'numeric',

        ],[
            'name.unique' => 'The region name has already been taken.',
            'minimum_order_value.numeric' => 'The minimum order value must be a numeric value.',
            'minimum_amt_delivery_charge.numeric' => 'The dry product delivery charge must be a numeric value.',
            'minimum_weight.numeric' => 'The frozen weigh must be a numeric value.',
            'minimum_weight_delivery_charge.numeric' => 'The frozen product delivery charge must be a numeric value.',
        ]);
        foreach ($request->name as $name) {
            if (strlen($name) > 255) {
                toastr::error(translate('Name is too long!'));
                return back();
            }
        }
        $ct = $this->regions->find($id);
        $ct->name = $request->name[array_search('en', $request->lang)];
        $ct->maximum_order_amt = $request->minimum_order_value;
        $ct->dry_delivery_charge = $request->minimum_amt_delivery_charge;
        $ct->frozen_weight = $request->minimum_weight;
        $ct->frozen_delivery_charge	 = $request->minimum_weight_delivery_charge;
        $ct->status = 1;//$request->status;
        $ct->save();
        foreach($request->lang as $index=>$key)
        {
            Translation::updateOrInsert(
                ['translationable_type'  => 'App\Model\Regions',
                    'translationable_id'    => $ct->id,
                    'locale'                => $key,
                    'key'                   => 'name'
                ],
                ['value'                 => $request->name[$index]]
            );
        }
        Toastr::success(translate('Region updated successfully'));
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $regions = $this->regions->find($id);
        if(!empty($regions)){
            $regions->delete();
            Toastr::success(translate('Region removed!'));
        }
        return back();
    }
}

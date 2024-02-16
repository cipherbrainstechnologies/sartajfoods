@extends('layouts.admin.app')

@section('title', translate('Add new product'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{asset('public/assets/admin/css/tags-input.min.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/admin')}}/css/select2.min.css" rel="stylesheet"/>
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/add-product.png')}}" class="w--24" alt="">
                </span>
                <span>
                    {{translate('add New Product')}}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->

        <form action="javascript:" method="post" id="product_form"
                enctype="multipart/form-data" class="row g-2">
            @csrf
            @php($data = Helpers::get_business_settings('language'))
            @php($default_lang = Helpers::get_default_language())

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body pt-2">
                        @if($data && array_key_exists('code', $data[0]))

                            <ul class="nav nav-tabs mb-4">

                                @foreach($data as $lang)
                                    <li class="nav-item">
                                        <a class="nav-link lang_link {{$lang['default'] == true ? 'active':''}}" href="#" id="{{$lang['code']}}-link">{{Helpers::get_language_name($lang['code']).'('.strtoupper($lang['code']).')'}}</a>
                                    </li>
                                @endforeach

                            </ul>
                            @foreach($data as $lang)
                                
                                <div class="{{$lang['default'] == false ? 'd-none':''}} lang_form" id="{{$lang['code']}}-form">
                                    <div class="form-group">
                                        <label class="input-label" for="{{$lang['code']}}_name">{{translate('name')}} ({{strtoupper($lang['code'])}})</label>
                                        <input type="text" name="name[]" id="{{$lang['code']}}_name" class="form-control"
                                            placeholder="{{translate('New Product')}}" {{$lang['status'] == true ? 'required':''}}
                                            @if($lang['status'] == true) oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                    </div>
                                    <input type="hidden" name="lang[]" value="{{$lang['code']}}">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="{{$lang['code']}}_description">{{translate('short')}} {{translate('description')}}  ({{strtoupper($lang['code'])}})</label>
                                        <textarea name="description[]" class="form-control h--172px" id=""></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label" for="{{$lang['code']}}_meta_title">{{translate('meta tag title')}} ({{strtoupper($lang['code'])}})</label>
                                        <input type="text" name="meta_title[]" class="form-control" id="" placeholder="{{translate('meta tag title')}}" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="{{$lang['code']}}__meta_description">{{translate('meta tag description')}}  ({{strtoupper($lang['code'])}})</label>
                                        <textarea name="meta_description[]" class="form-control h--172px" id="{{$lang['code']}}__meta_description"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label" for="{{$lang['code']}}__meta_keywords">{{translate('meta tag keywords')}}  ({{strtoupper($lang['code'])}})</label>
                                        <textarea name="meta_keywords[]" class="form-control h--172px" id="{{$lang['code']}}_meta_keywords"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label" for="{{$lang['code']}}_product_tags">{{translate('product tags')}} ({{strtoupper($lang['code'])}})</label>
                                        <input type="text" name="product_tags[]" class="form-control" id="{{$lang['code']}}_product_tags" placeholder="{{translate('product tags')}}">
                                    </div>
                                    @if($lang['code'] == "en")
                                        <div class="form-group">
                                            <label class="input-label" for="{{$lang['code']}}_seo_en">{{translate('SEO')}} ({{strtoupper($lang['code'])}})</label>
                                            <input type="text" name="en_seo" class="form-control" id="{{$lang['code']}}_seo" placeholder="{{translate('SEO')}} ({{translate('EN')}})">
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <label class="input-label" for="{{$lang['code']}}_seo_ja">{{translate('SEO')}} ({{strtoupper($lang['code'])}})</label>
                                            <input type="text" name="ja_seo" class="form-control" id="{{$lang['code']}}_seo" placeholder="{{translate('SEO')}} ({{translate('JA')}})">
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div id="{{$default_lang}}-form">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('name')}} (EN)</label>
                                    <input type="text" name="name[]" class="form-control" placeholder="{{translate('New Product')}}" required>
                                </div>
                                <input type="hidden" name="lang[]" value="en">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('short')}} {{translate('description')}} (EN)</label>
                                    <textarea name="description[]" class="form-control h--172px" id=""></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('meta tag title')}} (EN)</label>
                                    <input type="text" name="meta_title[]" class="form-control" placeholder="{{translate('meta tag title')}}" required>
                                </div>
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('meta tag description')}} (EN)</label>
                                    <textarea name="meta_description[]" class="form-control h--172px" id=""></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('meta tag keywords')}} (EN)</label>
                                    <textarea name="meta_keywords[]" class="form-control h--172px" id=""></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('product tags')}} (EN)</label>
                                    <input type="text" name="product_tags[]" class="form-control" placeholder="{{translate('product tags')}}">
                                </div>

                                @if($lang['code'] == "en")
                                    <div class="form-group">
                                        <label class="input-label" for="{{$lang['code']}}_seo_en">{{translate('SEO')}} (EN)</label>
                                        <input type="text" name="en_seo" class="form-control" id="{{$lang['code']}}_seo" placeholder="{{translate('SEO')}} ({{translate('EN')}})">
                                    </div>
                                @else
                                    <div class="form-group">
                                        <label class="input-label" for="{{$lang['code']}}_seo_ja">{{translate('SEO')}} (EN)</label>
                                        <input type="text" name="ja_seo" class="form-control" id="{{$lang['code']}}_seo" placeholder="{{translate('SEO')}} ({{translate('JA')}})">
                                    </div>
                                @endif
                                
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <span class="card-header-icon">
                                <i class="tio-user"></i>
                            </span>
                            <span>
                                {{translate('category')}}
                            </span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlSelect1">{{translate('category')}}<span
                                            class="input-label-secondary">*</span></label>
                                    <select name="category_id" class="form-control js-select2-custom"
                                            onchange="getRequest('{{url('/')}}/admin/product/get-categories?parent_id='+this.value,'sub-categories')">
                                        <option value="">---{{translate('select')}}---</option>
                                        @foreach($categories as $category)
                                            <option value="{{$category['id']}}">{{$category['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlSelect1">{{translate('sub_category')}}<span
                                            class="input-label-secondary"></span></label>
                                    <select name="sub_category_id" id="sub-categories"
                                            class="form-control js-select2-custom"
                                            onchange="getRequest('{{url('/')}}/admin/product/get-categories?parent_id='+this.value,'sub-sub-categories')">
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlSelect1">{{translate('sub_category')}}<span
                                            class="input-label-secondary"></span></label>
                                    <select name="sub-sub-categories" id="sub-sub-categories"
                                            class="form-control js-select2-custom"
                                           >
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{translate('unit')}}</label>
                                    <select name="unit" class="form-control js-select2-custom">
                                        <option value="kg">{{translate('kg')}}</option>
                                        <option value="gm">{{translate('gm')}}</option>
                                        <option value="ltr">{{translate('ltr')}}</option>
                                        <option value="pc">{{translate('pc')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{translate('capacity')}}</label>
                                    <input type="number" min="0" step="0.01" value="1" name="capacity"
                                        class="form-control"
                                        placeholder="{{ translate('Ex : 54ml') }}" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="input-label"
                                           for="exampleFormControlInput1">{{translate('Maximum_Order_Quantity')}}</label>
                                    <input type="number" min="1" step="1" value="1" name="maximum_order_quantity"
                                           class="form-control"
                                           placeholder="{{ translate('Ex : 3') }}" >
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="substrack_stock">{{translate('Substrak Stock')}}<span class="input-label-secondary"></span></label>
                                    <select name="substrack_stock" id="substrack_stock" class="form-control js-select2-custom">
                                        <option value="Yes">{{translate('yes')}}</option>
                                        <option value="No">{{translate('no')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="out_of_stock_status">{{translate('Out Of Stock Status')}}<span class="input-label-secondary"></span></label>
                                    <select name="out_of_stock_status" id="out_of_stock_status" class="form-control js-select2-custom">
                                        <option value="2-3 days">{{translate('2-3 Days')}}</option>
                                        <option value="in stock">{{translate('In Stock')}}</option>
                                        <option value="out of stock">{{translate('Out Of Stock')}}</option>
                                        <option value="pre order">{{translate('Pre-Order')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="requires_shipping">{{translate('Requires Shipping')}}<span class="input-label-secondary"></span></label>
                                    <select name="requires_shipping" id="requires_shipping" class="form-control js-select2-custom">
                                        <option value="Yes">{{translate('yes')}}</option>
                                        <option value="No">{{translate('no')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="product_mark">{{translate('Product Mark')}}<span class="input-label-secondary"></span></label>
                                    <select name="product_mark[]" id="product_mark" class="form-control js-select2-custom" multiple="multiple">
                                        <option value="Halal">{{translate('Halal')}}</option>
                                        <option value="Veg">{{translate('Veg')}}</option>
                                        <option value="Vegan">{{translate('Vegan')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="product_type">{{translate('Product Type')}}<span class="input-label-secondary"></span></label>
                                    <input type="checkbox" class="" name="product_type" value="1">
                                    <label >{{ translate('frozen') }}</p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="date_available">{{translate('Date Available')}}<span class="input-label-secondary"></span></label>
                                    <label class="">
                                        <input type="date" name="date_available" id="date_available" value="{{ old('date_available') }}" class="js-flatpickr form-control flatpickr-custom" placeholder="{{ \App\CentralLogics\translate('dd/mm/yy') }}" data-hs-flatpickr-options='{ "dateFormat": "Y/m/d", "minDate": "today" }'>
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{translate('Length')}}</label>
                                    <input type="text" name="length"
                                        class="form-control"
                                        placeholder="{{ translate('0.000000') }}" >
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="length_class">{{translate('Length Class')}}<span class="input-label-secondary"></span></label>
                                    <select name="length_class" id="length_class" class="form-control js-select2-custom">
                                        <option value="Centimeter">{{translate('Centimeter')}}</option>
                                        <option value="Millimeter">{{translate('Millimeter')}}</option>
                                        <option value="inch">{{translate('inch')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{translate('weight')}}</label>
                                    <input type="text" name="weight"
                                        class="form-control"
                                        placeholder="{{ translate('0.000000') }}" >
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="weight_class">{{translate('Weight Class')}}<span class="input-label-secondary"></span></label>
                                    <select name="weight_class" id="weight_class" class="form-control js-select2-custom">
                                        <option value="Kilogram">{{translate('Kilogram')}}</option>
                                        <option value="Gram">{{translate('Gram')}}</option>
                                        <option value="Pound">{{translate('Pound')}}</option>
                                        <option value="Ounce">{{translate('Ounce')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="input-label"
                                           for="exampleFormControlInput1">{{translate('Sort Order')}}</label>
                                    <input type="number" min="1" step="1" name="sort_order"
                                           class="form-control"
                                           placeholder="{{ translate('Ex : 3') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="mt-2">
                    <div class="card min-h-116px">
                        <div class="card-body d-flex flex-column justify-content-center">
                            <div class="d-flex flex-wrap-reverse justify-content-between">
                                <div class="w-200 flex-grow-1 mr-3">
                                    {{translate('Turning Visibility off will not show this product in the user app and website')}}
                                </div>
                                <div class="d-flex align-items-center mb-2 mb-sm-0">
                                    <h5 class="mb-0 mr-2">{{ translate('Visibility') }}</h5>
                                    <label class="toggle-switch my-0">
                                        <input type="checkbox" class="toggle-switch-input" name="status" checked>
                                        <span class="toggle-switch-label mx-auto text">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <span class="card-header-icon">
                                <i class="tio-user"></i>
                            </span>
                            <span>
                                {{translate('Data Storage')}}
                            </span>
                        </h5>
                    </div>
                    <div class="card-body">
                         <div class="form-group">
                            <label class="input-label" for="model">{{translate('Model')}}<span
                                            class="input-label-secondary">*</span></label>
                            <input type="txt" name="model" id="model" class="form-control" placeholder="{{ translate('model') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="input-label" for="sku">{{translate('SKU(Stock Keeping Unit)')}}</label>
                            <input type="txt" name="sku" id="sku" class="form-control" placeholder="{{ translate('Stock Keeping Unit') }}" >
                        </div>
                        <div class="form-group">
                            <label class="input-label" for="upc">{{translate('UPC(Universal Product Code)')}}</label>
                            <input type="txt" name="upc" id="upc" class="form-control" placeholder="{{ translate('Universal Product Code') }}" >
                        </div>
                        <div class="form-group">
                            <label class="input-label" for="ena">{{translate('EAN(European Artical Number)')}}</label>
                            <input type="txt" name="ena" id="ena" class="form-control" placeholder="{{ translate('European Artical Number') }}" >
                        </div>
                        <div class="form-group">
                            <label class="input-label" for="jan">{{translate('JAN(Japanese Artical Number)')}}</label>
                            <input type="txt" name="jan" id="jan" class="form-control" placeholder="{{ translate('Japanese Artical Number') }}" >
                        </div>
                        <div class="form-group">
                            <label class="input-label" for="isbn">{{translate('ISBN(International Standard Book Number)')}}</label>
                            <input type="txt" name="isbn" id="isbn" class="form-control" placeholder="{{ translate('International Standard Book Number') }}">
                        </div>
                        <div class="form-group">
                            <label class="input-label" for="mpn">{{translate('MPN(Manufacturer Part Numbaer)')}}</label>
                            <input type="txt" name="mpn" id="mpn" class="form-control" placeholder="{{ translate('Manufacturer Part Numbaer') }}" >
                        </div>
                         <div class="form-group">
                            <label class="input-label" for="location">{{translate('Location')}}</label>
                            <input type="txt" name="location" id="location" class="form-control" placeholder="{{ translate('location') }}" >
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                    <h5 class="mb-3">{{translate('product')}} {{translate('image')}} <small
                        class="text-danger">* ( {{translate('ratio')}} 1:1 )</small></h5>
                        <div class="product--coba">
                            <div class="row g-2" id="coba"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <span class="card-header-icon">
                                <i class="tio-dollar"></i>
                            </span>
                            <span>
                                {{translate('price_information')}}
                            </span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="p-2">
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{translate('default_unit_price')}}</label>
                                        <input type="number" min="0" max="100000000" step="any" value="1" name="price"
                                            class="form-control"
                                            placeholder="{{ translate('Ex : 349') }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{translate('product_stock')}}</label>
                                        <input type="number" min="0" max="100000000" value="0" name="total_stock" class="form-control"
                                            placeholder="{{ translate('Ex : 100') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('discount_type')}}</label>
                                        <select name="discount_type" id="discount_type" class="form-control js-select2-custom">
                                            <option value="percent">{{translate('percent')}}</option>
                                            <option value="amount">{{translate('amount')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('discount')}} <span id="discount_symbol">(%)</span></label>
                                        <input type="number" min="0" max="100000" value="0" name="discount" step="any" id="discount" class="form-control"
                                               placeholder="{{ translate('Ex : 5%') }}" >
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                               for="exampleFormControlInput1">{{translate('tax_type')}}</label>
                                        <select name="tax_type" id="tax_type" class="form-control js-select2-custom">
                                            <option value="percent">{{translate('percent')}}</option>
                                            <option value="amount">{{translate('amount')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                               for="exampleFormControlInput1">{{translate('tax_rate')}} <span id="tax_symbol">(%)</span></label>
                                        <input type="number" min="0" value="0" step="0.01" max="100000" name="tax"
                                               class="form-control"
                                               placeholder="{{ translate('Ex : $ 100') }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title">
                            <span class="card-header-icon">
                                <i class="tio-puzzle"></i>
                            </span>
                            <span>
                                {{translate('attribute')}}
                            </span>
                        </h5>
                    </div>
                    <div class="card-body pb-0">
                        <div class="form-group __select-attr">
                            <label class="input-label"
                                    for="exampleFormControlSelect1">{{translate('Select attribute')}}<span
                                    class="input-label-secondary"></span></label>
                            <select name="attribute_id[]" id="choice_attributes2"
                                    class="form-control js-select2-custom"
                                    multiple="multiple">
                                @foreach(\App\Model\Attribute::orderBy('name')->get() as $attribute)
                                    <option value="{{$attribute['id']}}">{{$attribute['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="customer_choice_options" id="customer_choice_options"></div>
                            </div>
                            <div class="col-md-12">
                                <div class="variant_combination" id="variant_combination"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->

            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title">
                            <span class="card-header-icon">
                                <i class="tio-puzzle"></i>
                            </span>
                            <span>
                                {{translate('manufacturer')}}
                            </span>
                        </h5>
                    </div>
                    <div class="card-body pb-0">
                        <div class="form-group __select-attr">
                            <label class="input-label"
                                    for="exampleFormControlSelect1">{{translate('Select Manufacturer')}}<span
                                    class="input-label-secondary"></span></label>
                            <select name="manufacturer_id" id="choice_attributes"
                                    class="form-control js-select2-custom">
                                @foreach( $manufacturers as $Manufacturer)
                                    <option value="{{$Manufacturer['id']}}">{{$Manufacturer['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title">
                            <span class="card-header-icon">
                                <i class="tio-puzzle"></i>
                            </span>
                            <span>
                                {{translate('Related Products')}}
                            </span>
                        </h5>
                    </div>
                    <div class="card-body pb-0">
                        <div class="form-group __select-attr">
                            <label class="input-label"
                                    for="exampleFormControlSelect1">{{translate('Select')}} {{translate('Related Products')}}<span
                                    class="input-label-secondary"></span></label>
                            <select name="related_product_ids[]" id="related_products"
                                    class="form-control js-select2-custom" multiple="multiple">
                                @if(!empty($products))
                                    @foreach( $products as $productData)
                                        <option value="{{$productData['id']}}">{{$productData['name']}}</option>
                                    @endforeach
                                @else
                                    <option value="">{{translate('No Any Products')}}</option>
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title">
                            <span class="card-header-icon">
                                <i class="tio-puzzle"></i>
                            </span>
                            <span>
                                {{translate('Filters')}}
                            </span>
                        </h5>
                    </div>
                    <div class="card-body pb-0">
                        <div class="form-group __select-attr">
                            <label class="input-label"
                                    for="exampleFormControlSelect1">{{translate('Select Filters')}}<span
                                    class="input-label-secondary"></span></label>
                            <select name="filter_id" id="choice_attributes1"
                                    class="form-control js-select2-custom" multiple="multiple">
                                @foreach( $filters as $filter)
                                    <option value="{{$filter['id']}}">{{$filter['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                         <div class="row">
                            <div class="col-md-12">
                                <div class="customer_choice_options" id="customer_choice_options"></div>
                            </div>
                            <div class="col-md-12">
                                <div class="variant_combination" id="variant_combination"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
            <!-- <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title">
                            <span class="card-header-icon">
                                <i class="tio-puzzle"></i>
                            </span>
                            <span>
                                {{translate('Downloads')}}
                            </span>
                        </h5>
                    </div>
                    <div class="card-body pb-0">
                        <div class="form-group __select-attr">
                            <label class="input-label"
                                    for="exampleFormControlSelect1">{{translate('Select downloads')}}<span
                                    class="input-label-secondary"></span></label>
                            <select name="download_id" id="choice_attributes"
                                    class="form-control js-select2-custom" multiple="multiple">
                                @foreach( $downloadLinks as $download)
                                    <option value="{{$download['id']}}">{{$download['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                         <div class="row">
                            <div class="col-md-12">
                                <div class="customer_choice_options" id="customer_choice_options"></div>
                            </div>
                            <div class="col-md-12">
                                <div class="variant_combination" id="variant_combination"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <span class="card-header-icon">
                                <i class="tio-user"></i>
                            </span>
                            <span>
                                {{translate('Special')}}
                            </span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="input-label" for="sale_start_date">{{translate('Start Date')}}<span class="input-label-secondary"></span></label>
                                    <label class="">
                                        <input type="date" name="sale_start_date" id="sale_start_date" value="{{ old('sale_start_date') }}" class="js-flatpickr form-control flatpickr-custom" placeholder="{{ \App\CentralLogics\translate('dd/mm/yy') }}" data-hs-flatpickr-options='{ "dateFormat": "Y/m/d", "minDate": "today" }'>
                                    </label>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="input-label" for="sale_end_date">{{translate('End Date')}}<span class="input-label-secondary"></span></label>
                                    <label class="">
                                        <input type="date" name="sale_end_date" id="sale_end_date" value="{{ old('sale_end_date') }}" class="js-flatpickr form-control flatpickr-custom" placeholder="{{ \App\CentralLogics\translate('dd/mm/yy') }}" data-hs-flatpickr-options='{ "dateFormat": "Y/m/d", "minDate": "today" }'>
                                    </label>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="input-label" for="special_price">{{translate('Special Price')}}<span class="input-label-secondary"></span></label>
                                    <label class="">
                                        <input type="text" name="sale_price" id="sale_price" pattern="[0-9]+" value="{{ old('sale_price') }}" class="form-control" placeholder="{{translate('Special Price')}}">
                                    </label>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <div class="btn--container justify-content-end m-4">
                                        <button type="button" id="remove_from_special" class="btn btn--danger">{{translate('Remove')}}</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="card min-h-116px">
                        <div class="card-body d-flex flex-column justify-content-center">
                            <div class="d-flex flex-wrap-reverse justify-content-between">
                                <div class="w-200 flex-grow-1 mr-3">
                                    {{translate('Turning Visibility off will not show this product in the user app and website')}}
                                </div>
                                <div class="d-flex align-items-center mb-2 mb-sm-0">
                                    <h5 class="mb-0 mr-2">{{ translate('Visibility') }}</h5>
                                    <label class="toggle-switch my-0">
                                        <input type="checkbox" class="toggle-switch-input" name="status" value="1" checked>
                                        <span class="toggle-switch-label mx-auto text">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="btn--container justify-content-end">
                    <a href="" class="btn btn--reset min-w-120px">{{translate('reset')}}</a>
                    <button type="submit" class="btn btn--primary">{{translate('submit')}}</button>
                </div>
            </div>
        </form>
    </div>

@endsection

@push('script')

@endpush

@push('script_2')
    <script src="{{asset('public/assets/admin')}}/js/select2.min.js"></script>
    <script src="{{asset('public/assets/admin/js/spartan-multi-image-picker.js')}}"></script>
    <script>
        $(".lang_link").click(function(e){
            e.preventDefault();
            $(".lang_link").removeClass('active');
            $(".lang_form").addClass('d-none');
            $(this).addClass('active');

            let form_id = this.id;
            let lang = form_id.split("-")[0];
            console.log(lang);
            $("#"+lang+"-form").removeClass('d-none');
            if(lang == '{{$default_lang}}')
            {
                $("#from_part_2").removeClass('d-none');
            }
            else
            {
                $("#from_part_2").addClass('d-none');
            }


        })
        $("#remove_from_special").click(function(e){
            e.preventDefault();
            $("#sale_start_date").val("");
            $("#sale_end_date").val("");
            $("#sale_price").val("");
        });
    </script>

    <script>


        $('#product_form').on('submit', function () {


            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.product.store')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.errors) {
                        for (var i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        toastr.success('{{ translate("product uploaded successfully!") }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function () {
                            location.href = '{{route('admin.product.list')}}';
                        }, 2000);
                    }
                }
            });
        });
    </script>

    <script type="text/javascript">
        $(function () {
            $("#coba").spartanMultiImagePicker({
                fieldName: 'images[]',
                maxCount: 4,
                rowHeight: '150px',
                groupClassName: '',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{asset('public/assets/admin/img/upload-en.png')}}',
                    width: '100%'
                },
                dropFileLabel: "Drop Here",
                onAddRow: function (index, file) {

                },
                onRenderedPreview: function (index) {

                },
                onRemoveRow: function (index) {

                },
                onExtensionErr: function (index, file) {
                    toastr.error('{{ translate("Please only input png or jpg type file") }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function (index, file) {
                    toastr.error('{{ translate("File size too big") }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        });
    </script>

    <script>
        function getRequest(route, id) {
            $.get({
                url: route,
                dataType: 'json',
                success: function (data) {
                    $('#' + id).empty().append(data.options);
                },
            });
        }
    </script>

    <script>
        $(document).on('ready', function () {
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>

    <script src="{{asset('public/assets/admin')}}/js/tags-input.min.js"></script>

    <script>
        $('#choice_attributes').on('change', function () {
            $('#customer_choice_options').html(null);
            $.each($("#choice_attributes option:selected"), function () {
                add_more_customer_choice_option($(this).val(), $(this).text());
            });
        });

        function add_more_customer_choice_option(i, name) {
            let n = name.split(' ').join('');
            $('#customer_choice_options').append('<div class="row g-1"><div class="col-md-3 col-sm-4"><input type="hidden" name="choice_no[]" value="' + i + '"><input type="text" class="form-control" name="choice[]" value="' + n + '" placeholder="Choice Title" readonly></div><div class="col-lg-9 col-sm-8"><input type="text" class="form-control" name="choice_options_' + i + '[]" placeholder="Enter choice values" data-role="tagsinput" onchange="combination_update()"></div></div>');
            $("input[data-role=tagsinput], select[multiple][data-role=tagsinput]").tagsinput();
        }

        function combination_update() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: '{{route('admin.product.variant-combination')}}',
                data: $('#product_form').serialize(),
                success: function (data) {
                    $('#variant_combination').html(data.view);
                    if (data.length > 1) {
                        $('#quantity').hide();
                    } else {
                        $('#quantity').show();
                    }
                }
            });
        }
    </script>

    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

    <script>
        var quill = new Quill('#editor', {
            theme: 'snow'
        });

        $('#product_form').on('submit', function () {

            var myEditor = document.querySelector('#editor')
            $("#hiddenArea").val(myEditor.children[0].innerHTML);

            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.product.store')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.errors) {
                        for (var i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        toastr.success('{{ translate("product uploaded successfully!") }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function () {
                            location.href = '{{route('admin.product.list')}}';
                        }, 2000);
                    }
                }
            });
        });
    </script>

    <script>
        function update_qty() {
            var total_qty = 0;
            var qty_elements = $('input[name^="stock_"]');
            for(var i=0; i<qty_elements.length; i++)
            {
                total_qty += parseInt(qty_elements.eq(i).val());
            }
            if(qty_elements.length > 0)
            {
                $('input[name="total_stock"]').attr("readonly", true);
                $('input[name="total_stock"]').val(total_qty);
                console.log(total_qty)
            }
            else{
                $('input[name="total_stock"]').attr("readonly", false);
            }
        }
    </script>

    <script>

        $('#discount_type').change(function(){
            if($('#discount_type').val() == 'percent') {
                $("#discount_symbol").html('(%)')
            } else {
                $("#discount_symbol").html('')
            }
        });

        $('#tax_type').change(function(){
            if($('#tax_type').val() == 'percent') {
                $("#tax_symbol").html('(%)')
            } else {
                $("#tax_symbol").html('')
            }
        });
    </script>

@endpush



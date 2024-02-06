@extends('layouts.admin.app')

@section('title', translate('Update product'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{asset('public/assets/admin/css/tags-input.min.css')}}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/edit.png')}}" class="w--24" alt="">
                </span>
                <span>
                    {{translate('product')}} {{translate('update')}}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->
        <form action="javascript:" method="post" id="product_form"
                enctype="multipart/form-data"class="row g-2">
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
                                    <a class="nav-link lang_link {{$lang['code'] == 'en'? 'active':''}}" href="#" id="{{$lang['code']}}-link">{{Helpers::get_language_name($lang['code']).'('.strtoupper($lang['code']).')'}}</a>
                                </li>
                                @endforeach

                            </ul>
                            @foreach($data as $lang)
                                <?php
                                    if(count($product['translations'])){
                                        $translate = [];
                                        foreach($product['translations'] as $t)
                                        {
                                            if($t->locale == $lang['code'] && $t->key=="name"){
                                                $translate[$lang['code']]['name'] = $t->value;
                                            }
                                            if($t->locale == $lang['code'] && $t->key=="description"){
                                                $translate[$lang['code']]['description'] = $t->value;
                                            }
                                            if($t->locale == $lang['code'] && $t->key=="meta_title"){
                                                $translate[$lang['code']]['meta_title'] = $t->value;
                                            }
                                            if($t->locale == $lang['code'] && $t->key=="meta_tag_description"){
                                                $translate[$lang['code']]['meta_tag_description'] = $t->value;
                                            }
                                            if($t->locale == $lang['code'] && $t->key=="meta_tag_keywords"){
                                                $translate[$lang['code']]['meta_tag_keywords'] = $t->value;
                                            }

                                        }
                                    }
                                ?>
                                <div class="{{$lang['code'] != 'en'? 'd-none':''}} lang_form" id="{{$lang['code']}}-form">
                                    <div class="form-group">
                                        <label class="input-label" for="{{$lang['code']}}_name">{{translate('name')}} ({{strtoupper($lang['code'])}})</label>
                                        <input type="text" {{$lang['status'] == true ? 'required':''}} name="name[]" id="{{$lang['code']}}_name" value="{{$translate[$lang['code']]['name']??$product['name']}}" class="form-control" placeholder="{{translate('New Product')}}" >
                                    </div>
                                    <input type="hidden" name="lang[]" value="{{$lang['code']}}">
                                    <div class="form-group">
                                        <label class="input-label" for="{{$lang['code']}}_description">{{translate('short')}} {{translate('description')}}  ({{strtoupper($lang['code'])}})</label>
                                        <textarea name="description[]" class="form-control h--172px" id="{{$lang['code']}}_hiddenArea">{!! strip_tags(htmlspecialchars_decode($translate[$lang['code']]['description'] ?? $product['description'])) !!}
</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label" for="{{$lang['code']}}_meta_title">{{translate('meta tag title')}} ({{strtoupper($lang['code'])}})</label>
                                        <input type="text" name="meta_title[]" class="form-control" id=""  placeholder="{{translate('meta tag title')}}" value="{{$translate[$lang['code']]['meta_title']??$product['meta_title']}}" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="{{$lang['code']}}__meta_description">{{translate('meta tag description')}}  ({{strtoupper($lang['code'])}})</label>
                                        <textarea name="meta_description[]" class="form-control h--172px" id="{{$lang['code']}}__meta_description">{{$translate[$lang['code']]['meta_tag_description']??$product['meta_tag_description']}}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label" for="{{$lang['code']}}__meta_keywords">{{translate('meta tag keywords')}}  ({{strtoupper($lang['code'])}})</label>
                                        <textarea name="meta_keywords[]" class="form-control h--172px" id="{{$lang['code']}}_meta_keywords">{{$translate[$lang['code']]['meta_tag_keywords']??$product['meta_tag_keywords']}}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label" for="{{$lang['code']}}_product_tags">{{translate('product tags')}} ({{strtoupper($lang['code'])}})</label>
                                        <input type="text" name="product_tags[]" class="form-control" id="{{$lang['code']}}_product_tags" value="{{$translate[$lang['code']]['product_tags']??$product['product_tag']}}" placeholder="{{translate('product tags')}}">
                                    </div>

                                    @if($lang['code'] == "en")
                                        <div class="form-group">
                                            <label class="input-label" for="{{$lang['code']}}_seo_en">{{translate('SEO')}} ({{strtoupper($lang['code'])}})</label>
                                            <input type="text" name="en_seo" class="form-control" id="{{$lang['code']}}_seo_en" placeholder="{{translate('SEO')}} ({{translate('EN')}})" value="{{$product['seo_en']}}">
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <label class="input-label" for="{{$lang['code']}}_seo_ja">{{translate('SEO')}} ({{strtoupper($lang['code'])}})</label>
                                            <input type="text" name="ja_seo" class="form-control" id="{{$lang['code']}}_seo_ja" placeholder="{{translate('SEO')}} ({{translate('JA')}})" value="{{$product['seo_ja']}}">
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div id="english-form">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('name')}} (EN)</label>
                                    <input type="text" name="name[]" value="{{$product['name']}}" class="form-control" placeholder="{{translate('New Product')}}" required>
                                </div>
                                <input type="hidden" name="lang[]" value="en">
                                <div class="form-group mb-0">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{translate('short')}} {{translate('description')}} (EN)</label>
                                    <textarea name="description[]" class="form-control h--172px" id="hiddenArea">{{!! $product['description'] !!}}</textarea>
                                </div>
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('meta tag title')}} (EN)</label>
                                    <input type="text" name="meta_title[]" class="form-control" value="{{$translate[$lang['code']]['meta_title']??$product['meta_title']}}" placeholder="{{translate('meta tag title')}}" required>
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
                                        <label class="input-label" for="{{$lang['code']}}_seo_en">{{translate('SEO')}} ({{strtoupper($lang['code'])}})</label>
                                        <input type="text" name="en_seo" class="form-control" id="{{$lang['code']}}_seo_en" placeholder="{{translate('SEO')}} ({{translate('EN')}})" value="{{$product['seo_ja']}}">
                                    </div>
                                @else
                                    <div class="form-group">
                                        <label class="input-label" for="{{$lang['code']}}_seo_ja">{{translate('SEO')}} ({{strtoupper($lang['code'])}})</label>
                                        <input type="text" name="ja_seo" class="form-control" id="{{$lang['code']}}_seo_ja" placeholder="{{translate('SEO')}} ({{translate('JA')}})"  value="{{$product['seo_ja']}}">
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
                                    <select name="category_id" id="category-id" class="form-control js-select2-custom"
                                            onchange="getRequest('{{url('/')}}/admin/product/get-categories?parent_id='+this.value,'sub-categories')">
                                        @foreach($categories as $category)
                                            <option
                                                value="{{$category['id']}}" {{ $category['id']==$product_category[0]['id'] ? 'selected' : ''}} >{{$category['name']}}</option>
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
                                            data-id="{{count($product_category)>=2?$product_category[1]['id']:''}}"
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
                                    <select name="sub_sub_category_id" id="sub-sub-categories"
                                            data-id="{{count($product_category)>2?$product_category[2]['id']:''}}"
                                            class="form-control js-select2-custom">

                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="input-label"
                                            for="exampleFormControlInput1">{{translate('unit')}}</label>
                                    <select name="unit" class="form-control js-select2-custom">
                                        <option value="kg" {{$product['unit']=='kg'?'selected':''}}>{{translate('kg')}}</option>
                                        <option value="gm" {{$product['unit']=='gm'?'selected':''}}>{{translate('gm')}}</option>
                                        <option value="ltr" {{$product['unit']=='ltr'?'selected':''}}>{{translate('ltr')}}</option><option value="pc" {{$product['unit']=='pc'?'selected':''}}>{{translate('pc')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="input-label"
                                            for="exampleFormControlInput1">{{translate('capacity')}}</label>
                                    <input type="number" min="0" step="0.01" value="{{$product['capacity']}}"  name="capacity"
                                            class="form-control"
                                            placeholder="{{ translate('Ex : 5') }}" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="input-label"
                                           for="exampleFormControlInput1">{{translate('maximum_order_quantity')}}</label>
                                    <input type="number" min="1" step="1" value="{{$product['maximum_order_quantity']}}" name="maximum_order_quantity"
                                           class="form-control"
                                           placeholder="{{ translate('Ex : 3') }}" >
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="substrack_stock">{{translate('Substrak Stock')}}<span class="input-label-secondary"></span></label>
                                    <select name="substrack_stock" id="substrack_stock" class="form-control js-select2-custom">
                                        <option value="yes" {{ ($product['substrack_stock'] === 'yes') ? 'selected' : '' }}>{{translate('yes')}}</option>
                                        <option value="no" {{ ($product['substrack_stock'] === 'no') ? 'selected' : '' }}>{{translate('no')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="out_of_stock_status">{{translate('Out Of Stock Status')}}<span class="input-label-secondary"></span></label>
                                    <select name="out_of_stock_status" id="out_of_stock_status" class="form-control js-select2-custom">
                                        <option value="2-3 days"  {{ ($product['out_of_stock_status'] === '2-3 days') ? 'selected' : '' }}>{{translate('2-3 Days')}}</option>
                                        <option value="in stock" {{ ($product['out_of_stock_status'] === 'in stock') ? 'selected' : '' }}>{{translate('In Stock')}}</option>
                                        <option value="out of stock" {{ ($product['out_of_stock_status'] === 'out of stock') ? 'selected' : '' }}>{{translate('Out Of Stock')}}</option>
                                        <option value="pre order" {{ ($product['out_of_stock_status'] === 'pre order') ? 'selected' : '' }}>{{translate('Pre-Order')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="requires_shipping">{{translate('Requires Shipping')}}<span class="input-label-secondary"></span></label>
                                    <select name="requires_shipping" id="requires_shipping" class="form-control js-select2-custom">
                                        <option value="yes" {{ ($product['requires_shipping'] === 'yes') ? 'selected' : '' }}>{{translate('yes')}}</option>
                                        <option value="no" {{ ($product['requires_shipping'] === 'no') ? 'selected' : '' }}>{{translate('no')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                @php($productMark = explode(",", $product['product_mark']))
                                    <label class="input-label" for="product_mark">{{translate('Product Mark')}}<span class="input-label-secondary"></span></label>
                                    <select name="product_mark[]" id="product_mark" class="form-control js-select2-custom" multiple="multiple">
                                        <option value="Halal" {{ (in_array('Halal', $productMark)) ? 'selected' : '' }}>{{translate('Halal')}}</option>
                                        <option value="Veg" {{ (in_array('Veg', $productMark)) ? 'selected' : '' }}>{{translate('Veg')}}</option>
                                        <option value="Vegan" {{ (in_array('Vegan', $productMark)) ? 'selected' : '' }}>{{translate('Vegan')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="product_type">{{translate('Product Type')}}<span class="input-label-secondary"></span></label>
                                    <input type="checkbox" class="" name="product_type" value="1" {{ ($product['product_type'] === '1') ? 'checked' : '' }}>
                                    <label >{{ translate('frozen') }}</p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="date_available">{{translate('Date Available')}}<span class="input-label-secondary"></span></label>
                                    <label class="">
                                        <input type="date" name="date_available" id="date_available" value="{{$product['date_available']}}" class="js-flatpickr form-control flatpickr-custom" placeholder="{{ \App\CentralLogics\translate('dd/mm/yy') }}" data-hs-flatpickr-options='{ "dateFormat": "Y/m/d", "minDate": "today" }'>
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="length_class">{{translate('Length Class')}}<span class="input-label-secondary"></span></label>
                                    <select name="length_class" id="length_class" class="form-control js-select2-custom">
                                        <option value="Centimeter" {{ ($product['length_class'] === 'Centimeter') ? 'selected' : '' }}>{{translate('Centimeter')}}</option>
                                        <option value="Millimeter" {{ ($product['length_class'] === 'Millimeter') ? 'selected' : '' }}>{{translate('Millimeter')}}</option>
                                        <option value="inch" {{ ($product['length_class'] === 'inch') ? 'selected' : '' }}>{{translate('inch')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{translate('weight')}}</label>
                                    <input type="text" name="weight"
                                        class="form-control"
                                        value="{{ $product['weight'] }}"
                                        placeholder="{{ translate('0.000000') }}" >
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="weight_class">{{translate('Weight Class')}}<span class="input-label-secondary"></span></label>
                                    <select name="weight_class" id="weight_class" class="form-control js-select2-custom">
                                        <option value="Kilogram" {{ ($product['weight_class'] === 'Kilogram') ? 'selected' : '' }}>{{translate('Kilogram')}}</option>
                                        <option value="Gram" {{ ($product['weight_class'] === 'Gram') ? 'selected' : '' }}>{{translate('Gram')}}</option>
                                        <option value="Pound" {{ ($product['weight_class'] === 'Pound') ? 'selected' : '' }}>{{translate('Pound')}}</option>
                                        <option value="Ounce" {{ ($product['weight_class'] === 'Ounce') ? 'selected' : '' }}>{{translate('Ounce')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="input-label"
                                           for="exampleFormControlInput1">{{translate('Sort Order')}}</label>
                                    <input type="number" min="1" step="1" name="sort_order"
                                           class="form-control" value="{{ $product['sort_order'] }}"
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
                                        <input type="checkbox" class="toggle-switch-input" name="status" value="1" {{$product['status']==1?'checked':''}}>
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
                            <input type="txt" name="model" id="model" class="form-control" value="{{ $product['model'] }}" placeholder="{{ translate('model') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="input-label" for="sku">{{translate('SKU(Stock Keeping Unit)')}}</label>
                            <input type="txt" name="sku" id="sku" class="form-control" value="{{ $product['sku'] }}" placeholder="{{ translate('Stock Keeping Unit') }}" >
                        </div>
                        <div class="form-group">
                            <label class="input-label" for="upc">{{translate('UPC(Universal Product Code)')}}</label>
                            <input type="txt" name="upc" id="upc" class="form-control" value="{{ $product['upc'] }}" placeholder="{{ translate('Universal Product Code') }}" >
                        </div>
                        <div class="form-group">
                            <label class="input-label" for="ena">{{translate('EAN(European Artical Number)')}}</label>
                            <input type="txt" name="ena" id="ena" class="form-control" value="{{ $product['ena'] }}" placeholder="{{ translate('European Artical Number') }}" >
                        </div>
                        <div class="form-group">
                            <label class="input-label" for="jan">{{translate('JAN(Japanese Artical Number)')}}</label>
                            <input type="txt" name="jan" id="jan" class="form-control" value="{{ $product['jan'] }}" placeholder="{{ translate('Japanese Artical Number') }}" >
                        </div>
                        <div class="form-group">
                            <label class="input-label" for="isbn">{{translate('ISBN(International Standard Book Number)')}}</label>
                            <input type="txt" name="isbn" id="isbn" class="form-control" value="{{ $product['isbn'] }}" placeholder="{{ translate('International Standard Book Number') }}">
                        </div>
                        <div class="form-group">
                            <label class="input-label" for="mpn">{{translate('MPN(Manufacturer Part Numbaer)')}}</label>
                            <input type="txt" name="mpn" id="mpn" class="form-control" value="{{ $product['mpn'] }}" placeholder="{{ translate('Manufacturer Part Numbaer') }}" >
                        </div>
                         <div class="form-group">
                            <label class="input-label" for="location">{{translate('Location')}}</label>
                            <input type="txt" name="location" id="location" class="form-control" value="{{ $product['location'] }}" placeholder="{{ translate('location') }}" >
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
                            <div class="row g-2" id="coba">
                                @if (!empty($product['image']))
                                    @foreach($product['image'] as $img)
                                        <div class="spartan_item_wrapper position-relative">
                                            <img class="img-150 border rounded p-3" src="{{$img}}">
                                            {{--<a href="{{route('admin.rm-image',[$product['id'],$img])}}" class="spartan__close"><i class="tio-add-to-trash"></i></a>--}}
                                            <a href="{{route('admin.rm-image',[$product['id'],$img])}}" class="spartan__close" >
                                                <i class="tio-add-to-trash"></i>
                                            </a>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card h-100">
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
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('default_unit_price')}}</label>
                                        <input type="number" value="{{$product['price']}}" min="0" max="100000000" name="price" class="form-control" step="any" placeholder="{{ translate('Ex : 100') }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                                for="exampleFormControlInput1">{{translate('stock')}}</label>
                                        <input type="number" min="0" max="100000000" value="{{$product['total_stock']}}" name="total_stock" class="form-control"
                                                placeholder="{{ translate('Ex : 100') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                                for="exampleFormControlInput1">{{translate('discount')}} {{translate('type')}}</label>
                                        <select name="discount_type" id="discount_type" class="form-control js-select2-custom">
                                            <option value="percent" {{$product['discount_type']=='percent'?'selected':''}}>
                                                {{translate('percent')}}
                                            </option>
                                            <option value="amount" {{$product['discount_type']=='amount'?'selected':''}}>
                                                {{translate('amount')}}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                                for="exampleFormControlInput1">{{translate('discount')}} <span id="discount_symbol">{{$product['discount_type']=='amount'?'':'(%)'}}</span></label>
                                        <input type="number" min="0" value="{{$product['discount']}}" max="100000"
                                                name="discount" class="form-control" step="any"
                                                placeholder="{{ translate('Ex : 100') }}" >
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                                for="exampleFormControlInput1">{{translate('tax_type')}}</label>
                                        <select name="tax_type" id="tax_type" class="form-control js-select2-custom">
                                            <option
                                                value="percent" {{$product['tax_type']=='percent'?'selected':''}}>{{translate('percent')}}
                                            </option>
                                            <option
                                                value="amount" {{$product['tax_type']=='amount'?'selected':''}}>{{translate('amount')}}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('tax_rate')}} <span id="tax_symbol">{{$product['tax_type']=='amount'?'':'(%)'}}</span></label>
                                        <input type="number" value="{{$product['tax']}}" min="0" max="100000" name="tax" class="form-control" step="any" placeholder="{{ translate('Ex : 7') }}" required>
                                    </div>
                                </div>
                            </div>
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
                                    <option value="{{$Manufacturer['id']}}"  {{ ($Manufacturer['id'] === $product['manufacturer_id']) ? 'selected' : ''  }}>{{$Manufacturer['name']}}</option>
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
                                
                                <select name="related_product_ids[]" id="related_products" class="form-control js-select2-custom" multiple="multiple">
                                    @if(!empty($products))
                                        @foreach($products as $productData)
                                            <?php 
                                                $isSelected = false; 
                                                if (!empty($product['relatedProducts'])) {
                                                    foreach ($product['relatedProducts'] as $relatedProduct){ 
                                                        if($relatedProduct['related_product_id'] === $productData['id']){ 
                                                            $isSelected = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                            ?>
                                            <option value="{{$productData['id']}}" {{ $isSelected ? 'selected' : '' }}>{{$productData['name']}}</option>
                                        @endforeach
                                    @else
                                        <option value="">{{ translate('No Any Products') }}</option>
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
                        @php($productFilter = !empty($product['filters']) ?  json_decode($product['filters']) : [])
                        @php($productFilter =  gettype($productFilter) !== 'array' ? explode(" ", $productFilter) : $productFilter)
                        <div class="form-group __select-attr">
                            <label class="input-label"
                                    for="exampleFormControlSelect1">{{translate('Select Filters')}}<span
                                    class="input-label-secondary"></span></label>
                            <select name="filter_id[]" id="choice_attributes1"
                                    class="form-control js-select2-custom" multiple="multiple">
                                @foreach( $filters as $filter)
                                    <option value="{{$filter['id']}}" {{ (in_array($filter['id'], $productFilter)) ? 'selected' : '' }} >{{$filter['name']}}</option>
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
                    @php($productDownloads = !empty($product['downloads']) ? json_decode($product['downloads']) : [])
                     @php($productDownloads =  gettype($productDownloads) !== 'array' ? explode(" ", $productDownloads) : $productDownloads)
                        <div class="form-group __select-attr">
                            <label class="input-label"
                                    for="exampleFormControlSelect1">{{translate('Select downloads')}}<span
                                    class="input-label-secondary"></span></label>
                            <select name="download_id" id="choice_attributes"
                                    class="form-control js-select2-custom" multiple="multiple">
                                @foreach( $downloadLinks as $download)
                                    <option value="{{$download['id']}}" {{ (in_array($download['id'], $productDownloads)) ? 'selected' : '' }}  >{{$download['name']}}</option>
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
                                    <input type="date" name="sale_start_date" id="sale_start_date" value="{{$product['sale_start_date']}}" class="js-flatpickr form-control flatpickr-custom" placeholder="{{ \App\CentralLogics\translate('dd/mm/yy') }}" data-hs-flatpickr-options='{ "dateFormat": "Y/m/d", "minDate": "today" }'>
                                </label>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="input-label" for="sale_end_date">{{translate('End Date')}}<span class="input-label-secondary"></span></label>
                                <label class="">
                                    <input type="date" name="sale_end_date" id="sale_end_date" value="{{ $product['sale_end_date'] }}" class="js-flatpickr form-control flatpickr-custom" placeholder="{{ \App\CentralLogics\translate('dd/mm/yy') }}" data-hs-flatpickr-options='{ "dateFormat": "Y/m/d", "minDate": "today" }'>
                                </label>
                            </div>
                        </div>

                        <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="input-label" for="special_price">{{translate('Special Price')}}<span class="input-label-secondary"></span></label>
                                    <label class="">
                                        <input type="text" name="sale_price" id="sale_price" pattern="[0-9]+" value="{{ $product['sale_price'] }}" class="form-control" placeholder="{{translate('Special Price')}}">
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
                                        <input type="checkbox" class="toggle-switch-input" name="status" {{ ($product['status']==1) ? 'checked' : ''  }}>
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
                    <button type="reset" class="btn btn--reset">{{translate('clear')}}</button>
                    <button type="submit" class="btn btn--primary">{{translate('update')}}</button>
                </div>
            </div>
        </form>
    </div>

@endsection

@push('script')

@endpush

@push('script_2')
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
            if(lang == 'en')
            {
                $("#from_part_2").removeClass('d-none');
            }
            else
            {
                $("#from_part_2").addClass('d-none');
            }


        })
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
                    image: '{{asset('/public/assets/admin/img/upload-en.png')}}',
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
                    toastr.error('Please only input png or jpg type file', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function (index, file) {
                    toastr.error('File size too big', {
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

        $(document).ready(function () {
            setTimeout(function () {
                let category = $("#category-id").val();
                let sub_category = '{{count($product_category)>=2?$product_category[1]['id']:''}}';
                let sub_sub_category = '{{count($product_category)>=3?$product_category[2]['id']:''}}';
                getRequest('{{url('/')}}/admin/product/get-categories?parent_id=' + category + '&&sub_category=' + sub_category, 'sub-categories');
                getRequest('{{url('/')}}/admin/product/get-categories?parent_id=' + sub_category + '&&sub_category=' + sub_sub_category, 'sub-sub-categories');
            }, 1000)
        });
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

        $("#remove_from_special").click(function(e){
            e.preventDefault();
            $("#sale_start_date").val("");
            $("#sale_end_date").val("");
            $("#sale_price").val("");
        });

        function add_more_customer_choice_option(i, name) {
            let n = name.split(' ').join('');
            $('#customer_choice_options').append('<div class="row"><div class="col-md-3"><input type="hidden" name="choice_no[]" value="' + i + '"><input type="text" class="form-control" name="choice[]" value="' + n + '" placeholder="Choice Title" readonly></div><div class="col-lg-9"><input type="text" class="form-control" name="choice_options_' + i + '[]" placeholder="Enter choice values" data-role="tagsinput" onchange="combination_update()"></div></div>');
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

    {{-- <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script> --}}

    <script>
    $(document).ready(function () {
        $('.spartan__close').on('click', function (e) {
            e.preventDefault();

            var url = $(this).attr('href');
            var productId = $(this).data('product-id');
            var imageName = $(this).data('image-name');

            $.ajax({
                type: 'get',
                url: url,
                data: {
                    _token: '{{ csrf_token() }}',
                    product_id: productId,
                    image_name: imageName,
                },
                success: function (response) {
                    toastr.success('{{translate('Image removed successfully!')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    setTimeout(function () {
                        location.href = location.href;
                    }, 2000);
                    $(e.target).closest('div.image-container').remove();
                },
                error: function (error) {
                    // Handle error, if any
                    console.error(error);
                }
            });
        });
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
                url: '{{route('admin.product.update',[$product['id']])}}',
                // data: $('#product_form').serialize(),
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
                        toastr.success('{{translate('product updated successfully!')}}', {
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

@endpush



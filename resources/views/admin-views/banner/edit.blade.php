@extends('layouts.admin.app')

@section('title', translate('Update banner'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/edit.png')}}" class="w--20" alt="">
                </span>
                <span>
                    {{translate('Update Banner')}}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->
        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.banner.update',[$banner['id']])}}" method="post"
                      enctype="multipart/form-data">
                    @csrf @method('put')
                    <div class="row g-3">
                        <div class="col-md-6">
                            @if(!empty($banner['type'])) 
                                <input type="hidden" id="banner_type" name="banner_type" value="home_banner">
                                <input type="hidden" id="is_home_banner" name="is_home_banner" value="1">
                            @else 
                                <input type="hidden" id="is_home_banner" name="is_home_banner" value="0">    
                            @endif
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('English')}} {{translate('title')}}</label>
                                        <input type="text" name="title" value="{{$banner['title']}}" class="form-control"
                                            placeholder="{{translate('English')}} {{translate('title')}}" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('Japanese')}} {{translate('title')}}</label>
                                        <input type="text" name="title_ja" value="{{$banner['title_ja']}}" class="form-control"
                                            placeholder="{{translate('Japanese')}} {{translate('title')}}" required>
                                    </div>
                                </div>
                                @if(empty($request->type))
                                <div class="col-12" >
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('ad')}} {{translate('banners')}}</label>
                                        <select name="ad_section" id="ad_section" class="form-control">
                                            <option value="slider_ad_banner" {{($banner['ad_section'] == "slider_ad_banner" ) ? 'selected' : ''}} >{{translate('slider ad banner')}}</option>
                                            <option value="best_seller_ad" {{($banner['ad_section'] == "best_seller_ad" ) ? 'selected' : ''}}>{{translate('best seller ad')}}</option>
                                            <option value="left_section_ad" {{($banner['ad_section'] == "left_section_ad" ) ? 'selected' : ''}}>{{translate('left ad banner')}}</option>
                                            <option value="right_section_ad" {{($banner['ad_section'] == "right_section_ad" ) ? 'selected' : ''}}>{{translate('right ad banner')}}</option>
                                        </select>
                                    </div>
                                </div>
                                @endif

                                <div class="col-12">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="link">{{translate('banner_link')}}</label>
                                        <input type="text" name="link" class="form-control" placeholder="{{ translate('banner_link') }}" value="{{$banner['link']}}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="description">{{translate('English')}} {{translate('Description')}}</label>
                                        <textarea class="form-control" name="description" placeholder="{{__('Description') }}">{{$banner['description']}}</textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="description_ja">{{translate('Japanese')}} {{translate('Description')}}</label>
                                        <textarea class="form-control" name="description_ja" placeholder="{{translate('Japanese')}} {{translate('Description')}}">{{$banner['description_ja']}}</textarea>
                                    </div>
                                </div>
                                @if(!empty($banner['type']))
                                <div class="col-12">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="order">{{translate('banner_order')}}</label>
                                        <input type="text" name="order" class="form-control" placeholder="{{ translate('banner_order') }}" value="{{$banner['banner_order']}}">
                                    </div>
                                </div>
                                @endif
                               {{-- @if(empty($banner['type']))
                                    <div class="col-12">
                                        <div class="form-group mb-0">
                                            <label class="input-label" for="exampleFormControlSelect1">{{translate('item')}} {{translate('type')}}<span
                                                    class="input-label-secondary">*</span></label>
                                            <select name="item_type" class="form-control" onchange="show_item(this.value)">
                                                <option value="product" {{$banner['product_id']==null?'':'selected'}}>{{translate('product')}}</option>
                                                <option value="category" {{$banner['category_id']==null?'':'selected'}}>{{translate('category')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group mb-0" id="type-product"
                                            style="display: {{$banner['product_id']==null?'none':'block'}}">
                                            <label class="input-label" for="exampleFormControlSelect1">{{translate('product')}} <span
                                                    class="input-label-secondary">*</span></label>
                                            <select name="product_id" class="form-control js-select2-custom">
                                                @foreach($products as $product)
                                                    <option
                                                        value="{{$product['id']}}" {{$banner['product_id']==$product['id']?'selected':''}}>
                                                        {{$product['name']}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group mb-0" id="type-category"
                                            style="display: {{$banner['category_id']==null?'none':'block'}}">
                                            <label class="input-label" for="exampleFormControlSelect1">{{translate('category')}} <span
                                                    class="input-label-secondary">*</span></label>
                                            <select name="category_id" class="form-control js-select2-custom">
                                                @foreach($categories as $category)
                                                    <option value="{{$category['id']}}" {{$banner['category_id']==$category['id']?'selected':''}}>{{$category['name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif --}}
                            </div>
                        </div>

                        <div class="col-md-6">
                           
                            <div class="d-flex flex-column justify-content-center h-30">
                                <h5 class="text-center mb-3 text--title text-capitalize">
                                    {{translate('banner')}} {{translate('image')}}
                                    <small class="text-danger">* ( {{translate('ratio')}} 3:1 )</small>
                                </h5>
                                <label class="upload--vertical">
                                    <input type="file" name="image" id="customFileEg1" class="" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" hidden>
                                    <img id="viewer" @if(empty($banner['image']))  src="{{asset('public/assets/admin/img/upload-vertical.png')}}" @else  src="{{asset('storage/banner')}}/{{$banner['image']}}" @endif alt="banner image"/>
                                </label>
                            </div>
                            @if($banner['type']!="home_banner")
                            <div class="d-flex flex-column justify-content-center h-60">
                                <h5 class="text-center mb-3 text--title text-capitalize">
                                    {{translate('banner')}} {{translate('Logo')}}
                                    <small class="text-danger">* ( {{translate('ratio')}} 3:1 )</small>
                                </h5>
                                <label class="upload--vertical">
                                    <input type="file" name="banner_logo" id="banner_logo" class="" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" hidden>
                                    <img id="banner-logo-viewer" @if(empty($banner['banner_logo']))  src="{{asset('public/assets/admin/img/upload-vertical.png')}}" @else  src="{{asset('storage/banner/logo')}}/{{$banner['banner_logo']}}" @endif alt="banner logo image"/>
                                </label>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="btn--container justify-content-end">
                            <button type="reset" class="btn btn--reset">{{translate('reset')}}</button>
                            <button type="submit" class="btn btn--primary">{{translate('update')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function readBannerLogoURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#banner-logo-viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this);
        });

        $("#banner_logo").change(function () {
            readBannerLogoURL(this);
        });

        function show_item(type) {
            if (type === 'product') {
                $("#type-product").show();
                $("#type-category").hide();
            } else {
                $("#type-product").hide();
                $("#type-category").show();
            }
        }
    </script>


@endpush

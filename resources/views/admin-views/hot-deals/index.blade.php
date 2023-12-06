@extends('layouts.admin.app')

@section('title', translate('Hot Deals'))

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
                    {{translate('Hot Deals')}}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.hot-deals.store') }}" method="post"
                      enctype="multipart/form-data">
                    @csrf @method('post')
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="input-label" for="order">{{translate('title')}}</label>
                                        <input type="text" name="title" class="form-control" placeholder="{{ translate('title') }}" value="{{ !empty($hotDeals->title) ? $hotDeals->title : '' }}">
                                    </div>
                                </div>
                                <div class="col-6" >
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('Product')}}</label>
                                        <select name="product" id="product" class="form-control">
                                            <option value="" >{{translate('Select Product')}}</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" @if(!empty($hotDeals)) {{ ($hotDeals->product_id === $product->id) ? 'selected' : '' }}  @endif>{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('discount')}}(%)</label>
                                        <input type="text" name="discount" value="{{ !empty($hotDeals->discount) ? $hotDeals->discount : '' }}" class="form-control"
                                            placeholder="{{ translate('discount') }}" >
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('start')}} {{translate('date')}}</label>
                                        <label class="input-date">
                                            <input type="text" name="start_date" id="start_date" value="{{ !empty($hotDeals->start_date) ? $hotDeals->start_date : '' }}" class="js-flatpickr form-control flatpickr-custom" placeholder="{{ \App\CentralLogics\translate('dd/mm/yy') }}" data-hs-flatpickr-options='{ "dateFormat": "Y/m/d", "minDate": "today" }'>
                                        </label>
                                    </div>
                                </div>
                                 <div class="col-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('end')}} {{translate('date')}}</label>
                                        <label class="input-date">
                                            <input type="text" name="end_date" id="end_date" value="{{ !empty($hotDeals->end_date) ? $hotDeals->end_date : '' }}" class="js-flatpickr form-control flatpickr-custom" placeholder="{{ \App\CentralLogics\translate('dd/mm/yy') }}" data-hs-flatpickr-options='{ "dateFormat": "Y/m/d", "minDate": "today" }'>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                            <div class="d-flex flex-column justify-content-center h-30">
                                <label class="input-label" for="exampleFormControlInput1">
                                    {{translate('poster')}} {{translate('image')}}
                                    <small class="text-danger">* ( {{translate('ratio')}} 3:1 )</small>
                                </label>
                                <label class="upload--vertical">
                                    <input type="file" name="image" id="customFileEg1" class="" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" hidden>
                                    @if(!empty($hotDeals->image)) 
                                        <img id="viewer" src="{{asset('storage/deals')}}/{{$hotDeals->image}}" alt="banner image"/>
                                    @else
                                        <img id="viewer" src="{{asset('public/assets/admin/img/upload-vertical.png')}}" alt="banner image"/>
                                    @endif
                                </label>
                            </div>
                        </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="btn--container justify-content-end">
                            <button type="submit" class="btn btn--primary">{{translate('save')}}</button>
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
     <script>
        $(document).on('ready', function () {
            // INITIALIZATION OF FLATPICKR
            // =======================================================
            $('.js-flatpickr').each(function () {
                $.HSCore.components.HSFlatpickr.init($(this));
            });
        });

        $('#start_date,#expire_date').change(function () {
            let fr = $('#start_date').val();
            let to = $('#expire_date').val();
            if (fr != '' && to != '') {
                if (fr > to) {
                    $('#start_date').val('');
                    $('#expire_date').val('');
                    toastr.error('Invalid date range!', Error, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            }
        });

        function coupon_type_change(order_type) {
            if(order_type==='first_order'){
                $('#user-limit').removeAttr('required');
                $('#limit-for-user').hide();

                $('#customer_id').removeAttr('required');
                $('#customer_div').addClass('d-none');

                $('#discount_type_div').show();

                $('#discount_amount').prop('required', true);
                $('#discount_amount_div').show();

                $('#max_discount_div').show();
            }
            else if(order_type==='customer_wise'){
                $('#user-limit').prop('required', true);
                $('#limit-for-user').show();
                //$('#customer_id').setAttribute('required', '');
                $('#customer_id').prop('required', true);
                $('#customer_div').removeClass('d-none');

                $('#discount_type_div').show();

                $('#discount_amount').prop('required');
                $('#discount_amount_div').show();

                $('#max_discount_div').show();
            }
            else if(order_type==='free_delivery'){
                $('#user-limit').prop('required');
                $('#limit-for-user').show();

                $('#customer_id').removeAttr('required');
                $('#customer_div').addClass('d-none');

                $('#discount_type_div').hide();

                $('#discount_amount').prop('disabled', true);
                $('#discount_amount_div').hide();

                $('#max_discount_div').hide();
            }
            else{
                $('#user-limit').prop('required',true);
                $('#limit-for-user').show();

                $('#customer_id').removeAttr('required');
                $('#customer_div').addClass('d-none');

                $('#discount_type_div').show();

                $('#discount_amount').prop('required', true);
                $('#discount_amount_div').show();

                $('#max_discount_div').show();
            }
        }

        function  generateCode(){
            let code = Math.random().toString(36).substring(2,12);
            $('#code').val(code)
        }

        function get_details(t){
            let id = $(t).data('id')

            $.ajax({
                type: 'GET',
                url: '{{route('admin.coupon.quick-view-details')}}',
                data: {
                    id: id
                },
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#loading').hide();
                    $('#quick-view').modal('show');
                    $('#quick-view-modal').empty().html(data.view);
                }
            });
        }

    </script>

@endpush

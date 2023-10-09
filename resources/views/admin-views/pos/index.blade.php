@extends('layouts.admin.app')

@section('title', translate('new sale'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
        <div class="content container-fluid">
            <div class="d-flex flex-wrap">
                <div class="order--pos-left">
                    <div class="card">
                        <div class="card-header m-1 bg-light border-0">
                            <h5 class="card-title">
                                <span>
                                {{translate('Product section')}}
                            </span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4 g-3">
                                <div class="col-sm-6">
                                    <div class="input-group header-item">
                                        <select name="category" id="category" class="form-control js-select2-custom mx-1"
                                                title="{{translate('select category')}}" onchange="set_category_filter(this.value)">
                                            <option value="">{{translate('All Categories')}}</option>
                                            @foreach ($categories as $item)
                                                <option
                                                    value="{{$item->id}}" {{$category==$item->id?'selected':''}}>{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <form id="search-form">
                                        <!-- Search -->
                                        <div class="input-group input-group-merge input-group-flush">
                                            <div class="input-group-prepend w--30 justify-content-center">
                                                <div class="input-group-text">
                                                    <i class="tio-search"></i>
                                                </div>
                                            </div>
                                            <input id="datatableSearch" type="search" value="{{$keyword?$keyword:''}}" name="search"
                                                class="form-control rounded border"
                                                placeholder="{{translate('Search by product name')}}"
                                                aria-label="Search here">
                                        </div>
                                        <!-- End Search -->
                                    </form>
                                </div>
                            </div>
                            <div id="items">
                                <div class="row g-1">
                                    @foreach($products as $product)
                                        <div class="order--item-box item-box">
                                            @include('admin-views.pos._single_product',['product'=>$product])
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="pt-4">
                                {!!$products->withQueryString()->links()!!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="order--pos-right">
                    <div class="card">
                        <div class="card-header bg-light border-0 m-1">
                            <h5 class="card-title">
                                <span>
                                    {{translate('Billing section')}}
                                </span>
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="px-4">
                                <div class="w-100">
                                    <div class="d-flex flex-wrap flex-row py-2 add--customer-btn">
                                        @php
                                            //$user = \App\User::all();
                                        @endphp
                                        <select id='customer' name="customer_id" onchange="store_key('customer_id',this.value)"
                                                data-placeholder="{{translate('Walk In Customer')}}"
                                                class="js-data-example-ajax form-control m-1">
                                            <option value="" selected disabled>{{ translate('select customer') }}</option>
                                            @foreach($users as $user)
                                                <option value="{{$user['id']}}" {{session('customer_id') == $user['id'] ? 'selected' : '' }}>{{$user['f_name'].' '.$user['l_name'] }}</option>
                                            @endforeach
                                        </select>
                                        <button class="btn btn--primary rounded font-regular" data-toggle="modal" data-target="#add-customer" type="button">{{translate('Add New Customer')}}</button>
                                    </div>
                                </div>
                                <div class="w-100 py-2">
                                    <h5>{{translate('Select Branch')}}</h5>
                                    <select onchange="store_key('branch_id',this.value)" id='branch' name="branch_id" class="js-data-example-ajax-2 form-control">
                                        @foreach($branches as $branch)
                                            <option value="{{$branch['id']}}" {{session('branch_id') == $branch['id'] ? 'selected' : '' }}>{{$branch['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class='w-100' id="cart">
                                    @include('admin-views.pos._cart')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- container //  -->
        <!-- End Content -->
        <div class="modal fade" id="quick-view" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content" id="quick-view-modal">

                </div>
            </div>
        </div>

    @php($order=\App\Model\Order::find(session('last_order')))
    @if($order)
        @php(session(['last_order'=> false]))
        <div class="modal fade" id="print-invoice" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{translate('Print Invoice')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <center>
                            <input type="button" class="btn btn-primary non-printable"
                                    onclick="printDiv('printableArea')"
                                    value="{{ translate('Proceed, If thermal printer is ready.') }}"/>
                            <a href="{{url()->previous()}}"
                                class="btn btn-danger non-printable">{{translate('Back')}}</a>
                        </center>
                        <hr class="non-printable">
                        <div id="printableArea">
                            @include('admin-views.pos.order.invoice')
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endif


        {{--add new customer modal start--}}
        @include('admin-views.pos.add-customer-modal')
        {{--add new customer modal end--}}
@endsection


@push('script_2')
<script>
    $(document).on('ready', function () {
        @if($order)
        $('#print-invoice').modal('show');
        @endif
    });

    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();
    }

    function set_category_filter(id) {
        var nurl = new URL('{!!url()->full()!!}');
        nurl.searchParams.set('category_id', id);
        location.href = nurl;
    }

    $('#search-form').on('submit', function (e) {
        e.preventDefault();
        var keyword = $('#datatableSearch').val();
        var nurl = new URL('{!!url()->full()!!}');
        nurl.searchParams.set('keyword', keyword);
        location.href = nurl;
    });

    function quickView(product_id) {
        $.ajax({
            url: '{{route('admin.pos.quick-view')}}',
            type: 'GET',
            data: {
                product_id: product_id
            },
            dataType: 'json', // added data type
            beforeSend: function () {
                $('#loading').show();
            },
            success: function (data) {
                console.log("success...");
                console.log(data);

                // $("#quick-view").removeClass('fade');
                // $("#quick-view").addClass('show');

                $('#quick-view').modal('show');
                $('#quick-view-modal').empty().html(data.view);

            },
            complete: function () {
                $('#loading').hide();
            },
        });
    }

    function checkAddToCartValidity() {
        // var names = {};
        // $('#add-to-cart-form input:radio').each(function () { // find unique names
        //     names[$(this).attr('name')] = true;
        // });
        // var count = 0;
        // $.each(names, function () { // then count them
        //     count++;
        // });
        // if ($('input:radio:checked').length == count) {
        //     return true;
        // }
        return true;
    }

    function cartQuantityInitialize() {
        $('.btn-number').click(function (e) {
            e.preventDefault();

            var fieldName = $(this).attr('data-field');
            var type = $(this).attr('data-type');
            var input = $("input[name='" + fieldName + "']");
            var currentVal = parseInt(input.val());

            if (!isNaN(currentVal)) {
                if (type == 'minus') {

                    if (currentVal > input.attr('min')) {
                        input.val(currentVal - 1).change();
                    }
                    if (parseInt(input.val()) == input.attr('min')) {
                        $(this).attr('disabled', true);
                    }

                } else if (type == 'plus') {

                    if (currentVal < input.attr('max')) {
                        input.val(currentVal + 1).change();
                    }
                    // if (parseInt(input.val()) == input.attr('max')) {
                    //     $(this).attr('disabled', true);
                    // }

                    var qty_max_val = parseInt($('#check_max_qty').val());
                    var qty_max_val = qty_max_val + 1;
                    if (parseInt(input.val()) >= qty_max_val) {
                        Swal.fire({
                            icon: 'error',
                            title: '{{translate("Cart")}}',
                            text: '{{translate('stock limit exceeded')}}.',
                            confirmButtonText: '{{translate("Yes")}}',
                        });
                        //$(this).val($(this).data());
                        input.val(qty_max_val-1);
                    }

                }
            } else {
                input.val(0);
            }
        });

        $('.input-number').focusin(function () {
            $(this).data('oldValue', $(this).val());
        });

        $('.input-number').change(function () {

            minValue = parseInt($(this).attr('min'));
            maxValue = parseInt($(this).attr('max'));
            valueCurrent = parseInt($(this).val());

            var input_qty_max_val = parseInt($('#check_max_qty').val());
            var input_qty_max_val = input_qty_max_val + 1;


            var name = $(this).attr('name');
            if (valueCurrent >= minValue) {
                $(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '{{translate("Cart")}}',
                    text: '{{translate('Sorry, the minimum value was reached')}}',
                    confirmButtonText: '{{translate("Yes")}}',
                });
                $(this).val($(this).data('oldValue'));
            }

            if(valueCurrent >= input_qty_max_val){
                console.log(input_qty_max_val);
                Swal.fire({
                    icon: 'error',
                    title: '{{translate("Cart")}}',
                    text: '{{translate('the maximum value was reached')}}',
                    confirmButtonText: '{{translate("Yes")}}',
                });
                $(this).val(input_qty_max_val-1)
            } else if (valueCurrent <= maxValue) {
                $(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled')
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '{{translate("Cart")}}',
                    text: '{{translate('Sorry, stock limit exceeded')}}.',
                    confirmButtonText: '{{translate("Yes")}}',
                });
                $(this).val(1)
            }
        });
        $(".input-number").keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                // Allow: Ctrl+A
                (e.keyCode == 65 && e.ctrlKey === true) ||
                // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                // let it happen, don't do anything
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
    }

    function getVariantPrice() {
        if ($('#add-to-cart-form input[name=quantity]').val() > 0 && checkAddToCartValidity()) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: '{{ route('admin.pos.variant_price') }}',
                data: $('#add-to-cart-form').serializeArray(),
                success: function (data) {
                    $('#add-to-cart-form #chosen_price_div').removeClass('d-none');
                    $('#add-to-cart-form #chosen_price_div #chosen_price').html(data.price);
                }
            });
        }
    }

    function addToCart(form_id = 'add-to-cart-form') {
        if (checkAddToCartValidity()) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.pos.add-to-cart') }}',
                data: $('#' + form_id).serializeArray(),
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    console.log(data);
                    if (data.data == 1) {
                        Swal.fire({
                            icon: 'info',
                            title: "{{translate('Cart')}}",
                            text: "{{translate('Product already added in cart')}}",
                            confirmButtonText: '{{translate("Yes")}}',
                        });
                        return false;
                    } else if (data.quantity <= 0) {
                        Swal.fire({
                            icon: 'info',
                            title: "{{translate('Cart')}}",
                            text: "{{translate('Product is out of stock')}}",
                            confirmButtonText: '{{translate("Yes")}}',
                        });
                        return false;

                    }   else if (data.data == 0) {
                        Swal.fire({
                            icon: 'error',
                            title: "{{translate('Cart')}}",
                            text: '{{translate('product out of stock')}}.',
                            confirmButtonText: '{{translate("Yes")}}',
                        });
                        return false;
                    }
                    $('.call-when-done').click();

                    toastr.success('{{translate('Item has been added in your cart')}}!', {
                        CloseButton: true,
                        ProgressBar: true
                    });

                    updateCart();
                },
                complete: function () {
                    $('#loading').hide();
                }
            });
        } else {
            Swal.fire({
                type: 'info',
                title: "{{translate('Cart')}}",
                text: '{{translate('Please choose all the options')}}',
                confirmButtonText: '{{translate("Yes")}}',
            });
        }
    }

    function removeFromCart(key) {
        $.post('{{ route('admin.pos.remove-from-cart') }}', {_token: '{{ csrf_token() }}', key: key}, function (data) {
            if (data.errors) {
                for (var i = 0; i < data.errors.length; i++) {
                    toastr.error(data.errors[i].message, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            } else {
                updateCart();
                toastr.info('{{translate('Item has been removed from cart')}}', {
                    CloseButton: true,
                    ProgressBar: true
                });
            }

        });
    }

    function emptyCart() {
        $.post('{{ route('admin.pos.emptyCart') }}', {_token: '{{ csrf_token() }}'}, function (data) {
            updateCart();
            toastr.info('{{translate('Item has been removed from cart')}}', {
                CloseButton: true,
                ProgressBar: true
            });
        });
    }

    function updateCart() {
        $.post('<?php echo e(route('admin.pos.cart_items')); ?>', {_token: '<?php echo e(csrf_token()); ?>'}, function (data) {
            $('#cart').empty().html(data);
        });
    }

    function store_key(key, value) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{csrf_token()}}"
            }
        });
        $.post({
            url: '{{route('admin.pos.store-keys')}}',
            data: {
                key:key,
                value:value,
            },
            success: function (data) {
                key = key=='customer_id' ? "{{translate('customer_id')}}" : (key=='branch_id' ? "{{translate('branch_id')}}":'');
                toastr.success(key+' '+'{{translate('selected')}}!', {
                    CloseButton: true,
                    ProgressBar: true
                });
            },
        });
    }

    $(function () {
        $(document).on('click', 'input[type=number]', function () {
            this.select();
        });
    });

    function updateQuantity(e) {
        var element = $(e.target);
        var minValue = parseInt(element.attr('min'));
        var maxValue = parseInt(element.attr('max'));
        // maxValue = parseInt(element.attr('max'));
        var valueCurrent = parseInt(element.val());

        var key = element.data('key');
        var product_id = element.attr("id");
        if (valueCurrent >= minValue && valueCurrent <= maxValue) {
            $.post('{{ route('admin.pos.updateQuantity') }}', {
                _token: '{{ csrf_token() }}',
                key: key,
                quantity: valueCurrent
            }, function (data) {
                updateCart();
            });
        } else if(valueCurrent >= maxValue) {Swal.fire({
            icon: 'error',
            title: '{{translate("Cart")}}',
            text: '{{translate('Product out of stock!!!')}}',
            confirmButtonText: '{{translate("Yes")}}',
        });
            element.val(element.data('oldValue'));
            updateCart();
        } else {
            Swal.fire({
                icon: 'error',
                title: "{{translate('Cart')}}",
                text: '{{translate('Sorry, the minimum value was reached')}}',
                confirmButtonText: '{{translate("Yes")}}',
            });
            element.val(element.data('oldValue'));
            updateCart();
        }


        // Allow: backspace, delete, tab, escape, enter and .
        if (e.type == 'keydown') {
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                // Allow: Ctrl+A
                (e.keyCode == 65 && e.ctrlKey === true) ||
                // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                // let it happen, don't do anything
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        }

    };

    // INITIALIZATION OF SELECT2
    // =======================================================
    $('.js-select2-custom').each(function () {
        var select2 = $.HSCore.components.HSSelect2.init($(this));
    });

    $('.js-data-example-ajax').select2({
        ajax: {
            url: '{{route('admin.pos.customers')}}',
            data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            __port: function (params, success, failure) {
                var $request = $.ajax(params);

                $request.then(success);
                $request.fail(failure);

                return $request;
            }
        }
    });

    $('.js-data-example-ajax-2').select2()

    $('#order_place').submit(function (eventObj) {
        if ($('#customer').val()) {
            $(this).append('<input type="hidden" name="user_id" value="' + $('#customer').val() + '" /> ');
        }
        return true;
    });


</script>
@endpush

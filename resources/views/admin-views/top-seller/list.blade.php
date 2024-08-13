@extends('layouts.admin.app')

@section('title', translate('Top Selling Products'))

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/products.png')}}" class="w--24" alt="">
                </span>
                <span>
                    {{ translate('Top Selling Products') }}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->

        <div class="card">
            <!-- Header -->
            <!-- <div class="card-header border-0 order-header-shadow">
                <h5 class="card-title d-flex justify-content-between flex-grow-1">
                    <span>{{translate('Top Selling Products')}}</span>
                    <a href="{{route('admin.product.list')}}" class="fz-12px font-medium text-006AE5">{{translate('view_all')}}</a>
                </h5>
            </div> -->
            <!-- Body -->
            <div class="card-body">
              <div class="table-responsive datatable-custom">
                <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                        <tr>
                            <th>{{translate('#')}}</th>
                            <th>{{translate('Product')}}</th> <!-- Merged column for Product Name and Image -->
                            <th>{{translate('Sold Quantity')}}</th>
                        </tr>
                    </thead>

                    <tbody id="set-rows">
                        @foreach($top_sell as $key=>$item)
                        @if(isset($item->product))
                        <tr>
                            <td class="pt-1 pb-3 {{$key == 0 ? 'pt-4' : '' }}">{{ $key + 1 }}</td>
                            <td class="pt-1 pb-3 {{$key == 0 ? 'pt-4' : '' }}">
                                <a href="{{route('admin.product.view',[$item['product_id']])}}" class="d-flex align-items-center">
                                    @if (!empty($item->product->image))
                                    <img src="{{ $item->product->image[0] ?? '' }}"
                                    onerror="this.src='{{asset('public/assets/admin/img/400x400/img2.jpg')}}'"
                                    alt="{{$item->product->name}} image" class="img-thumbnail mr-2" style="width: 50px;">
                                    @endif
                                    <span>{{ substr($item->product['name'], 0, 10) . (strlen($item->product['name']) > 10 ? '...' : '') }}</span>
                                </a>
                            </td>
                            <td class="pt-1 pb-3 {{$key == 0 ? 'pt-4' : '' }}">
                                <span class="badge badge-soft">{{ translate('Sold') }} : {{$item['count']}}</span>
                            </td>
                        </tr>
                        @endif
                        @endforeach
                       </tbody>
                       </table>

                    <!-- Pagination -->
                    <div class="page-area">
                        <table>
                            <tfoot class="border-top">
                            {!! $top_sell->links() !!}
                            </tfoot>
                        </table>
                    </div>

                    @if(count($top_sell) == 0)
                        <div class="text-center p-4">
                            <img class="w-120px mb-3" src="{{asset('/public/assets/admin/svg/illustrations/sorry.svg')}}" alt="Image Description">
                            <p class="mb-0">{{translate('No_data_to_show')}}</p>
                        </div>
                    @endif
                </div>
            </div>
            <!-- End Body -->
        </div>
    </div>
@endsection

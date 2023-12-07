@extends('layouts.admin.app')

@section('title', translate('Recent Activity List'))

@section('content')
    <div class="content container-fluid product-list-page">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/products.png')}}" class="w--24" alt="">
                </span>
                <span>
                    {{ translate('Recent Activity List') }}
                    <span class="badge badge-soft-secondary">{{ $activities->total() }}</span>
                </span>
            </h1>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <!-- Card -->
                <div class="card">
                    <!-- Header -->
                    <div class="card-header border-0">
                        <div class="card--header justify-content-end max--sm-grow">
                            <form action="{{route('admin.user-activity.list')}}" method="GET" class="mr-sm-auto">
                                <div class="input-group">
                                    <input id="datatableSearch_" type="search" name="search"
                                        class="form-control"
                                        placeholder="{{translate('Search_by_ID_or_name')}}" aria-label="Search"
                                        value="{{$search}}" required autocomplete="off">
                                    <div class="input-group-append">
                                        <button type="submit" class="input-group-text">
                                            {{translate('search')}}
                                        </button>
                                    </div>
                                    <div class="col-sm-6 col-md-12 col-lg-4 __btn-row">
                                        <a href="{{route('admin.user-activity.list')}}" id="" class="btn w-100 btn--reset min-h-45px">{{translate('clear')}}</a>
                                    </div>
                                </div>
                            </form>
                            <!-- Unfold -->
                    </div>

                    
                    <!-- End Table -->
                </div>
                <div class="table-responsive datatable-custom">
                        <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                            <tr>
                                <th>{{translate('#')}}</th>
                                <th>{{translate('user_id')}}</th>
                                <th>{{translate('message')}}</th>
                                <th>{{translate('date')}}</th>
                            </tr>
                            </thead>

                            <tbody id="set-rows">
                            @foreach($activities as $key=>$activity)
                                <tr>
                                    <td class="pt-1 pb-3  {{$key == 0 ? 'pt-4' : '' }}">{{$activity->firstItem+$key}}</td>
                                    <td class="pt-1 pb-3  {{$key == 0 ? 'pt-4' : '' }}">{{$activity->user_id}}</td>
                                    <td class="pt-1 pb-3  {{$key == 0 ? 'pt-4' : '' }}">{{$activity->message}}</td>
                                    <td class="pt-1 pb-3  {{$key == 0 ? 'pt-4' : '' }}">{{$activity->created_at}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <div class="page-area">
                            <table>
                                <tfoot class="border-top">
                                {!! $activities->links() !!}
                                </tfoot>
                            </table>
                        </div>
                        @if(count($activities)==0)
                            <div class="text-center p-4">
                                <img class="w-120px mb-3" src="{{asset('/public/assets/admin/svg/illustrations/sorry.svg')}}" alt="Image Description">
                                <p class="mb-0">{{translate('No_data_to_show')}}</p>
                            </div>
                        @endif
                    </div>
                <!-- End Card -->
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        $('#search-form').on('submit', function () {
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.user-activity.list')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#set-rows').html(data.view);
                    $('.page-area').hide();
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });
    </script>

@endpush

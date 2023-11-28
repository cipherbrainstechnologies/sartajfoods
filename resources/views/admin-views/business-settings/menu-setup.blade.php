@extends('layouts.admin.app')

@section('title', translate('business settings'))


@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        @include('admin-views.business-settings.partial.business-settings-navmenu')

        @php($config=\App\CentralLogics\Helpers::get_business_settings('maintenance_mode'))
        <div class="tab-content">
            <div class="tab-pane fade show active" id="business-setting">
                
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title d-flex align-items-center">
                            <span class="card-header-icon mb-1 mr-2">
                                <img src="{{asset('public/assets/admin/img/bag.png')}}" class="w--17" alt="">
                            </span>
                            <span>{{translate('Menu Setting')}}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.business-settings.store.menu-setup-update')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <!-- about us -->
                                @php($link_about_us=!empty(\App\Model\BusinessSetting::where('key','link_about_us')->first()->value) ? \App\Model\BusinessSetting::where('key','link_about_us')->first()->value : null)
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('About Us')}}</label>
                                        <input type="text" value="{{$link_about_us}}" name="link_about_us" class="form-control" placeholder="{{translate('google.com')}}" required>
                                    </div>
                                </div>
                                <!-- Delivery information -->
                                @php($link_delivery_information=!empty(\App\Model\BusinessSetting::where('key','link_delivery_information')->first()->value) ? \App\Model\BusinessSetting::where('key','link_delivery_information')->first()->value : null)
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('Delivery Information')}}</label>
                                        <input type="text" value="{{$link_delivery_information}}" name="link_delivery_information" class="form-control" placeholder="{{translate('google.com')}}" required>
                                    </div>
                                </div>
                                <!-- Privacy Policy -->
                                @php($link_privacy_policy=!empty(\App\Model\BusinessSetting::where('key','link_privacy_policy')->first()->value) ? \App\Model\BusinessSetting::where('key','link_privacy_policy')->first()->value : null)
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('Privacy Policy')}}</label>
                                        <input type="text" value="{{$link_privacy_policy}}" name="link_privacy_policy" class="form-control" placeholder="{{translate('google.com')}}" required>
                                    </div>
                                </div>

                                 <!-- Term Conditions -->
                                 @php($link_term_conditions=!empty(\App\Model\BusinessSetting::where('key','link_term_conditions')->first()->value) ?  \App\Model\BusinessSetting::where('key','link_term_conditions')->first()->value : null)
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('Privacy Policy')}}</label>
                                        <input type="text" value="{{$link_term_conditions}}" name="link_term_conditions" class="form-control" placeholder="{{translate('google.com')}}" required>
                                    </div>
                                </div>

                                <!-- Contact us -->
                                @php($link_contact_us= !empty(\App\Model\BusinessSetting::where('key','link_contact_us')->first()->value) ? \App\Model\BusinessSetting::where('key','link_contact_us')->first()->value : null)
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('Contact us')}}</label>
                                        <input type="text" value="{{$link_contact_us}}" name="link_contact_us" class="form-control" placeholder="{{translate('google.com')}}" required>
                                    </div>
                                </div>
                                
                                 <!-- Support Center -->
                                 @php($link_support_center=!empty(\App\Model\BusinessSetting::where('key','link_support_center')->first()->value) ? \App\Model\BusinessSetting::where('key','link_support_center')->first()->value : null)
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('Support Center')}}</label>
                                        <input type="text" value="{{$link_support_center}}" name="link_support_center" class="form-control" placeholder="{{translate('google.com')}}" required>
                                    </div>
                                </div>

                                <!-- Career -->
                                @php($link_career=!empty(\App\Model\BusinessSetting::where('key','link_career')->first()->value) ? \App\Model\BusinessSetting::where('key','link_career')->first()->value : null)
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('Career')}}</label>
                                        <input type="text" value="{{$link_career}}" name="link_career" class="form-control" placeholder="{{translate('google.com')}}" required>
                                    </div>
                                </div>

                                <!-- Sign in -->
                                @php($link_sign_in=!empty(\App\Model\BusinessSetting::where('key','link_sign_in')->first()->value) ? \App\Model\BusinessSetting::where('key','link_sign_in')->first()->value : null)
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('Sign In')}}</label>
                                        <input type="text" value="{{$link_sign_in}}" name="link_sign_in" class="form-control" placeholder="{{translate('google.com')}}" required>
                                    </div>
                                </div>


                                <!-- View Cart -->
                                @php($link_view_cart=!empty(\App\Model\BusinessSetting::where('key','link_view_cart')->first()->value) ? \App\Model\BusinessSetting::where('key','link_view_cart')->first()->value : null)
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('View Cart')}}</label>
                                        <input type="text" value="{{$link_view_cart}}" name="link_view_cart" class="form-control" placeholder="{{translate('google.com')}}" required>
                                    </div>
                                </div>

                                <!-- My Wishlist -->
                                @php($link_my_wishlist=!empty(\App\Model\BusinessSetting::where('key','link_my_wishlist')->first()->value) ? \App\Model\BusinessSetting::where('key','link_my_wishlist')->first()->value : null)
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('My Wishlist')}}</label>
                                        <input type="text" value="{{$link_my_wishlist}}" name="link_my_wishlist" class="form-control" placeholder="{{translate('google.com')}}" required>
                                    </div>
                                </div>

                                 <!-- Track My Order -->
                                 @php($link_track_my_order=!empty(\App\Model\BusinessSetting::where('key','link_track_my_order')->first()->value) ? \App\Model\BusinessSetting::where('key','link_track_my_order')->first()->value : null)
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('Track My Order')}}</label>
                                        <input type="text" value="{{$link_track_my_order}}" name="link_track_my_order" class="form-control" placeholder="{{translate('google.com')}}" required>
                                    </div>
                                </div>

                                <!-- Help Ticket -->
                                @php($link_help_ticket=!empty(\App\Model\BusinessSetting::where('key','link_help_ticket')->first()->value) ? \App\Model\BusinessSetting::where('key','link_help_ticket')->first()->value : null)
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('Help Ticket')}}</label>
                                        <input type="text" value="{{$link_help_ticket}}" name="link_help_ticket" class="form-control" placeholder="{{translate('google.com')}}" required>
                                    </div>
                                </div>

                                 <!-- Shipping Details -->
                                 @php($link_shipping_detail=!empty(\App\Model\BusinessSetting::where('key','link_shipping_detail')->first()->value) ? \App\Model\BusinessSetting::where('key','link_shipping_detail')->first()->value : null)
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('Shipping Detail')}}</label>
                                        <input type="text" value="{{$link_shipping_detail}}" name="link_shipping_detail" class="form-control" placeholder="{{translate('google.com')}}" required>
                                    </div>
                                </div>

                                 <!-- Compare Products -->
                                 @php($link_compare_product=!empty(\App\Model\BusinessSetting::where('key','compare_product')->first()->value) ? \App\Model\BusinessSetting::where('key','link_compare_product')->first()->value : null)
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('Compare Product')}}</label>
                                        <input type="text" value="{{$link_compare_product}}" name="link_compare_product" class="form-control" placeholder="{{translate('google.com')}}" required>
                                    </div>
                                </div>

                                <!-- Play store -->
                                @php($link_google_play=!empty(\App\Model\BusinessSetting::where('key','link_google_play')->first()->value) ? \App\Model\BusinessSetting::where('key','link_google_play')->first()->value : null)
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('Google Play')}}</label>
                                        <input type="text" value="{{$link_google_play}}" name="link_google_play" class="form-control" placeholder="{{translate('google.com')}}" required>
                                    </div>
                                </div>

                                <!-- App store -->
                                @php($link_app_store=!empty(\App\Model\BusinessSetting::where('key','link_app_store')->first()->value) ? \App\Model\BusinessSetting::where('key','link_app_store')->first()->value : null)
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('App Store')}}</label>
                                        <input type="text" value="{{$link_app_store}}" name="link_app_store" class="form-control" placeholder="{{translate('google.com')}}" required>
                                    </div>
                                </div>

                            <div class="btn--container justify-content-end mt-5">
                                <button type="reset" class="btn btn--reset">{{translate('reset')}}</button>
                                <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                    onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                    class="btn btn--primary">{{translate('save')}}</button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

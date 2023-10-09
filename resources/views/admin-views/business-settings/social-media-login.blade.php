@extends('layouts.admin.app')

@section('title', translate('social media login'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">


        <div class="page-header">
            @include('admin-views.business-settings.partial.third-party-api-navmenu')
        </div>
        <!-- Content Row -->
        <div class="row g-3">
            <div class="col-md-6">
                <?php
                    $google=\App\Model\BusinessSetting::where('key','google_social_login')->first()->value;
                    $status = $google == 1 ? 0 : 1;
                ?>
                <div class="card __social-media-login __shadow">
                    <div class="card-body">
                        <div class="__social-media-login-top">
                            <div class="__social-media-login-icon">
                                <img src="{{asset('/public/assets/admin/img/google.png')}}" alt="">
                            </div>
                            <div class="text-center sub-txt">{{translate('Google Login')}}</div>
                            <div class="custom--switch switch--right">
                                <input type="checkbox" id="google_social_login" name="google" switch="primary" class="toggle-switch-input" {{ $google == 1 ? 'checked' : '' }}>
                                <label for="google_social_login" data-on-label="on" data-off-label="off"
                                       onclick="google_social_login('{{route('admin.business-settings.web-app.third-party.google-social-login',[$status])}}')"></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <?php
                    $facebook =\App\Model\BusinessSetting::where('key','facebook_social_login')->first()->value;
                    $status = $facebook == 1 ? 0 : 1;
                ?>
                <div class="card __social-media-login __shadow">
                    <div class="card-body">
                        <div class="__social-media-login-top">
                            <div class="__social-media-login-icon">
                                <img src="{{asset('/public/assets/admin/img/facebook.png')}}" alt="">
                            </div>
                            <div class="text-center sub-txt">{{translate('Facebook Login')}}</div>
                            <div class="custom--switch switch--right">
                                <input type="checkbox" id="facebook" name="facebook_social_login" switch="primary" class="toggle-switch-input" {{ $facebook == 1 ? 'checked' : '' }}>
                                <label for="facebook" data-on-label="on" data-off-label="off"
                                       onclick="facebook_social_login('{{route('admin.business-settings.web-app.third-party.facebook-social-login',[$status])}}')"></label>
                            </div>
                        </div>
                        <!--<form>
                            <div class="form-group">
                                <label class="form-label font-semibold">{{translate('Callback URI')}}</label>
                                <div class="__input-grp">
                                    <input type="text" class="form-control" placeholder="{{translate('Ex: facebook.com/your-page-name')}}">
                                    <button class="btn btn&#45;&#45;primary rounded" type="button"><i class="tio-copy"></i> <span class="d-sm-inline-block d-none">copy url</span> </button>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label font-semibold">{{translate('Store Client ID')}}</label>
                                <input type="text" class="form-control" placeholder="{{translate('Ex: 4DKDDKD38DDL')}}">
                            </div>
                            <div class="form-group">
                                <label class="form-label font-semibold">{{translate('Store Client Secret Key')}}</label>
                                <input type="text" class="form-control" placeholder="{{translate('Ex: Kdfkdkd93402934342l3kkoef')}}">
                            </div>
                            <div class="btn&#45;&#45;container justify-content-between">
                                <button type="button" class="btn btn&#45;&#45;primary-fades">
                                    <img src="{{asset('/public/assets/admin/img/bi_info-circle.png')}}" data-toggle="tooltip"
                                         data-placement="top" data-original-title="{{translate('See Setup Instructions')}}" alt="">
                                    {{translate('See Setup Instructions')}}
                                </button>
                                <button type="submit" class="btn btn&#45;&#45;primary">{{translate('save')}}</button>
                            </div>
                        </form>-->
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('script_2')
    <script>
        function google_social_login(route) {

            $.get({
                url: route,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    setTimeout(function () {
                        location.reload(true);
                    }, 1000);
                    toastr.success(data.message);
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }

        function facebook_social_login(route) {

            $.get({
                url: route,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    setTimeout(function () {
                        location.reload(true);
                    }, 1000);
                    toastr.success(data.message);
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }
    </script>
@endpush



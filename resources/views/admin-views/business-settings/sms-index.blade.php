@extends('layouts.admin.app')

@section('title', translate('SMS config'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            @include('admin-views.business-settings.partial.third-party-api-navmenu')
        </div>
        <!-- End Page Header -->

        <div class="row g-3">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-center text-uppercase mb-1">
                            <h5 class="text-center">{{translate('twilio_sms')}}</h5>
                            <div class="pl-2">
                                <img src="{{asset('public/assets/admin/img/twilio.png')}}" alt="public" style="height: 50px">
                            </div>
                        </div>
                        <span class="badge badge-soft-info mb-3 word-break">{{ translate('NB : #OTP# will be replace with otp') }}</span>
                        @php($config=\App\CentralLogics\Helpers::get_business_settings('twilio_sms'))
                        <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.web-app.sms-module-update',['twilio_sms']):'javascript:'}}"
                              method="post">
                            @csrf
                            <div class="d-flex flex-wrap mb-4">
                                <label class="form-check form--check mr-2 mr-md-4">
                                    <input class="form-check-input" type="radio" name="status"  value="1" {{isset($config) && $config['status']==1?'checked':''}}>
                                    <span class="form-check-label text--title pl-2">{{translate('active')}}</span>
                                </label>
                                <label class="form-check form--check">
                                    <input class="form-check-input" type="radio" name="status" value="0" {{isset($config) && $config['status']==0?'checked':''}}>
                                    <span class="form-check-label text--title pl-2">{{translate('inactive')}}</span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="text-capitalize"
                                       class="form-label">{{translate('sid')}}</label><br>
                                <input type="text" class="form-control" name="sid"
                                       value="{{env('APP_MODE')!='demo'?$config['sid']??"":''}}" placeholder="{{translate('Ex: ACbf855229b8b2e5d02cad58e116365164')}}">
                            </div>

                            <div class="form-group">
                                <label class="text-capitalize"
                                       class="form-label">{{translate('messaging_service_sid')}}</label><br>
                                <input type="text" class="form-control" name="messaging_service_sid"
                                       value="{{env('APP_MODE')!='demo'?$config['messaging_service_sid']??"":''}}" placeholder="{{translate('Ex: ACbf855229b8b2e5d02cad58e116365164')}}">
                            </div>

                            <div class="form-group">
                                <label class="form-label">{{translate('token')}}</label><br>
                                <input type="text" class="form-control" name="token"
                                       value="{{env('APP_MODE')!='demo'?$config['token']??"":''}}" placeholder="{{translate('Ex: ACbf855229b8b2e5d02cad58e116365164')}}">
                            </div>

                            <div class="form-group">
                                <label class="form-label">{{translate('from')}}</label><br>
                                <input type="text" class="form-control" name="from"
                                       value="{{env('APP_MODE')!='demo'?$config['from']??"":''}}" placeholder="{{translate('Ex: +91-46482373636')}}">
                            </div>

                            <div class="form-group">
                                <label class="form-label">{{translate('otp_template')}}</label><br>
                                <input type="text" class="form-control" name="otp_template"
                                       value="{{env('APP_MODE')!='demo'?$config['otp_template']??"":''}}" placeholder="{{translate('Ex : Your OTP is #otp#')}}">
                            </div>
                            <div class="text-right">
                                <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                    onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                    class="btn btn-primary px-lg-5">{{translate('save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-center text-uppercase mb-1">
                            <h5 class="text-center">{{translate('nexmo_sms')}}</h5>
                            <div class="pl-2">
                                <img src="{{asset('public/assets/admin/img/nexmo.png')}}" alt="public" style="height: 50px">
                            </div>
                        </div>
                        <span class="badge badge-soft-info mb-3 word-break">{{ translate('NB : #OTP# will be replace with otp') }}</span>
                        @php($config=\App\CentralLogics\Helpers::get_business_settings('nexmo_sms'))
                        <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.web-app.sms-module-update',['nexmo_sms']):'javascript:'}}"
                              method="post">
                            @csrf


                            <div class="d-flex flex-wrap mb-4">
                                <label class="form-check form--check mr-2 mr-md-4">
                                    <input class="form-check-input" type="radio" name="status"  value="1" {{isset($config) && $config['status']==1?'checked':''}}>
                                    <span class="form-check-label text--title pl-2">{{translate('active')}}</span>
                                </label>
                                <label class="form-check form--check">
                                    <input class="form-check-input" type="radio" name="status" value="0" {{isset($config) && $config['status']==0?'checked':''}}>
                                    <span class="form-check-label text--title pl-2">{{translate('inactive')}}</span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="text-capitalize"
                                       class="form-label">{{translate('api_key')}}</label><br>
                                <input type="text" class="form-control" name="api_key"
                                       value="{{env('APP_MODE')!='demo'?$config['api_key']??"":''}}" placeholder="{{translate('Ex :5923ec0959')}}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">{{translate('api_secret')}}</label><br>
                                <input type="text" class="form-control" name="api_secret"
                                       value="{{env('APP_MODE')!='demo'?$config['api_secret']??"":''}}" placeholder="{{translate('Ex : RYysbkdscnUIizx')}}">
                            </div>

                            <div class="form-group">
                                <label class="form-label">{{translate('from')}}</label><br>
                                <input type="text" class="form-control" name="from"
                                       value="{{env('APP_MODE')!='demo'?$config['from']??"":''}}" placeholder="{{translate('Ex : RYysbkdscnUIizx')}}">
                            </div>

                            <div class="form-group">
                                <label class="form-label">{{translate('otp_template')}}</label><br>
                                <input type="text" class="form-control" name="otp_template"
                                       value="{{env('APP_MODE')!='demo'?$config['otp_template']??"":''}}" placeholder="{{translate('Ex : Your OTP is #otp#')}}">
                            </div>
                            <div class="text-right">
                                <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                    onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                    class="btn btn-primary px-lg-5">{{translate('save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-center text-uppercase mb-1">
                            <h5 class="text-center">{{translate('2factor_sms')}}</h5>
                            <div class="pl-2">
                                <img src="{{asset('public/assets/admin/img/2factor.png')}}" alt="public" style="height: 50px">
                            </div>
                        </div>
                        <span class="badge badge-soft-info word-break">{{ translate("EX of SMS provider's template : your OTP is XXXX here, please check.") }}</span><br>
                        <span class="badge badge-soft-info mb-3 word-break">{{ translate('NB : XXXX will be replace with otp') }}</span>
                        @php($config=\App\CentralLogics\Helpers::get_business_settings('2factor_sms'))
                        <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.web-app.sms-module-update',['2factor_sms']):'javascript:'}}"
                              method="post">
                            @csrf


                            <div class="d-flex flex-wrap mb-4">
                                <label class="form-check form--check mr-2 mr-md-4">
                                    <input class="form-check-input" type="radio" name="status"  value="1" {{isset($config) && $config['status']==1?'checked':''}}>
                                    <span class="form-check-label text--title pl-2">{{translate('active')}}</span>
                                </label>
                                <label class="form-check form--check">
                                    <input class="form-check-input" type="radio" name="status" value="0" {{isset($config) && $config['status']==0?'checked':''}}>
                                    <span class="form-check-label text--title pl-2">{{translate('inactive')}}</span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="text-capitalize"
                                       class="form-label">{{translate('api_key')}}</label><br>
                                <input type="text" class="form-control" name="api_key"
                                       value="{{env('APP_MODE')!='demo'?$config['api_key']??"":''}}" placeholder="{{translate('Ex :ACbf855229b8b2e5d02cad58e116365164')}}">
                            </div>
                            <div class="text-right">
                                <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                    onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                    class="btn btn-primary px-lg-5">{{translate('save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-center text-uppercase mb-1">
                            <h5 class="text-center">{{translate('msg91_sms')}}</h5>
                            <div class="pl-2">
                                <img src="{{asset('public/assets/admin/img/msg91.png')}}" alt="public" style="height: 50px">
                            </div>
                        </div>
                        <span class="badge badge-soft-info mb-3 word-break">{{ translate('NB : Keep an OTP variable in your SMS providers OTP Template.') }}</span><br>
                        @php($config=\App\CentralLogics\Helpers::get_business_settings('msg91_sms'))
                        <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.web-app.sms-module-update',['msg91_sms']):'javascript:'}}"
                              method="post">
                            @csrf


                            <div class="d-flex flex-wrap mb-4">
                                <label class="form-check form--check mr-2 mr-md-4">
                                    <input class="form-check-input" type="radio" name="status"  value="1" {{isset($config) && $config['status']==1?'checked':''}}>
                                    <span class="form-check-label text--title pl-2">{{translate('active')}}</span>
                                </label>
                                <label class="form-check form--check">
                                    <input class="form-check-input" type="radio" name="status" value="0" {{isset($config) && $config['status']==0?'checked':''}}>
                                    <span class="form-check-label text--title pl-2">{{translate('inactive')}}</span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="text-capitalize"
                                       class="form-label">{{translate('template_id')}}</label><br>
                                <input type="text" class="form-control" name="template_id"
                                       value="{{env('APP_MODE')!='demo'?$config['template_id']??"":''}}" placeholder="{{translate('Ex :ACbf855229b8b2e5d02cad58e116365164')}}">
                            </div>
                            <div class="form-group">
                                <label class="text-capitalize"
                                       class="form-label">{{translate('authkey')}}</label><br>
                                <input type="text" class="form-control" name="authkey"
                                       value="{{env('APP_MODE')!='demo'?$config['authkey']??"":''}}" placeholder="{{translate('Ex :ACbf855229b8b2e5d02cad58e116365164')}}">
                            </div>
                            <div class="text-right">
                                <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                    onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                    class="btn btn-primary px-lg-5">{{translate('save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-center text-uppercase mb-1">
                            <h5 class="text-center">{{translate('signalwire_sms')}}</h5>
                            <div class="pl-2">
                                <img src="{{asset('public/assets/admin/img/signalwire.png')}}" alt="public" style="height: 50px">
                            </div>
                        </div>
                        <span class="badge badge-soft-info mb-3 word-break">{{translate('NB : #OTP# will be replace with otp')}}</span><br>
                        @php($config=\App\CentralLogics\Helpers::get_business_settings('signalwire_sms'))
                        <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.web-app.sms-module-update',['signalwire_sms']):'javascript:'}}"
                              method="post">
                            @csrf


                            <div class="d-flex flex-wrap mb-4">
                                <label class="form-check form--check mr-2 mr-md-4">
                                    <input class="form-check-input" type="radio" name="status"  value="1" {{isset($config) && $config['status']==1?'checked':''}}>
                                    <span class="form-check-label text--title pl-2">{{translate('active')}}</span>
                                </label>
                                <label class="form-check form--check">
                                    <input class="form-check-input" type="radio" name="status" value="0" {{isset($config) && $config['status']==0?'checked':''}}>
                                    <span class="form-check-label text--title pl-2">{{translate('inactive')}}</span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="text-capitalize"
                                       style="padding-left: 2px">{{translate('project_id')}}</label><br>
                                <input type="text" class="form-control" name="project_id"
                                       value="{{env('APP_MODE')!='demo'?$config['project_id']??"":''}}" placeholder="{{translate('Ex :ACbf855229b8b2e5d02cad58e116365164')}}">
                            </div>
                            <div class="form-group">
                                <label class="text-capitalize"
                                       style="padding-left: 2px">{{translate('token')}}</label><br>
                                <input type="text" class="form-control" name="token"
                                       value="{{env('APP_MODE')!='demo'?$config['token']??"":''}}" placeholder="{{translate('Ex :ACbf855229b8b2e5d02cad58e116365164')}}">
                            </div>
                            <div class="form-group">
                                <label class="text-capitalize"
                                       style="padding-left: 2px">{{translate('space_url')}}</label><br>
                                <input type="text" class="form-control" name="space_url"
                                       value="{{env('APP_MODE')!='demo'?$config['space_url']??"":''}}" placeholder="{{translate('Ex: ACbf855229b8b2e5d02cad58e116365164')}}">
                            </div>
                            <div class="form-group">
                                <label class="text-capitalize"
                                       style="padding-left: 2px">{{translate('from')}}</label><br>
                                <input type="text" class="form-control" name="from"
                                       value="{{env('APP_MODE')!='demo'?$config['from']??"":''}}" placeholder="{{translate('Ex: +91-46482373636')}}">
                            </div>
                            <div class="form-group">
                                <label style="padding-left: 2px">{{translate('otp_template')}}</label><br>
                                <input type="text" class="form-control" name="otp_template"
                                       value="{{env('APP_MODE')!='demo'?$config['otp_template']??"":''}}" placeholder="{{translate('Ex : Your OTP is #otp#')}}">
                            </div>
                            <div class="text-right">
                                <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                    onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                    class="btn btn-primary px-lg-5">{{translate('save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')

@endpush

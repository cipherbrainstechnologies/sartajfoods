@extends('layouts.admin.app')

@section('title', translate('About us'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            @include('admin-views.business-settings.partial.page-setup-menu')
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{route('admin.business-settings.page-setup.about-us')}}" method="post" id="tnc-form">
                    @csrf
                    <label class="input-label" for="about_us">{{translate('English')}}</label>
                    <div class="form-group">
                        <textarea class="ckeditor form-control" name="about_us">{!! $data['value'] !!}</textarea>
                    </div>

                    <label class="input-label" for="japanese_about_us">{{translate('Japanese')}}</label>
                    <div class="form-group">
                        <textarea class="ckeditor form-control" name="japanese_about_us">{!! $japaneseAboutUs['value'] !!}</textarea>
                    </div>
                    
                    <div class="btn--container justify-content-end">
                        <button type="reset" class="btn btn--reset" id="reset">{{translate('reset')}}</button>
                        <button type="submit" class="btn btn--primary">{{translate('submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.ckeditor').ckeditor();
        });

        $('#reset').click(function() {
            location.reload();
        });
    </script>
@endpush

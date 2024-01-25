@extends('layouts.admin.app')

@section('title', translate('Update manufacturer'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/attribute.png')}}" class="w--24" alt="">
                </span>
                <span>
                    {{translate('manufacturer')}} {{translate('update')}}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->
        <div class="card">
            <div class="card-body pt-2">
                <form action="{{route('admin.manufacturer.update',[$manufacturer['id']])}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @php($data = Helpers::get_business_settings('language'))
                    @php($default_lang = Helpers::get_default_language())

                    @if($data && array_key_exists('code', $data[0]))
                        
                        <ul class="nav nav-tabs mb-4 d-inline-flex">
                            @foreach($data as $lang)
                                <li class="nav-item">
                                    <a class="nav-link lang_link {{$lang['default'] == true ? 'active':''}}" href="#" id="{{$lang['code']}}-link">
                                        {{ Helpers::get_language_name($lang['code']).'('.strtoupper($lang['code']).')' }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="row">
                            <div class="col-12">
                                @foreach($data as $lang)
                                    <?php
                                        if(count($manufacturer['translations'])){
                                            $translate = [];
                                            foreach($manufacturer['translations'] as $t)
                                            {
                                                if($t->locale == $lang['code'] && $t->key=="name"){
                                                    $translate[$lang['code']]['name'] = $t->value;
                                                }
                                                if ($t->locale == $lang['code'] && $t->key == "meta_title") {
                                                    $translate[$lang['code']]['meta_title'] = $t->value;
                                                }
                                                if ($t->locale == $lang['code'] && $t->key == "meta_description") {
                                                    $translate[$lang['code']]['meta_description'] = $t->value;
                                                }
                                                if ($t->locale == $lang['code'] && $t->key == "meta_keywords") {
                                                    $translate[$lang['code']]['meta_keywords'] = $t->value;
                                                }
                                            }
                                        }
                                    ?>
                                    <div class="form-group lang_form {{$lang['default'] == false ? 'd-none':''}}" id="{{$lang['code']}}-form">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label class="input-label" for="exampleFormControlInput1">{{translate('manufacturer name')}} ({{strtoupper($lang['code'])}})</label>
                                                    <input type="text" name="name[]" class="form-control"
                                                    placeholder="{{translate('manufacturer name')}}" value="{{$lang['code'] == 'en' ? $manufacturer['name'] : ($translate[$lang['code']]['name']??'')}}"
                                                        {{$lang['status'] == true ? 'required':''}} maxlength="255"
                                                    @if($lang['status'] == true) oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label" for="exampleFormControlInput1">{{translate('meta tag title')}} ({{strtoupper($lang['code'])}})</label>
                                                    <input type="text" name="meta_title[]" class="form-control" maxlength="255" value="{{$lang['code'] == 'en' ? $manufacturer['meta_title'] : ($translate[$lang['code']]['meta_title']??'')}}" required>
                                                </div>
                                                @if($lang['code'] == "en")
                                                    <div class="form-group">
                                                        <label class="input-label" for="{{$lang['code']}}_seo_en">{{translate('SEO')}} ({{strtoupper($lang['code'])}})</label>
                                                        <input type="text" name="en_seo" class="form-control" id="{{$lang['code']}}_seo" placeholder="{{translate('SEO')}} ({{translate('EN')}})" value="{{$manufacturer['seo_en']??''}}" required>
                                                    </div>
                                                @else
                                                    <div class="form-group">
                                                        <label class="input-label" for="{{$lang['code']}}_seo_ja">{{translate('SEO')}} ({{strtoupper($lang['code'])}})</label>
                                                        <input type="text" name="ja_seo" class="form-control" id="{{$lang['code']}}_seo" placeholder="{{translate('SEO')}} ({{translate('JA')}})" value="{{$manufacturer['seo_ja'] ?? ''}}" required>
                                                    </div>
                                                @endif
                                               
                                            </div> 
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label class="form-group"
                                                        for="exampleFormControlInput1">{{translate('meta tag description')}}
                                                    ({{strtoupper($lang['code'])}})</label>
                                                    <textarea name="meta_description[]" class="form-control">{{$lang['code'] == 'en' ? $manufacturer['meta_description'] : ($translate[$lang['code']]['meta_description']??'')}}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label"
                                                        for="exampleFormControlInput1">{{translate('meta tag keywords')}}
                                                    ({{strtoupper($lang['code'])}})</label>
                                                    <textarea name="meta_keywords[]" class="form-control">{{$lang['code'] == 'en' ? $manufacturer['meta_keywords'] : ($translate[$lang['code']]['meta_keywords']??'')}}</textarea>
                                                </div>
                                            </div>
                                           
                                            </div>    
                                    </div>
                                    <input type="hidden" name="lang[]" value="{{$lang['code']}}">
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group lang_form" id="{{$default_lang}}-form">
                                    <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label class="input-label" for="exampleFormControlInput1">{{translate('manufacturer name')}} ({{strtoupper($default_lang)}})</label>
                                                    <input type="text" name="name[]" class="form-control"
                                                    placeholder="{{translate('manufacturer name')}}" value="{{$manufacturer['name']}}"
                                                        {{$lang['status'] == true ? 'required':''}} maxlength="255"
                                                    @if($lang['status'] == true) oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label" for="exampleFormControlInput1">{{translate('meta tag title')}} ({{strtoupper($default_lang)}})</label>
                                                    <input type="text" name="meta_title[]" class="form-control" maxlength="255" value="$manufacturer['meta_title']" required>
                                                </div>
                                            </div> 
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label class="form-group"
                                                        for="exampleFormControlInput1">{{translate('meta tag description')}}
                                                    ({{strtoupper($default_lang)}})</label>
                                                    <textarea name="meta_description[]" class="form-control">$manufacturer['meta_description']</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label"
                                                        for="exampleFormControlInput1">{{translate('meta tag keywords')}}
                                                    ({{strtoupper($default_lang)}})</label>
                                                    <textarea name="meta_keywords[]" class="form-control">$manufacturer['meta_keywords']</textarea>
                                                </div>
                                            </div>
                                        </div>  
                                </div>
                                <input type="hidden" name="lang[]" value="{{$default_lang}}">
                            </div>
                        </div>
                    @endif

                    <div class="card">
                            <div class="card-body">
                                <h5 class="mb-3">{{translate('manufacturer')}} {{translate('image')}} <small
                                    class="text-danger">* ( {{translate('ratio')}} 1:1 )</small></h5>
                                <div class="product--coba">
                                    <div class="row g-2" id="coba">
                                         <div class="spartan_item_wrapper position-relative">
                                            <img class="img-150 border rounded p-3" src="{{asset('storage/product/image')}}/{{$manufacturer['image']}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="form-group">
                        <label class="input-label" for="exampleFormControlInput1">{{translate('sort order')}}</label>
                        <input type="number" name="sort_order"  min="1" step="1" class="form-control" value="{{ $manufacturer['sort_order'] }}" placeholder="{{ translate('Ex : 1') }}">
                    </div>
                    <div class="btn--container justify-content-end">
                        <button type="reset" class="btn btn--reset">{{translate('reset')}}</button>
                        <button type="submit" class="btn btn--primary">{{translate('update')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

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
            if(lang == '{{$default_lang}}')
            {
                $(".from_part_2").removeClass('d-none');
            }
            else
            {
                $(".from_part_2").addClass('d-none');
            }
        });

          $(function () {
            $("#coba").spartanMultiImagePicker({
                fieldName: 'image',
                maxCount: 1,
                rowHeight: '150px',
                groupClassName: '',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{asset('public/assets/admin/img/upload-en.png')}}',
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
                    toastr.error('{{ translate("Please only input png or jpg type file") }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function (index, file) {
                    toastr.error('{{ translate("File size too big") }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        });
    </script>
@endpush

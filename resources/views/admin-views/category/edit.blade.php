@extends('layouts.admin.app')

@section('title', translate('Update category'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">

        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/category.png')}}" class="w--24" alt="">
                </span>
                <span>
                    @if($category->parent_id == 0)
                        {{ translate('category Update') }}
                    @else
                    {{ translate('Sub Category Update') }}
                    @endif
                </span>
            </h1>
        </div>
        <!-- End Page Header -->


        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.category.update',[$category['id']])}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @php($data = Helpers::get_business_settings('language'))
                    @php($default_lang = Helpers::get_default_language())

                    @if($data && array_key_exists('code', $data[0]))
                            <ul class="nav nav-tabs d-inline-flex mb-5">
                                @foreach($data as $lang)
                                <li class="nav-item">
                                    <a class="nav-link lang_link {{$lang['default'] == true? 'active':''}}" href="#"
                                    id="{{$lang['code']}}-link">{{\App\CentralLogics\Helpers::get_language_name($lang['code']).'('.strtoupper($lang['code']).')'}}</a>
                                </li>
                                @endforeach
                            </ul>

                    @endif

                    @if($data && array_key_exists('code', $data[0]))
                    <div class="row  g-4">
                        @foreach($data as $lang)
                            <?php
                                if (count($category['translations'])) {
                                    $translate = [];
                                    foreach ($category['translations'] as $t) {
                                        if ($t->locale == $lang['code'] && $t->key == "name") {
                                            $translate[$lang['code']]['name'] = $t->value;
                                        }
                                        if ($t->locale == $lang['code'] && $t->key == "description") {
                                            $translate[$lang['code']]['description'] = $t->value;
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
                            <div class="col-sm-6 {{$lang['default'] == false ? 'd-none':''}} lang_form" id="{{$lang['code']}}-form">
                                <div class="col-lg-12">
                                    <label class="form-label" for="exampleFormControlInput1">{{ translate('category') }} {{ translate('name') }} ({{ strtoupper($lang['code']) }})</label>
                                    <input type="text" name="name[]" class="form-control" placeholder="{{ translate('category') }} {{ translate('name') }} ({{ strtoupper($lang['code']) }})" maxlength="255"
                                        {{$lang['status'] == true ? 'required':''}}
                                        value="{{ $lang['code'] == 'en' ? htmlspecialchars_decode($category['name']) : htmlspecialchars_decode($translate[$lang['code']]['name']) }}"
                                        @if($lang['status'] == true) oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>

                                </div>
                                <div class="col-lg-12">
                                    <label class="form-label mt-3" for="exampleFormControlInput1">{{ translate('category') }} {{ translate('description') }} ({{ strtoupper($lang['code']) }})</label>
                                    <textarea name="description[]" class="form-control h--172px">@if($lang['code'] == 'en') {!! strip_tags(htmlspecialchars_decode($category['meta_description'])) !!} @else {!! strip_tags(htmlspecialchars_decode($translate[$lang['code']]['description'])) !!} @endif</textarea>
                                </div>
                              {{--  @if(empty($category->parent_id)) --}}
                                    @if($lang['code'] == "en")
                                        <div class="col-lg-12">
                                            <label class="form-label mt-3" for="{{$lang['code']}}_seo_en">{{translate('SEO')}} ({{strtoupper($lang['code'])}})</label>
                                            <input type="text" name="en_seo" class="form-control" id="{{$lang['code']}}_seo" placeholder="{{translate('SEO')}} ({{translate('EN')}})" value="{{$category['seo_en']??''}}" {{$lang['status'] == true ? 'required':''}}  @if($lang['status'] == true) oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                            
                                        </div>
                                    @else
                                        <div class="col-lg-12">
                                            <label class="form-label mt-3" for="{{$lang['code']}}_seo_ja">{{translate('SEO')}} ({{strtoupper($lang['code'])}})</label>
                                            <input type="text" name="ja_seo" class="form-control" id="{{$lang['code']}}_seo" placeholder="{{translate('SEO')}} ({{translate('JA')}})" value="{{$category['seo_ja'] ?? ''}}" {{$lang['status'] == true ? 'required':''}}  @if($lang['status'] == true) oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                        </div>
                                    @endif
                                    <div class="col-lg-12">
                                        <label class="form-label mt-3" for="exampleFormControlInput1">{{translate('meta tag title')}} ({{ strtoupper($lang['code']) }})</label>
                                        <input type="text" name="meta_title[]" class="form-control" maxlength="255" value="{{ $lang['code'] == 'en' ? htmlspecialchars_decode($category['meta_title']) : htmlspecialchars_decode($translate[$lang['code']]['meta_title']) }}" required>
                                    </div>
                                    <div class="col-lg-12">
                                            <label class="form-label mt-3"
                                            for="exampleFormControlInput1">{{translate('meta tag description')}}
                                        ({{ strtoupper($lang['code']) }})</label>
                                        <textarea name="meta_description[]" class="form-control">{{ $lang['code'] == 'en' ? htmlspecialchars_decode($category['meta_description']) : ($translate[$lang['code']]['meta_description'] ?? '') }}</textarea>

                                    </div>
                                    <div class="col-lg-12">
                                            <label class="form-label mt-3"
                                            for="exampleFormControlInput1">{{translate('meta tag keywords')}}
                                        ({{ strtoupper($lang['code']) }})</label>
                                            <textarea name="meta_keywords[]" class="form-control">{{ $lang['code'] == 'en' ? htmlspecialchars_decode($category['meta_keywords']) : ($translate[$lang['code']]['meta_keywords'] ?? '') }}</textarea>
                                    </div>
                                {{-- @endif --}}
                            </div>
                            <input type="hidden" name="lang[]" value="{{$lang['code']}}">
                        @endforeach
                        @else
                            <div class="col-sm-6 lang_form" id="{{$default_lang}}-form">
                                <div class="col-lg-12">
                                    <label class="form-label"
                                        for="exampleFormControlInput1">{{translate('category')}} {{ translate('name') }}
                                    ({{ strtoupper($default_lang) }})</label>
                                    <input type="text" name="name[]" class="form-control" maxlength="255" value="{!! strip_tags(htmlspecialchars_decode($translate[$lang['code']]['description'])) !!}{{$category['name']}}"
                                        placeholder="{{ translate('New Category') }}" required>
                                </div>
                                <div class="col-lg-12">
                                        <label class="form-label mt-3"
                                        for="exampleFormControlInput1">{{ translate('category') }} {{ translate('description') }}
                                    ({{ strtoupper($default_lang) }})</label>
                                        <textarea name="description[]" class="form-control h--172px">@if($lang['code'] == 'en') {!! strip_tags(htmlspecialchars_decode($category['meta_description'])) !!} @else {!! strip_tags(htmlspecialchars_decode($translate[$lang['code']]['description'])) !!} @endif</textarea>
                                </div>
                                @if($lang['code'] == "en")
                                    <div class="col-lg-12">
                                        <label class="form-label mt-3" for="{{$lang['code']}}_seo_en">{{translate('SEO')}} ({{strtoupper($lang['code'])}})</label>
                                        <input type="text" name="en_seo" class="form-control" id="{{$lang['code']}}_seo" placeholder="{{translate('SEO')}} ({{translate('EN')}})" value="{{$category['seo_en']??''}}" required>
                                    </div>
                                @else
                                    <div class="col-lg-12">
                                        <label class="form-label mt-3" for="{{$lang['code']}}_seo_ja">{{translate('SEO')}} ({{strtoupper($lang['code'])}})</label>
                                        <input type="text" name="ja_seo" class="form-control" id="{{$lang['code']}}_seo" placeholder="{{translate('SEO')}} ({{translate('JA')}})" value="{{$category['seo_ja'] ?? ''}}" required>
                                    </div>
                                @endif
                                <div class="col-lg-12">
                                    <label class="form-label mt-3" for="exampleFormControlInput1">{{translate('meta tag title')}} ({{ strtoupper($default_lang) }})</label>
                                    <input type="text" name="meta_title[]" class="form-control" maxlength="255" value="{{$category['meta_title']}}" required>
                                </div>
                                <div class="col-lg-12">
                                        <label class="form-label mt-3"
                                        for="exampleFormControlInput1">{{translate('meta tag description')}}
                                    ({{ strtoupper($default_lang) }})</label>
                                        <textarea name="meta_description[]" class="form-control">{{ $lang['code'] == 'en' ? htmlspecialchars_decode($category['meta_description']) : $translate[$lang['code']]['meta_description'] }}</textarea>
                                </div>
                                <div class="col-lg-12">
                                        <label class="form-label mt-3"
                                        for="exampleFormControlInput1">{{translate('meta tag keywords')}}
                                    ({{ strtoupper($default_lang) }})</label>
                                        <textarea name="meta_keywords[]" class="form-control">{{ $lang['code'] == 'en' ? htmlspecialchars_decode($category['meta_keywords']) : $translate[$lang['code']]['meta_keywords'] }}</textarea>
                                </div>
                                        
                            </div>
                            <input type="hidden" name="lang[]" value="{{$default_lang}}">
                        @endif
                        <input name="position" value="0" hidden>
                        
                        
                        <div class="col-sm-6">
                        @if($category['position']==1 )
                            <input name="position" value="1" hidden>
                            <div class="form-group">
                                <label class="form-label"
                                    for="exampleFormControlSelect1">{{translate('main')}} {{translate('category')}}
                                    <span class="input-label-secondary">*</span></label>
                                <select id="exampleFormControlSelect1" name="parent_id" class="form-control js-select2-custom" required>
                                    @foreach(\App\Model\Category::with('translations')->where(['position'=>0])->get() as $cat)
                                        <option value="{{$cat['id']}}" @if($cat['id'] == $category['parent_id']) selected @endif>{!! strip_tags(htmlspecialchars_decode($cat['name'])) !!}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        @if($category['position']==2 )
                        <input name="position" value="2" hidden>
                            <div class="form-group">
                                <label class="form-label"
                                    for="exampleFormControlSelect1">{{translate('main')}} {{translate('category')}}
                                    <span class="input-label-secondary">*</span></label>
                                <select id="exampleFormControlSelect1" name="parent_id" class="form-control js-select2-custom" required>
                                    @foreach(\App\Model\Category::with('translations')->where(['position'=>1])->get() as $cat)
                                        <option value="{{$cat['id']}}" @if($cat['id'] == $category['parent_id']) selected @endif>{!! strip_tags(htmlspecialchars_decode($cat['name'])) !!}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                            <div class="col-lg-12">
                           
                            <label>{{\App\CentralLogics\translate('image')}}</label><small style="color: red">* ( {{\App\CentralLogics\translate('ratio')}} 3:1 )</small>
                            <div class="custom-file mb-3">
                                <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label" for="customFileEg1">{{\App\CentralLogics\translate('choose')}} {{\App\CentralLogics\translate('file')}}</label>
                            </div>
                             <center>
                                <img class="img--105" id="viewer" onerror="this.src='{{asset('public/assets/admin/img/900x400/img1.jpg')}}'"
                                    src="{{asset('storage/product/')}}/{{$category['image']}}" alt="image"/>
                            </center>
                        </div>
                        </div>
                        <div class="col-12">
                            <div class="btn--container justify-content-end">
                                <button type="reset" class="btn btn--reset">{{translate('reset')}}</button>
                                <button type="submit" class="btn btn--primary">{{translate('update')}}</button>
                            </div>
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

        $("#customFileEg1").change(function () {
            readURL(this);
        });
    </script>
<script>
    $(document).ready(function() {
        $('select[name="parent_id"]').select2();
    });
</script>
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
    </script>
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

        $("#customFileEg1").change(function () {
            readURL(this);
        });
    </script>
@endpush

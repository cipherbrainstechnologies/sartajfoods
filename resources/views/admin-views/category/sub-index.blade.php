@extends('layouts.admin.app')

@section('title', translate('Add new sub category'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                    {{translate('sub_category_setup')}}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('admin.category.store')}}" method="post"  enctype="multipart/form-data">
                            @csrf
                            @php($data = Helpers::get_business_settings('language'))
                            @php($default_lang = Helpers::get_default_language())

                            @if($data && array_key_exists('code', $data[0]))

                                <ul class="nav nav-tabs mb-4 d-inline-flex">
                                    @foreach($data as $lang)
                                        <li class="nav-item">
                                            <a class="nav-link lang_link {{$lang['default'] == true? 'active':''}}" href="#" id="{{$lang['code']}}-link">{{\App\CentralLogics\Helpers::get_language_name($lang['code']).'('.strtoupper($lang['code']).')'}}</a>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="row">
                                @foreach($data as $lang)
                                    <div class="col-sm-6 {{$lang['default'] == false ? 'd-none':''}} lang_form" id="{{$lang['code']}}-form">
                                        <div class="col-lg-12">
                                            <label class="form-label" for="exampleFormControlInput1">{{translate('sub_category')}} {{translate('name')}} ({{strtoupper($lang['code'])}})</label>
                                            <input type="text" name="name[]" class="form-control" placeholder="{{ translate('New Sub Category') }}" maxlength="255"
                                                {{$lang['status'] == true ? 'required':''}}
                                                @if($lang['status'] == true) oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                        </div>
                                        <div class="col-lg-12">
                                            <label class="form-label mt-3"
                                                for="exampleFormControlInput1">{{translate('sub_category')}} {{ translate('description') }}
                                                ({{ strtoupper($lang['code']) }})</label>
                                            <textarea name="description[]" class="form-control h--172px"></textarea>
                                        </div>
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
                                        <div class="col-lg-12 mt-3">
                                            <label class="form-label" for="exampleFormControlInput1">{{translate('meta tag title')}} ({{ strtoupper($lang['code']) }})</label>
                                            <input type="text" name="meta_title[]" class="form-control" maxlength="255" required>
                                        </div>
                                        <div class="col-lg-12">
                                                <label class="form-label mt-3"
                                                for="exampleFormControlInput1">{{translate('meta tag description')}}
                                            ({{ strtoupper($lang['code']) }})</label>
                                                <textarea name="meta_description[]" class="form-control"></textarea>
                                        </div>
                                        <div class="col-lg-12">
                                                <label class="form-label mt-3"
                                                for="exampleFormControlInput1">{{translate('meta tag keywords')}}
                                            ({{ strtoupper($lang['code']) }})</label>
                                                <textarea name="meta_keywords[]" class="form-control"></textarea>
                                        </div> 
                                         
                                    </div>
                                    <input type="hidden" name="lang[]" value="{{$lang['code']}}">
                                @endforeach
                                @else
                                <div class="col-sm-6 lang_form" id="{{$default_lang}}-form">
                                    <div class="col-lg-12">
                                        <label class="form-label" for="exampleFormControlInput1"> <label class="form-label" for="exampleFormControlInput1">{{translate('sub_category')}} {{translate('name')}}({{strtoupper($default_lang)}})</label>
                                        <input type="text" name="name[]" class="form-control" placeholder="{{ translate('New Sub Category') }}" maxlength="255" required>
                                    </div>
                                    <div class="col-lg-12">
                                        <label class="form-label mt-3" for="exampleFormControlInput1">{{translate('sub_category')}} {{ translate('description') }} ({{strtoupper($default_lang)}})</label>
                                        <textarea name="description[]" class="form-control h--172px"></textarea>
                                    </div>
                                    @if($lang['code'] == "en")
                                        <div class="col-lg-12">
                                            <label class="form-label mt-3" for="{{$lang['code']}}_seo_en">{{translate('SEO')}} ({{strtoupper($lang['code'])}})</label>
                                            <input type="text" name="en_seo" class="form-control" id="{{$lang['code']}}_seo"  value="{{$category['seo_en']??''}}" {{$lang['status'] == true ? 'required':''}}  @if($lang['status'] == true) oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                            
                                        </div>
                                    @else
                                        <div class="col-lg-12">
                                            <label class="form-label mt-3" for="{{$lang['code']}}_seo_ja">{{translate('SEO')}} ({{strtoupper($lang['code'])}})</label>
                                            <input type="text" name="ja_seo" class="form-control" id="{{$lang['code']}}_seo"  value="{{$category['seo_ja'] ?? ''}}" {{$lang['status'] == true ? 'required':''}}  @if($lang['status'] == true) oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                        </div>
                                    @endif
                                    
                                    <div class="col-lg-12 mt-3">
                                        <label class="form-label" for="exampleFormControlInput1">{{translate('meta tag title')}} ({{ strtoupper($default_lang) }})</label>
                                        <input type="text" name="meta_title[]" class="form-control" maxlength="255" required>
                                    </div>
                                    <div class="col-lg-12">
                                            <label class="form-label mt-3"
                                                    for="exampleFormControlInput1">{{translate('meta tag description')}}
                                        ({{ strtoupper($default_lang) }})</label>
                                            <textarea name="meta_description[]" class="form-control"></textarea>
                                    </div>
                                    <div class="col-lg-12">
                                            <label class="form-label mt-3"
                                            for="exampleFormControlInput1">{{translate('meta tag keywords')}}
                                        ({{ strtoupper($default_lang) }})</label>
                                            <textarea name="meta_keywords[]" class="form-control"></textarea>
                                    </div>
                                    
                                </div>
                                <input type="hidden" name="lang[]" value="{{$default_lang}}">
                                @endif
                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label"
                                            for="exampleFormControlSelect1">{{translate('main')}} {{translate('category')}}
                                            <span class="input-label-secondary">*</span></label>
                                        <select id="exampleFormControlSelect1" name="parent_id" class="form-control js-select2-custom" required>
                                            @foreach(\App\Model\Category::where(['position'=>0])->get() as $category)
                                                <option value="{{$category['id']}}">{!! strip_tags(htmlspecialchars_decode($category['name'])) !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="col-lg-12">
                                    <input name="position" value="1" hidden>
                                        <label class="form-label text-capitalize">{{ translate('sub category image') }}</label><small class="text-danger">* ( {{ translate('ratio') }}
                                            3:1 )</small>
                                        <div class="custom-file mb-3">
                                            <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required oninvalid="document.getElementById('en-link').click()">
                                            <label class="custom-file-label" for="customFileEg1">{{ translate('choose') }}
                                                {{ translate('file') }}</label>
                                        </div>
                                            <center>
                                                <img id="viewer" class="img--105" src="{{ asset('public/assets/admin/img/160x160/1.png') }}" alt="image" />
                                            </center>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="btn--container justify-content-end">
                                        <a href="" class="btn btn--reset min-w-120px">{{translate('reset')}}</a>
                                        <button type="submit" class="btn btn--primary">{{translate('submit')}}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-header border-0">
                        <div class="card--header">
                            <h5 class="card-title">{{translate('Sub Category Table')}} <span class="badge badge-soft-secondary">{{ $categories->total() }}</span> </h5>
                            <form action="{{url()->current()}}" method="GET">
                                <div class="input-group">
                                    <input id="datatableSearch_" type="search" name="search"
                                            class="form-control pl-5"
                                           placeholder="{{translate('Search_by_Name')}}" aria-label="Search"
                                            value="{{$search}}" required autocomplete="off">
                                           <i class="tio-search tio-input-search"></i>
                                    <div class="input-group-append">
                                        <button type="submit" class="input-group-text">
                                            {{translate('search')}}
                                        </button>
                                    </div>
                                    <div class="col-sm-6 col-md-12 col-lg-4 __btn-row">
                                        <a href="{{route('admin.category.add-sub-category')}}" id="" class="btn w-100 btn--reset min-h-45px">{{translate('clear')}}</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                            <tr>
                                <th class="text-center">{{translate('#')}}</th>
                                <th>{{translate('sub_category_image')}}</th>
                                <th>{{translate('main')}} {{translate('category')}}</th>
                                <th>{{translate('sub_category')}}</th>
                                <th>{{translate('status')}}</th>
                                <th class="text-center">{{translate('action')}}</th>
                            </tr>

                            </thead>

                            <tbody id="set-rows">
                            @foreach($categories as $key=>$category)
                                <tr>
                                    <td class="text-center">{{$categories->firstItem()+$key}}</td>
                                    <td>
                                        <img src="{{asset('storage/product/')}}/{{$category['image']}}"
                                        onerror="this.src='{{asset('public/assets/admin/img/400x400/img2.jpg')}}'" class="img--50 ml-3" alt="">
                                    </td>
                                    <td>
                                        <span class="d-block font-size-sm text-body">
                                        {!! strip_tags(htmlspecialchars_decode($category->parent['name'])) !!}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="d-block font-size-sm text-body">
                                        {!! strip_tags(htmlspecialchars_decode($category['name'])) !!}
                                        </span>
                                    </td>

                                    <td>

                                        <label class="toggle-switch">
                                            <input type="checkbox"
                                                onclick="status_change_alert('{{ route('admin.category.status', [$category->id, $category->status ? 0 : 1]) }}', '{{ $category->status? translate('you_want_to_disable_this_category'): translate('you_want_to_active_this_category') }}', event)"
                                                class="toggle-switch-input" id="stocksCheckbox{{ $category->id }}"
                                                {{ $category->status ? 'checked' : '' }}>
                                            <span class="toggle-switch-label text">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>

                                    </td>
                                    <td>
                                        <!-- Dropdown -->
                                        <div class="btn--container justify-content-center">
                                            <a class="action-btn"
                                                href="{{route('admin.category.edit',[$category['id']])}}">
                                            <i class="tio-edit"></i></a>
                                            <a class="action-btn btn--danger btn-outline-danger" href="javascript:"
                                                onclick="form_alert('category-{{$category['id']}}','{{ translate("Want to delete this") }}')">
                                                <i class="tio-delete-outlined"></i>
                                            </a>
                                        </div>
                                        <form action="{{route('admin.category.delete',[$category['id']])}}"
                                                method="post" id="category-{{$category['id']}}">
                                            @csrf @method('delete')
                                        </form>
                                        <!-- End Dropdown -->
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                         
                        @if(count($categories) == 0)
                        <div class="text-center p-4">
                            <img class="w-120px mb-3" src="{{asset('/public/assets/admin/svg/illustrations/sorry.svg')}}" alt="Image Description">
                            <p class="mb-0">{{translate('No_data_to_show')}}</p>
                        </div>
                        @endif

                        <div class="page-area">
                            <table>
                                <tfoot>
                                {!! $categories->links() !!}
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Table -->
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

        function status_change_alert(url, message, e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: message,
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#107980',
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    location.href = url;
                }
            })
        }
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
        $('#search-form').on('submit', function () {
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.category.search')}}',
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

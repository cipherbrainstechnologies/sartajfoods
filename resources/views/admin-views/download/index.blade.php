@extends('layouts.admin.app')

@section('title', translate('Add new download'))

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
                    {{ translate('downloads Setup') }}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->
        <div class="card">
            <div class="card-header border-0">
                <div class="card--header">
                    <h5 class="card-title">{{translate('download Table')}} <span class="badge badge-soft-secondary">{{ $downloads->total() }}</span> </h5>
                    <form action="{{url()->current()}}" method="GET">
                        <div class="input-group">
                            <input id="datatableSearch_" type="search" name="search"
                                class="form-control"
                                placeholder="{{translate('Search')}}" aria-label="Search"
                                value="{{$search}}" required autocomplete="off">
                            <div class="input-group-append">
                                <button type="submit" class="input-group-text"><i class="tio-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <button class="btn btn--primary ml-lg-4" data-toggle="modal" data-target="#download-modal"><i class="tio-add"></i> {{translate('add_download')}}</button>
                </div>
            </div>
            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                    <tr>
                        <th>{{translate('#')}}</th>
                        <th>{{translate('download name')}}</th>
                        <th>{{translate('Date Added	')}}</th>
                        <th class="text-center">{{translate('action')}}</th>
                    </tr>

                    </thead>

                    <tbody>
                    @foreach($downloads as $key=>$download)
                        <tr>
                            <td>{{$downloads->firstItem()+$key}}</td>
                            <td>
                                <span class="d-block font-size-sm text-body text-trim-70">
                                    {{$download['name']}}
                                </span>
                            </td>
                             <td>
                                <span class="d-block font-size-sm text-body text-trim-70">
                                    {{ Carbon\Carbon::parse($download['created_at'])->format('d-M-Y') }}
                                </span>
                            </td>
                            <td>
                                <!-- Dropdown -->
                                <div class="btn--container justify-content-center">
                                    <a class="action-btn"
                                        href="{{route('admin.download.edit',[$download['id']])}}">
                                    <i class="tio-edit"></i></a>
                                    <a class="action-btn btn--danger btn-outline-danger" href="javascript:"
                                        onclick="form_alert('download-{{$download['id']}}','{{ translate("Want to delete this") }}')">
                                        <i class="tio-delete-outlined"></i>
                                    </a>
                                </div>
                                <form action="{{route('admin.download.delete',[$download['id']])}}"
                                        method="post" id="download-{{$download['id']}}">
                                    @csrf @method('delete')
                                </form>
                                <!-- End Dropdown -->
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <table>
                    <tfoot>
                    {!! $downloads->links() !!}
                    </tfoot>
                </table>

                @if(count($downloads) == 0)
                <div class="text-center p-4">
                    <img class="w-120px mb-3" src="{{asset('/public/assets/admin/svg/illustrations/sorry.svg')}}" alt="Image Description">
                    <p class="mb-0">{{translate('No_data_to_show')}}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="download-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{route('admin.download.store')}}" method="post" enctype="multipart/form-data">
                    <div class="modal-body pt-3">
                        @csrf
                        @php($data = Helpers::get_business_settings('language'))
                        @php($default_lang = Helpers::get_default_language())

                        @if($data && array_key_exists('code', $data[0]))
                            <ul class="nav nav-tabs mb-4">
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
                                        <div class="lang_form {{$lang['default'] == false ? 'd-none':''}}" id="{{$lang['code']}}-form">
                                            <div class="form-group">
                                                <label class="input-label" for="exampleFormControlInput1">{{translate('download name')}} ({{strtoupper($lang['code'])}})</label>
                                                <input type="text" name="name[]" class="form-control"
                                                    placeholder="{{translate('download name')}}"
                                                    {{$lang['status'] == true ? 'required':''}} maxlength="255"
                                                    @if($lang['status'] == true) oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                            </div>
                                            <input type="hidden" name="lang[]" value="{{$lang['code']}}">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                        <div class="row">
                            <div class="col-12">
                                <div class="lang_form" id="{{$lang['code']}}-form">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('name')}} ({{strtoupper($default_lang)}})</label>
                                        <input type="text" name="name[]" class="form-control" placeholder="{{translate('download name')}}" maxlength="255">
                                    </div>
                                    
                                    <input type="hidden" name="lang[]" value="{{$default_lang}}">
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="card">
                            <div class="card-body">
                                <h5 class="mb-3">{{translate('file')}}</h5>
                                <input type="file" name="file" id="customFileEg1" class="form-control" required">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="input-label" for="exampleFormControlInput1">{{translate('mask')}}</label>
                            <input type="text" name="mask"  min="1" step="1" class="form-control" required>
                        </div>

                        <div class="btn--container justify-content-end">
                            <button type="reset" class="btn btn--reset" data-dismiss="modal">{{translate('cancel')}}</button>
                            <button type="submit" class="btn btn--primary">{{translate('submit')}}</button>
                        </div>
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

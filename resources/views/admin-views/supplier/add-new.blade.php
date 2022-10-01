@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('supplier_add'))

@push('css_or_js')
    <link href="{{ asset('assets/back-end') }}/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset('assets/back-end/css/croppie.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Custom styles for this page -->
    <link href="{{ asset('assets/back-end') }}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{ route('admin.dashboard') }}">{{ \App\CPU\translate('Dashboard') }}</a></li>
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('suppliers') }}</li>
            </ol>
        </nav>

        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ \App\CPU\translate('Add') }} {{ \App\CPU\translate('new') }}
                        {{ \App\CPU\translate('supplier') }}
                    </div>
                    <div class="card-body"
                        style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
                        <form action="{{ route('admin.supplier.add-new') }}" method="post">
                            @csrf
                            @php
                                $language = \App\Model\BusinessSetting::where('type', 'pnc_language')->first();
                                $language = $language->value ?? null;
                                $default_lang = 'en';
                                $default_lang = json_decode($language)[0];
                            @endphp
                            <ul class="nav nav-tabs mb-4">
                                @foreach (json_decode($language) as $lang)
                                    <li class="nav-item">
                                        <a class="nav-link lang_link {{ $lang == $default_lang ? 'active' : '' }}"
                                            href="#"
                                            id="{{ $lang }}-link">{{ \App\CPU\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="row">
                                <div class="col-md-8">
                                    @foreach (json_decode($language) as $lang)
                                        <div class="form-group {{ $lang != $default_lang ? 'd-none' : '' }} lang_form"
                                            id="{{ $lang }}-form">
                                            <label for="name">{{ \App\CPU\translate('name') }}
                                                ({{ strtoupper($lang) }})
                                            </label>
                                            <input type="text" name="name[]" class="form-control" id="name"
                                                value="{{ old('name') }}"
                                                placeholder="{{ \App\CPU\translate('Ex') }} : {{ \App\CPU\translate('LUX') }}"
                                                {{ $lang == $default_lang ? 'required' : '' }}>
                                        </div>
                                        <input type="hidden" name="lang[]" value="{{ $lang }}">
                                    @endforeach


                                    <div class="form-group-item ">
                                        <label class="control-label">{{ \App\CPU\translate('phone') }}</label>
                                        <div class="g-items-header">
                                            <div class="row">
                                                <div class="col-md-11">{{ \App\CPU\translate('phone') }}</div>
                                                <div class="col-md-1"></div>
                                            </div>
                                        </div>
                                        <div class="g-items">
                                            @if (!empty($phones))
                                                @foreach ($phones as $key => $phone)
                                                    <div class="item" data-number="{{ $key }}">
                                                        <div class="row">
                                                            <div class="col-md-11">
                                                                <input type="tel" name="phones[{{ $key }}]"
                                                                    class="form-control" value="{{ $phone ?? '' }}"
                                                                    placeholder="{{ __('ticket_vip_1') }}">
                                                            </div>
                                                            <div class="col-md-1">

                                                                <span class="btn btn-danger btn-sm btn-remove-item"><i
                                                                        class="tio-remove-from-trash"></i></span>

                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <span class="btn btn-info btn-sm btn-add-item">
                                                {{ \App\CPU\translate('add') }}</span>
                                        </div>
                                        <div class="g-more hide">
                                            <div class="item" data-number="__number__">
                                                <div class="row">
                                                    <div class="col-md-11">
                                                        <input type="tel" __name__="phones[__number__]"
                                                            class="form-control"
                                                            placeholder="{{ \App\CPU\translate('phone') }}">
                                                    </div>
                                                    <div class="col-md-1">
                                                        <span class="btn btn-danger btn-sm btn-remove-item"><i
                                                                class="tio-remove-from-trash"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>





                                    <div class="form-group">
                                        <label for="address">{{ \App\CPU\translate('address') }}</label>
                                        <input type="text" name="address" class="form-control" id="address"
                                            value="{{ old('address') }}"
                                            placeholder="{{ \App\CPU\translate('address') }}"
                                            {{ $lang == $default_lang ? 'required' : '' }}>
                                    </div>



                                </div>
                            </div>


                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">{{ \App\CPU\translate('save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    

    <script>
        $(".lang_link").click(function(e) {
            e.preventDefault();
            $(".lang_link").removeClass('active');
            $(".lang_form").addClass('d-none');
            $(this).addClass('active');

            let form_id = this.id;
            let lang = form_id.split("-")[0];
            console.log(lang);
            $("#" + lang + "-form").removeClass('d-none');
            if (lang == '{{ $default_lang }}') {
                $(".from_part_2").removeClass('d-none');
            } else {
                $(".from_part_2").addClass('d-none');
            }
        });

        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
    </script>
    <script src="{{ asset('assets/back-end') }}/js/select2.min.js"></script>
    <script>
        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            width: 'resolve'
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
        $(document).on('click', '.delete', function() {
            var id = $(this).attr("id");
            Swal.fire({
                title: '{{ \App\CPU\translate('are_you_sure?') }}',
                text: "{{ \App\CPU\translate('You_will_not_be_able_to_revert_this!') }}",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ \App\CPU\translate('Yes') }} {{ \App\CPU\translate('delete_it') }}!'
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{ route('admin.supplier.delete') }}",
                        method: 'POST',
                        data: {
                            id: id
                        },
                        success: function() {
                            toastr.success(
                                '{{ \App\CPU\translate('supplier_deleted_successfully') }}'
                            );
                            location.reload();
                        }
                    });
                }
            })
        });
    </script>
@endpush

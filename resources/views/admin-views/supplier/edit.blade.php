@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('supplier_edit'))

@push('css_or_js')
    <link href="{{ asset('assets/back-end') }}/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset('assets/back-end/css/croppie.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{ route('admin.dashboard') }}">{{ \App\CPU\translate('Dashboard') }}</a></li>
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('supplier') }}
                    {{ \App\CPU\translate('Update') }}</li>
            </ol>
        </nav>

        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h1 class="h3 mb-0 text-black-50">{{ \App\CPU\translate('supplier') }}
                            {{ \App\CPU\translate('Update') }}</h1>
                    </div>
                    <div class="card-body"
                        style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
                        <form action="{{ route('admin.supplier.update', [$b['id']]) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            @php($language = \App\Model\BusinessSetting::where('type', 'pnc_language')->first())
                            @php($language = $language->value ?? null)
                            @php($default_lang = 'en')

                            @php($default_lang = json_decode($language)[0])
                            <ul class="nav nav-tabs mb-4">
                                @foreach (json_decode($language) as $lang)
                                    <li class="nav-item">
                                        <a class="nav-link lang_link {{ $lang == $default_lang ? 'active' : '' }}" href="#"
                                            id="{{ $lang }}-link">{{ \App\CPU\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="row">
                                <div class="col-md-8">
                                    @foreach (json_decode($language) as $lang)
                                        <?php
                                        if (count($b['translations'])) {
                                            $translate = [];
                                            foreach ($b['translations'] as $t) {
                                                if ($t->locale == $lang && $t->key == 'name') {
                                                    $translate[$lang]['name'] = $t->value;
                                                }
                                            }
                                        }
                                        ?>
                                        <div class="form-group {{ $lang != $default_lang ? 'd-none' : '' }} lang_form"
                                            id="{{ $lang }}-form">
                                            <label for="name">{{ \App\CPU\translate('name') }}
                                                ({{ strtoupper($lang) }})</label>
                                            <input type="text" name="name[]"
                                                value="{{ $lang == $default_lang ? $b['name'] : $translate[$lang]['name'] ?? '' }}"
                                                class="form-control" id="name"
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
                                            @if (!empty($b['phones']))
                                                @foreach ($b['phones'] as $key => $phone)
                                                    <div class="item" data-number="{{ $key }}">
                                                        <div class="row">
                                                            <div class="col-md-11">
                                                                <input type="tel" name="phones[{{ $key }}]"
                                                                    class="form-control" value="{{ $phone ?? '' }}"
                                                                    placeholder="{{ \App\CPU\translate('phone') }}">
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
                                            value="{{ $b['address'] ?? '' }}"
                                            placeholder="{{ \App\CPU\translate('address') }}"
                                            {{ $lang == $default_lang ? 'required' : '' }}>
                                    </div>

                                </div>
                            </div>


                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">{{ \App\CPU\translate('update') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!--modal-->
        @include('shared-partials.image-process._image-crop-modal', [
            'modal_id' => 'brand-image-modal',
            'width' => 1000,
            'margin_left' => '-53%',
        ])
        <!--modal-->
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
        $(".form-group-item").each(function() {
            let container = $(this);
            $(this).on('click', '.btn-remove-item', function() {
                $(this).closest(".item").remove();
            });
            $(this).on('press', 'input,select', function() {
                let value = $(this).val();
                $(this).attr("value", value);
            });
        });
        $(".form-group-item .btn-add-item").on('click', function() {
            var p = $(this).closest(".form-group-item").find(".g-items");
            var number = $(this).closest(".form-group-item").find(".g-items .item:last-child").data("number");
            if (number === undefined) number = 0;
            else number++;
            var extra_html = $(this).closest(".form-group-item").find(".g-more").html();
            extra_html = extra_html.replace(/__name__=/gi, "name=");
            extra_html = extra_html.replace(/__number__/gi, number);

            // dynamic ajax mod
            if (extra_html.indexOf('ent_id_x') != -1) {
                extra_html = extra_html.replaceAll('ent_id_x', `ent_id_${number}`);
                // console.log(extra_html);
            }

            p.append(extra_html);


        });
    </script>
@endpush

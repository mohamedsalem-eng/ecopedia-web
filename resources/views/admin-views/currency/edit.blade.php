@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Update Currency'))

@push('css_or_js')
@endpush

@section('content')
    @php($currency_model = \App\CPU\Helpers::get_business_settings('currency_model'))
    <div class="content container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{ route('admin.dashboard') }}">{{ \App\CPU\translate('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('Currency') }}</li>
            </ol>
        </nav>
        <!-- Page Heading -->



        <div class="row">
            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header">
                        <h5 class="text-center">
                            <i class="tio-money"></i>
                            {{ \App\CPU\translate('Update Currency') }}
                        </h5>
                    </div>
                    <div class="card-body rest-part">
                        <form action="{{ route('admin.currency.update', [$data['id']]) }}" method="post"
                            id="currency_form"
                            style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>{{ \App\CPU\translate('Currency Name') }} :</label>
                                        <input type="text" name="name"
                                            placeholder="{{ \App\CPU\translate('Currency Name') }}" class="form-control"
                                            id="name" value="{{ $data->name }}">
                                    </div>
                                    {{-- <div class="col-md-6">
                                        <label>{{ \App\CPU\translate('Currency Symbol') }} :</label>
                                        <input type="text" name="symbol"
                                            placeholder="{{ \App\CPU\translate('Currency Symbol') }}"
                                            class="form-control" id="symbol" value="{{ $data->symbol }}">
                                    </div> --}}
                                </div>

                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>{{ \App\CPU\translate('Currency Code') }} :</label>
                                        <input type="text" name="code"
                                            placeholder="{{ \App\CPU\translate('Currency Code') }}"
                                            class="form-control" id="code" value="{{ $data->code }}">
                                    </div>
                                    @if ($currency_model == 'multi_currency')
                                        <div class="col-md-6">
                                            <label>{{ \App\CPU\translate('Exchange Rate') }} :</label>
                                            <input type="number" min="0" max="1000000" name="exchange_rate"
                                                step="0.00000001" placeholder="{{ \App\CPU\translate('Exchange Rate') }}"
                                                class="form-control" id="exchange_rate"
                                                value="{{ $data->exchange_rate }}">
                                        </div>
                                    @endif
                                </div>
                            </div>


                        </form>
                    </div>
                    {{-- </div>


                <div class="card"> --}}
                    <div class="card-header">
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
                    </div>

                    <div class="card-body">
                        @foreach (json_decode($language) as $lang)
                            <?php
                            if (count($data['translations'])) {
                                $translate = [];
                                foreach ($data['translations'] as $t) {
                                    if ($t->locale == $lang && $t->key == 'symbol') {
                                        $translate[$lang]['symbol'] = $t->value;
                                    }
                                 
                                }
                            }
                            ?>
                            <div class="{{ $lang != 'en' ? 'd-none' : '' }} lang_form" id="{{ $lang }}-form">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="{{ $lang }}_symbol">{{ \App\CPU\translate('Currency Symbol') }}

                                        ({{ strtoupper($lang) }})
                                    </label>

                                    <input type="text" name="symbol[]" form="currency_form"
                                        placeholder="{{ \App\CPU\translate('Currency Symbol') }}" class="form-control"
                                        id="symbol" value="{{ $translate[$lang]['symbol'] ?? $data['symbol'] }}">
                                </div>
                                <input type="hidden" name="lang[]" form="currency_form"  value="{{ $lang }}">

                            </div>
                        @endforeach
                    </div>

                    <div class="card-footer">
                        <div class="form-group text-center">
                            <button type="submit" id="add" class="btn btn-primary" form="currency_form"
                                style="color: white">{{ \App\CPU\translate('Update') }}
                            </button>
                        </div>
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
                $(".rest-part").removeClass('d-none');
            } else {
                $(".rest-part").addClass('d-none');
            }
        })
    </script>
@endpush

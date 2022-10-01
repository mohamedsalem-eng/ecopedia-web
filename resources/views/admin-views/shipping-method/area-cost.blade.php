@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('area_cost'))

@push('css_or_js')
    <!-- Custom styles for this page -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Custom styles for this page -->
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Heading -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{ route('admin.dashboard') }}">{{ \App\CPU\translate('Dashboard') }}</a></li>
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('Shipping Method') }}</li>
            </ol>
        </nav>
        <form action="{{ route('admin.business-settings.shipping-method.area-cost') }}" method="post">
            @csrf

            <div class="row" style="margin-top: 20px">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ \App\CPU\translate('shipping_method') }} {{ \App\CPU\translate('table') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" cellspacing="0"
                                    style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
                                    <thead>
                                        <tr>
                                            <th scope="col">{{ \App\CPU\translate('sl#') }}</th>
                                            <th scope="col">{{ \App\CPU\translate('Area') }}</th>
                                            <th scope="col">{{ \App\CPU\translate('cost') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($area_array as $k => $area)
                                            <tr>
                                                <th scope="row">{{ $k + 1 }}</th>
                                                <td>
                                                    {{ $area['name'] }}
                                                </td>
                                                <td class="col-md-5">
                                                    <input type="number" name="area_cost[{{$k}}][cost]" class="form-control"
                                                        value="{{ \App\CPU\BackEndHelper::usd_to_currency($area['cost'] ?? 0) }}">
                                                        <input type="hidden" name="area_cost[{{$k}}][id]" value="{{$area['id']}}">
                                                        <input type="hidden" name="area_cost[{{$k}}][name]" value="{{$area['name']}}">

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer"
                            style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
                            <button class="btn btn-primary" type="submit">{{ \App\CPU\translate('save') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('script')
@endpush

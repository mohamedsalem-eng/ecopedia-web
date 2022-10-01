@extends('layouts.back-end.app')

@section('content')
    <div class="content container-fluid ">
        <div class="col-md-4" style="margin-bottom: 20px;">
            <h3 class="text-capitalize">{{ \App\CPU\translate('trace_table') }}
                <span class="badge badge-soft-dark mx-2">{{ $traces->total() }}</span>

            </h3>
        </div>
        {{-- <div class="row" style="margin-top: 20px"> --}}
        {{-- <div class="col-md-12"> --}}
        <div class="card">
            <div class="card-header">
                <div class="flex-between justify-content-between align-items-center flex-grow-1">
                    <div class="col-md-5 ">
                        <form action="{{ url()->current() }}" method="GET">
                            <!-- Search -->
                            <div class="input-group input-group-merge input-group-flush">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="tio-search"></i>
                                    </div>
                                </div>
                                <input id="datatableSearch_" type="search" name="search" class="form-control"
                                    placeholder="Search by orders id or trace id" aria-label="Search orders"
                                    value="{{ $search }}" required>
                                <button type="submit" class="btn btn-primary">{{ \App\CPU\translate('search') }}</button>
                            </div>
                            <!-- End Search -->
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body" style="padding: 0">
                <div class="table-responsive">
                    <table id="datatable"
                        style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};"
                        class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                        <thead class="thead-light">
                            <tr>
                                <th>{{ \App\CPU\translate('SL#') }}</th>
                                <th>{{ \App\CPU\translate('order#') }}</th>
                                {{-- <th>{{\App\CPU\translate('seller')}}</th> --}}
                                <th>{{ \App\CPU\translate('type') }}</th>
                                <th>{{ \App\CPU\translate('from') }}</th>
                                <th>{{ \App\CPU\translate('to') }}</th>
                                <th>{{ \App\CPU\translate('changed_by') }}</th>
                                <th>{{ \App\CPU\translate('date') }}</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($traces as $key => $trace)
                                <tr>
                                    <td>{{ $trace->id }}</td>
                                    <td><a href="{{ $trace->order->link }}">{{ $trace->order->id }}</a></td>
                                    {{-- <td>{{$trace['seller_id']}}</td> --}}
                                    <td>
                                        {{ implode(' ', explode('_', $trace->type)) }}

                                    </td>
                                    @if ($trace->type == 'add_delivery_man')
                                        <td>{{ $trace->from . '# ' }}{{ $deliver_men->firstWhere('id', $trace->from)->display_name ?? '' }}
                                        </td>
                                        <td>{{ $trace->to . '# ' }}{{ $deliver_men->firstWhere('id', $trace->to)->display_name ?? '' }}
                                        </td>
                                    @else
                                        <td>{{ $trace->from }}</td>
                                        <td>{{ $trace->to }}</td>
                                    @endif
                                    <td>{{ $trace->admin->name }}</td>
                                    <td>{{ $trace->updated_at }}</td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                    @if (count($traces) == 0)
                        <div class="text-center p-4">
                            <img class="mb-3" src="{{ asset('assets/back-end') }}/svg/illustrations/sorry.svg"
                                alt="Image Description" style="width: 7rem;">
                            <p class="mb-0">{{ \App\CPU\translate('No_data_to_show') }}</p>
                        </div>
                    @endif
                </div>
            </div>
            <div class="card-footer">
                {{ $traces->links() }}
            </div>

        </div>
        {{-- </div> --}}

        {{-- </div> --}}
    </div>
@endsection

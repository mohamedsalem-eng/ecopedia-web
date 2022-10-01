@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Enquiry'))

@push('css_or_js')
@endpush

@section('content')

    <div class="content container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{ route('admin.dashboard') }}">{{ \App\CPU\translate('Dashboard') }}</a></li>
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('Enquiry') }}</li>
            </ol>
        </nav>

        <div class="container space-2 d-flex justify-content-around">

            @foreach ($enquiries as $enquiry)
                <p class="font-size-lg font-weight-bold mb-1">
                    <a target="_blank" class="text-font-bold"
                        href="{{ route('product', ['slug' => $enquiry->product->slug]) }}">{{ $enquiry->product->name }}</a>
                </p>
                <p class="font-size-lg font-weight-bold mb-1">
                    <a target="_blank" href="{{ route('admin.service-enquiry.download-file', ['id' => $enquiry->id]) }}"
                        download>{{ \App\CPU\translate('download_file') }}</a>
                </p>
            @endforeach
        </div>

        @foreach ($enquiries as $enquiry)
            <?php
            $userDetails = $enquiry->user;
            //$conversations = \App\Model\SupportTicketConv::where('support_ticket_id', $enquiry['id'])->get();
            $admin = \App\Model\Admin::get();
            ?>
            <div class="media pb-4">
                <img class="rounded-circle" style="width: 40px; height:40px;"
                    src="{{ asset('storage/app/public/profile') }}/{{ isset($userDetails) ? $userDetails['image'] : '' }}"
                    onerror="this.src='{{ asset('assets/back-end/img/160x160/img1.jpg') }}"
                    alt="{{ isset($userDetails) ? $userDetails['name'] : 'not found' }}" />
                <div class="media-body {{ Session::get('direction') === 'rtl' ? 'pr-3' : 'pl-3' }}">
                    <h6 class="font-size-md mb-2">{{ isset($userDetails) ? $userDetails['name'] : 'not found' }}</h6>
                    <p class="font-size-md mb-1">{{ $enquiry['description'] }}</p>
                    <span class="font-size-ms text-muted">
                        <i
                            class="czi-time align-middle {{ Session::get('direction') === 'rtl' ? 'ml-2' : 'mr-2' }}">{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $enquiry['created_at'])->format('Y-m-d h:i A') }}</i></span>
                </div>
            </div>
            @foreach ($enquiry->conversations as $conversation)
                @if ($conversation['admin_message'] == null)
                    <div class="media pb-4">
                        <img class="rounded-circle" style="width: 40px; height:40px;"
                            src="{{ asset('storage/app/public/profile') }}/{{ isset($userDetails) ? $userDetails['image'] : $userDetails->display_name }}"
                            onerror="this.src='{{ asset('assets/back-end/img/160x160/img1.jpg') }}"
                            {{-- alt="{{ isset($userDetails) ? $userDetails->display_name : 'not found' }}" --}} />
                        <div class="media-body {{ Session::get('direction') === 'rtl' ? 'pr-3' : 'pl-3' }}">
                            <h6 class="font-size-md mb-2">
                                {{ isset($userDetails) ? $userDetails->display_name : 'not found' }}
                            </h6>
                            <p class="font-size-md mb-1">{{ $conversation['customer_message'] }}</p>
                            <span class="font-size-ms text-muted">
                                <i
                                    class="czi-time align-middle {{ Session::get('direction') === 'rtl' ? 'ml-2' : 'mr-2' }}"></i>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $conversation['created_at'])->format('Y-m-d h:i A') }}</span>
                        </div>
                    </div>
                @endif
                @if ($conversation['customer_message'] == null)
                    <div class="media pb-4 " style="text-align: right">
                        <div class="media-body {{ Session::get('direction') === 'rtl' ? 'pr-3' : 'pl-3' }} ">
                            <h6 class="font-size-md mb-2"></h6>
                            <p class="font-size-md mb-1">{{ $conversation['admin_message'] }}</p>
                            <span class="font-size-ms text-muted">
                                {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $conversation['updated_at'])->format('Y-m-d h:i A') }}</span>
                        </div>
                    </div>
                @endif
            @endforeach
        @endforeach
        <!-- Leave message-->
        <h3 class="h5 mt-2 pt-4 pb-2">{{ \App\CPU\translate('Leave a Message') }}</h3>
        @foreach ($enquiries as $reply)
            <form class="needs-validation" href="{{ route('admin.service-enquiry.replay', $reply['id']) }}"
                method="post">
                @csrf
                <input type="hidden" name="id" value="{{ $reply['id'] }}">
                <input type="hidden" name="adminId" value="1">
                <div class="form-group">
                    <textarea class="form-control" name="replay" rows="8" placeholder="Write your message here..." required></textarea>
                    <div class="invalid-tooltip">{{ \App\CPU\translate('Please write the message') }}!</div>
                </div>
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <div class="custom-control custom-checkbox d-block">
                    </div>
                    <button class="btn btn-primary my-2" type="submit">{{ \App\CPU\translate('Submit Reply') }}</button>
                </div>
            </form>
        @endforeach

    </div>
@endsection

@push('script')
    <!-- Page level plugins -->
    <script src="{{ asset('assets/back-end') }}/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('assets/back-end') }}/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('assets/back-end') }}/js/demo/datatables-demo.js"></script>
    <script src="{{ asset('assets/back-end/js/croppie.js') }}"></script>
@endpush

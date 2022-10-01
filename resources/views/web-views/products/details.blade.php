@extends('layouts.front-end.app')

@section('title', $product['name'])

@push('css_or_js')
    <meta name="description" content="{{ $product->slug }}">
    <meta name="keywords"
        content="@foreach (explode(' ', $product['name']) as $keyword) {{ $keyword . ' , ' }} @endforeach">
    @if ($product->added_by == 'seller')
        <meta name="author"
            content="{{ $product->seller->shop ? $product->seller->shop->name : $product->seller->f_name }}">
    @elseif($product->added_by == 'admin')
        <meta name="author" content="{{ $web_config['name']->value }}">
    @endif
    <!-- Viewport-->

    @if ($product['meta_image'] != null)
        <meta property="og:image" content="{{ asset('storage/app/public/product/meta') }}/{{ $product->meta_image }}" />
        <meta property="twitter:card"
            content="{{ asset('storage/app/public/product/meta') }}/{{ $product->meta_image }}" />
    @else
        <meta property="og:image"
            content="{{ asset('storage/app/public/product/thumbnail') }}/{{ $product->thumbnail }}" />
        <meta property="twitter:card"
            content="{{ asset('storage/app/public/product/thumbnail/') }}/{{ $product->thumbnail }}" />
    @endif

    @if ($product['meta_title'] != null)
        <meta property="og:title" content="{{ $product->meta_title }}" />
        <meta property="twitter:title" content="{{ $product->meta_title }}" />
    @else
        <meta property="og:title" content="{{ $product->name }}" />
        <meta property="twitter:title" content="{{ $product->name }}" />
    @endif
    <meta property="og:url" content="{{ route('product', [$product->slug]) }}">

    @if ($product['meta_description'] != null)
        <meta property="twitter:description" content="{!! $product['meta_description'] !!}">
        <meta property="og:description" content="{!! $product['meta_description'] !!}">
    @else
        <meta property="og:description"
            content="@foreach (explode(' ', $product['name']) as $keyword) {{ $keyword . ' , ' }} @endforeach">
        <meta property="twitter:description"
            content="@foreach (explode(' ', $product['name']) as $keyword) {{ $keyword . ' , ' }} @endforeach">
    @endif
    <meta property="twitter:url" content="{{ route('product', [$product->slug]) }}">

    <link rel="stylesheet" href="{{ asset('assets/front-end/css/product-details.css') }}" />
    <style>
        .msg-option {
            display: none;
        }

        .chatInputBox {
            width: 100%;
        }

        .go-to-chatbox {
            width: 100%;
            text-align: center;
            padding: 5px 0px;
            display: none;
        }

        .feature_header {
            display: flex;
            justify-content: center;
        }

        .btn-number:hover {
            color: {{ $web_config['secondary_color'] }};

        }

        .for-total-price {
            margin- {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }}: -30%;
        }

        .feature_header span {
            padding- {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }}: 15px;
            font-weight: 700;
            font-size: 25px;
            background-color: #ffffff;
            text-transform: uppercase;
        }

        @media (max-width: 768px) {
            .feature_header span {
                margin-bottom: -40px;
            }

            .for-total-price {
                padding- {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }}: 30%;
            }

            .product-quantity {
                padding- {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }}: 4%;
            }

            .for-margin-bnt-mobile {
                margin- {{ Session::get('direction') === 'rtl' ? 'left' : 'right' }}: 7px;
            }

            .font-for-tab {
                font-size: 11px !important;
            }

            .pro {
                font-size: 13px;
            }
        }

        @media (max-width: 375px) {
            .for-margin-bnt-mobile {
                margin- {{ Session::get('direction') === 'rtl' ? 'left' : 'right' }}: 3px;
            }

            .for-discount {
                margin- {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }}: 10% !important;
            }

            .for-dicount-div {
                margin-top: -5%;
                margin- {{ Session::get('direction') === 'rtl' ? 'left' : 'right' }}: -7%;
            }

            .product-quantity {
                margin- {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }}: 4%;
            }

        }

        @media (max-width: 500px) {
            .for-dicount-div {
                margin-top: -4%;
                margin- {{ Session::get('direction') === 'rtl' ? 'left' : 'right' }}: -5%;
            }

            .for-total-price {
                margin- {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }}: -20%;
            }

            .view-btn-div {

                margin-top: -9%;
                float: {{ Session::get('direction') === 'rtl' ? 'left' : 'right' }};
            }

            .for-discount {
                margin- {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }}: 7%;
            }

            .viw-btn-a {
                font-size: 10px;
                font-weight: 600;
            }

            .feature_header span {
                margin-bottom: -7px;
            }

            .for-mobile-capacity {
                margin- {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }}: 7%;
            }
        }

    </style>
    <style>
        th,
        td {
            border-bottom: 1px solid #ddd;
            padding: 5px;
        }

        thead {
            background: {{ $web_config['primary_color'] }} !important;
            color: white;
        }

    </style>
@endpush

@section('content')
    @if ($product->product_type == 'service')
        @include('web-views.products.service-content')
        @if (auth('customer')->id())
            @include('web-views.products.enquiryModal')
        @endif
    @else
        @include('web-views.products.product-content')
    @endif

@endsection

@push('script')
    <script type="text/javascript">
        cartQuantityInitialize();
        getVariantPrice();
        $('#add-to-cart-form input').on('change', function() {
            getVariantPrice();
        });

        function showInstaImage(link) {
            $("#attachment-view").attr("src", link);
            $('#show-modal-view').modal('toggle')
        }
    </script>

    {{-- Messaging with shop seller --}}
    <script>
        $('#contact-seller').on('click', function(e) {
            // $('#seller_details').css('height', '200px');
            $('#seller_details').animate({
                'height': '276px'
            });
            $('#msg-option').css('display', 'block');
        });
        $('#sendBtn').on('click', function(e) {
            e.preventDefault();
            let msgValue = $('#msg-option').find('textarea').val();
            let data = {
                message: msgValue,
                shop_id: $('#msg-option').find('textarea').attr('shop-id'),
                seller_id: $('.msg-option').find('.seller_id').attr('seller-id'),
            }
            if (msgValue != '') {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "post",
                    url: '{{ route('messages_store') }}',
                    data: data,
                    success: function(respons) {
                        console.log('send successfully');
                    }
                });
                $('#chatInputBox').val('');
                $('#msg-option').css('display', 'none');
                $('#contact-seller').find('.contact').attr('disabled', '');
                $('#seller_details').animate({
                    'height': '125px'
                });
                $('#go_to_chatbox').css('display', 'block');
            } else {
                console.log('say something');
            }
        });
        $('#cancelBtn').on('click', function(e) {
            e.preventDefault();
            $('#seller_details').animate({
                'height': '114px'
            });
            $('#msg-option').css('display', 'none');
        });
    </script>

    <script type="text/javascript"
        src="https://platform-api.sharethis.com/js/sharethis.js#property=5f55f75bde227f0012147049&product=sticky-share-buttons"
        async="async"></script>
@endpush

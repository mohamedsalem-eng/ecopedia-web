    <?php
    $overallRating = \App\CPU\ProductManager::get_overall_rating($product->reviews);
    $rating = \App\CPU\ProductManager::get_rating($product->reviews);
    ?>
    <!-- Page Content-->
    <div class="container mt-4 rtl" style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
        <!-- General info tab-->
        <div class="row" style="direction: ltr">
            <!-- Product gallery-->
            <div class="col-lg-6 col-md-6">
                <div class="cz-product-gallery">
                    <div class="cz-preview">
                        @if ($product->images != null)
                            @foreach (json_decode($product->images) as $key => $photo)
                                <div class="cz-preview-item d-flex align-items-center justify-content-center {{ $key == 0 ? 'active' : '' }}"
                                    id="image{{ $key }}">
                                    <img class="cz-image-zoom img-responsive" style="max-height: 400px"
                                        onerror="this.src='{{ asset('assets/front-end/img/image-place-holder.png') }}'"
                                        src="{{ asset("storage/app/public/product/$photo") }}"
                                        data-zoom="{{ asset("storage/app/public/product/$photo") }}"
                                        alt="Product image" width="">
                                    <div class="cz-image-zoom-pane"></div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="cz">
                        <div class="container">
                            <div class="row">
                                <div class="table-responsive" data-simplebar style="max-height: 515px; padding: 1px;">
                                    <div class="d-flex">
                                        @if ($product->images != null)
                                            @foreach (json_decode($product->images) as $key => $photo)
                                                <div class="cz-thumblist">
                                                    <a class="cz-thumblist-item  {{ $key == 0 ? 'active' : '' }} d-flex align-items-center justify-content-center "
                                                        href="#image{{ $key }}">
                                                        <img onerror="this.src='{{ asset('assets/front-end/img/image-place-holder.png') }}'"
                                                            src="{{ asset("storage/app/public/product/$photo") }}"
                                                            alt="Product thumb">
                                                    </a>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Product details-->
            <div class="col-lg-6 col-md-6 mt-md-0 mt-sm-3" style="direction: {{ Session::get('direction') }}">
                <div class="details">
                    <h1 class="h3 mb-2">{{ $product->name }}</h1>
                    <div class="d-flex align-items-center mb-2 pro">
                        <span
                            class="d-inline-block font-size-sm text-body align-middle mt-1 {{ Session::get('direction') === 'rtl' ? 'ml-md-2 ml-sm-0 pl-2' : 'mr-md-2 mr-sm-0 pr-2' }}">{{ $overallRating[0] }}</span>
                        <div class="star-rating">
                            @for ($inc = 0; $inc < 5; $inc++)
                                @if ($inc < $overallRating[0])
                                    <i class="sr-star czi-star-filled active"></i>
                                @else
                                    <i class="sr-star czi-star"></i>
                                @endif
                            @endfor
                        </div>
                        <span
                            class="font-for-tab d-inline-block font-size-sm text-body align-middle mt-1 {{ Session::get('direction') === 'rtl' ? 'mr-1 ml-md-2 ml-1 pr-md-2 pr-sm-1 pl-md-2 pl-sm-1' : 'ml-1 mr-md-2 mr-1 pl-md-2 pl-sm-1 pr-md-2 pr-sm-1' }}">{{ $overallRating[1] }}
                            {{ \App\CPU\translate('Reviews') }}</span>
                        
                        

                    </div>
                   
                   
                    <hr style="padding-bottom: 10px">
                    <div style="text-align:{{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};"
                        class="sharethis-inline-share-buttons"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- seller section --}}
    {{-- @if ($product->added_by == 'seller')
        @if (isset($product->seller->shop))
            <div class="container mt-4 rtl"
                style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
                <div class="row seller_details d-flex align-items-center" id="sellerOption">
                    <div class="col-md-6">
                        <div class="seller_shop">
                            <div class="shop_image d-flex justify-content-center align-items-center">
                                <a href="#" class="d-flex justify-content-center">
                                    <img style="height: 65px; width: 65px; border-radius: 50%"
                                        src="{{ asset('storage/app/public/shop') }}/{{ $product->seller->shop->image }}"
                                        onerror="this.src='{{ asset('assets/front-end/img/image-place-holder.png') }}'"
                                        alt="">
                                </a>
                            </div>
                            <div
                                class="shop-name-{{ Session::get('direction') === 'rtl' ? 'right' : 'left' }} d-flex justify-content-center align-items-center">
                                <div>
                                    <a href="#" class="d-flex align-items-center">
                                        <div class="title">{{ $product->seller->shop->name }}</div>
                                    </a>
                                    <div class="review d-flex align-items-center">
                                        <div class="">
                                            <span
                                                class="d-inline-block font-size-sm text-body align-middle mt-1 {{ Session::get('direction') === 'rtl' ? 'ml-2' : 'mr-2' }}">{{ \App\CPU\translate('Seller') }}
                                                {{ \App\CPU\translate('Info') }} </span>
                                            <span
                                                class="d-inline-block font-size-sm text-body align-middle mt-1 {{ Session::get('direction') === 'rtl' ? 'mr-2' : 'ml-2' }}"></span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 p-md-0 pt-sm-3">
                        <div class="seller_contact">
                            <div
                                class="d-flex align-items-center {{ Session::get('direction') === 'rtl' ? 'pl-4' : 'pr-4' }}">
                                <a href="{{ route('shopView', [$product->seller->id]) }}">
                                    <button class="btn btn-secondary">
                                        <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                                        {{ \App\CPU\translate('Visit') }}
                                    </button>
                                </a>
                            </div>

                            @if (auth('customer')->id() == '')
                                <div class="d-flex align-items-center">
                                    <a href="{{ route('customer.auth.login') }}">
                                        <button class="btn btn-primary">
                                            <i class="fa fa-envelope" aria-hidden="true"></i>
                                            {{ \App\CPU\translate('Contact') }} {{ \App\CPU\translate('Seller') }}
                                        </button>
                                    </a>
                                </div>
                            @else
                                <div class="d-flex align-items-center" id="contact-seller">
                                    <button class="btn btn-primary">
                                        <i class="fa fa-envelope" aria-hidden="true"></i>
                                        {{ \App\CPU\translate('Contact') }} {{ \App\CPU\translate('Seller') }}
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row msg-option" id="msg-option">
                    <form action="">
                        <input type="text" class="seller_id" hidden seller-id="{{ $product->seller->id }}">
                        <textarea shop-id="{{ $product->seller->shop->id }}" class="chatInputBox" id="chatInputBox" rows="5"> </textarea>

                        <button class="btn btn-secondary" style="color: white;"
                            id="cancelBtn">{{ \App\CPU\translate('cancel') }}
                        </button>
                        <button class="btn btn-primary" style="color: white;"
                            id="sendBtn">{{ \App\CPU\translate('send') }}</button>
                    </form>
                </div>
                <div class="go-to-chatbox" id="go_to_chatbox">
                    <a href="{{ route('chat-with-seller') }}" class="btn btn-primary" id="go_to_chatbox_btn">
                        {{ \App\CPU\translate('go_to') }} {{ \App\CPU\translate('chatbox') }} </a>
                </div>
            </div>
        @endif
    @else
        <div class="container rtl mt-3"
            style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
            <div class="row seller_details d-flex align-items-center" id="sellerOption">
                <div class="col-md-6">
                    <div class="seller_shop">
                        <div class="shop_image d-flex justify-content-center align-items-center">
                            <a href="{{ route('shopView', [0]) }}" class="d-flex justify-content-center">
                                <img style="height: 65px;width: 65px; border-radius: 50%"
                                    src="{{ asset('storage/app/public/company') }}/{{ $web_config['fav_icon']->value }}"
                                    onerror="this.src='{{ asset('assets/front-end/img/image-place-holder.png') }}'"
                                    alt="">
                            </a>
                        </div>
                        <div
                            class="shop-name-{{ Session::get('direction') === 'rtl' ? 'right' : 'left' }} d-flex justify-content-center align-items-center">
                            <div>
                                <a href="#" class="d-flex align-items-center">
                                    <div class="title">{{ $web_config['name']->value }}</div>
                                </a>
                                <div class="review d-flex align-items-center">
                                    <div class="">
                                        <span
                                            class="d-inline-block font-size-sm text-body align-middle mt-1 {{ Session::get('direction') === 'rtl' ? 'ml-2' : 'mr-2' }}">{{ \App\CPU\translate('web_admin') }}</span>
                                        <span
                                            class="d-inline-block font-size-sm text-body align-middle mt-1 {{ Session::get('direction') === 'rtl' ? 'mr-2' : 'ml-2' }}"></span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-6 p-md-0 pt-sm-3">
                    <div class="seller_contact">

                        <div
                            class="d-flex align-items-center {{ Session::get('direction') === 'rtl' ? 'pl-4' : 'pr-4' }}">
                            <a href="{{ route('shopView', [0]) }}">
                                <button class="btn btn-secondary">
                                    <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                                    {{ \App\CPU\translate('Visit') }}
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif --}}

    {{-- overview --}}
    <div class="container mt-4 rtl"
        style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
        <div class="row" style="background: white">
            <div class="col-12">
                <div class="product_overview mt-1">
                    <!-- Tabs-->
                    <ul class="nav nav-tabs d-flex justify-content-center" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" href="#overview" data-toggle="tab" role="tab"
                                style="color: black !important;">
                                {{ \App\CPU\translate('OVERVIEW') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#reviews" data-toggle="tab" role="tab"
                                style="color: black !important;">
                                {{ \App\CPU\translate('REVIEWS') }}
                            </a>
                        </li>
                    </ul>
                    <div class="px-4 pt-lg-3 pb-3 mb-3">
                        <div class="tab-content px-lg-3">
                            <!-- Tech specs tab-->
                            <div class="tab-pane fade show active" id="overview" role="tabpanel">
                                <div class="row pt-2 specification">
                                    @if ($product->video_url != null)
                                        <div class="col-12 mb-4">
                                            <iframe width="420" height="500" src="{{ $product->video_url }}"
                                                allowfullscreen>
                                            </iframe>
                                        </div>
                                    @endif

                                    <div class="col-lg-12 col-md-12">
                                        {!! $product['details'] !!}
                                    </div>
                                </div>
                            </div>
                            <!-- Reviews tab-->
                            <div class="tab-pane fade" id="reviews" role="tabpanel">
                                <div class="row pt-2 pb-3">
                                    <div class="col-lg-4 col-md-5 ">
                                        <h2 class="overall_review mb-2">{{ $overallRating[1] }}
                                            &nbsp{{ \App\CPU\translate('Reviews') }} </h2>
                                        <div
                                            class="star-rating {{ Session::get('direction') === 'rtl' ? 'ml-2' : 'mr-2' }}">
                                            @if (round($overallRating[0]) == 5)
                                                @for ($i = 0; $i < 5; $i++)
                                                    <i
                                                        class="czi-star-filled font-size-sm text-accent {{ Session::get('direction') === 'rtl' ? 'ml-1' : 'mr-1' }}"></i>
                                                @endfor
                                            @endif
                                            @if (round($overallRating[0]) == 4)
                                                @for ($i = 0; $i < 4; $i++)
                                                    <i
                                                        class="czi-star-filled font-size-sm text-accent {{ Session::get('direction') === 'rtl' ? 'ml-1' : 'mr-1' }}"></i>
                                                @endfor
                                                <i
                                                    class="czi-star font-size-sm text-muted {{ Session::get('direction') === 'rtl' ? 'ml-1' : 'mr-1' }}"></i>
                                            @endif
                                            @if (round($overallRating[0]) == 3)
                                                @for ($i = 0; $i < 3; $i++)
                                                    <i
                                                        class="czi-star-filled font-size-sm text-accent {{ Session::get('direction') === 'rtl' ? 'ml-1' : 'mr-1' }}"></i>
                                                @endfor
                                                @for ($j = 0; $j < 2; $j++)
                                                    <i
                                                        class="czi-star font-size-sm text-accent {{ Session::get('direction') === 'rtl' ? 'ml-1' : 'mr-1' }}"></i>
                                                @endfor
                                            @endif
                                            @if (round($overallRating[0]) == 2)
                                                @for ($i = 0; $i < 2; $i++)
                                                    <i
                                                        class="czi-star-filled font-size-sm text-accent {{ Session::get('direction') === 'rtl' ? 'ml-1' : 'mr-1' }}"></i>
                                                @endfor
                                                @for ($j = 0; $j < 3; $j++)
                                                    <i
                                                        class="czi-star font-size-sm text-accent {{ Session::get('direction') === 'rtl' ? 'ml-1' : 'mr-1' }}"></i>
                                                @endfor
                                            @endif
                                            @if (round($overallRating[0]) == 1)
                                                @for ($i = 0; $i < 4; $i++)
                                                    <i
                                                        class="czi-star font-size-sm text-accent {{ Session::get('direction') === 'rtl' ? 'ml-1' : 'mr-1' }}"></i>
                                                @endfor
                                                <i
                                                    class="czi-star-filled font-size-sm text-accent {{ Session::get('direction') === 'rtl' ? 'ml-1' : 'mr-1' }}"></i>
                                            @endif
                                            @if (round($overallRating[0]) == 0)
                                                @for ($i = 0; $i < 5; $i++)
                                                    <i
                                                        class="czi-star font-size-sm text-muted {{ Session::get('direction') === 'rtl' ? 'ml-1' : 'mr-1' }}"></i>
                                                @endfor
                                            @endif
                                        </div>
                                        <span class="d-inline-block align-middle">
                                            {{ $overallRating[0] }} {{ \App\CPU\translate('Overall') }}
                                            {{ \App\CPU\translate('rating') }}
                                        </span>
                                    </div>
                                    <div class="col-lg-8 col-md-7 pt-sm-3 pt-md-0">
                                        <div class="d-flex align-items-center mb-2">
                                            <div
                                                class="text-nowrap {{ Session::get('direction') === 'rtl' ? 'ml-3' : 'mr-3' }}">
                                                <span
                                                    class="d-inline-block align-middle text-muted">{{ \App\CPU\translate('5') }}</span><i
                                                    class="czi-star-filled font-size-xs {{ Session::get('direction') === 'rtl' ? 'mr-1' : 'ml-1' }}"></i>
                                            </div>
                                            <div class="w-100">
                                                <div class="progress" style="height: 4px;">
                                                    <div class="progress-bar bg-success" role="progressbar"
                                                        style="width: <?php echo $widthRating = $rating[0] != 0 ? ($rating[0] / $overallRating[1]) * 100 : 0; ?>%;" aria-valuenow="60"
                                                        aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                            <span
                                                class="text-muted {{ Session::get('direction') === 'rtl' ? 'mr-3' : 'ml-3' }}">
                                                {{ $rating[0] }}
                                            </span>
                                        </div>

                                        <div class="d-flex align-items-center mb-2">
                                            <div
                                                class="text-nowrap {{ Session::get('direction') === 'rtl' ? 'ml-3' : 'mr-3' }}">
                                                <span
                                                    class="d-inline-block align-middle text-muted">{{ \App\CPU\translate('4') }}</span><i
                                                    class="czi-star-filled font-size-xs {{ Session::get('direction') === 'rtl' ? 'mr-1' : 'ml-1' }}"></i>
                                            </div>
                                            <div class="w-100">
                                                <div class="progress" style="height: 4px;">
                                                    <div class="progress-bar" role="progressbar"
                                                        style="width: <?php echo $widthRating = $rating[1] != 0 ? ($rating[1] / $overallRating[1]) * 100 : 0; ?>%; background-color: #a7e453;"
                                                        aria-valuenow="27" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                            <span
                                                class="text-muted {{ Session::get('direction') === 'rtl' ? 'mr-3' : 'ml-3' }}">
                                                {{ $rating[1] }}
                                            </span>
                                        </div>

                                        <div class="d-flex align-items-center mb-2">
                                            <div
                                                class="text-nowrap {{ Session::get('direction') === 'rtl' ? 'ml-3' : 'mr-3' }}">
                                                <span
                                                    class="d-inline-block align-middle text-muted">{{ \App\CPU\translate('3') }}</span><i
                                                    class="czi-star-filled font-size-xs ml-1"></i>
                                            </div>
                                            <div class="w-100">
                                                <div class="progress" style="height: 4px;">
                                                    <div class="progress-bar" role="progressbar"
                                                        style="width: <?php echo $widthRating = $rating[2] != 0 ? ($rating[2] / $overallRating[1]) * 100 : 0; ?>%; background-color: #ffda75;"
                                                        aria-valuenow="17" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                            <span
                                                class="text-muted {{ Session::get('direction') === 'rtl' ? 'mr-3' : 'ml-3' }}">
                                                {{ $rating[2] }}
                                            </span>
                                        </div>

                                        <div class="d-flex align-items-center mb-2">
                                            <div
                                                class="text-nowrap {{ Session::get('direction') === 'rtl' ? 'ml-3' : 'mr-3' }}">
                                                <span
                                                    class="d-inline-block align-middle text-muted">{{ \App\CPU\translate('2') }}</span><i
                                                    class="czi-star-filled font-size-xs {{ Session::get('direction') === 'rtl' ? 'mr-1' : 'ml-1' }}"></i>
                                            </div>
                                            <div class="w-100">
                                                <div class="progress" style="height: 4px;">
                                                    <div class="progress-bar" role="progressbar"
                                                        style="width: <?php echo $widthRating = $rating[3] != 0 ? ($rating[3] / $overallRating[1]) * 100 : 0; ?>%; background-color: #fea569;"
                                                        aria-valuenow="9" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                            <span
                                                class="text-muted {{ Session::get('direction') === 'rtl' ? 'mr-3' : 'ml-3' }}">
                                                {{ $rating[3] }}
                                            </span>
                                        </div>

                                        <div class="d-flex align-items-center">
                                            <div
                                                class="text-nowrap {{ Session::get('direction') === 'rtl' ? 'ml-3' : 'mr-3' }}">
                                                <span
                                                    class="d-inline-block align-middle text-muted">{{ \App\CPU\translate('1') }}</span><i
                                                    class="czi-star-filled font-size-xs {{ Session::get('direction') === 'rtl' ? 'mr-1' : 'ml-1' }}"></i>
                                            </div>
                                            <div class="w-100">
                                                <div class="progress" style="height: 4px;">
                                                    <div class="progress-bar bg-danger" role="progressbar"
                                                        style="width: <?php echo $widthRating = $rating[4] != 0 ? ($rating[4] / $overallRating[1]) * 100 : 0; ?>%;" aria-valuenow="4"
                                                        aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                            <span
                                                class="text-muted {{ Session::get('direction') === 'rtl' ? 'mr-3' : 'ml-3' }}">
                                                {{ $rating[4] }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <hr class="mt-4 pb-4 mb-3">
                                <div class="row pb-4">
                                    <div class="col-12">
                                        @foreach ($product->reviews as $productReview)
                                            <div class="single_product_review p-2" style="margin-bottom: 20px">
                                                <div class="product-review d-flex justify-content-between">
                                                    <div
                                                        class="d-flex mb-3 {{ Session::get('direction') === 'rtl' ? 'pl-5' : 'pr-5' }}">
                                                        <div
                                                            class="media media-ie-fix align-items-center {{ Session::get('direction') === 'rtl' ? 'ml-4 pl-2' : 'mr-4 pr-2' }}">
                                                            <img style="max-height: 64px;" class="rounded-circle"
                                                                width="64"
                                                                onerror="this.src='{{ asset('assets/front-end/img/image-place-holder.png') }}'"
                                                                src="{{ asset('storage/app/public/profile') }}/{{ isset($productReview->user) ? $productReview->user->image : '' }}"
                                                                alt="{{ isset($productReview->user) ? $productReview->user->f_name : 'not exist' }}" />
                                                            <div
                                                                class="media-body {{ Session::get('direction') === 'rtl' ? 'pr-3' : 'pl-3' }}">
                                                                <h6 class="font-size-sm mb-0">
                                                                    {{ isset($productReview->user) ? $productReview->user->f_name : 'not exist' }}
                                                                </h6>
                                                                <div class="d-flex justify-content-between">
                                                                    <div class="product_review_rating">
                                                                        {{ $productReview->rating }}</div>
                                                                    <div class="star-rating">
                                                                        @for ($inc = 0; $inc < 5; $inc++)
                                                                            @if ($inc < $productReview->rating)
                                                                                <i
                                                                                    class="sr-star czi-star-filled active"></i>
                                                                            @else
                                                                                <i class="sr-star czi-star"></i>
                                                                            @endif
                                                                        @endfor
                                                                    </div>
                                                                </div>

                                                                <div class="font-size-ms text-muted">
                                                                    {{ $productReview->created_at->format('M d Y') }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <p class="font-size-md mt-3 mb-2">
                                                            {{ $productReview->comment }}</p>
                                                        @if (!empty(json_decode($productReview->attachment)))
                                                            @foreach (json_decode($productReview->attachment) as $key => $photo)
                                                                <img style="cursor: pointer;border-radius: 5px;border:1px;border-color: #7a6969; height: 67px ; margin-{{ Session::get('direction') === 'rtl' ? 'left' : 'right' }}: 5px;"
                                                                    onclick="showInstaImage('{{ asset("storage/app/public/review/$photo") }}')"
                                                                    class="cz-image-zoom"
                                                                    onerror="this.src='{{ asset('assets/front-end/img/image-place-holder.png') }}'"
                                                                    src="{{ asset("storage/app/public/review/$photo") }}"
                                                                    alt="Product review" width="67">
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        @if (count($product->reviews) == 0)
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="text-danger text-center">
                                                        {{ \App\CPU\translate('product_review_not_available') }}
                                                    </h6>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product carousel (You may also like)-->
    <div class="container  mb-3 rtl"
        style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
        <div class="flex-between">
            <div class="feature_header">
                <span>{{ \App\CPU\translate('similar_products') }}</span>
            </div>

            <div class="view_all ">
                <div>
                    @php($category = json_decode($product['category_ids']))
                    <a class="btn btn-outline-accent btn-sm viw-btn-a"
                        href="{{ route('products', ['id' => $category[0]->id, 'data_from' => 'category', 'page' => 1]) }}">{{ \App\CPU\translate('view_all') }}
                        <i
                            class="czi-arrow-{{ Session::get('direction') === 'rtl' ? 'left mr-1 ml-n1' : 'right ml-1 mr-n1' }}"></i>
                    </a>
                </div>
            </div>
        </div>
        <!-- Grid-->
        <hr class="view_border">
        <!-- Product-->
        <div class="row mt-4">
            @if (count($relatedProducts) > 0)
                @foreach ($relatedProducts as $key => $relatedProduct)
                    <div class="col-xl-2 col-sm-3 col-6" style="margin-bottom: 20px">
                        @include('web-views.partials._single-product', [
                            'product' => $relatedProduct,
                        ])
                    </div>
                @endforeach
            @else
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-danger text-center">{{ \App\CPU\translate('similar') }}
                                {{ \App\CPU\translate('product_not_available') }}</h6>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="modal fade rtl" id="show-modal-view" tabindex="-1" role="dialog" aria-labelledby="show-modal-image"
        aria-hidden="true" style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body" style="display: flex;justify-content: center">
                    <button class="btn btn-default"
                        style="border-radius: 50%;margin-top: -25px;position: absolute;{{ Session::get('direction') === 'rtl' ? 'left' : 'right' }}: -7px;"
                        data-dismiss="modal">
                        <i class="fa fa-close"></i>
                    </button>
                    <img class="element-center" id="attachment-view" src="">
                </div>
            </div>
        </div>
    </div>

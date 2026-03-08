@if(count($todays_deal_products) > 0)
    <section class="mb-2 mb-md-3 mt-2 mt-md-3">
        <div class="container">
            <!-- Title -->
            <h3 class="fs-16 fs-md-20 fw-700 mb-3">{{ translate('Productos en oferta hoy') }}</h3>
            <!-- Products -->
            <div class="" style="background-color: {{ get_setting('todays_deal_bg_color', '#3d4666') }}">
                <div class="text-right px-4 px-xl-5 pt-4 pt-md-3">
                    <a href="{{ route('todays-deal') }}" class="fs-12 fw-700 text-white has-transition hov-text-warning">{{ translate('View All') }}</a>
                </div>
                <div class="c-scrollbar-light overflow-hidden pl-5 pr-5 pb-3 pt-2 pb-md-5 pt-md-3">
                    <div class="h-100 d-flex flex-column justify-content-center">
                        <div class="todays-deal aiz-carousel" data-items="4" data-xxl-items="4" data-xl-items="4" data-lg-items="4" data-md-items="4" data-sm-items="2" data-xs-items="1" data-arrows="true" data-dots="false" data-autoplay="true" data-infinite="true">
                            @foreach ($todays_deal_products as $key => $product)
                                <div class="carousel-box h-100 px-3 px-lg-0">
                                    <a href="{{ route('product', $product->slug) }}" class="h-100 overflow-hidden hov-scale-img mx-auto" title="{{  $product->getTranslation('name')  }}">
                                        <!-- Image -->
                                        <div class="img h-180px w-180px rounded-2 overflow-hidden mx-auto">
                                            <img class="lazyload img-fit m-auto has-transition"
                                                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                data-src="{{ $product->thumbnail != null ? my_asset($product->thumbnail->file_name) : static_asset('assets/img/placeholder.jpg') }}"
                                                alt="{{ $product->getTranslation('name') }}"
                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                        </div>
                                        <!-- Price -->
                                        <div class="fs-14 mt-3 text-center">
                                            <span class="d-block text-white fw-700">{{ home_discounted_base_price($product) }}</span>
                                            @if(home_base_price($product) != home_discounted_base_price($product))
                                                <del class="d-block text-secondary fw-400">{{ home_base_price($product) }}</del>
                                            @endif
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
@extends('frontend.layouts.app')

@section('content')
    @php $lang = get_system_language()->code;  @endphp

  <style>
        /* =============================================
           FULL WIDTH BANNER
        ============================================= */

        .home-banner-area {
            position: relative;
            padding: 0;
            margin: 0 0 1rem 0;
        }

        .banner-and-menu-wrapper {
            position: relative;
            min-height: 380px;
            display: flex;
        }

        /* Slider rompe el container en ambos lados - full 100vw */
        .home-slider-fullwidth {
            position: absolute;
            top: 0;
            left: 0;
            right: calc(-50vw + 50%);
            margin-left: calc(-50vw + 50%);
            width: 100vw;
            height: 100%;
            min-height: 380px;
            z-index: 1;
        }

        .slider-main {
            background: linear-gradient(135deg, #f0eded 0%, #e8dfe5 100%);
            width: 100%;
            height: 100%;
            min-height: 380px;
            position: relative;
            overflow: hidden;
        }

        /* Menú flota ENCIMA del banner */
        .category-menu-over-banner {
            position: relative;
            z-index: 10;
            flex-shrink: 0;
        }

        .category-menu-over-banner > * {
            background-color: #fff !important;
            min-height: 380px;
            height: 100%;
        }

        /* =============================================
           SLIDES
        ============================================= */
        .slides-container {
            position: relative;
            width: 100%;
            height: 100%;
            min-height: 380px;
        }

        .slide {
            position: absolute !important;
            width: 100% !important;
            height: 100% !important;
            top: 0 !important;
            left: 0 !important;
            display: none !important;
            align-items: stretch;
            visibility: hidden !important;
            opacity: 0 !important;
            z-index: 0 !important;
        }

        .slide.active {
            display: flex !important;
            visibility: visible !important;
            opacity: 1 !important;
            z-index: 1 !important;
        }

        .slide-content {
            flex: 0 0 850px;
            z-index: 2;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 40px 80px 40px 60px;
            min-width: 0;
        }

        .slide-image {
            flex: 1;
            display: flex;
            align-items: stretch;
            overflow: hidden;
            min-width: 0;
            margin-left: 0;
        }

        .slide-image img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: center;
            display: block;
        }

        /* Animaciones de dirección de imagen */
        .slide-image img.anim-right-to-left {
            animation: revealRL 0.9s ease forwards;
        }

        .slide-image img.anim-left-to-right {
            animation: revealLR 0.9s ease forwards;
        }

        .slide-image img.anim-bottom-to-top {
            animation: revealBT 0.9s ease forwards;
        }

        .slide-image img.anim-top-to-bottom {
            animation: revealTB 0.9s ease forwards;
        }

        @keyframes revealRL {
            from { opacity: 0; transform: translateX(60px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        @keyframes revealLR {
            from { opacity: 0; transform: translateX(-60px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        @keyframes revealBT {
            from { opacity: 0; transform: translateY(60px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes revealTB {
            from { opacity: 0; transform: translateY(-60px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .slide-tag {
            font-size: 13px;
            color: #a90000;
            font-weight: 700;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            animation: itemSlideIn 0.5s ease forwards 0s;
            opacity: 0;
        }

        .slide-old {
            font-size: 15px;
            color: #aaa;
            text-decoration: line-through;
            margin-right: 8px;
            animation: itemSlideIn 0.5s ease forwards 0.1s;
            opacity: 0;
            display: inline;
        }

        .slide-title {
            font-size: 52px;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 14px;
            line-height: 1.05;
            animation: itemSlideIn 0.5s ease forwards 0.25s;
            opacity: 0;
        }

        .slide-prices {
            display: flex;
            align-items: center;
            margin-bottom: 24px;
        }

        .slide-new {
            font-size: 22px;
            color: #a90000;
            font-weight: 700;
            animation: itemSlideIn 0.5s ease forwards 0.4s;
            opacity: 0;
            display: inline;
        }

        .slide-btn {
            display: inline-block;
            padding: 12px 32px;
            background-color: #a90000;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            font-size: 15px;
            transition: background-color 0.3s ease;
            width: fit-content;
            animation: itemSlideIn 0.5s ease forwards 0.55s;
            opacity: 0;
        }

        .slide-btn:hover {
            background-color: #8b0000;
            color: white;
        }

        @keyframes itemSlideIn {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .arrow-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255,255,255,0.6);
            backdrop-filter: blur(4px);
            border: none;
            color: #333;
            font-size: 18px;
            padding: 10px 13px;
            cursor: pointer;
            z-index: 20;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .arrow-btn:hover {
            background-color: #a90000;
            color: white;
        }

        .arrow-btn.next { right: 16px; }

        .dot {
            height: 8px;
            width: 8px;
            margin: 0 4px;
            background-color: rgba(0,0,0,0.2);
            border-radius: 50%;
            display: inline-block;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .dot.active {
            background-color: #a90000;
            width: 22px;
            border-radius: 4px;
        }

        .dots-container {
            position: absolute;
            bottom: 14px;
            z-index: 20;
        }

        /* =============================================
           OFFSET SECTIONS - todas las secciones bajo el banner
           se desplazan a la derecha igual que el slider
        ============================================= */
        .section-offset {
            padding-left: 0; /* se setea por JS */
            transition: padding-left 0.1s;
        }

        /* =============================================
           RESPONSIVE
        ============================================= */
        @media (max-width: 1199px) {
            .slide {
                padding-left: 0 !important;
            }

            .slide-content {
                flex: 1;
                padding: 30px 20px;
            }

            .arrow-btn.prev { left: 10px !important; }

            .dots-container {
                left: 50% !important;
                transform: translateX(-50%);
            }

            .section-offset {
                padding-left: 0 !important;
            }
        }

        @media (max-width: 992px) {
            .slide-title { font-size: 32px; }
        }

        @media (max-width: 768px) {
            .banner-and-menu-wrapper,
            .home-slider-fullwidth,
            .slider-main,
            .slides-container { min-height: 260px; }

            /* Banner 1 Left Sidebar - Stack on mobile */
            #section_featured {
                margin-top: 0 !important;
            }
        }

        /* Banner 1 Left Sidebar Styles */
        .banner-1-left-sidebar {
            display: flex;
            flex-direction: column;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .banner-1-left-sidebar .mb-2 {
            margin-bottom: 0 !important;
        }

        .banner-1-left-sidebar img {
            width: 100%;
            height: auto;
            object-fit: cover;
            border-radius: 0;
        }

        @media (max-width: 991.98px) {
            .banner-1-left-sidebar {
                margin-bottom: 2rem;
            }

            .slide {
                flex-direction: column;
                align-items: center;
                padding-left: 0 !important;
            }

            .slide-content {
                width: 100%;
                flex: 0 0 auto;
                padding: 20px 20px 10px;
            }

            .slide-image {
                width: 100%;
                flex: 1;
                min-height: 120px;
            }

            .slide-title { font-size: 24px; }
            .slide-new   { font-size: 18px; }

            .section-offset {
                padding-left: 0 !important;
            }
        }

        /* =============================================
           CONTAINER WIDTH - Make page wider
        ============================================= */
        .container {
            max-width: 1400px !important;
        }

        @media (min-width: 1400px) {
            .container {
                max-width: 1400px !important;
            }
        }



        /* =============================================
           UTILITY CLASSES - Heights
        ============================================= */
        .h-140px {
            height: 140px !important;
        }
        
        .h-md-200px {
            height: 200px !important;
        }
        
        .h-35px {
            height: 35px !important;
        }
        
        .h-40px {
            height: 40px !important;
        }
        
        .h-100px {
            height: 100px !important;
        }

        /* =============================================
           PRODUCT CARD SIZE - Wider cards, sin espacios
        ============================================= */
        .carousel-box {
            width: 100%;
            border: none !important;
            border-color: transparent !important;
            padding: 0 !important;
            gap: 0 !important;
        }
        
        .carousel-box:hover {
            border: none !important;
            border-color: transparent !important;
            outline: none !important;
        }

        .aiz-carousel .carousel-box {
            padding: 0 !important;
            gap: 0 !important;
            margin: 0 !important;
        }
        
        /* Remover animación de outline en hover */
        .carousel-box.hov-animate-outline:hover {
            outline: none !important;
            border: none !important;
        }
        
        /* Asegurar que el carousel no tenga gutters */
        .aiz-carousel {
            gap: 0 !important;
        }
        
        .aiz-carousel.sm-gutters-16 {
            gap: 0 !important;
        }
        
        /* Banner Section 2 - Espacio entre banners */
        .aiz-carousel.gutters-16 {
            gap: 16px !important;
        }
        
        .aiz-carousel.gutters-16 .slick-track {
            gap: 16px !important;
        }
        
        .aiz-carousel.gutters-16 .slick-slide {
            padding: 0 8px !important;
        }
        
        /* Slick carousel slides sin espacios */
        .slick-slide {
            padding: 0 !important;
            margin: 0 !important;
        }
        
        .slick-track {
            gap: 0 !important;
        }

        /* =============================================
           PRODUCT CARD ANIMATIONS - Premium Hover Effects (a.html style)
        ============================================= */
        
        /* Card Base - Flex layout */
        .product-card {
            position: relative;
            overflow: hidden;
            transition: box-shadow 0.3s ease, transform 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
            min-height: 350px;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
        }

        .product-card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15) !important;
            transform: translateY(-4px);
            border: 1px solid #e0e0e0;
            outline: none !important;
            border-color: #e0e0e0 !important;
        }

        /* Image Container */
        .product-img-wrap {
            position: relative;
            overflow: hidden;
            background: #fafafa;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 140px;
            animation: skeleton-loading 1s linear infinite alternate;
        }

        .product-img-container {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            overflow: hidden;
            text-decoration: none;
        }
        
        .product-img-container:hover {
            text-decoration: none;
        }

        /* Skeleton loader animation */
        @keyframes skeleton-loading {
            0% {
                background-color: #f0f0f0;
            }
            100% {
                background-color: #e0e0e0;
            }
        }

        /* Remover animación cuando las imágenes están cargadas */
        .product-img-wrap.loaded {
            animation: none;
        }

        .product-img-wrap img.lazyloaded {
            animation: fade-in 0.3s ease-in-out;
        }

        @keyframes fade-in {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* PRIMARY IMAGE */
        .product-img.img-primary {
            visibility: visible;
            opacity: 1;
            transform: scale(1);
            transition: opacity 0.35s cubic-bezier(0.4, 0, 0.2, 1), transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1;
            position: relative;
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
            display: block;
            object-fit: contain;
        }

        /* HOVER IMAGE */
        .product-img.img-hover {
            position: absolute;
            top: 50%;
            left: 50%;
            visibility: hidden;
            opacity: 0;
            transform: translate(-50%, -50%) scale(1.08);
            width: auto;
            height: auto;
            max-width: 100%;
            max-height: 100%;
            transition: opacity 0.35s cubic-bezier(0.4, 0, 0.2, 1), transform 0.35s cubic-bezier(0.4, 0, 0.2, 1), visibility 0s 0.35s;
            z-index: 2;
            display: block;
            object-fit: contain;
        }

        /* Image swap on hover */
        .product-card:hover .product-img.img-primary {
            visibility: hidden;
            opacity: 0;
            transform: scale(1.08);
            z-index: 1;
            transition: opacity 0.35s cubic-bezier(0.4, 0, 0.2, 1), transform 0.35s cubic-bezier(0.4, 0, 0.2, 1), visibility 0s 0.35s;
        }

        .product-card:hover .product-img.img-hover {
            visibility: visible;
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
            z-index: 2;
            transition: opacity 0.35s cubic-bezier(0.4, 0, 0.2, 1), transform 0.35s cubic-bezier(0.4, 0, 0.2, 1), visibility 0s;
        }

        /* ACTIONS (WISHLIST, COMPARE) */
        .product-card .aiz-p-hov-icon {
            position: absolute;
            top: 12px;
            right: 12px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            opacity: 0;
            transform: translateX(15px);
            transition: opacity 0.35s cubic-bezier(0.4, 0, 0.2, 1), transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 5;
        }

        .product-card:hover .aiz-p-hov-icon {
            opacity: 1;
            transform: translateX(0);
        }

        .product-card .aiz-p-hov-icon a {
            width: 36px;
            height: 36px;
            background: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.15);
            transition: background 0.25s ease, box-shadow 0.25s ease, transform 0.2s ease;
        }

        .product-card .aiz-p-hov-icon a:hover {
            background: var(--primary);
            box-shadow: 0 5px 16px rgba(0, 0, 0, 0.2);
            transform: scale(1.1);
        }

        .product-card .aiz-p-hov-icon svg {
            stroke: #333;
            fill: none;
            stroke-width: 2;
        }

        /* Product Info Section */
        .product-card .flex-grow-1 {
            flex-grow: 1;
            padding: 12px;
            text-align: center;
            display: flex;
            flex-direction: column;
        }

        /* PRODUCT NAME */
        .product-card h3 {
            opacity: 0.9;
            transition: opacity 0.3s ease;
            margin-bottom: 8px;
            margin-top: 0;
            font-size: 13px;
            line-height: 1.4;
            color: #0961b3;
        }

        .product-card:hover h3 {
            opacity: 1;
        }
        
        /* PRODUCT RATING / STARS */
        .product-card .text-warning {
            color: #ffc107 !important;
            font-size: 12px;
            margin-bottom: 8px;
            line-height: 1.2;
        }
        
        .product-card .text-warning i {
            display: inline-block;
            margin: 0 1px;
        }

        /* PRICES */
        .product-card .fs-14 {
            opacity: 0.85;
            transition: opacity 0.3s ease;
            margin-bottom: 0;
            margin-top: auto;
            font-size: 16px;
            font-weight: 700;
            color: #a90000;
        }

        .product-card:hover .fs-14 {
            opacity: 1;
        }

        /* ADD TO CART BUTTON - At bottom, slide up */
        .cart-btn {
            position: relative;
            width: 100%;
            height: 40px;
            background: var(--primary) !important;
            color: white !important;
            border: none;
            font-weight: 700;
            font-size: 12px;
            cursor: pointer;
            transform: translateY(100%);
            opacity: 0;
            transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.35s ease, background 0.25s ease;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin-top: auto;
        }

        .product-card:hover .cart-btn {
            transform: translateY(0);
            opacity: 1;
        }

        .cart-btn:hover {
            background: var(--hov-primary) !important;
        }

        .cart-btn-text {
            font-size: 11px;
            margin-bottom: 2px;
        }
        
        /* Discount Tag - Sobre las imágenes */
        .product-card .absolute-top-left {
            z-index: 10 !important;
            position: absolute;
            top: 0;
            left: 0;
        }

        /* =============================================
           BANNER SECTION 3 - Different widths, SAME HEIGHT
        ============================================= */
        .banner-section-3.aiz-carousel {
            display: flex !important;
            height: 250px !important;
            min-height: 250px !important;
        }

        .banner-section-3 .slick-track {
            display: flex !important;
            width: 100% !important;
            height: 250px !important;
            align-items: stretch !important;
            gap: 20px !important;
        }

        .banner-section-3 .slick-slide {
            height: 250px !important;
            min-height: 250px !important;
            display: flex !important;
            align-items: stretch !important;
            padding: 0 !important;
        }

        .banner-section-3 .slick-slide > div {
            height: 100% !important;
            display: flex !important;
            align-items: stretch !important;
        }

        .banner-section-3 .slick-slide:first-child {
            width: 28% !important;
            flex: 0 0 28% !important;
        }

        .banner-section-3 .slick-slide:nth-child(2) {
            width: 72% !important;
            flex: 0 0 72% !important;
        }

        .banner-section-3 .carousel-box {
            width: 100% !important;
            height: 250px !important;
            min-height: 250px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .banner-section-3 .carousel-box img {
            width: 100% !important;
            height: 250px !important;
            min-height: 250px !important;
            object-fit: cover !important;
            object-position: center !important;
            display: block !important;
        }

        @media (max-width: 992px) {
            .banner-section-3.aiz-carousel {
                height: 250px !important;
                min-height: 250px !important;
            }

            .banner-section-3 .slick-track {
                height: 250px !important;
                gap: 20px !important;
            }

            .banner-section-3 .slick-slide {
                height: 250px !important;
                min-height: 250px !important;
            }

            .banner-section-3 .carousel-box {
                height: 250px !important;
                min-height: 250px !important;
            }

            .banner-section-3 .carousel-box img {
                height: 250px !important;
                min-height: 250px !important;
            }

            .banner-section-3 .slick-slide:first-child,
            .banner-section-3 .slick-slide:nth-child(2) {
                width: 100% !important;
                flex: 0 0 100% !important;
            }
        }

        @media (max-width: 768px) {
            .banner-section-3.aiz-carousel {
                height: 200px !important;
                min-height: 200px !important;
            }

            .banner-section-3 .slick-track {
                height: 200px !important;
                gap: 15px !important;
            }

            .banner-section-3 .slick-slide {
                height: 200px !important;
                min-height: 200px !important;
            }

            .banner-section-3 .carousel-box {
                height: 200px !important;
                min-height: 200px !important;
            }

            .banner-section-3 .carousel-box img {
                height: 200px !important;
                min-height: 200px !important;
            }
        }
    </style>

    <!-- =============================================
         BANNER AREA
    ============================================= -->
    <div class="home-banner-area mb-3">
        <div class="container">
            <div class="banner-and-menu-wrapper">

                <!-- ① MENÚ encima del banner -->
                <div class="category-menu-over-banner d-none d-xl-block" id="bannerCategoryMenu">
                    @include('frontend.'.get_setting("homepage_select").'.partials.category_menu')
                </div>

                <!-- ② SLIDER full width -->
                <div class="home-slider-fullwidth">
                    <div class="slider-main">
                        <div class="slides-container" id="hero-slides-container">
                            @if (get_setting('home_slider_images') != null)
                                @php
                                    $decoded_slider_images = json_decode(get_setting('home_slider_images', null, $lang), true);
                                    $sliders = get_slider_images($decoded_slider_images);
                                    $slider_tags = json_decode(get_setting('home_slider_tags', null, $lang), true) ?? [];
                                    $slider_old_prices = json_decode(get_setting('home_slider_old_prices', null, $lang), true) ?? [];
                                    $slider_titles = json_decode(get_setting('home_slider_titles', null, $lang), true) ?? [];
                                    $slider_new_prices = json_decode(get_setting('home_slider_new_prices', null, $lang), true) ?? [];
                                    $slider_btn_texts = json_decode(get_setting('home_slider_btn_texts', null, $lang), true) ?? [];
                                    $slider_anim_directions = json_decode(get_setting('home_slider_anim_direction', null, $lang), true) ?? [];
                                @endphp
                                @foreach ($sliders as $key => $slider)
                                    <div class="slide {{ $key == 0 ? 'active' : '' }}">
                                        <div class="slide-content">
                                            <span class="slide-tag">{{ $slider_tags[$key] ?? translate('Special Offer') }}</span>
                                            <h1 class="slide-title">{{ $slider_titles[$key] ?? translate('Amazing Product') }}</h1>
                                            <div class="slide-prices">
                                                <span class="slide-old">{{ $slider_old_prices[$key] ?? '$99.99' }}</span>
                                                <span class="slide-new">{{ $slider_new_prices[$key] ?? '$49.99' }}</span>
                                            </div>
                                            <a href="{{ json_decode(get_setting('home_slider_links'), true)[$key] ?? '#' }}" class="slide-btn">{{ $slider_btn_texts[$key] ?? translate('Shop Now') }}</a>
                                        </div>
                                        <div class="slide-image">
                                            @php
                                                $anim_class = '';
                                                $direction = $slider_anim_directions[$key] ?? 'right-to-left';
                                                if ($direction == 'left-to-right') {
                                                    $anim_class = 'anim-left-to-right';
                                                } elseif ($direction == 'bottom-to-top') {
                                                    $anim_class = 'anim-bottom-to-top';
                                                } elseif ($direction == 'top-to-bottom') {
                                                    $anim_class = 'anim-top-to-bottom';
                                                } else {
                                                    $anim_class = 'anim-right-to-left';
                                                }
                                            @endphp
                                            <img src="{{ $slider ? my_asset($slider->file_name) : static_asset('assets/img/placeholder.jpg') }}"
                                                alt="{{ env('APP_NAME') }}"
                                                class="{{ $anim_class }}"
                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <button class="arrow-btn prev" id="sliderPrevBtn" onclick="changeSlide(-1)">&#10094;</button>
                        <button class="arrow-btn next" onclick="changeSlide(1)">&#10095;</button>

                        <div class="dots-container" id="hero-dots-container">
                            @if (get_setting('home_slider_images') != null)
                                @foreach ($sliders as $key => $slider)
                                    <span class="dot {{ $key == 0 ? 'active' : '' }}" onclick="goSlide({{ $key }})"></span>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        // =============================================
        // Detectar ancho real del menú y ajustar layout
        // =============================================
        function getMenuOffset() {
            const menuEl = document.getElementById('bannerCategoryMenu');
            if (!menuEl || window.getComputedStyle(menuEl).display === 'none') {
                return 0;
            }
            const container = menuEl.closest('.container');
            const containerRect = container ? container.getBoundingClientRect() : { left: 0 };
            return containerRect.left + menuEl.getBoundingClientRect().width;
        }

        function adjustSliderLayout() {
            const heroContainer = document.getElementById('hero-slides-container');
            const menuEl = document.getElementById('bannerCategoryMenu');
            const prevBtn = document.getElementById('sliderPrevBtn');
            const dotsEl = document.getElementById('hero-dots-container');
            
            // Solo obtener los slides del hero, no todos
            const slideContents = heroContainer ? Array.from(heroContainer.querySelectorAll('.slide-content')) : [];

            if (!menuEl || window.getComputedStyle(menuEl).display === 'none') {
                slideContents.forEach(s => s.style.paddingLeft = '60px');
                if (prevBtn) prevBtn.style.left = '10px';
                if (dotsEl) {
                    dotsEl.style.left = '50%';
                    dotsEl.style.transform = 'translateX(-50%)';
                }
                return;
            }

            const totalPadding = getMenuOffset();

            // Ajustar padding de slide-content basado en el ancho del menú
            slideContents.forEach(s => {
                s.style.paddingLeft = (totalPadding + 40) + 'px';
            });

            if (prevBtn) {
                prevBtn.style.left = (totalPadding + 15) + 'px';
            }

            if (dotsEl) {
                const visibleWidth = window.innerWidth - totalPadding;
                dotsEl.style.left = (totalPadding + visibleWidth / 2) + 'px';
                dotsEl.style.transform = 'translateX(-50%)';
            }
        }

        // =============================================
        // Ajustar padding-left de todas las secciones
        // que deben quedar alineadas a la derecha del menú
        // =============================================
        function adjustSectionOffsets() {
            const offset = getMenuOffset();
            document.querySelectorAll('.section-offset').forEach(el => {
                el.style.paddingLeft = offset > 0 ? offset + 'px' : '0';
            });
        }

        function adjustAllLayouts() {
            adjustSliderLayout();
            adjustSectionOffsets();
        }

        document.addEventListener('DOMContentLoaded', adjustAllLayouts);
        window.addEventListener('resize', adjustAllLayouts);
        setTimeout(adjustAllLayouts, 100);
        setTimeout(adjustAllLayouts, 500);

        // =============================================
        // Skeleton Loading - Remover cuando las imágenes cargan
        // =============================================
        document.addEventListener('lazyloaded', function(e) {
            const img = e.target;
            const imgWrap = img.closest('.product-img-wrap');
            if (imgWrap) {
                imgWrap.classList.add('loaded');
            }
        });

        // Detectar imágenes que ya estaban cargadas
        document.querySelectorAll('.product-img-wrap img').forEach(img => {
            if (img.complete || img.naturalHeight !== 0) {
                img.closest('.product-img-wrap')?.classList.add('loaded');
            }
        });

        // =============================================
        // Slider Hero - Funciones del carrusel
        // =============================================
        let currentSlide = 0;
        let slides = [];
        let dots = [];

        function showSlide(n) {
            if (slides.length === 0) return;
            
            // Calcular indice válido
            currentSlide = ((n % slides.length) + slides.length) % slides.length;
            
            // Remover active de todos
            slides.forEach(slide => slide.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));
            
            // Añadir active al slide actual
            slides[currentSlide].classList.add('active');
            
            if (dots[currentSlide]) {
                dots[currentSlide].classList.add('active');
            }
        }

        function changeSlide(direction) {
            showSlide(currentSlide + direction);
        }

        function goSlide(n) {
            showSlide(n);
        }

        // Inicializar slider cuando el DOM está listo
        document.addEventListener('DOMContentLoaded', function() {
            const heroContainer = document.getElementById('hero-slides-container');
            const heroDots = document.getElementById('hero-dots-container');
            
            if (heroContainer && heroDots) {
                // Obtener solo los slides directos dentro del contenedor
                slides = Array.from(heroContainer.children).filter(el => el.classList.contains('slide'));
                dots = Array.from(heroDots.children).filter(el => el.classList.contains('dot'));
                
                if (slides.length > 0) {
                    // Mostrar primer slide
                    showSlide(0);
                    
                    // Auto-advance slides cada 5 segundos
                    setInterval(() => changeSlide(1), 5000);
                }
            }
        });
    </script>



    <!-- Section: Banner 1 Left + Featured/Best/New Products Right -->
    <div class="mb-2 mb-md-3 mt-2 mt-md-3">
        <div class="container">
            <div class="row">
                <!-- Left: Banner 1 Column (col-xl-3 col-md-5) -->
                <div class="col-xl-3 col-md-5">
                    @php $homeBanner1Images = get_setting('home_banner1_images', null, $lang); @endphp
                    @if ($homeBanner1Images != null)
                        @php
                            $banner_1_imags = json_decode($homeBanner1Images);
                        @endphp
                        <div class="bg-white px-3 py-3 py-md-2rem mb-2 mb-md-3">
                            <div class="aiz-carousel gutters-16 overflow-hidden arrow-inactive-none arrow-dark arrow-x-15"
                                data-items="1" data-xxl-items="1"
                                data-xl-items="1" data-lg-items="1"
                                data-md-items="1" data-sm-items="1" data-xs-items="1" data-arrows="false"
                                data-dots="false">
                                @foreach ($banner_1_imags as $key => $value)
                                    <div class="carousel-box overflow-hidden hov-scale-img">
                                        <a href="{{ json_decode(get_setting('home_banner1_links'), true)[$key] }}"
                                            class="d-block text-reset overflow-hidden">
                                            <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                                data-src="{{ uploaded_asset($value) }}" alt="{{ env('APP_NAME') }} promo"
                                                class="img-fluid lazyload w-100 has-transition"
                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Today's Deals Card -->
                    @php $flash_deal = get_featured_flash_deal(); @endphp
                    @if ($flash_deal != null)
                        <div class="mb-2 mb-md-3">
                            @include('frontend.'.get_setting('homepage_select').'.partials.todays_deals_card')
                        </div>
                    @endif
                </div>

                <!-- Right: Featured Products + Best Selling + New Products (remaining space) -->
                <div class="col">
                    <div class="bg-white">
                        <!-- Featured Products -->
                        <div id="section_featured" class="mb-2 mb-md-3"></div>

                        <!-- Banner Section 2 -->
                        @php $homeBanner2Images = get_setting('home_banner2_images', null, $lang); @endphp
                        @if ($homeBanner2Images != null)
                            <div class="mb-2 mb-md-3 px-3">
                                @php
                                    $banner_2_imags = json_decode($homeBanner2Images);
                                    $data_md = count($banner_2_imags) >= 2 ? 2 : 1;
                                @endphp
                                <div class="aiz-carousel gutters-16 overflow-hidden arrow-inactive-none arrow-dark arrow-x-15"
                                    data-items="{{ count($banner_2_imags) }}" data-xxl-items="{{ count($banner_2_imags) }}"
                                    data-xl-items="{{ count($banner_2_imags) }}" data-lg-items="{{ $data_md }}"
                                    data-md-items="{{ $data_md }}" data-sm-items="1" data-xs-items="1" data-arrows="true"
                                    data-dots="false">
                                    @foreach ($banner_2_imags as $key => $value)
                                        <div class="carousel-box overflow-hidden hov-scale-img">
                                            <a href="{{ json_decode(get_setting('home_banner2_links'), true)[$key] }}"
                                                class="d-block text-reset overflow-hidden">
                                                <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                                    data-src="{{ uploaded_asset($value) }}" alt="{{ env('APP_NAME') }} promo"
                                                    class="img-fluid lazyload w-100 has-transition"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- New Products -->
                        <div id="section_newest" class="mb-2 mb-md-3"></div>

                        <!-- Featured Categories Carousel -->
                        @if (count($featured_categories) > 0)
                            <div class="mb-2 mb-md-3">
                                <div class="d-flex mb-2 mb-md-3 align-items-baseline justify-content-between">
                                    <h3 class="fs-16 fs-md-20 fw-700 mb-2 mb-sm-0">
                                        {{ translate('Featured Categories') }}
                                    </h3>
                                    <div class="d-flex">
                                        <a class="text-blue fs-10 fs-md-12 fw-700 hov-text-primary animate-underline-primary"
                                            href="{{ route('categories.all') }}">{{ translate('View All Categories') }}</a>
                                    </div>
                                </div>
                                <div class="bg-white px-3">
                                    <div class="aiz-carousel arrow-x-0 arrow-inactive-none" data-items="6" data-xxl-items="6"
                                        data-xl-items="5" data-lg-items="4" data-md-items="3" data-sm-items="2" data-xs-items="1.5"
                                        data-arrows="true" data-dots="false">
                                        @foreach ($featured_categories->take(12) as $category)
                                            @php $category_name = $category->getTranslation('name'); @endphp
                                            <div class="carousel-box text-center border-right border-bottom has-transition hov-shadow-out z-1">
                                                <div class="p-3 p-md-4">
                                                    <div class="mb-3 overflow-hidden hov-scale-img">
                                                        <a href="{{ route('products.category', $category->slug) }}" class="d-block">
                                                            <img src="{{ isset($category->bannerImage->file_name) ? my_asset($category->bannerImage->file_name) : static_asset('assets/img/placeholder.jpg') }}"
                                                                class="lazyload w-100 h-auto"
                                                                alt="{{ $category_name }}"
                                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                        </a>
                                                    </div>
                                                    <h6 class="text-dark mb-0 text-truncate-2">
                                                        <a class="text-reset fw-700 fs-14 hov-text-primary"
                                                            href="{{ route('products.category', $category->slug) }}"
                                                            title="{{ $category_name }}">
                                                            {{ $category_name }}
                                                        </a>
                                                    </h6>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Banner Section 3 -->
                        @php $homeBanner3Images = get_setting('home_banner3_images', null, $lang); @endphp
                        @if ($homeBanner3Images != null)
                            <div class="mb-2 mb-md-3 px-3">
                                @php
                                    $banner_3_imags = json_decode($homeBanner3Images);
                                    $data_md = count($banner_3_imags) >= 2 ? 2 : 1;
                                @endphp
                                <div class="banner-section-3 aiz-carousel gutters-16 overflow-hidden arrow-inactive-none arrow-dark arrow-x-15"
                                    data-items="{{ count($banner_3_imags) }}" data-xxl-items="{{ count($banner_3_imags) }}"
                                    data-xl-items="{{ count($banner_3_imags) }}" data-lg-items="{{ $data_md }}"
                                    data-md-items="{{ $data_md }}" data-sm-items="1" data-xs-items="1" data-arrows="true"
                                    data-dots="false">
                                    @foreach ($banner_3_imags as $key => $value)
                                        <div class="carousel-box overflow-hidden hov-scale-img">
                                            <a href="{{ json_decode(get_setting('home_banner3_links'), true)[$key] }}"
                                                class="d-block text-reset overflow-hidden">
                                                <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                                    data-src="{{ uploaded_asset($value) }}" alt="{{ env('APP_NAME') }} promo"
                                                    class="img-fluid lazyload w-100 has-transition"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Today's Deal -->
                        <div id="todays_deal_bottom" class="mb-2 mb-md-3"></div>

                        <!-- Best Selling -->
                        <div id="section_best_selling" class="mb-2 mb-md-3"></div>

                        <!-- Top Brands -->
                        @if (get_setting('top_brands') != null)
                            <div class="mb-2 mb-md-3">
                                <h3 class="fs-16 fs-md-20 fw-700 mb-2 mb-md-3">{{ translate('Top Brands') }}</h3>
                                <div class="bg-white px-3">
                                    <div class="aiz-carousel arrow-x-0 arrow-inactive-none" data-items="5" data-xxl-items="5"
                                        data-xl-items="4" data-lg-items="3.4" data-md-items="2.5" data-sm-items="2" data-xs-items="1.4"
                                        data-arrows="true" data-dots="false">
                                        @php
                                            $top_brands = json_decode(get_setting('top_brands'));
                                            $brands = get_brands($top_brands);
                                        @endphp
                                        @foreach ($brands as $brand)
                                            <div class="carousel-box text-center has-transition hov-scale-img">
                                                <a href="{{ route('products.brand', $brand->slug) }}" class="d-block">
                                                    <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                                        data-src="{{ uploaded_asset($brand->logo) }}" alt="{{ $brand->getTranslation('name') }}"
                                                        class="lazyload w-100 h-auto"
                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Auction Product -->
    @if (addon_is_activated('auction'))
        <div id="auction_products" class="section-offset"></div>
    @endif

    <!-- Cupon -->
    @if (get_setting('coupon_system') == 1)
        <div class="mb-2 mb-md-3 mt-2 mt-md-3 section-offset"
            style="background-color: {{ get_setting('cupon_background_color', '#292933') }}">
            <div class="container">
                <div class="row py-5">
                    <div class="col-xl-8 text-center text-xl-left">
                        <div class="d-lg-flex">
                            <div class="mb-3 mb-lg-0">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="109.602" height="93.34" viewBox="0 0 109.602 93.34">
                                    <defs>
                                        <clipPath id="clip-pathcup">
                                            <path id="Union_10" data-name="Union 10" d="M12263,13778v-15h64v-41h12v56Z"
                                                transform="translate(-11966 -8442.865)" fill="none" stroke="#fff"
                                                stroke-width="2" />
                                        </clipPath>
                                    </defs>
                                    <g id="Group_24326" data-name="Group 24326"
                                        transform="translate(-274.201 -5254.611)">
                                        <g id="Mask_Group_23" data-name="Mask Group 23"
                                            transform="translate(-3652.459 1785.452) rotate(-45)"
                                            clip-path="url(#clip-pathcup)">
                                            <g id="Group_24322" data-name="Group 24322"
                                                transform="translate(207 18.136)">
                                                <g id="Subtraction_167" data-name="Subtraction 167"
                                                    transform="translate(-12177 -8458)" fill="none">
                                                    <path
                                                        d="M12335,13770h-56a8.009,8.009,0,0,1-8-8v-8a8,8,0,0,0,0-16v-8a8.009,8.009,0,0,1,8-8h56a8.009,8.009,0,0,1,8,8v8a8,8,0,0,0,0,16v8A8.009,8.009,0,0,1,12335,13770Z"
                                                        stroke="none" />
                                                    <path
                                                        d="M 12335.0009765625 13768.0009765625 C 12338.3095703125 13768.0009765625 12341.0009765625 13765.30859375 12341.0009765625 13762 L 12341.0009765625 13755.798828125 C 12336.4423828125 13754.8701171875 12333.0009765625 13750.8291015625 12333.0009765625 13746 C 12333.0009765625 13741.171875 12336.4423828125 13737.130859375 12341.0009765625 13736.201171875 L 12341.0009765625 13729.9990234375 C 12341.0009765625 13726.6904296875 12338.3095703125 13723.9990234375 12335.0009765625 13723.9990234375 L 12278.9990234375 13723.9990234375 C 12275.6904296875 13723.9990234375 12272.9990234375 13726.6904296875 12272.9990234375 13729.9990234375 L 12272.9990234375 13736.201171875 C 12277.5576171875 13737.1298828125 12280.9990234375 13741.1708984375 12280.9990234375 13746 C 12280.9990234375 13750.828125 12277.5576171875 13754.869140625 12272.9990234375 13755.798828125 L 12272.9990234375 13762 C 12272.9990234375 13765.30859375 12275.6904296875 13768.0009765625 12278.9990234375 13768.0009765625 L 12335.0009765625 13768.0009765625 M 12335.0009765625 13770.0009765625 L 12278.9990234375 13770.0009765625 C 12274.587890625 13770.0009765625 12270.9990234375 13766.412109375 12270.9990234375 13762 L 12270.9990234375 13754 C 12275.4111328125 13753.9990234375 12278.9990234375 13750.4111328125 12278.9990234375 13746 C 12278.9990234375 13741.5888671875 12275.41015625 13738 12270.9990234375 13738 L 12270.9990234375 13729.9990234375 C 12270.9990234375 13725.587890625 12274.587890625 13721.9990234375 12278.9990234375 13721.9990234375 L 12335.0009765625 13721.9990234375 C 12339.412109375 13721.9990234375 12343.0009765625 13725.587890625 12343.0009765625 13729.9990234375 L 12343.0009765625 13738 C 12338.5888671875 13738.0009765625 12335.0009765625 13741.5888671875 12335.0009765625 13746 C 12335.0009765625 13750.4111328125 12338.58984375 13754 12343.0009765625 13754 L 12343.0009765625 13762 C 12343.0009765625 13766.412109375 12339.412109375 13770.0009765625 12335.0009765625 13770.0009765625 Z"
                                                        stroke="none" fill="#fff" />
                                                </g>
                                            </g>
                                        </g>
                                        <g id="Group_24321" data-name="Group 24321"
                                            transform="translate(-3514.477 1653.317) rotate(-45)">
                                            <g id="Subtraction_167-2" data-name="Subtraction 167"
                                                transform="translate(-12177 -8458)" fill="none">
                                                <path
                                                    d="M12335,13770h-56a8.009,8.009,0,0,1-8-8v-8a8,8,0,0,0,0-16v-8a8.009,8.009,0,0,1,8-8h56a8.009,8.009,0,0,1,8,8v8a8,8,0,0,0,0,16v8A8.009,8.009,0,0,1,12335,13770Z"
                                                    stroke="none" />
                                                <path
                                                    d="M 12335.0009765625 13768.0009765625 C 12338.3095703125 13768.0009765625 12341.0009765625 13765.30859375 12341.0009765625 13762 L 12341.0009765625 13755.798828125 C 12336.4423828125 13754.8701171875 12333.0009765625 13750.8291015625 12333.0009765625 13746 C 12333.0009765625 13741.171875 12336.4423828125 13737.130859375 12341.0009765625 13736.201171875 L 12341.0009765625 13729.9990234375 C 12341.0009765625 13726.6904296875 12338.3095703125 13723.9990234375 12335.0009765625 13723.9990234375 L 12278.9990234375 13723.9990234375 C 12275.6904296875 13723.9990234375 12272.9990234375 13726.6904296875 12272.9990234375 13729.9990234375 L 12272.9990234375 13736.201171875 C 12277.5576171875 13737.1298828125 12280.9990234375 13741.1708984375 12280.9990234375 13746 C 12280.9990234375 13750.828125 12277.5576171875 13754.869140625 12272.9990234375 13755.798828125 L 12272.9990234375 13762 C 12272.9990234375 13765.30859375 12275.6904296875 13768.0009765625 12278.9990234375 13768.0009765625 L 12335.0009765625 13768.0009765625 M 12335.0009765625 13770.0009765625 L 12278.9990234375 13770.0009765625 C 12274.587890625 13770.0009765625 12270.9990234375 13766.412109375 12270.9990234375 13762 L 12270.9990234375 13754 C 12275.4111328125 13753.9990234375 12278.9990234375 13750.4111328125 12278.9990234375 13746 C 12278.9990234375 13741.5888671875 12275.41015625 13738 12270.9990234375 13738 L 12270.9990234375 13729.9990234375 C 12270.9990234375 13725.587890625 12274.587890625 13721.9990234375 12278.9990234375 13721.9990234375 L 12335.0009765625 13721.9990234375 C 12339.412109375 13721.9990234375 12343.0009765625 13725.587890625 12343.0009765625 13729.9990234375 L 12343.0009765625 13738 C 12338.5888671875 13738.0009765625 12335.0009765625 13741.5888671875 12335.0009765625 13746 C 12335.0009765625 13750.4111328125 12338.58984375 13754 12343.0009765625 13754 L 12343.0009765625 13762 C 12343.0009765625 13766.412109375 12339.412109375 13770.0009765625 12335.0009765625 13770.0009765625 Z"
                                                    stroke="none" fill="#fff" />
                                            </g>
                                            <g id="Group_24325" data-name="Group 24325">
                                                <rect id="Rectangle_18578" data-name="Rectangle 18578" width="8"
                                                    height="2" transform="translate(120 5287)" fill="#fff" />
                                                <rect id="Rectangle_18579" data-name="Rectangle 18579" width="8"
                                                    height="2" transform="translate(132 5287)" fill="#fff" />
                                                <rect id="Rectangle_18581" data-name="Rectangle 18581" width="8"
                                                    height="2" transform="translate(144 5287)" fill="#fff" />
                                                <rect id="Rectangle_18580" data-name="Rectangle 18580" width="8"
                                                    height="2" transform="translate(108 5287)" fill="#fff" />
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                            </div>
                            <div class="ml-lg-3">
                                <h5 class="fs-36 fw-400 text-white mb-3">{{ translate(get_setting('cupon_title')) }}</h5>
                                <h5 class="fs-20 fw-400 text-gray">{{ translate(get_setting('cupon_subtitle')) }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 text-center text-xl-right mt-4">
                        <a href="{{ route('coupons.all') }}"
                            class="btn text-white hov-bg-white hov-text-dark border border-width-2 fs-16 px-4"
                            style="border-radius: 28px;background: rgba(255, 255, 255, 0.2);box-shadow: 0px 20px 30px rgba(0, 0, 0, 0.16);">{{ translate('View All Coupons') }}</a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Category wise Products -->
    <div id="section_home_categories" class="mb-2 mb-md-3 mt-2 mt-md-3 section-offset"></div>

    <!-- Classified Product -->
    @if (get_setting('classified_product') == 1)
        @php
            $classified_products = get_home_page_classified_products(6);
        @endphp
        @if (count($classified_products) > 0)
            <section class="mb-2 mb-md-3 mt-2 mt-md-3 section-offset">
                <div class="container">
                    <div class="d-flex mb-2 mb-md-3 align-items-baseline justify-content-between">
                        <h3 class="fs-16 fs-md-20 fw-700 mb-2 mb-sm-0">
                            <span class="">{{ translate('Classified Ads') }}</span>
                        </h3>
                        <div class="d-flex">
                            <a class="text-blue fs-10 fs-md-12 fw-700 hov-text-primary animate-underline-primary"
                                href="{{ route('customer.products') }}">{{ translate('View All Products') }}</a>
                        </div>
                    </div>
                    @php
                        $classifiedBannerImage = get_setting('classified_banner_image', null, $lang);
                        $classifiedBannerImageSmall = get_setting('classified_banner_image_small', null, $lang);
                    @endphp
                    @if ($classifiedBannerImage != null || $classifiedBannerImageSmall != null)
                        <div class="mb-3 overflow-hidden hov-scale-img d-none d-md-block">
                            <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                data-src="{{ uploaded_asset($classifiedBannerImage) }}"
                                alt="{{ env('APP_NAME') }} promo" class="lazyload img-fit h-100 has-transition"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                        </div>
                        <div class="mb-3 overflow-hidden hov-scale-img d-md-none">
                            <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                data-src="{{ $classifiedBannerImageSmall != null ? uploaded_asset($classifiedBannerImageSmall) : uploaded_asset($classifiedBannerImage) }}"
                                alt="{{ env('APP_NAME') }} promo" class="lazyload img-fit h-100 has-transition"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                        </div>
                    @endif
                    <div class="bg-white">
                        <div class="row no-gutters border-top border-left">
                            @foreach ($classified_products as $key => $classified_product)
                                <div class="col-xl-4 col-md-6 border-right border-bottom has-transition hov-shadow-out z-1">
                                    <div class="aiz-card-box p-2 has-transition bg-white">
                                        <div class="row hov-scale-img">
                                            <div class="col-4 col-md-5 mb-3 mb-md-0">
                                                <a href="{{ route('customer.product', $classified_product->slug) }}"
                                                    class="d-block overflow-hidden h-auto h-md-150px text-center">
                                                    <img class="img-fluid lazyload mx-auto has-transition"
                                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                        data-src="{{ isset($classified_product->thumbnail->file_name) ? my_asset($classified_product->thumbnail->file_name) : static_asset('assets/img/placeholder.jpg') }}"
                                                        alt="{{ $classified_product->getTranslation('name') }}"
                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                </a>
                                            </div>
                                            <div class="col">
                                                <h3 class="fw-400 fs-14 text-dark text-truncate-2 lh-1-4 mb-3 h-35px d-none d-sm-block">
                                                    <a href="{{ route('customer.product', $classified_product->slug) }}"
                                                        class="d-block text-reset hov-text-primary">{{ $classified_product->getTranslation('name') }}</a>
                                                </h3>
                                                <div class="fs-14 mb-3">
                                                    <span class="text-secondary">{{ $classified_product->user ? $classified_product->user->name : '' }}</span><br>
                                                    <span class="fw-700 text-primary">{{ single_price($classified_product->unit_price) }}</span>
                                                </div>
                                                @if ($classified_product->conditon == 'new')
                                                    <span class="badge badge-inline badge-soft-info fs-13 fw-700 p-3 text-info" style="border-radius: 20px;">{{ translate('New') }}</span>
                                                @elseif($classified_product->conditon == 'used')
                                                    <span class="badge badge-inline badge-soft-danger fs-13 fw-700 p-3 text-danger" style="border-radius: 20px;">{{ translate('Used') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        @endif
    @endif

@endsection


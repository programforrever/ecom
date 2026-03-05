@php
    $flash_deal = get_featured_flash_deal();
    $flash_deal_products = collect();
    
    if ($flash_deal) {
        $flash_deal_products = get_flash_deal_products($flash_deal->id);
    }
    
    $products = $flash_deal_products->take(10);
    
    // DEBUG
    \Log::info('Flash Deal Debug:', [
        'flash_deal' => $flash_deal ? $flash_deal->id : 'NULL',
        'products_count' => $products->count(),
        'product_ids' => $products->pluck('product_id')->toArray(),
    ]);
@endphp

<style>
    @keyframes slideUpFade {
        from {
            opacity: 0;
            transform: translateY(40px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Deals Card */
    .deals-card {
        width: 100%;
        border: 2px solid #e8c83a;
        border-radius: 6px;
        background: #fff;
        padding: 18px 18px 22px;
        position: relative;
    }

    .deals-title {
        text-align: center;
        font-size: 17px;
        font-weight: 800;
        color: #222;
        margin-bottom: 16px;
        letter-spacing: 0.3px;
    }

    .slider-wrapper {
        position: relative;
        width: 100%;
    }

    .slider-track {
        overflow: hidden;
        width: 100%;
        border-radius: 4px;
        min-height: 320px;
    }

    .slides {
        display: flex;
        transition: transform 0.42s cubic-bezier(.4,0,.2,1);
        width: 100%;
        min-height: 100%;
    }

    .slider-track .slide {
        min-width: 100%;
        min-height: 100%;
        display: none !important;
        flex-direction: column;
        align-items: center;
        padding: 10px;
        cursor: pointer;
        transition: opacity 0.3s ease;
    }

    .slider-track .slide:hover {
        opacity: 0.85;
    }

    .slider-track .slide.active {
        display: flex !important;
    }

    .slider-track .slide.active .img-wrap img {
        animation: slideUpFade 0.6s ease-out forwards;
    }

    .img-wrap {
        position: relative;
        width: 100%;
        max-width: 260px;
        height: 220px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
    }

    .img-wrap img {
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
        display: block;
    }

    .badge {
        position: absolute;
        top: 8px;
        left: 8px;
        background: #e74c3c;
        color: #fff;
        font-size: 12px;
        font-weight: 800;
        border-radius: 50%;
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 3;
        box-shadow: 0 2px 6px rgba(0,0,0,.18);
    }

    .nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: #fff;
        border: 2px solid #ddd;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 18px;
        line-height: 1;
        color: #555;
        z-index: 10;
        opacity: 0;
        pointer-events: none;
        transition: opacity .22s, background .2s, border-color .2s, color .2s;
        padding: 0;
    }

    .nav-btn.prev { left: -15px; }
    .nav-btn.next { right: -15px; }

    .deals-card:hover .nav-btn {
        opacity: 1;
        pointer-events: all;
    }

    .nav-btn:hover {
        background: #e8c83a;
        border-color: #e8c83a;
        color: #fff;
    }

    .product-info {
        text-align: center;
        margin-top: 14px;
        width: 100%;
        padding: 0 4px;
    }

    .slider-track .slide.active .product-info {
        animation: slideUpFade 0.6s ease-out 0.2s forwards;
    }

    .product-name {
        font-size: 13px;
        font-weight: 700;
        color: #0961b3;
        line-height: 1.45;
        margin-bottom: 8px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .stars {
        margin-bottom: 8px;
        display: flex;
        justify-content: center;
        gap: 2px;
    }

    .star {
        color: #ffc107;
        font-size: 14px;
    }

    .price-row {
        font-size: 13px;
        margin-bottom: 2px;
    }

    .old-price {
        color: #999;
        text-decoration: line-through;
        margin-right: 5px;
        font-size: 12px;
    }

    .new-price {
        color: #a90000;
        font-weight: 800;
        font-size: 16px;
    }

    .countdown {
        display: flex;
        justify-content: center;
        gap: 6px;
        margin-top: 14px;
    }

    .count-block {
        background: #fafafa;
        border: 1px solid #eee;
        border-radius: 4px;
        padding: 5px 8px;
        min-width: 52px;
        text-align: center;
        box-shadow: 0 1px 3px rgba(0,0,0,.06);
    }

    .count-num {
        display: block;
        font-size: 15px;
        font-weight: 800;
        color: #222;
    }

    .count-label {
        font-size: 9px;
        color: #888;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .dots {
        display: flex;
        justify-content: center;
        gap: 6px;
        margin-top: 12px;
    }

    .deals-card .dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #ddd;
        transition: background .3s;
        cursor: pointer;
    }

    .deals-card .dot.active { background: #e8c83a; }

    @media (max-width: 768px) {
        .deals-card {
            padding: 14px 14px 18px;
        }
        .deals-title {
            font-size: 15px;
            margin-bottom: 12px;
        }
        .img-wrap {
            height: 180px;
        }
        .product-info {
            margin-top: 10px;
        }
    }
</style>

<div class="deals-card">
    <div class="deals-title">🔥 Ofertas del Día</div>

    <div class="slider-wrapper">
        <button class="nav-btn prev" onclick="changeOfferSlide(-1)">&#8249;</button>
        <button class="nav-btn next" onclick="changeOfferSlide(1)">&#8250;</button>

        <div class="slider-track">
            <div class="slides" id="deal-slides">
                @if ($products->count() > 0)
                    @foreach ($products as $key => $product)
                        @php
                            $prod = $product->product;
                            if (!$prod) continue;
                        @endphp
                        <a href="{{ route('product', $prod->slug) }}" class="slide {{ $key == 0 ? 'active' : '' }}" style="text-decoration: none; color: inherit;">
                            <div class="img-wrap">
                                @php $discount = discount_in_percentage($prod); @endphp
                                @if ($discount > 0)
                                    <span class="badge">-{{ round($discount) }}%</span>
                                @endif
                                <img src="{{ get_image($prod->thumbnail) }}" 
                                     alt="{{ $prod->getTranslation('name') }}"
                                     onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';" />
                            </div>
                            <div class="product-info">
                                <div class="product-name">{{ Str::limit($prod->getTranslation('name'), 50) }}</div>
                                <div class="stars">
                                    @php $rating = ($prod->reviews->avg('rating') ?? 0); @endphp
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= floor($rating))
                                            <i class="las la-star" style="color: #ffc107;"></i>
                                        @elseif ($i - $rating < 1)
                                            <i class="las la-star-half-alt" style="color: #ffc107;"></i>
                                        @else
                                            <i class="las la-star" style="color: #ddd;"></i>
                                        @endif
                                    @endfor
                                </div>
                                <div class="price-row">
                                    @if (home_base_price($prod) != home_discounted_base_price($prod))
                                        <span class="old-price">{{ home_base_price($prod) }}</span>
                                    @endif
                                    <span class="new-price">{{ home_discounted_base_price($prod) }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                @else
                    <div class="slide">
                        <div style="text-align: center; padding: 40px 0; color: #999; width: 100%;">
                            <p>❌ No hay productos en la oferta flash activa</p>
                            <p style="font-size: 12px; margin-top: 10px;">Flash Deal: {{ $flash_deal ? 'ID='.$flash_deal->id : 'NO ACTIVO' }}</p>
                            <p style="font-size: 12px;">Productos: {{ $products->count() }} encontrados</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Countdown -->
    <div class="countdown">
        <div class="count-block"><span class="count-num" id="deal-days">00</span><span class="count-label">Día</span></div>
        <div class="count-block"><span class="count-num" id="deal-hours">00</span><span class="count-label">Hora</span></div>
        <div class="count-block"><span class="count-num" id="deal-mins">00</span><span class="count-label">Min</span></div>
        <div class="count-block"><span class="count-num" id="deal-secs">00</span><span class="count-label">Seg</span></div>
    </div>

    <!-- Dots -->
    <div class="dots" id="deal-dots">
        @for ($i = 0; $i < $products->count(); $i++)
            <div class="dot {{ $i == 0 ? 'active' : '' }}" onclick="goOfferSlide({{ $i }})"></div>
        @endfor
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Deal Slider Init');
        
        const dealSlidesContainer = document.getElementById('deal-slides');
        const dealSlides = document.querySelectorAll('#deal-slides .slide');
        const dealDots = document.querySelectorAll('#deal-dots .dot');
        
        console.log('Slides found:', dealSlides.length);
        console.log('Dots found:', dealDots.length);
        
        if (dealSlides.length === 0) {
            console.warn('No slides found!');
            return;
        }
        
        let dealCurrent = 0;

        window.goOfferSlide = function(idx) {
            dealCurrent = (idx + dealSlides.length) % dealSlides.length;
            
            // Remove active from all slides and dots
            [...dealSlides].forEach(s => {
                s.classList.remove('active');
                // Force reflow to restart animation
                void s.offsetWidth;
            });
            [...dealDots].forEach(d => d.classList.remove('active'));
            
            // Add active to current slide and dot (this triggers animation)
            dealSlides[dealCurrent].classList.add('active');
            dealDots[dealCurrent].classList.add('active');
            
            console.log('Showing slide:', dealCurrent);
        };

        window.changeOfferSlide = function(dir) {
            goOfferSlide(dealCurrent + dir);
        };

        // Initialize first slide
        goOfferSlide(0);

        // Auto-advance every 2 seconds
        setInterval(() => changeOfferSlide(1), 2000);

        // Countdown
        @if (get_featured_flash_deal())
            let totalSec = Math.floor(({{ get_featured_flash_deal()->end_date * 1000 }} - new Date().getTime()) / 1000);
            if (totalSec < 0) totalSec = 0;

            function padDeal(n, l = 2) { return String(n).padStart(l, '0'); }

            function tickDeal() {
                if (totalSec <= 0) {
                    document.getElementById('deal-days').textContent = '00';
                    document.getElementById('deal-hours').textContent = '00';
                    document.getElementById('deal-mins').textContent = '00';
                    document.getElementById('deal-secs').textContent = '00';
                    return;
                }
                totalSec--;
                document.getElementById('deal-days').textContent = padDeal(Math.floor(totalSec / 86400), 2);
                document.getElementById('deal-hours').textContent = padDeal(Math.floor((totalSec % 86400) / 3600));
                document.getElementById('deal-mins').textContent = padDeal(Math.floor((totalSec % 3600) / 60));
                document.getElementById('deal-secs').textContent = padDeal(totalSec % 60);
            }

            tickDeal();
            setInterval(tickDeal, 1000);
        @endif
    });
</script>

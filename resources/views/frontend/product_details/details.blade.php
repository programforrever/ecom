<style>
    /* ── Neumorfismo: Reseñas ── */
    .neu-reviews {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #e8ecef;
        border-radius: 20px;
        box-shadow: 5px 5px 10px #c5c9cc, -5px -5px 10px #ffffff;
        padding: 5px 14px;
    }

    /* ── Neumorfismo: Guía de tallas ── */
    .neu-size-guide {
        background: #e8ecef !important;
        border: none !important;
        border-radius: 10px !important;
        box-shadow: 5px 5px 10px #c5c9cc, -5px -5px 10px #ffffff !important;
        padding: 6px 14px !important;
        font-size: 13px !important;
        font-weight: 700 !important;
        color: #f6a623 !important;
        text-decoration: none !important;
        transition: box-shadow 0.15s ease, transform 0.1s ease !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 5px !important;
    }
    .neu-size-guide:hover {
        box-shadow: 3px 3px 6px #c5c9cc, -3px -3px 6px #ffffff !important;
        color: #f6a623 !important;
    }
    .neu-size-guide:active {
        box-shadow: inset 4px 4px 8px #c5c9cc, inset -4px -4px 8px #ffffff !important;
        transform: scale(0.97) !important;
    }

    /* ── Neumorfismo: Badge disponible ── */
    .neu-stock-badge {
        background: #e8ecef;
        border-radius: 20px;
        box-shadow: 4px 4px 8px #c5c9cc, -4px -4px 8px #ffffff;
        padding: 4px 12px;
        font-size: 13px;
        font-weight: 700;
        color: #4caf7d;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .neu-stock-badge::before {
        content: '';
        width: 7px;
        height: 7px;
        background: #4caf7d;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 5px #4caf7d88;
    }

    /* ── Neumorfismo: Botón Añadir a la cesta ── */
    .neu-btn-cart {
        background: #ffc519 !important;
        border: none !important;
        border-radius: 12px !important;
        box-shadow: 6px 6px 12px rgba(255,197,25,0.35), -2px -2px 6px rgba(255,255,255,0.5) !important;
        padding: 11px 22px !important;
        font-size: 14px !important;
        font-weight: 700 !important;
        color: #ffffff !important;
        transition: box-shadow 0.15s ease, transform 0.1s ease !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 7px !important;
        cursor: pointer !important;
        min-width: 150px !important;
        justify-content: center !important;
    }
    .neu-btn-cart:hover {
        box-shadow: 3px 3px 8px rgba(255,197,25,0.45), -2px -2px 5px rgba(255,255,255,0.4) !important;
        color: #ffffff !important;
        opacity: 0.92 !important;
    }
    .neu-btn-cart:active {
        box-shadow: inset 3px 3px 8px rgba(0,0,0,0.15), inset -2px -2px 5px rgba(255,255,255,0.2) !important;
        transform: scale(0.97) !important;
    }

    /* ── Neumorfismo: Botón Comprar ahora ── */
    .neu-btn-buy {
        background: #f6a623 !important;
        border: none !important;
        border-radius: 12px !important;
        box-shadow: 6px 6px 12px rgba(246,166,35,0.35), -2px -2px 6px rgba(255,255,255,0.5) !important;
        padding: 11px 22px !important;
        font-size: 14px !important;
        font-weight: 700 !important;
        color: #ffffff !important;
        transition: box-shadow 0.15s ease, transform 0.1s ease !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 7px !important;
        cursor: pointer !important;
        min-width: 150px !important;
        justify-content: center !important;
    }
    .neu-btn-buy:hover {
        box-shadow: 3px 3px 8px rgba(246,166,35,0.45), -2px -2px 5px rgba(255,255,255,0.4) !important;
        color: #ffffff !important;
        opacity: 0.92 !important;
    }
    .neu-btn-buy:active {
        box-shadow: inset 3px 3px 8px rgba(0,0,0,0.15), inset -2px -2px 5px rgba(255,255,255,0.2) !important;
        transform: scale(0.97) !important;
    }

    /* ── Neumorfismo: Botón WhatsApp ── */
    .neu-btn-whatsapp {
        background: #25d366 !important;
        border: none !important;
        border-radius: 12px !important;
        box-shadow: 6px 6px 12px rgba(37,211,102,0.35), -2px -2px 6px rgba(255,255,255,0.5) !important;
        padding: 11px 22px !important;
        font-size: 14px !important;
        font-weight: 700 !important;
        color: #ffffff !important;
        transition: box-shadow 0.15s ease, transform 0.1s ease !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
        cursor: pointer !important;
        white-space: nowrap !important;
        text-decoration: none !important;
        justify-content: center !important;
    }
    .neu-btn-whatsapp:hover {
        box-shadow: 3px 3px 8px rgba(37,211,102,0.45), -2px -2px 5px rgba(255,255,255,0.4) !important;
        color: #ffffff !important;
        opacity: 0.92 !important;
        text-decoration: none !important;
    }
    .neu-btn-whatsapp:active {
        box-shadow: inset 3px 3px 8px rgba(0,0,0,0.15), inset -2px -2px 5px rgba(255,255,255,0.2) !important;
        transform: scale(0.97) !important;
    }

    /* ── Bloque Precio / Cantidad / Total — diseño elegante ── */
    .neu-info-block {
        background: #ffffff;
        border-radius: 14px;
        border: 1.5px solid #f0f0f0;
        padding: 4px 20px;
        margin-bottom: 20px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.06);
    }
    .neu-info-block .row.no-gutters {
        margin-bottom: 0 !important;
        padding: 13px 0;
        border-bottom: 1px dashed #ebebeb;
        align-items: center;
    }
    .neu-info-block .row.no-gutters:last-child {
        border-bottom: none;
    }
    .neu-info-block .col-sm-2 .text-secondary {
        font-size: 12px !important;
        font-weight: 600 !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #aab0bb !important;
    }
    .neu-info-block #chosen_price,
    .neu-info-block .text-primary {
        font-size: 1.15rem !important;
        font-weight: 800 !important;
    }

    /* ── Bloque visual: precio + cantidad + total juntos ── */
    .price-qty-block {
        background: #ffffff;
        border-radius: 14px;
        border: 1.5px solid #f0f0f0;
        box-shadow: 0 4px 18px rgba(0,0,0,0.06);
        padding: 0 20px;
        margin-bottom: 20px;
        overflow: hidden;
        max-width: 480px;
    }
    .price-qty-block .pqb-row {
        display: flex;
        align-items: center;
        padding: 13px 0;
        border-bottom: 1px dashed #ebebeb;
        gap: 12px;
    }
    .price-qty-block .pqb-row:last-child {
        border-bottom: none;
    }
    .price-qty-block .pqb-label {
        min-width: 100px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #b0b8c4;
    }
    .price-qty-block .pqb-value {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .price-qty-block .price-main {
        font-size: 1.1rem;
        font-weight: 800;
        color: #f6a623;
    }
    .price-qty-block .price-old {
        font-size: 0.85rem;
        color: #b0b8c4;
        text-decoration: line-through;
    }
    .price-qty-block .price-unit {
        font-size: 0.78rem;
        color: #b0b8c4;
    }
    .price-qty-block .price-badge {
        background: #e05c5c;
        color: white;
        font-size: 0.68rem;
        font-weight: 800;
        padding: 2px 7px;
        border-radius: 5px;
    }
    .price-qty-block .total-main {
        font-size: 1.2rem;
        font-weight: 800;
        color: #f6a623;
    }

    /* ── Badge personas viendo ── */
    .neu-watching-badge {
        background: #e8ecef;
        border-radius: 20px;
        box-shadow: 4px 4px 8px #c5c9cc, -4px -4px 8px #ffffff;
        padding: 5px 14px;
        font-size: 12px;
        font-weight: 700;
        color: rgb(14, 134, 204);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 10px;
    }
    .neu-watching-badge .watch-dot {
        width: 7px;
        height: 7px;
        background: #258bc7;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 5px #e05c5c88;
        animation: watchPulse 1.5s infinite;
        flex-shrink: 0;
    }
    @keyframes watchPulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.4); }
    }
    .shipping-pill {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: linear-gradient(135deg, #fff8ee 0%, #fff3e0 100%);
        border: 1.5px solid #fde8c0;
        border-radius: 30px;
        padding: 5px 14px 5px 10px;
        margin-top: 8px;
    }
    .shipping-pill .ship-icon {
        width: 26px;
        height: 26px;
        background: #f6a623;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .shipping-pill .ship-icon svg { display: block; }
    .shipping-pill .ship-text {
        font-size: 12px;
        color: #a07020;
        font-weight: 600;
    }
    .shipping-pill .ship-days {
        font-size: 13px;
        font-weight: 800;
        color: #f6a623;
    }

    /* ── Wishlist elegante ── */
    .wishlist-fancy {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #fff5f5;
        border: 1.5px solid #fdd8d8;
        border-radius: 30px;
        padding: 5px 14px 5px 10px;
        font-size: 13px;
        font-weight: 700;
        color: #e05c5c;
        text-decoration: none !important;
        transition: background 0.15s, border-color 0.15s, transform 0.1s;
        cursor: pointer;
    }
    .wishlist-fancy:hover {
        background: #ffe8e8;
        border-color: #e05c5c;
        color: #e05c5c !important;
        transform: translateY(-1px);
        text-decoration: none !important;
    }
    .wishlist-fancy .heart-icon {
        width: 26px;
        height: 26px;
        background: #e05c5c;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: transform 0.15s;
    }
    .wishlist-fancy:hover .heart-icon {
        transform: scale(1.15);
    }
    .wishlist-fancy .heart-icon svg { display: block; }

    /* ── Sección Compartir elegante ── */
    .share-wrapper {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-top: 20px;
        padding: 14px 18px;
        background: #f9fafb;
        border-radius: 12px;
        border: 1.5px solid #f0f0f0;
        width: fit-content;
    }
    .share-wrapper .share-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #b0b8c4;
        white-space: nowrap;
    }
    /* Íconos del aiz-share */
    .aiz-share a {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 36px !important;
        height: 36px !important;
        border-radius: 10px !important;
        background: #e8ecef !important;
        box-shadow: 4px 4px 8px #c5c9cc, -4px -4px 8px #ffffff !important;
        margin-right: 8px !important;
        transition: transform 0.15s, box-shadow 0.15s !important;
        text-decoration: none !important;
    }
    .aiz-share a:hover {
        transform: translateY(-2px) !important;
        box-shadow: 3px 3px 6px #c5c9cc, -3px -3px 6px #ffffff !important;
    }
    .aiz-share a:active {
        box-shadow: inset 3px 3px 6px #c5c9cc, inset -3px -3px 6px #ffffff !important;
        transform: scale(0.95) !important;
    }
    .aiz-share a i, .aiz-share a svg {
        font-size: 16px !important;
    }
    /* Color Facebook */
    .aiz-share a[href*="facebook"],
    .aiz-share a[title*="Facebook"],
    .aiz-share a.facebook {
        background: #1877f2 !important;
        box-shadow: 4px 4px 8px rgba(24,119,242,0.3), -4px -4px 8px #ffffff !important;
    }
    .aiz-share a[href*="facebook"] i,
    .aiz-share a[title*="Facebook"] i,
    .aiz-share a.facebook i { color: #ffffff !important; }

    /* Color WhatsApp */
    .aiz-share a[href*="whatsapp"],
    .aiz-share a[title*="WhatsApp"],
    .aiz-share a.whatsapp {
        background: #25d366 !important;
        box-shadow: 4px 4px 8px rgba(37,211,102,0.3), -4px -4px 8px #ffffff !important;
    }
    .aiz-share a[href*="whatsapp"] i,
    .aiz-share a[title*="WhatsApp"] i,
    .aiz-share a.whatsapp i { color: #ffffff !important; }

    /* ── Ocultar íconos de share excepto FB y WA ── */
    .aiz-share a[href*="twitter"],
    .aiz-share a[href*="linkedin"],
    .aiz-share a[href*="mailto"],
    .aiz-share a[title*="Twitter"],
    .aiz-share a[title*="LinkedIn"],
    .aiz-share a[title*="Email"],
    .aiz-share a[title*="Mail"],
    .aiz-share .twitter,
    .aiz-share .linkedin,
    .aiz-share .email {
        display: none !important;
    }
</style>

<div class="text-left">
    <!-- Product Name -->
    <h2 class="mb-4 fs-16 fw-700 text-dark">
        {{ $detailedProduct->getTranslation('name') }}
    </h2>

    <div class="row align-items-center mb-3">
        <!-- Review con neumorfismo -->
        @if ($detailedProduct->auction_product != 1)
            <div class="col-12">
                @php
                    $total = 0;
                    $total += $detailedProduct->reviews->count();
                @endphp
                <span class="neu-reviews">
                    <span class="rating rating-mr-1">
                        {{ renderStarRating($detailedProduct->rating) }}
                    </span>
                    <span class="opacity-50 fs-14">({{ $total }}
                        {{ translate('reviews') }})</span>
                </span>
            </div>
        @endif
        <!-- Estimate Shipping Time -->
        @if ($detailedProduct->est_shipping_days)
            <div class="col-auto mt-2">
                <span class="shipping-pill">
                    <span class="ship-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 5v4h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                        </svg>
                    </span>
                    <span class="ship-text">{{ translate('Estimate Shipping Time') }}:</span>
                    <span class="ship-days">{{ $detailedProduct->est_shipping_days }} {{ translate('Days') }}</span>
                </span>
            </div>
        @endif
        <!-- In stock -->
        @if ($detailedProduct->digital == 1)
            <div class="col-12 mt-1">
                <span class="badge badge-md badge-inline badge-pill badge-success">{{ translate('In stock') }}</span>
            </div>
        @endif
    </div>
    <div class="row align-items-center">
        @if(get_setting('product_query_activation') == 1)
            <!-- Ask about this product -->
            <div class="col-xl-3 col-lg-4 col-md-3 col-sm-4 mb-3">
                <a href="javascript:void();" onclick="goToView('product_query')" class="text-primary fs-14 fw-600 d-flex">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 32 32">
                        <g id="Group_25571" data-name="Group 25571" transform="translate(-975 -411)">
                            <g id="Path_32843" data-name="Path 32843" transform="translate(975 411)" fill="#fff">
                                <path
                                    d="M 16 31 C 11.9933500289917 31 8.226519584655762 29.43972969055176 5.393400192260742 26.60659980773926 C 2.560270071029663 23.77347946166992 1 20.00665092468262 1 16 C 1 11.9933500289917 2.560270071029663 8.226519584655762 5.393400192260742 5.393400192260742 C 8.226519584655762 2.560270071029663 11.9933500289917 1 16 1 C 20.00665092468262 1 23.77347946166992 2.560270071029663 26.60659980773926 5.393400192260742 C 29.43972969055176 8.226519584655762 31 11.9933500289917 31 16 C 31 20.00665092468262 29.43972969055176 23.77347946166992 26.60659980773926 26.60659980773926 C 23.77347946166992 29.43972969055176 20.00665092468262 31 16 31 Z"
                                    stroke="none" />
                                <path
                                    d="M 16 2 C 12.26045989990234 2 8.744749069213867 3.456249237060547 6.100500106811523 6.100500106811523 C 3.456249237060547 8.744749069213867 2 12.26045989990234 2 16 C 2 19.73954010009766 3.456249237060547 23.2552490234375 6.100500106811523 25.89949989318848 C 8.744749069213867 28.54375076293945 12.26045989990234 30 16 30 C 19.73954010009766 30 23.2552490234375 28.54375076293945 25.89949989318848 25.89949989318848 C 28.54375076293945 23.2552490234375 30 19.73954010009766 30 16 C 30 12.26045989990234 28.54375076293945 8.744749069213867 25.89949989318848 6.100500106811523 C 23.2552490234375 3.456249237060547 19.73954010009766 2 16 2 M 16 0 C 24.8365592956543 0 32 7.163440704345703 32 16 C 32 24.8365592956543 24.8365592956543 32 16 32 C 7.163440704345703 32 0 24.8365592956543 0 16 C 0 7.163440704345703 7.163440704345703 0 16 0 Z"
                                    stroke="none" fill="{{ get_setting('secondary_base_color', '#ffc519') }}" />
                            </g>
                            <path id="Path_32842" data-name="Path 32842"
                                d="M28.738,30.935a1.185,1.185,0,0,1-1.185-1.185,3.964,3.964,0,0,1,.942-2.613c.089-.095.213-.207.361-.344.735-.658,2.252-2.032,2.252-3.555a2.228,2.228,0,0,0-2.37-2.37,2.228,2.228,0,0,0-2.37,2.37,1.185,1.185,0,1,1-2.37,0,4.592,4.592,0,0,1,4.74-4.74,4.592,4.592,0,0,1,4.74,4.74c0,2.577-2.044,4.432-3.028,5.333l-.284.255a1.89,1.89,0,0,0-.243.948A1.185,1.185,0,0,1,28.738,30.935Zm0,3.561a1.185,1.185,0,0,1-.835-2.026,1.226,1.226,0,0,1,1.671,0,1.061,1.061,0,0,1,.148.184,1.345,1.345,0,0,1,.113.2,1.41,1.41,0,0,1,.065.225,1.138,1.138,0,0,1,0,.462,1.338,1.338,0,0,1-.065.219,1.185,1.185,0,0,1-.113.207,1.06,1.06,0,0,1-.148.184A1.185,1.185,0,0,1,28.738,34.5Z"
                                transform="translate(962.004 400.504)" fill="{{ get_setting('secondary_base_color', '#ffc519') }}" />
                        </g>
                    </svg>
                    <span class="ml-2 text-primary animate-underline-blue">{{ translate('Product Inquiry') }}</span>
                </a>
            </div>
        @endif
        <div class="col mb-3">
            @if ($detailedProduct->auction_product != 1)
                <div class="d-flex align-items-center flex-wrap" style="gap:10px;">
                    <!-- Add to wishlist button elegante -->
                    <a href="javascript:void(0)" onclick="addToWishList({{ $detailedProduct->id }})" class="wishlist-fancy">
                        <span class="heart-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="white" stroke="white" stroke-width="1.5">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                            </svg>
                        </span>
                        {{ translate('Add to Wishlist') }}
                    </a>
                    {{-- Add to Compare ELIMINADO --}}
                </div>
            @endif
        </div>
    </div>


    <!-- Brand Logo & Name -->
    @if ($detailedProduct->brand != null)
        <div class="d-flex flex-wrap align-items-center mb-3">
            <span class="text-secondary fs-14 fw-400 mr-4 w-50px">{{ translate('Brand') }}</span><br>
            <a href="{{ route('products.brand', $detailedProduct->brand->slug) }}"
                class="text-reset hov-text-primary fs-14 fw-700">{{ $detailedProduct->brand->name }}</a>
        </div>
    @endif

    <!-- Seller Info -->
    <div class="d-flex flex-wrap align-items-center">
        <div class="d-flex align-items-center mr-4">
            <!-- Shop Name -->
            @if ($detailedProduct->added_by == 'seller' && get_setting('vendor_system_activation') == 1)
                <span class="text-secondary fs-14 fw-400 mr-4 w-50px">{{ translate('Sold by') }}</span>
                <a href="{{ route('shop.visit', $detailedProduct->user->shop->slug) }}"
                    class="text-reset hov-text-primary fs-14 fw-700">{{ $detailedProduct->user->shop->name }}</a>
            @else
                <p class="mb-0 fs-14 fw-700">{{ translate('Inhouse product') }}</p>
            @endif
        </div>
        <!-- Messase to seller -->
        @if (get_setting('conversation_system') == 1)
            <div class="">
                <button class="btn btn-sm btn-soft-secondary-base btn-outline-secondary-base hov-svg-white hov-text-white rounded-4"
                    onclick="show_chat_modal()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"
                        class="mr-2 has-transition">
                        <g id="Group_23918" data-name="Group 23918" transform="translate(1053.151 256.688)">
                            <path id="Path_3012" data-name="Path 3012"
                                d="M134.849,88.312h-8a2,2,0,0,0-2,2v5a2,2,0,0,0,2,2v3l2.4-3h5.6a2,2,0,0,0,2-2v-5a2,2,0,0,0-2-2m1,7a1,1,0,0,1-1,1h-8a1,1,0,0,1-1-1v-5a1,1,0,0,1,1-1h8a1,1,0,0,1,1,1Z"
                                transform="translate(-1178 -341)" fill="{{ get_setting('secondary_base_color', '#ffc519') }}" />
                            <path id="Path_3013" data-name="Path 3013"
                                d="M134.849,81.312h8a1,1,0,0,1,1,1v5a1,1,0,0,1-1,1h-.5a.5.5,0,0,0,0,1h.5a2,2,0,0,0,2-2v-5a2,2,0,0,0-2-2h-8a2,2,0,0,0-2,2v.5a.5.5,0,0,0,1,0v-.5a1,1,0,0,1,1-1"
                                transform="translate(-1182 -337)" fill="{{ get_setting('secondary_base_color', '#ffc519') }}" />
                            <path id="Path_3014" data-name="Path 3014"
                                d="M131.349,93.312h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                transform="translate(-1181 -343.5)" fill="{{ get_setting('secondary_base_color', '#ffc519') }}" />
                            <path id="Path_3015" data-name="Path 3015"
                                d="M131.349,99.312h5a.5.5,0,1,1,0,1h-5a.5.5,0,1,1,0-1"
                                transform="translate(-1181 -346.5)" fill="{{ get_setting('secondary_base_color', '#ffc519') }}" />
                        </g>
                    </svg>

                    {{ translate('Message Seller') }}
                </button>
            </div>
        @endif
        <!-- Size guide con neumorfismo -->
        @php
            $sizeChartId = ($detailedProduct->main_category && $detailedProduct->main_category->sizeChart) ? $detailedProduct->main_category->sizeChart->id : 0;
            $sizeChartName = ($detailedProduct->main_category && $detailedProduct->main_category->sizeChart) ? $detailedProduct->main_category->sizeChart->name : null;
        @endphp
        <div class="ml-4">
            <a href="javascript:void(1);" onclick="showSizeChartDetail({{ $sizeChartId }}, '{{ $sizeChartName }}')" class="neu-size-guide">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
                {{ translate('Show size guide') }}
            </a>
        </div>
    </div>

    <hr>

    <!-- For auction product -->
    @if ($detailedProduct->auction_product)
        <div class="row no-gutters mb-3">
            <div class="col-sm-2">
                <div class="text-secondary fs-14 fw-400 mt-1">{{ translate('Auction Will End') }}</div>
            </div>
            <div class="col-sm-10">
                @if ($detailedProduct->auction_end_date > strtotime('now'))
                    <div class="aiz-count-down align-items-center"
                        data-date="{{ date('Y/m/d H:i:s', $detailedProduct->auction_end_date) }}"></div>
                @else
                    <p>{{ translate('Ended') }}</p>
                @endif

            </div>
        </div>

        <div class="row no-gutters mb-3">
            <div class="col-sm-2">
                <div class="text-secondary fs-14 fw-400 mt-1">{{ translate('Starting Bid') }}</div>
            </div>
            <div class="col-sm-10">
                <span class="opacity-50 fs-20">
                    {{ single_price($detailedProduct->starting_bid) }}
                </span>
                @if ($detailedProduct->unit != null)
                    <span class="opacity-70">/{{ $detailedProduct->getTranslation('unit') }}</span>
                @endif
            </div>
        </div>

        @if (Auth::check() &&
                Auth::user()->product_bids->where('product_id', $detailedProduct->id)->first() != null)
            <div class="row no-gutters mb-3">
                <div class="col-sm-2">
                    <div class="text-secondary fs-14 fw-400 mt-1">{{ translate('My Bidded Amount') }}</div>
                </div>
                <div class="col-sm-10">
                    <span class="opacity-50 fs-20">
                        {{ single_price(Auth::user()->product_bids->where('product_id', $detailedProduct->id)->first()->amount) }}
                    </span>
                </div>
            </div>
            <hr>
        @endif

        @php $highest_bid = $detailedProduct->bids->max('amount'); @endphp
        <div class="row no-gutters my-2 mb-3">
            <div class="col-sm-2">
                <div class="text-secondary fs-14 fw-400 mt-1">{{ translate('Highest Bid') }}</div>
            </div>
            <div class="col-sm-10">
                <strong class="h3 fw-600 text-primary">
                    @if ($highest_bid != null)
                        {{ single_price($highest_bid) }}
                    @endif
                </strong>
            </div>
        </div>
    @else
        <!-- Without auction product -->
        @if ($detailedProduct->wholesale_product == 1)
            <!-- Wholesale -->
            <table class="table mb-3">
                <thead>
                    <tr>
                        <th class="border-top-0">{{ translate('Min Qty') }}</th>
                        <th class="border-top-0">{{ translate('Max Qty') }}</th>
                        <th class="border-top-0">{{ translate('Unit Price') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detailedProduct->stocks->first()->wholesalePrices as $wholesalePrice)
                        <tr>
                            <td>{{ $wholesalePrice->min_qty }}</td>
                            <td>{{ $wholesalePrice->max_qty }}</td>
                            <td>{{ single_price($wholesalePrice->price) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <!-- Without Wholesale — Bloque precio/cantidad/total elegante -->
            {{-- INICIO del bloque unificado — se cierra dentro del form --}}
            <div class="price-qty-block">

                {{-- Fila Precio --}}
                <div class="pqb-row">
                    <span class="pqb-label">{{ translate('Price') }}</span>
                    <div class="pqb-value">
                        @if (home_price($detailedProduct) != home_discounted_price($detailedProduct))
                            <span class="price-main">{{ home_discounted_price($detailedProduct) }}</span>
                            <span class="price-old">{{ home_price($detailedProduct) }}</span>
                            @if ($detailedProduct->unit != null)
                                <span class="price-unit">/{{ $detailedProduct->getTranslation('unit') }}</span>
                            @endif
                            @if (discount_in_percentage($detailedProduct) > 0)
                                <span class="price-badge">-{{ discount_in_percentage($detailedProduct) }}%</span>
                            @endif
                        @else
                            <span class="price-main">{{ home_discounted_price($detailedProduct) }}</span>
                            @if ($detailedProduct->unit != null)
                                <span class="price-unit">/{{ $detailedProduct->getTranslation('unit') }}</span>
                            @endif
                        @endif
                        @if (addon_is_activated('club_point') && $detailedProduct->earn_point > 0)
                            <div class="ml-1 bg-secondary-base d-flex justify-content-center align-items-center px-3 py-1" style="width:fit-content;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12">
                                    <g transform="translate(-973 -633)">
                                        <circle cx="6" cy="6" r="6" transform="translate(973 633)" fill="#fff"/>
                                        <g transform="translate(973 633)">
                                            <path d="M7.667,3H4.333L3,5,6,9,9,5Z" fill="#f3af3d"/>
                                            <path d="M5.30,3h-1L3,5,6,9,4.331,5Z" fill="#f3af3d" opacity="0.5"/>
                                            <path d="M12.666,3h1L15,5,12,9l1.664-4Z" transform="translate(-5.995 0)" fill="#f3af3d"/>
                                        </g>
                                    </g>
                                </svg>
                                <small class="fs-11 fw-500 text-white ml-2">{{ translate('Club Point') }}: {{ $detailedProduct->earn_point }}</small>
                            </div>
                        @endif
                    </div>
                </div>

        @endif
    @endif

    @if ($detailedProduct->auction_product != 1)
        <form id="option-choice-form">
            @csrf
            <input type="hidden" name="id" value="{{ $detailedProduct->id }}">

            @if ($detailedProduct->digital == 0)
                <!-- Choice Options -->
                @if ($detailedProduct->choice_options != null)
                    @foreach (json_decode($detailedProduct->choice_options) as $key => $choice)
                        <div class="row no-gutters mb-3">
                            <div class="col-sm-2">
                                <div class="text-secondary fs-14 fw-400 mt-2 ">
                                    {{ get_single_attribute_name($choice->attribute_id) }}
                                </div>
                            </div>
                            <div class="col-sm-10">
                                <div class="aiz-radio-inline">
                                    @foreach ($choice->values as $key => $value)
                                        <label class="aiz-megabox pl-0 mr-2 mb-0">
                                            <input type="radio" name="attribute_id_{{ $choice->attribute_id }}"
                                                value="{{ $value }}"
                                                @if ($key == 0) checked @endif>
                                            <span class="aiz-megabox-elem rounded-0 d-flex align-items-center justify-content-center py-1 px-3">
                                                {{ $value }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

                <!-- Color Options -->
                @if ($detailedProduct->colors != null && count(json_decode($detailedProduct->colors)) > 0)
                    <div class="row no-gutters mb-3">
                        <div class="col-sm-2">
                            <div class="text-secondary fs-14 fw-400 mt-2">{{ translate('Color') }}</div>
                        </div>
                        <div class="col-sm-10">
                            <div class="aiz-radio-inline">
                                @foreach (json_decode($detailedProduct->colors) as $key => $color)
                                    <label class="aiz-megabox pl-0 mr-2 mb-0" data-toggle="tooltip"
                                        data-title="{{ get_single_color_name($color) }}">
                                        <input type="radio" name="color"
                                            value="{{ get_single_color_name($color) }}"
                                            @if ($key == 0) checked @endif>
                                        <span class="aiz-megabox-elem rounded-0 d-flex align-items-center justify-content-center p-1">
                                            <span class="size-25px d-inline-block rounded" style="background: {{ $color }};"></span>
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Fila Cantidad (continúa el price-qty-block abierto arriba) --}}
                <div class="pqb-row">
                    <span class="pqb-label">{{ translate('Quantity') }}</span>
                    <div class="pqb-value">
                        <div class="row no-gutters align-items-center aiz-plus-minus" style="width:130px;">
                            <button class="btn col-auto btn-icon btn-sm btn-light rounded-0" type="button" data-type="minus" data-field="quantity" disabled="">
                                <i class="las la-minus"></i>
                            </button>
                            <input type="number" name="quantity"
                                class="col border-0 text-center flex-grow-1 fs-16 input-number" placeholder="1"
                                value="{{ $detailedProduct->min_qty }}" min="{{ $detailedProduct->min_qty }}" max="10" lang="en">
                            <button class="btn col-auto btn-icon btn-sm btn-light rounded-0" type="button" data-type="plus" data-field="quantity">
                                <i class="las la-plus"></i>
                            </button>
                        </div>
                        @php
                            $qty = 0;
                            foreach ($detailedProduct->stocks as $key => $stock) {
                                $qty += $stock->qty;
                            }
                        @endphp
                        @if ($detailedProduct->stock_visibility_state == 'quantity')
                            <span class="neu-stock-badge ml-2">
                                <span id="available-quantity">{{ $qty }}</span> {{ translate('available') }}
                            </span>
                        @elseif($detailedProduct->stock_visibility_state == 'text' && $qty >= 1)
                            <span class="neu-stock-badge ml-2">
                                <span id="available-quantity">{{ translate('In Stock') }}</span>
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Fila Precio Total --}}
                <div class="pqb-row d-none" id="chosen_price_div">
                    <span class="pqb-label">{{ translate('Total Price') }}</span>
                    <div class="pqb-value">
                        <span id="chosen_price" class="total-main"></span>
                    </div>
                </div>

            </div>{{-- /price-qty-block --}}

            {{-- Badge personas viendo --}}
            <div>
                <span class="neu-watching-badge">
                    <span class="watch-dot"></span>
                    <span id="watching-count">27</span> {{ translate('personas estan viendo este producto') }}
                </span>
            </div>

            <script>
                (function() {
                    var base = [23, 25, 27, 28, 30, 32, 34, 35, 37, 38, 40, 42];
                    var el = document.getElementById('watching-count');
                    if (!el) return;
                    el.textContent = base[Math.floor(Math.random() * base.length)];

                    function scheduleNext() {
                        var delay = (Math.floor(Math.random() * 6) + 2) * 1000; // 2 a 7 seg aleatorio
                        setTimeout(function() {
                            var current = parseInt(el.textContent);
                            var change = Math.random() > 0.5 ? 1 : -1;
                            var next = current + change;
                            if (next < 18) next = 20;
                            if (next > 45) next = 42;
                            el.textContent = next;
                            scheduleNext();
                        }, delay);
                    }
                    scheduleNext();
                })();
            </script>

            @else
                <input type="hidden" name="quantity" value="1">
            @endif

        </form>
    @endif

    @if ($detailedProduct->auction_product)
        @php
            $highest_bid = $detailedProduct->bids->max('amount');
            $min_bid_amount = $highest_bid != null ? $highest_bid + 1 : $detailedProduct->starting_bid;
        @endphp
        @if ($detailedProduct->auction_end_date >= strtotime('now'))
            <div class="mt-4">
                @if (Auth::check() && $detailedProduct->user_id == Auth::user()->id)
                    <span
                        class="badge badge-inline badge-danger">{{ translate('Seller cannot Place Bid to His Own Product') }}</span>
                @else
                    <button type="button" class="btn btn-primary buy-now  fw-600 min-w-150px rounded-0"
                        onclick="bid_modal()">
                        <i class="las la-gavel"></i>
                        @if (Auth::check() &&
                                Auth::user()->product_bids->where('product_id', $detailedProduct->id)->first() != null)
                            {{ translate('Change Bid') }}
                        @else
                            {{ translate('Place Bid') }}
                        @endif
                    </button>
                @endif
            </div>
        @endif
    @else
        <!-- Add to cart & Buy now Buttons con neumorfismo -->
        <div class="mt-3 d-flex flex-wrap" style="gap:12px;">
            @if ($detailedProduct->digital == 0)
                @if ($detailedProduct->external_link != null)
                    <a type="button" class="neu-btn-cart add-to-cart"
                        href="{{ $detailedProduct->external_link }}">
                        <i class="la la-share"></i> {{ translate($detailedProduct->external_link_btn) }}
                    </a>
                @else
                    <button type="button"
                        class="neu-btn-cart add-to-cart"
                        @if (Auth::check()) onclick="addToCart()" @else onclick="showLoginModal()" @endif>
                        <i class="las la-shopping-bag"></i> {{ translate('Añadir al carrito') }}
                    </button>
                    <button type="button" class="neu-btn-buy buy-now add-to-cart"
                        @if (Auth::check()) onclick="buyNow()" @else onclick="showLoginModal()" @endif>
                        <i class="la la-shopping-cart"></i> {{ translate('Buy Now') }}
                    </button>
                @endif
                <button type="button" class="btn btn-secondary out-of-stock fw-600 d-none" disabled>
                    <i class="la la-cart-arrow-down"></i> {{ translate('Out of Stock') }}
                </button>
            @elseif ($detailedProduct->digital == 1)
                <button type="button"
                    class="neu-btn-cart add-to-cart"
                    @if (Auth::check()) onclick="addToCart()" @else onclick="showLoginModal()" @endif>
                    <i class="las la-shopping-bag"></i> {{ translate('Añadir al carrito') }}
                </button>
                <button type="button" class="neu-btn-buy buy-now add-to-cart"
                    @if (Auth::check()) onclick="buyNow()" @else onclick="showLoginModal()" @endif>
                    <i class="la la-shopping-cart"></i> {{ translate('Buy Now') }}
                </button>
            @endif
        </div>

        @if (get_setting('vendor_system_activation') != 1)
            <div class="mt-3">
                <a href="https://api.whatsapp.com/send/?phone={{ get_setting('helpline_number') }}&text={{ translate('Hello! I want more product information')}} {{ url()->current() }}" target="_blank" class="neu-btn-whatsapp col-md-6 col-lg-6 col-sm-12">
                    <i class="lab la-whatsapp" style="font-size:20px;"></i>
                    <span>{{ translate('Send Product Details to WhatsApp') }}</span>
                </a>
            </div>
        @endif

        <!-- Promote Link -->
        <div class="d-table width-100 mt-3">
            <div class="d-table-cell">
                @if (Auth::check() &&
                        addon_is_activated('affiliate_system') &&
                        get_affliate_option_status() &&
                        Auth::user()->affiliate_user != null &&
                        Auth::user()->affiliate_user->status)
                    @php
                        if (Auth::check()) {
                            if (Auth::user()->referral_code == null) {
                                Auth::user()->referral_code = substr(Auth::user()->id . Str::random(10), 0, 10);
                                Auth::user()->save();
                            }
                            $referral_code = Auth::user()->referral_code;
                            $referral_code_url = URL::to('/product') . '/' . $detailedProduct->slug . "?product_referral_code=$referral_code";
                        }
                    @endphp
                    <div>
                        <button type="button" id="ref-cpurl-btn" class="btn btn-secondary w-200px rounded-0"
                            data-attrcpy="{{ translate('Copied') }}" onclick="CopyToClipboard(this)"
                            data-url="{{ $referral_code_url }}">{{ translate('Copy the Promote Link') }}</button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Refund -->
        @php
            $refund_sticker = get_setting('refund_sticker');
        @endphp
        @if (addon_is_activated('refund_request'))
            <div class="row no-gutters mt-3">
                <div class="col-sm-2">
                    <div class="text-secondary fs-14 fw-400 mt-2">{{ translate('Refund') }}</div>
                </div>
                <div class="col-sm-10">
                    @if ($detailedProduct->refundable == 1)
                        <a href="{{ route('returnpolicy') }}" target="_blank">
                            @if ($refund_sticker != null)
                                <img src="{{ uploaded_asset($refund_sticker) }}" height="36">
                            @else
                                <img src="{{ static_asset('assets/img/refund-sticker.jpg') }}" height="36">
                            @endif
                        </a>
                        <a href="{{ route('returnpolicy') }}" class="text-blue hov-text-primary fs-14 ml-3"
                            target="_blank">{{ translate('View Policy') }}</a>
                    @else
                        <div class="text-dark fs-14 fw-400 mt-2">{{ translate('Not Applicable') }}</div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Seller Guarantees -->
        @if ($detailedProduct->digital == 1)
            @if ($detailedProduct->added_by == 'seller')
                <div class="row no-gutters mt-3">
                    <div class="col-2">
                        <div class="text-secondary fs-14 fw-400">{{ translate('Seller Guarantees') }}</div>
                    </div>
                    <div class="col-10">
                        @if ($detailedProduct->user->shop->verification_status == 1)
                            <span class="text-success fs-14 fw-700">{{ translate('Verified seller') }}</span>
                        @else
                            <span class="text-danger fs-14 fw-700">{{ translate('Non verified seller') }}</span>
                        @endif
                    </div>
                </div>
            @endif
        @endif
    @endif

    <!-- Share — solo FB y WA via CSS oculta el resto -->
    <div class="share-wrapper mt-4">
        <span class="share-label">{{ translate('Share') }}</span>
        <div class="aiz-share"></div>
    </div>
</div>
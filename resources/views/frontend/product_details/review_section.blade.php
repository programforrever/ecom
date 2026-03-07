<style>
    /* ── Sección Reseñas & Calificaciones ── */
    .review-section-wrapper {
        background: #ffffff;
        border-radius: 16px;
        border: 1.5px solid #f0f0f0;
        box-shadow: 0 4px 18px rgba(0,0,0,0.06);
        margin-bottom: 24px;
        overflow: hidden;
    }

    .review-section-header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 18px 24px;
        border-bottom: 1.5px solid #f5f5f5;
    }
    .review-section-header .header-icon {
        width: 34px;
        height: 34px;
        background: linear-gradient(135deg, #ffc519, #f6a623);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 3px 8px rgba(246,166,35,0.35);
    }
    .review-section-header h3 {
        font-size: 15px;
        font-weight: 800;
        color: #2d3748;
        margin: 0;
        letter-spacing: 0.2px;
    }

    /* ── Rating box ── */
    .rating-box {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        padding: 12px 16px;
        background: linear-gradient(135deg, #fffdf5 0%, #fff8e1 100%);
        border-radius: 10px;
        margin: 0 24px 20px;
        margin-top: 16px;
        border: 1.5px solid #fde8c0;
        max-width: 560px;
    }

    .rating-left {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .rating-left .big-score {
        font-size: 1.6rem;
        font-weight: 900;
        color: #f6a623;
        line-height: 1;
    }
    .rating-left .out-of {
        font-size: 11px;
        font-weight: 700;
        color: #c49030;
        background: #ffefc0;
        border-radius: 20px;
        padding: 2px 8px;
    }
    .rating-left .review-count-pill {
        font-size: 11px;
        font-weight: 700;
        color: #a07020;
        background: #fff3cc;
        border: 1px solid #fde8c0;
        border-radius: 20px;
        padding: 2px 8px;
    }

    /* ── Botón Calificar ── */
    .btn-rate-product {
        background: linear-gradient(135deg, #ffc519, #f6a623) !important;
        border: none !important;
        border-radius: 10px !important;
        padding: 10px 20px !important;
        font-size: 13px !important;
        font-weight: 700 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(246,166,35,0.35) !important;
        transition: transform 0.1s ease, box-shadow 0.15s ease !important;
        white-space: nowrap !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
        text-decoration: none !important;
        cursor: pointer !important;
    }
    .btn-rate-product:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 16px rgba(246,166,35,0.45) !important;
        color: #ffffff !important;
        text-decoration: none !important;
    }
    .btn-rate-product:active {
        transform: scale(0.97) !important;
    }
</style>

<div class="review-section-wrapper">

    {{-- Header --}}
    <div class="review-section-header">
        <div class="header-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="white">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
        </div>
        <h3>{{ translate('Reviews & Ratings') }}</h3>
    </div>

    {{-- Rating box --}}
    <div style="padding: 0 24px;">
        <div class="rating-box">
            <div class="rating-left">
                <span class="big-score">{{ $detailedProduct->rating }}</span>
                <span class="out-of">{{ translate('out of 5.0') }}</span>
                <span class="rating rating-mr-1">{{ renderStarRating($detailedProduct->rating) }}</span>
                @php $total = $detailedProduct->reviews->count(); @endphp
                <span class="review-count-pill">({{ $total }} {{ translate('reviews') }})</span>
            </div>

            {{-- Botón calificar --}}
            <a href="javascript:void(0);" onclick="product_review('{{ $detailedProduct->id }}')" class="btn-rate-product">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="white">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
                {{ translate('Rate this Product') }}
            </a>
        </div>
    </div>

    {{-- Reviews list --}}
    @include('frontend.product_details.reviews')

</div>
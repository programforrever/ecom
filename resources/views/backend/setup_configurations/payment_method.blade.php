@extends('backend.layouts.app')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600&family=Jost:wght@200;300;400;500&display=swap');

    :root {
        --bg: #eef0f5;
        --neu-light: #ffffff;
        --neu-dark: #d1d5de;
        --neu-shadow-light: 8px 8px 20px #d1d5de, -8px -8px 20px #ffffff;
        --neu-shadow-inset: inset 4px 4px 10px #d1d5de, inset -4px -4px 10px #ffffff;
        --neu-shadow-pressed: inset 6px 6px 14px #c8ccd5, inset -6px -6px 14px #ffffff;
        --accent: #7b8cde;
        --accent-soft: rgba(123, 140, 222, 0.15);
        --orange: #f97316;
        --orange-dark: #ea6a05;
        --orange-soft: rgba(249, 115, 22, 0.12);
        --text-primary: #1e2235;
        --text-secondary: #5a6075;
        --text-muted: #8a90a8;
        --card-width: 280px;
        --card-height: auto;
        --transition: cubic-bezier(0.4, 0, 0.2, 1);
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    .pm-wrapper {
        background: var(--bg);
        min-height: 100vh;
        padding: 20px 20px 20px;
        font-family: 'Jost', sans-serif;
    }

    /* ── Header / Logo ── */
    .pm-header {
        text-align: center;
        margin-bottom: 16px;
    }

    .pm-logo {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px;
    }

    .pm-logo-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: var(--bg);
        box-shadow: var(--neu-shadow-light);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .pm-logo-icon::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(249,115,22,0.15) 0%, rgba(251,146,60,0.08) 100%);
        border-radius: inherit;
    }

    .pm-logo-icon svg {
        width: 20px;
        height: 20px;
        position: relative;
        z-index: 1;
    }

    .pm-logo-text {
        text-align: left;
    }

    .pm-logo-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 18px;
        font-weight: 500;
        color: var(--text-primary);
        letter-spacing: 0.02em;
        line-height: 1;
    }

    .pm-logo-sub {
        font-size: 10px;
        font-weight: 300;
        color: var(--text-muted);
        letter-spacing: 0.25em;
        text-transform: uppercase;
        margin-top: 3px;
    }

    .pm-header-desc {
        font-size: 11px;
        font-weight: 300;
        color: var(--text-secondary);
        letter-spacing: 0.08em;
    }

    /* ── Carousel Track ── */
    .pm-carousel-outer {
        position: relative;
        width: 100%;
        overflow: hidden;
        padding: 12px 0 16px;
    }

    .pm-carousel-track {
        display: flex;
        gap: 16px;
        align-items: center;
        transition: transform 0.65s var(--transition);
        will-change: transform;
        padding: 4px 0;
    }

    /* ── Individual Card ── */
    .pm-card {
        min-width: var(--card-width);
        background: var(--bg);
        border-radius: 18px;
        box-shadow: var(--neu-shadow-light);
        padding: 16px 14px 14px;
        transition: transform 0.4s var(--transition), box-shadow 0.4s var(--transition), opacity 0.4s var(--transition);
        transform: scale(0.88);
        opacity: 0.55;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .pm-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: linear-gradient(90deg, transparent, var(--orange), transparent);
        opacity: 0;
        transition: opacity 0.5s ease;
        border-radius: 28px 28px 0 0;
    }

    .pm-card.active {
        transform: scale(1);
        opacity: 1;
        background: linear-gradient(145deg, #fff8f3 0%, #fff2e8 60%, #ffeedd 100%);
        box-shadow: 8px 8px 16px #d4ccc6, -8px -8px 16px #ffffff, 0 0 0 1px rgba(249,115,22,0.15);
        cursor: default;
    }

    .pm-card.active::before { opacity: 1; }

    .pm-card.active .pm-card-header {
        border-bottom-color: rgba(249, 115, 22, 0.18);
    }

    .pm-card.active .pm-card-icon {
        background: linear-gradient(145deg, #fff8f3, #ffe8d5);
        box-shadow: 5px 5px 12px #e8cfc0, -5px -5px 12px #ffffff;
    }

    .pm-card.active .pm-card-icon svg { stroke: var(--orange); }

    .pm-card.active .pm-card-title { color: #c44a00; }

    .pm-card.active .pm-card-badge {
        color: var(--orange);
        background: var(--orange-soft);
    }

    .pm-card.active .pm-label { color: #b36030; }

    .pm-card.active .pm-input {
        background: linear-gradient(145deg, #fff8f3, #fff2e8);
        box-shadow: inset 4px 4px 10px #e8d0c0, inset -4px -4px 10px #ffffff;
        color: #1e2235;
    }

    .pm-card.active .pm-input:focus {
        box-shadow: inset 4px 4px 10px #e8d0c0, inset -4px -4px 10px #ffffff, 0 0 0 2px rgba(249,115,22,0.25);
    }

    .pm-card.active .pm-toggle-label { color: #7a4020; }

    .pm-card.active .pm-switch input:checked + .pm-slider {
        background: linear-gradient(135deg, rgba(249,115,22,0.28), rgba(249,115,22,0.1));
        box-shadow: inset 4px 4px 10px #e8d0c0, inset -4px -4px 10px #ffffff, inset 0 0 0 1px rgba(249,115,22,0.3);
    }

    .pm-card.active .pm-switch input:checked + .pm-slider::before {
        background: linear-gradient(135deg, var(--orange), var(--orange-dark));
        box-shadow: 2px 2px 6px rgba(249,115,22,0.45), -1px -1px 4px rgba(255,255,255,0.9);
    }

    .pm-card.active .pm-alert {
        background: rgba(249,115,22,0.06);
        box-shadow: inset 3px 3px 8px rgba(220,140,80,0.2), inset -3px -3px 8px rgba(255,255,255,0.8);
        color: #7a4020;
    }

    .pm-card.active .pm-alert b { color: var(--orange-dark); }

    .pm-card:hover:not(.active) {
        opacity: 0.78;
        transform: scale(0.92);
    }

    /* ── Card Header ── */
    .pm-card-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
        padding-bottom: 8px;
        border-bottom: 1px solid rgba(161, 168, 190, 0.2);
    }

    .pm-card-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: var(--bg);
        box-shadow: var(--neu-shadow-light);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .pm-card-icon svg { width: 18px; height: 18px; }

    .pm-card-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 16px;
        font-weight: 500;
        color: var(--text-primary);
        letter-spacing: 0.02em;
    }

    .pm-card-badge {
        margin-left: auto;
        font-size: 9px;
        font-weight: 400;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: var(--accent);
        background: var(--accent-soft);
        padding: 4px 10px;
        border-radius: 20px;
    }

    /* ── Form Fields ── */
    .pm-field {
        margin-bottom: 8px;
    }

    .pm-label {
        display: block;
        font-size: 8px;
        font-weight: 500;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: var(--text-secondary);
        margin-bottom: 4px;
    }

    .pm-input {
        width: 100%;
        background: var(--bg);
        border: none;
        border-radius: 10px;
        box-shadow: var(--neu-shadow-inset);
        padding: 9px 12px;
        font-family: 'Jost', sans-serif;
        font-size: 13px;
        font-weight: 400;
        color: var(--text-primary);
        outline: none;
        transition: box-shadow 0.3s ease;
        letter-spacing: 0.03em;
    }

    .pm-input:focus {
        box-shadow: var(--neu-shadow-pressed), 0 0 0 2px rgba(123, 140, 222, 0.2);
    }

    .pm-input::placeholder {
        color: var(--text-muted);
        font-weight: 300;
    }

    /* ── Toggle Switch ── */
    .pm-toggle-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 6px 0;
        margin-bottom: 4px;
    }

    .pm-toggle-label {
        font-size: 11px;
        font-weight: 400;
        letter-spacing: 0.1em;
        color: var(--text-secondary);
    }

    .pm-switch {
        position: relative;
        display: inline-block;
        width: 48px;
        height: 26px;
        flex-shrink: 0;
    }

    .pm-switch input { opacity: 0; width: 0; height: 0; }

    .pm-slider {
        position: absolute;
        inset: 0;
        background: var(--bg);
        box-shadow: var(--neu-shadow-inset);
        border-radius: 26px;
        transition: 0.35s ease;
        cursor: pointer;
    }

    .pm-slider::before {
        content: '';
        position: absolute;
        width: 18px;
        height: 18px;
        left: 4px;
        top: 4px;
        background: var(--bg);
        box-shadow: 3px 3px 8px #c8ccd5, -2px -2px 6px #ffffff;
        border-radius: 50%;
        transition: 0.35s var(--transition);
    }

    .pm-switch input:checked + .pm-slider {
        background: linear-gradient(135deg, rgba(123,140,222,0.25), rgba(123,140,222,0.1));
        box-shadow: var(--neu-shadow-inset), inset 0 0 0 1px rgba(123,140,222,0.3);
    }

    .pm-switch input:checked + .pm-slider::before {
        transform: translateX(22px);
        background: linear-gradient(135deg, #7b8cde, #9ba8e8);
        box-shadow: 2px 2px 6px rgba(123,140,222,0.4), -1px -1px 4px rgba(255,255,255,0.8);
    }

    /* ── Alert Box ── */
    .pm-alert {
        background: var(--bg);
        box-shadow: var(--neu-shadow-inset);
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 11px;
        font-weight: 300;
        color: var(--text-secondary);
        line-height: 1.7;
        letter-spacing: 0.04em;
        margin-top: 8px;
    }

    .pm-alert b {
        color: var(--accent);
        font-weight: 500;
    }

    /* ── Submit Button ── */
    .pm-submit-row {
        margin-top: 12px;
        text-align: right;
    }

    .pm-btn {
        background: linear-gradient(135deg, var(--orange), var(--orange-dark));
        border: none;
        border-radius: 12px;
        box-shadow: 6px 6px 16px rgba(249,115,22,0.35), -3px -3px 10px rgba(255,255,255,0.7), 0 2px 8px rgba(249,115,22,0.25);
        padding: 10px 24px;
        font-family: 'Jost', sans-serif;
        font-size: 9px;
        font-weight: 500;
        letter-spacing: 0.22em;
        text-transform: uppercase;
        color: #ffffff;
        cursor: pointer;
        transition: box-shadow 0.25s ease, transform 0.2s ease, filter 0.2s ease;
        position: relative;
        overflow: hidden;
    }

    .pm-btn::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255,255,255,0.18) 0%, transparent 60%);
        border-radius: inherit;
        pointer-events: none;
    }

    .pm-btn:hover {
        box-shadow: 8px 8px 20px rgba(249,115,22,0.45), -3px -3px 12px rgba(255,255,255,0.8), 0 4px 14px rgba(249,115,22,0.3);
        transform: translateY(-2px);
        filter: brightness(1.06);
    }

    .pm-btn:active {
        box-shadow: 3px 3px 8px rgba(249,115,22,0.3), inset 2px 2px 6px rgba(180,60,0,0.2);
        transform: translateY(0);
        filter: brightness(0.97);
    }

    /* ── Top Navigation ── */
    .pm-nav-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        gap: 12px;
    }

    .pm-nav-top .pm-nav-btn {
        flex-shrink: 0;
    }

    /* ── Carousel Navigation ── */
    .pm-nav {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 20px;
        margin-top: 12px;
    }

    .pm-nav-btn {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: var(--bg);
        box-shadow: var(--neu-shadow-light);
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: box-shadow 0.25s ease, transform 0.2s ease;
        color: var(--text-secondary);
    }

    .pm-nav-btn:hover {
        box-shadow: 10px 10px 22px #c8ccd5, -10px -10px 22px #ffffff;
        color: var(--accent);
        transform: scale(1.05);
    }

    .pm-nav-btn:active {
        box-shadow: var(--neu-shadow-pressed);
        transform: scale(0.97);
    }

    .pm-nav-btn svg { width: 16px; height: 16px; }

    .pm-dots {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .pm-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--bg);
        box-shadow: 2px 2px 5px #c8ccd5, -2px -2px 5px #ffffff;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .pm-dot.active {
        width: 22px;
        border-radius: 3px;
        background: linear-gradient(90deg, var(--orange), #fb923c);
        box-shadow: 0 2px 6px rgba(249,115,22,0.35);
    }

    /* ── Progress indicator ── */
    .pm-progress {
        position: absolute;
        bottom: 0; left: 0;
        height: 2px;
        background: linear-gradient(90deg, var(--orange), #fb923c);
        border-radius: 0 2px 2px 0;
        transition: width 0.65s var(--transition);
    }

    /* Decorative floating orbs */
    .pm-orb {
        position: fixed;
        border-radius: 50%;
        filter: blur(60px);
        pointer-events: none;
        z-index: 0;
        opacity: 0.35;
    }
    .pm-orb-1 {
        width: 320px; height: 320px;
        background: radial-gradient(circle, rgba(249,115,22,0.1), transparent 70%);
        top: -80px; right: -60px;
    }
    .pm-orb-2 {
        width: 240px; height: 240px;
        background: radial-gradient(circle, rgba(251,146,60,0.08), transparent 70%);
        bottom: 80px; left: -40px;
    }

    .pm-wrapper > * { position: relative; z-index: 1; }

    /* Responsive */
    @media (max-width: 600px) {
        :root { --card-width: 88vw; }
        .pm-card { padding: 28px 22px 24px; }
    }
</style>

<!-- Decorative orbs -->
<div class="pm-orb pm-orb-1"></div>
<div class="pm-orb pm-orb-2"></div>

<div class="pm-wrapper">

    <!-- Header -->
    <div class="pm-header">
        <div class="pm-logo">
            <div class="pm-logo-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="url(#grad)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <defs>
                        <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#f97316"/>
                            <stop offset="100%" style="stop-color:#fb923c"/>
                        </linearGradient>
                    </defs>
                    <rect x="2" y="5" width="20" height="14" rx="3"/>
                    <path d="M2 10h20"/>
                    <path d="M6 15h4"/>
                    <path d="M14 15h4"/>
                </svg>
            </div>
            <div class="pm-logo-text">
                <div class="pm-logo-title">PayVault</div>
                <div class="pm-logo-sub">Payment Configuration</div>
            </div>
        </div>
        <p class="pm-header-desc">Configure your payment gateways with precision and ease</p>
    </div>

    <!-- Top Navigation -->
    <div class="pm-nav-top">
        <button class="pm-nav-btn" id="pmPrevTop" aria-label="Previous">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 18l-6-6 6-6"/>
            </svg>
        </button>
        <span style="flex: 1; text-align: center; font-size: 12px; color: var(--text-secondary);">Desliza o usa los botones</span>
        <button class="pm-nav-btn" id="pmNextTop" aria-label="Next">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 18l6-6-6-6"/>
            </svg>
        </button>
    </div>

    <!-- Carousel -->
    <div class="pm-carousel-outer" id="carouselOuter">
        <div class="pm-carousel-track" id="carouselTrack">

            {{-- ── 1. PayPal ── --}}
            <div class="pm-card" data-index="0">
                <div class="pm-card-header">
                    <div class="pm-card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M7 11C7 11 6.5 15 11 15.5C11 15.5 8 16 7.5 20H5L7 11Z"/>
                            <path d="M10 8C10 8 9.5 12 14 12.5C14 12.5 11 13 10.5 17H8L10 8Z"/>
                            <path d="M16 5C16 5 19 5 19 8C19 11 16 11 14 11H12L13.5 5H16Z"/>
                        </svg>
                    </div>
                    <span class="pm-card-title">{{ translate('PayPal') }}</span>
                    <span class="pm-card-badge">Gateway</span>
                </div>
                <form action="{{ route('payment_method.update') }}" method="POST">
                    <input type="hidden" name="payment_method" value="paypal">
                    @csrf
                    <input type="hidden" name="types[]" value="PAYPAL_CLIENT_ID">
                    <input type="hidden" name="types[]" value="PAYPAL_CLIENT_SECRET">
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('Client ID') }}</label>
                        <input type="text" class="pm-input" name="PAYPAL_CLIENT_ID" value="{{ env('PAYPAL_CLIENT_ID') }}" placeholder="{{ translate('Enter Client ID') }}" required>
                    </div>
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('Client Secret') }}</label>
                        <input type="text" class="pm-input" name="PAYPAL_CLIENT_SECRET" value="{{ env('PAYPAL_CLIENT_SECRET') }}" placeholder="{{ translate('Enter Client Secret') }}" required>
                    </div>
                    <div class="pm-toggle-row">
                        <span class="pm-toggle-label">{{ translate('Sandbox Mode') }}</span>
                        <label class="pm-switch">
                            <input value="1" name="paypal_sandbox" type="checkbox" @if(get_setting('paypal_sandbox') == 1) checked @endif>
                            <span class="pm-slider"></span>
                        </label>
                    </div>
                    <div class="pm-submit-row">
                        <button type="submit" class="pm-btn">{{ translate('Save') }}</button>
                    </div>
                </form>
            </div>

            {{-- ── 2. Stripe ── --}}
            <div class="pm-card" data-index="1">
                <div class="pm-card-header">
                    <div class="pm-card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="5" width="20" height="14" rx="3"/>
                            <path d="M2 10h20"/>
                            <circle cx="7" cy="15" r="1" fill="var(--accent)"/>
                        </svg>
                    </div>
                    <span class="pm-card-title">{{ translate('Stripe') }}</span>
                    <span class="pm-card-badge">Gateway</span>
                </div>
                <form action="{{ route('payment_method.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_method" value="stripe">
                    <input type="hidden" name="types[]" value="STRIPE_KEY">
                    <input type="hidden" name="types[]" value="STRIPE_SECRET">
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('Stripe Key') }}</label>
                        <input type="text" class="pm-input" name="STRIPE_KEY" value="{{ env('STRIPE_KEY') }}" placeholder="{{ translate('pk_live_...') }}" required>
                    </div>
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('Stripe Secret') }}</label>
                        <input type="text" class="pm-input" name="STRIPE_SECRET" value="{{ env('STRIPE_SECRET') }}" placeholder="{{ translate('sk_live_...') }}" required>
                    </div>
                    <div class="pm-submit-row">
                        <button type="submit" class="pm-btn">{{ translate('Save') }}</button>
                    </div>
                </form>
            </div>

            {{-- ── 3. Bkash ── --}}
            <div class="pm-card" data-index="2">
                <div class="pm-card-header">
                    <div class="pm-card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/>
                            <path d="M12 6v6l4 2"/>
                        </svg>
                    </div>
                    <span class="pm-card-title">{{ translate('Bkash') }}</span>
                    <span class="pm-card-badge">Mobile</span>
                </div>
                <form action="{{ route('payment_method.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_method" value="bkash">
                    <input type="hidden" name="types[]" value="BKASH_CHECKOUT_APP_KEY">
                    <input type="hidden" name="types[]" value="BKASH_CHECKOUT_APP_SECRET">
                    <input type="hidden" name="types[]" value="BKASH_CHECKOUT_USER_NAME">
                    <input type="hidden" name="types[]" value="BKASH_CHECKOUT_PASSWORD">
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('App Key') }}</label>
                        <input type="text" class="pm-input" name="BKASH_CHECKOUT_APP_KEY" value="{{ env('BKASH_CHECKOUT_APP_KEY') }}" placeholder="{{ translate('App Key') }}" required>
                    </div>
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('App Secret') }}</label>
                        <input type="text" class="pm-input" name="BKASH_CHECKOUT_APP_SECRET" value="{{ env('BKASH_CHECKOUT_APP_SECRET') }}" placeholder="{{ translate('App Secret') }}" required>
                    </div>
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('Username') }}</label>
                        <input type="text" class="pm-input" name="BKASH_CHECKOUT_USER_NAME" value="{{ env('BKASH_CHECKOUT_USER_NAME') }}" placeholder="{{ translate('Username') }}" required>
                    </div>
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('Password') }}</label>
                        <input type="text" class="pm-input" name="BKASH_CHECKOUT_PASSWORD" value="{{ env('BKASH_CHECKOUT_PASSWORD') }}" placeholder="{{ translate('Password') }}" required>
                    </div>
                    <div class="pm-toggle-row">
                        <span class="pm-toggle-label">{{ translate('Sandbox Mode') }}</span>
                        <label class="pm-switch">
                            <input value="1" name="bkash_sandbox" type="checkbox" @if(get_setting('bkash_sandbox') == 1) checked @endif>
                            <span class="pm-slider"></span>
                        </label>
                    </div>
                    <div class="pm-submit-row">
                        <button type="submit" class="pm-btn">{{ translate('Save') }}</button>
                    </div>
                </form>
            </div>

            {{-- ── 4. Nagad ── --}}
            <div class="pm-card" data-index="3">
                <div class="pm-card-header">
                    <div class="pm-card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 8h2a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2h-2"/>
                            <path d="M3 8h14v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8z"/>
                            <path d="M6 8V6a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                        </svg>
                    </div>
                    <span class="pm-card-title">{{ translate('Nagad') }}</span>
                    <span class="pm-card-badge">Mobile</span>
                </div>
                <form action="{{ route('payment_method.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_method" value="nagad">
                    <input type="hidden" name="types[]" value="NAGAD_MODE">
                    <input type="hidden" name="types[]" value="NAGAD_MERCHANT_ID">
                    <input type="hidden" name="types[]" value="NAGAD_MERCHANT_NUMBER">
                    <input type="hidden" name="types[]" value="NAGAD_PG_PUBLIC_KEY">
                    <input type="hidden" name="types[]" value="NAGAD_MERCHANT_PRIVATE_KEY">
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('Mode') }}</label>
                        <input type="text" class="pm-input" name="NAGAD_MODE" value="{{ env('NAGAD_MODE') }}" placeholder="live / sandbox" required>
                    </div>
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('Merchant ID') }}</label>
                        <input type="text" class="pm-input" name="NAGAD_MERCHANT_ID" value="{{ env('NAGAD_MERCHANT_ID') }}" placeholder="{{ translate('Merchant ID') }}" required>
                    </div>
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('Merchant Number') }}</label>
                        <input type="text" class="pm-input" name="NAGAD_MERCHANT_NUMBER" value="{{ env('NAGAD_MERCHANT_NUMBER') }}" placeholder="{{ translate('Merchant Number') }}" required>
                    </div>
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('PG Public Key') }}</label>
                        <input type="text" class="pm-input" name="NAGAD_PG_PUBLIC_KEY" value="{{ env('NAGAD_PG_PUBLIC_KEY') }}" placeholder="{{ translate('PG Public Key') }}" required>
                    </div>
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('Merchant Private Key') }}</label>
                        <input type="text" class="pm-input" name="NAGAD_MERCHANT_PRIVATE_KEY" value="{{ env('NAGAD_MERCHANT_PRIVATE_KEY') }}" placeholder="{{ translate('Private Key') }}" required>
                    </div>
                    <div class="pm-submit-row">
                        <button type="submit" class="pm-btn">{{ translate('Save') }}</button>
                    </div>
                </form>
            </div>

            {{-- ── 5. SSLCommerz ── --}}
            <div class="pm-card" data-index="4">
                <div class="pm-card-header">
                    <div class="pm-card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </div>
                    <span class="pm-card-title">{{ translate('SSLCommerz') }}</span>
                    <span class="pm-card-badge">Secure</span>
                </div>
                <form action="{{ route('payment_method.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_method" value="sslcommerz">
                    <input type="hidden" name="types[]" value="SSLCZ_STORE_ID">
                    <input type="hidden" name="types[]" value="SSLCZ_STORE_PASSWD">
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('Store ID') }}</label>
                        <input type="text" class="pm-input" name="SSLCZ_STORE_ID" value="{{ env('SSLCZ_STORE_ID') }}" placeholder="{{ translate('Store ID') }}" required>
                    </div>
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('Store Password') }}</label>
                        <input type="text" class="pm-input" name="SSLCZ_STORE_PASSWD" value="{{ env('SSLCZ_STORE_PASSWD') }}" placeholder="{{ translate('Store Password') }}" required>
                    </div>
                    <div class="pm-toggle-row">
                        <span class="pm-toggle-label">{{ translate('Sandbox Mode') }}</span>
                        <label class="pm-switch">
                            <input value="1" name="sslcommerz_sandbox" type="checkbox" @if(get_setting('sslcommerz_sandbox') == 1) checked @endif>
                            <span class="pm-slider"></span>
                        </label>
                    </div>
                    <div class="pm-submit-row">
                        <button type="submit" class="pm-btn">{{ translate('Save') }}</button>
                    </div>
                </form>
            </div>

            {{-- ── 6. Aamarpay ── --}}
            <div class="pm-card" data-index="5">
                <div class="pm-card-header">
                    <div class="pm-card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                            <path d="M2 17l10 5 10-5"/>
                            <path d="M2 12l10 5 10-5"/>
                        </svg>
                    </div>
                    <span class="pm-card-title">{{ translate('Aamarpay') }}</span>
                    <span class="pm-card-badge">Gateway</span>
                </div>
                <form action="{{ route('payment_method.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_method" value="aamarpay">
                    <input type="hidden" name="types[]" value="AAMARPAY_STORE_ID">
                    <input type="hidden" name="types[]" value="AAMARPAY_SIGNATURE_KEY">
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('Store ID') }}</label>
                        <input type="text" class="pm-input" name="AAMARPAY_STORE_ID" value="{{ env('AAMARPAY_STORE_ID') }}" placeholder="{{ translate('Store ID') }}" required>
                    </div>
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('Signature Key') }}</label>
                        <input type="text" class="pm-input" name="AAMARPAY_SIGNATURE_KEY" value="{{ env('AAMARPAY_SIGNATURE_KEY') }}" placeholder="{{ translate('Signature Key') }}" required>
                    </div>
                    <div class="pm-toggle-row">
                        <span class="pm-toggle-label">{{ translate('Sandbox Mode') }}</span>
                        <label class="pm-switch">
                            <input value="1" name="aamarpay_sandbox" type="checkbox" @if(get_setting('aamarpay_sandbox') == 1) checked @endif>
                            <span class="pm-slider"></span>
                        </label>
                    </div>
                    <div class="pm-submit-row">
                        <button type="submit" class="pm-btn">{{ translate('Save') }}</button>
                    </div>
                </form>
            </div>

            {{-- ── 7. Iyzico ── --}}
            <div class="pm-card" data-index="6">
                <div class="pm-card-header">
                    <div class="pm-card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 8v4l3 3"/>
                        </svg>
                    </div>
                    <span class="pm-card-title">{{ translate('Iyzico') }}</span>
                    <span class="pm-card-badge">Gateway</span>
                </div>
                <form action="{{ route('payment_method.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_method" value="iyzico">
                    <input type="hidden" name="types[]" value="IYZICO_API_KEY">
                    <input type="hidden" name="types[]" value="IYZICO_SECRET_KEY">
                    <input type="hidden" name="types[]" value="IYZICO_CURRENCY_CODE">
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('API Key') }}</label>
                        <input type="text" class="pm-input" name="IYZICO_API_KEY" value="{{ env('IYZICO_API_KEY') }}" placeholder="{{ translate('API Key') }}" required>
                    </div>
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('Secret Key') }}</label>
                        <input type="text" class="pm-input" name="IYZICO_SECRET_KEY" value="{{ env('IYZICO_SECRET_KEY') }}" placeholder="{{ translate('Secret Key') }}" required>
                    </div>
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('Currency Code') }}</label>
                        <input type="text" class="pm-input" name="IYZICO_CURRENCY_CODE" value="{{ env('IYZICO_CURRENCY_CODE') }}" placeholder="TRY" required>
                    </div>
                    <div class="pm-toggle-row">
                        <span class="pm-toggle-label">{{ translate('Sandbox Mode') }}</span>
                        <label class="pm-switch">
                            <input value="1" name="iyzico_sandbox" type="checkbox" @if(get_setting('iyzico_sandbox') == 1) checked @endif>
                            <span class="pm-slider"></span>
                        </label>
                    </div>
                    <div class="pm-submit-row">
                        <button type="submit" class="pm-btn">{{ translate('Save') }}</button>
                    </div>
                </form>
            </div>

            {{-- ── 8. Instamojo ── --}}
            <div class="pm-card" data-index="7">
                <div class="pm-card-header">
                    <div class="pm-card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                        </svg>
                    </div>
                    <span class="pm-card-title">{{ translate('Instamojo') }}</span>
                    <span class="pm-card-badge">India</span>
                </div>
                <form action="{{ route('payment_method.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_method" value="instamojo">
                    <input type="hidden" name="types[]" value="IM_API_KEY">
                    <input type="hidden" name="types[]" value="IM_AUTH_TOKEN">
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('API Key') }}</label>
                        <input type="text" class="pm-input" name="IM_API_KEY" value="{{ env('IM_API_KEY') }}" placeholder="{{ translate('API Key') }}" required>
                    </div>
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('Auth Token') }}</label>
                        <input type="text" class="pm-input" name="IM_AUTH_TOKEN" value="{{ env('IM_AUTH_TOKEN') }}" placeholder="{{ translate('Auth Token') }}" required>
                    </div>
                    <div class="pm-toggle-row">
                        <span class="pm-toggle-label">{{ translate('Sandbox Mode') }}</span>
                        <label class="pm-switch">
                            <input value="1" name="instamojo_sandbox" type="checkbox" @if(get_setting('instamojo_sandbox') == 1) checked @endif>
                            <span class="pm-slider"></span>
                        </label>
                    </div>
                    <div class="pm-submit-row">
                        <button type="submit" class="pm-btn">{{ translate('Save') }}</button>
                    </div>
                </form>
            </div>

            {{-- ── 9. PayStack ── --}}
            <div class="pm-card" data-index="8">
                <div class="pm-card-header">
                    <div class="pm-card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14"/>
                            <path d="M5 8h14"/>
                            <path d="M5 16h10"/>
                        </svg>
                    </div>
                    <span class="pm-card-title">{{ translate('PayStack') }}</span>
                    <span class="pm-card-badge">Africa</span>
                </div>
                <form action="{{ route('payment_method.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_method" value="paystack">
                    <input type="hidden" name="types[]" value="PAYSTACK_PUBLIC_KEY">
                    <input type="hidden" name="types[]" value="PAYSTACK_SECRET_KEY">
                    <input type="hidden" name="types[]" value="MERCHANT_EMAIL">
                    <input type="hidden" name="types[]" value="PAYSTACK_CURRENCY_CODE">
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('Public Key') }}</label>
                        <input type="text" class="pm-input" name="PAYSTACK_PUBLIC_KEY" value="{{ env('PAYSTACK_PUBLIC_KEY') }}" placeholder="pk_live_..." required>
                    </div>
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('Secret Key') }}</label>
                        <input type="text" class="pm-input" name="PAYSTACK_SECRET_KEY" value="{{ env('PAYSTACK_SECRET_KEY') }}" placeholder="sk_live_..." required>
                    </div>
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('Merchant Email') }}</label>
                        <input type="text" class="pm-input" name="MERCHANT_EMAIL" value="{{ env('MERCHANT_EMAIL') }}" placeholder="merchant@email.com" required>
                    </div>
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('Currency Code') }}</label>
                        <input type="text" class="pm-input" name="PAYSTACK_CURRENCY_CODE" value="{{ env('PAYSTACK_CURRENCY_CODE') }}" placeholder="NGN" required>
                    </div>
                    <div class="pm-submit-row">
                        <button type="submit" class="pm-btn">{{ translate('Save') }}</button>
                    </div>
                </form>
            </div>

            {{-- ── 10. Payhere ── --}}
            <div class="pm-card" data-index="9">
                <div class="pm-card-header">
                    <div class="pm-card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                    </div>
                    <span class="pm-card-title">{{ translate('Payhere') }}</span>
                    <span class="pm-card-badge">Sri Lanka</span>
                </div>
                <form action="{{ route('payment_method.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_method" value="payhere">
                    <input type="hidden" name="types[]" value="PAYHERE_MERCHANT_ID">
                    <input type="hidden" name="types[]" value="PAYHERE_SECRET">
                    <input type="hidden" name="types[]" value="PAYHERE_CURRENCY">
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('Merchant ID') }}</label>
                        <input type="text" class="pm-input" name="PAYHERE_MERCHANT_ID" value="{{ env('PAYHERE_MERCHANT_ID') }}" placeholder="{{ translate('Merchant ID') }}" required>
                    </div>
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('Secret') }}</label>
                        <input type="text" class="pm-input" name="PAYHERE_SECRET" value="{{ env('PAYHERE_SECRET') }}" placeholder="{{ translate('Secret') }}" required>
                    </div>
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('Currency') }}</label>
                        <input type="text" class="pm-input" name="PAYHERE_CURRENCY" value="{{ env('PAYHERE_CURRENCY') }}" placeholder="LKR" required>
                    </div>
                    <div class="pm-toggle-row">
                        <span class="pm-toggle-label">{{ translate('Sandbox Mode') }}</span>
                        <label class="pm-switch">
                            <input value="1" name="payhere_sandbox" type="checkbox" @if(get_setting('payhere_sandbox') == 1) checked @endif>
                            <span class="pm-slider"></span>
                        </label>
                    </div>
                    <div class="pm-submit-row">
                        <button type="submit" class="pm-btn">{{ translate('Save') }}</button>
                    </div>
                </form>
            </div>

            {{-- ── 11. Ngenius ── --}}
            <div class="pm-card" data-index="10">
                <div class="pm-card-header">
                    <div class="pm-card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                        </svg>
                    </div>
                    <span class="pm-card-title">{{ translate('Ngenius') }}</span>
                    <span class="pm-card-badge">UAE</span>
                </div>
                <form action="{{ route('payment_method.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_method" value="ngenius">
                    <input type="hidden" name="types[]" value="NGENIUS_OUTLET_ID">
                    <input type="hidden" name="types[]" value="NGENIUS_API_KEY">
                    <input type="hidden" name="types[]" value="NGENIUS_CURRENCY">
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('Outlet ID') }}</label>
                        <input type="text" class="pm-input" name="NGENIUS_OUTLET_ID" value="{{ env('NGENIUS_OUTLET_ID') }}" placeholder="{{ translate('Outlet ID') }}" required>
                    </div>
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('API Key') }}</label>
                        <input type="text" class="pm-input" name="NGENIUS_API_KEY" value="{{ env('NGENIUS_API_KEY') }}" placeholder="{{ translate('API Key') }}" required>
                    </div>
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('Currency') }}</label>
                        <input type="text" class="pm-input" name="NGENIUS_CURRENCY" value="{{ env('NGENIUS_CURRENCY') }}" placeholder="AED" required>
                        <div class="pm-alert">Currency must be <b>AED</b>, <b>USD</b>, or <b>EUR</b>. Defaults to <b>AED</b> if empty.</div>
                    </div>
                    <div class="pm-submit-row">
                        <button type="submit" class="pm-btn">{{ translate('Save') }}</button>
                    </div>
                </form>
            </div>

            {{-- ── 12. VoguePay ── --}}
            <div class="pm-card" data-index="11">
                <div class="pm-card-header">
                    <div class="pm-card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                    </div>
                    <span class="pm-card-title">{{ translate('VoguePay') }}</span>
                    <span class="pm-card-badge">Gateway</span>
                </div>
                <form action="{{ route('payment_method.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_method" value="voguepay">
                    <input type="hidden" name="types[]" value="VOGUE_MERCHANT_ID">
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('Merchant ID') }}</label>
                        <input type="text" class="pm-input" name="VOGUE_MERCHANT_ID" value="{{ env('VOGUE_MERCHANT_ID') }}" placeholder="{{ translate('Merchant ID') }}" required>
                    </div>
                    <div class="pm-toggle-row">
                        <span class="pm-toggle-label">{{ translate('Sandbox Mode') }}</span>
                        <label class="pm-switch">
                            <input value="1" name="voguepay_sandbox" type="checkbox" @if(get_setting('voguepay_sandbox') == 1) checked @endif>
                            <span class="pm-slider"></span>
                        </label>
                    </div>
                    <div class="pm-submit-row">
                        <button type="submit" class="pm-btn">{{ translate('Save') }}</button>
                    </div>
                </form>
            </div>

            {{-- ── 13. RazorPay ── --}}
            <div class="pm-card" data-index="12">
                <div class="pm-card-header">
                    <div class="pm-card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2l9 4.9V17L12 22l-9-5.1V6.9L12 2z"/>
                            <path d="M12 22V12"/>
                            <path d="M21 7l-9 5"/>
                            <path d="M3 7l9 5"/>
                        </svg>
                    </div>
                    <span class="pm-card-title">{{ translate('RazorPay') }}</span>
                    <span class="pm-card-badge">India</span>
                </div>
                <form action="{{ route('payment_method.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_method" value="razorpay">
                    <input type="hidden" name="types[]" value="RAZOR_KEY">
                    <input type="hidden" name="types[]" value="RAZOR_SECRET">
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('Razor Key') }}</label>
                        <input type="text" class="pm-input" name="RAZOR_KEY" value="{{ env('RAZOR_KEY') }}" placeholder="rzp_live_..." required>
                    </div>
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('Razor Secret') }}</label>
                        <input type="text" class="pm-input" name="RAZOR_SECRET" value="{{ env('RAZOR_SECRET') }}" placeholder="{{ translate('Secret') }}" required>
                    </div>
                    <div class="pm-submit-row">
                        <button type="submit" class="pm-btn">{{ translate('Save') }}</button>
                    </div>
                </form>
            </div>

            {{-- ── 14. Authorize.Net ── --}}
            <div class="pm-card" data-index="13">
                <div class="pm-card-header">
                    <div class="pm-card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </div>
                    <span class="pm-card-title">{{ translate('Authorize.Net') }}</span>
                    <span class="pm-card-badge">Secure</span>
                </div>
                <form action="{{ route('payment_method.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_method" value="authorizenet">
                    <input type="hidden" name="types[]" value="MERCHANT_LOGIN_ID">
                    <input type="hidden" name="types[]" value="MERCHANT_TRANSACTION_KEY">
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('Login ID') }}</label>
                        <input type="text" class="pm-input" name="MERCHANT_LOGIN_ID" value="{{ env('MERCHANT_LOGIN_ID') }}" placeholder="{{ translate('Login ID') }}" required>
                    </div>
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('Transaction Key') }}</label>
                        <input type="text" class="pm-input" name="MERCHANT_TRANSACTION_KEY" value="{{ env('MERCHANT_TRANSACTION_KEY') }}" placeholder="{{ translate('Transaction Key') }}" required>
                    </div>
                    <div class="pm-toggle-row">
                        <span class="pm-toggle-label">{{ translate('Sandbox Mode') }}</span>
                        <label class="pm-switch">
                            <input value="1" name="authorizenet_sandbox" type="checkbox" @if(get_setting('authorizenet_sandbox') == 1) checked @endif>
                            <span class="pm-slider"></span>
                        </label>
                    </div>
                    <div class="pm-submit-row">
                        <button type="submit" class="pm-btn">{{ translate('Save') }}</button>
                    </div>
                </form>
            </div>

            {{-- ── 15. Payku ── --}}
            <div class="pm-card" data-index="14">
                <div class="pm-card-header">
                    <div class="pm-card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                        </svg>
                    </div>
                    <span class="pm-card-title">{{ translate('Payku') }}</span>
                    <span class="pm-card-badge">Chile</span>
                </div>
                <form action="{{ route('payment_method.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_method" value="payku">
                    <input type="hidden" name="types[]" value="PAYKU_BASE_URL">
                    <input type="hidden" name="types[]" value="PAYKU_PUBLIC_TOKEN">
                    <input type="hidden" name="types[]" value="PAYKU_PRIVATE_TOKEN">
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('Base URL') }}</label>
                        <input type="text" class="pm-input" name="PAYKU_BASE_URL" value="{{ env('PAYKU_BASE_URL') }}" placeholder="https://app.payku.cl" required>
                    </div>
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('Public Token') }}</label>
                        <input type="text" class="pm-input" name="PAYKU_PUBLIC_TOKEN" value="{{ env('PAYKU_PUBLIC_TOKEN') }}" placeholder="{{ translate('Public Token') }}" required>
                    </div>
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('Private Token') }}</label>
                        <input type="text" class="pm-input" name="PAYKU_PRIVATE_TOKEN" value="{{ env('PAYKU_PRIVATE_TOKEN') }}" placeholder="{{ translate('Private Token') }}" required>
                    </div>
                    <div class="pm-submit-row">
                        <button type="submit" class="pm-btn">{{ translate('Save') }}</button>
                    </div>
                </form>
            </div>

            {{-- ── 16. Mercadopago ── --}}
            <div class="pm-card" data-index="15">
                <div class="pm-card-header">
                    <div class="pm-card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M8 12h8"/>
                            <path d="M12 8l4 4-4 4"/>
                        </svg>
                    </div>
                    <span class="pm-card-title">{{ translate('Mercadopago') }}</span>
                    <span class="pm-card-badge">LATAM</span>
                </div>
                <form action="{{ route('payment_method.update') }}" method="POST">
                    <input type="hidden" name="payment_method" value="mercadopago">
                    @csrf
                    <input type="hidden" name="types[]" value="MERCADOPAGO_KEY">
                    <input type="hidden" name="types[]" value="MERCADOPAGO_ACCESS">
                    <input type="hidden" name="types[]" value="MERCADOPAGO_CURRENCY">
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('Key') }}</label>
                        <input type="text" class="pm-input" name="MERCADOPAGO_KEY" value="{{ env('MERCADOPAGO_KEY') }}" placeholder="{{ translate('Key') }}" required>
                    </div>
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('Access Token') }}</label>
                        <input type="text" class="pm-input" name="MERCADOPAGO_ACCESS" value="{{ env('MERCADOPAGO_ACCESS') }}" placeholder="{{ translate('Access Token') }}" required>
                    </div>
                    <div class="pm-field">
                        <label class="pm-label">{{ translate('Currency') }}</label>
                        <input type="text" class="pm-input" name="MERCADOPAGO_CURRENCY" value="{{ env('MERCADOPAGO_CURRENCY') }}" placeholder="es-AR" required>
                        <div class="pm-alert">Options: <b>es-AR</b>, <b>es-CL</b>, <b>es-CO</b>, <b>es-MX</b>, <b>es-VE</b>, <b>es-UY</b>, <b>es-PE</b>, <b>pt-BR</b>. Defaults to <b>en-US</b>.</div>
                    </div>
                    <div class="pm-submit-row">
                        <button type="submit" class="pm-btn">{{ translate('Save') }}</button>
                    </div>
                </form>
            </div>

        </div>
        <div class="pm-progress" id="pmProgress"></div>
    </div>

    <!-- Navigation -->
    <div class="pm-nav">
        <button class="pm-nav-btn" id="pmPrev" aria-label="Previous">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 18l-6-6 6-6"/>
            </svg>
        </button>
        <div class="pm-dots" id="pmDots"></div>
        <button class="pm-nav-btn" id="pmNext" aria-label="Next">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 18l6-6-6-6"/>
            </svg>
        </button>
    </div>

</div>

<script>
(function() {
    const track = document.getElementById('carouselTrack');
    const outer = document.getElementById('carouselOuter');
    const cards = Array.from(track.querySelectorAll('.pm-card'));
    const dotsContainer = document.getElementById('pmDots');
    const progress = document.getElementById('pmProgress');
    const total = cards.length;
    let current = 0;
    let startX = 0;
    let isDragging = false;

    // Build dots
    cards.forEach((_, i) => {
        const dot = document.createElement('button');
        dot.className = 'pm-dot' + (i === 0 ? ' active' : '');
        dot.setAttribute('aria-label', 'Go to slide ' + (i + 1));
        dot.addEventListener('click', () => goTo(i));
        dotsContainer.appendChild(dot);
    });

    function getOffset() {
        const outerW = outer.offsetWidth;
        const cardW = cards[0].offsetWidth + 16; // card + gap
        return (outerW / 2) - (cards[0].offsetWidth / 2) - (current * cardW);
    }

    function update(animate = true) {
        if (!animate) track.style.transition = 'none';
        else track.style.transition = 'transform 0.65s cubic-bezier(0.4,0,0.2,1)';

        track.style.transform = `translateX(${getOffset()}px)`;

        cards.forEach((c, i) => {
            c.classList.toggle('active', i === current);
        });

        const dots = dotsContainer.querySelectorAll('.pm-dot');
        dots.forEach((d, i) => d.classList.toggle('active', i === current));

        const pct = ((current + 1) / total) * 100;
        progress.style.width = pct + '%';
    }

    function goTo(idx) {
        current = Math.max(0, Math.min(total - 1, idx));
        update();
    }

    // Bottom navigation buttons
    document.getElementById('pmPrev').addEventListener('click', () => goTo(current - 1));
    document.getElementById('pmNext').addEventListener('click', () => goTo(current + 1));

    // Top navigation buttons
    document.getElementById('pmPrevTop').addEventListener('click', () => goTo(current - 1));
    document.getElementById('pmNextTop').addEventListener('click', () => goTo(current + 1));

    // Click on side cards
    cards.forEach((c, i) => {
        c.addEventListener('click', () => { if (i !== current) goTo(i); });
    });

    // Touch/swipe
    outer.addEventListener('touchstart', e => { startX = e.touches[0].clientX; isDragging = true; }, { passive: true });
    outer.addEventListener('touchend', e => {
        if (!isDragging) return;
        const diff = startX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 50) diff > 0 ? goTo(current + 1) : goTo(current - 1);
        isDragging = false;
    }, { passive: true });

    // Mouse drag
    outer.addEventListener('mousedown', e => { startX = e.clientX; isDragging = true; });
    outer.addEventListener('mouseup', e => {
        if (!isDragging) return;
        const diff = startX - e.clientX;
        if (Math.abs(diff) > 50) diff > 0 ? goTo(current + 1) : goTo(current - 1);
        isDragging = false;
    });

    // Keyboard
    document.addEventListener('keydown', e => {
        if (e.key === 'ArrowLeft') goTo(current - 1);
        if (e.key === 'ArrowRight') goTo(current + 1);
    });

    // Init
    update(false);
    requestAnimationFrame(() => update(true));
    window.addEventListener('resize', () => update(false));
})();
</script>

@endsection
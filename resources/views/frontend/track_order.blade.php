@extends('frontend.layouts.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&display=swap');

    :root {
        --accent: #f97316;
        --accent-light: #fff7ed;
        --accent-border: rgba(249,115,22,0.2);
        --text: #1c1917;
        --muted: #78716c;
        --border: #e7e5e4;
        --card-bg: #ffffff;
    }

    .tracker { padding: 36px 0 60px; font-family: 'DM Sans', sans-serif; }

    .tracker-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 28px;
    }
    .tracker-head h1 {
        font-family: 'Syne', sans-serif;
        font-size: 1.9rem;
        font-weight: 800;
        color: var(--text);
        margin: 0;
    }
    .tracker-head h1 span { color: var(--accent); }

    .search-bar { display: flex; gap: 8px; flex: 1; max-width: 420px; }
    .search-bar input {
        flex: 1;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 14px;
        font-family: 'DM Sans', sans-serif;
        color: var(--text);
        transition: border-color .2s, box-shadow .2s;
    }
    .search-bar input:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(249,115,22,.12);
    }
    .search-bar button {
        background: var(--accent);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        font-family: 'Syne', sans-serif;
        font-weight: 700;
        font-size: 13px;
        cursor: pointer;
        white-space: nowrap;
        transition: background .2s, transform .15s;
    }
    .search-bar button:hover { background: #ea6c0a; transform: translateY(-1px); }

    .card-box {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 20px 22px;
        margin-bottom: 16px;
        box-shadow: 0 1px 8px rgba(0,0,0,.05);
    }
    .card-title {
        font-family: 'Syne', sans-serif;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--muted);
        margin-bottom: 14px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 7px;
    }
    .card-title i { color: var(--accent); }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--accent-light);
        border: 1px solid var(--accent-border);
        color: var(--accent);
        padding: 7px 15px;
        border-radius: 30px;
        font-family: 'Syne', sans-serif;
        font-size: 13px;
        font-weight: 700;
    }
    .live-dot {
        width: 7px; height: 7px;
        background: var(--accent);
        border-radius: 50%;
        animation: blink 1.4s infinite;
        flex-shrink: 0;
    }
    @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.25} }

    /* Timeline horizontal */
    .timeline-h {
        display: flex;
        align-items: flex-start;
        margin-top: 4px;
    }
    .tl-step {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        text-align: center;
    }
    .tl-step:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 16px;
        left: 50%;
        width: 100%;
        height: 2px;
        background: var(--border);
        z-index: 0;
    }
    .tl-step.done:not(:last-child)::after { background: var(--accent); }
    .tl-step.active:not(:last-child)::after {
        background: linear-gradient(to right, var(--accent), var(--border));
    }
    .tl-dot {
        width: 34px; height: 34px;
        border-radius: 50%;
        border: 2px solid var(--border);
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        color: #ccc;
        position: relative;
        z-index: 1;
        transition: all .3s;
    }
    .tl-step.done .tl-dot { background: var(--accent); border-color: var(--accent); color: #fff; }
    .tl-step.active .tl-dot {
        background: var(--accent-light);
        border-color: var(--accent);
        color: var(--accent);
        box-shadow: 0 0 0 5px rgba(249,115,22,.12);
    }
    .tl-label {
        font-size: 10px;
        font-weight: 600;
        color: var(--muted);
        margin-top: 6px;
        line-height: 1.3;
    }
    .tl-step.active .tl-label,
    .tl-step.done .tl-label { color: var(--text); }

    /* Info rows */
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid var(--border);
        font-size: 13px;
        gap: 10px;
    }
    .info-row:last-child { border-bottom: none; }
    .info-label { color: var(--muted); flex-shrink: 0; }
    .info-val { color: var(--text); font-weight: 500; text-align: right; }
    .info-val.accent { color: var(--accent); font-family: 'Syne', sans-serif; font-weight: 700; font-size: 15px; }

    .badge-pay {
        background: #fff7ed;
        color: var(--accent);
        border: 1px solid var(--accent-border);
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        font-family: 'Syne', sans-serif;
    }

    .addr-block {
        background: var(--accent-light);
        border: 1px solid var(--accent-border);
        border-radius: 8px;
        padding: 14px 16px;
        font-size: 13px;
        color: var(--text);
        line-height: 1.9;
    }
    .addr-block strong { color: var(--accent); }

    /* Productos scroll horizontal */
    .products-scroll {
        display: flex;
        gap: 14px;
        overflow-x: auto;
        padding-bottom: 4px;
        scrollbar-width: thin;
        scrollbar-color: var(--accent-border) transparent;
    }
    .prod-card {
        min-width: 170px;
        max-width: 170px;
        border: 1px solid var(--border);
        border-radius: 10px;
        overflow: hidden;
        flex-shrink: 0;
        background: #fff;
        transition: box-shadow .2s, transform .2s;
    }
    .prod-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,.1); transform: translateY(-2px); }
    .prod-card img { width: 100%; height: 110px; object-fit: cover; display: block; }
    .prod-ph {
        width: 100%; height: 110px;
        background: var(--accent-light);
        display: flex; align-items: center; justify-content: center;
        color: var(--accent); font-size: 26px;
    }
    .prod-body { padding: 9px 11px; }
    .prod-name { font-size: 12px; font-weight: 600; color: var(--text); margin-bottom: 5px; line-height: 1.3; }
    .prod-meta { font-size: 11px; color: var(--muted); display: flex; justify-content: space-between; }
    .prod-meta strong { color: var(--accent); }
</style>

<div class="tracker">
    <div class="container">

        {{-- Header + Search --}}
        <div class="tracker-head">
            <h1>Track Your <span>Order</span></h1>
            <form action="{{ route('orders.track') }}" method="GET" style="display:flex;">
                <div class="search-bar">
                    <input type="text" name="order_code"
                           placeholder="{{ translate('Enter order code') }}"
                           value="{{ request('order_code') }}" required>
                    <button type="submit">
                        <i class="fas fa-search"></i> {{ translate('Track') }}
                    </button>
                </div>
            </form>
        </div>

        @isset($order)
            @php
                $shippingAddress = json_decode($order->shipping_address, true);
                if (!is_array($shippingAddress)) $shippingAddress = [];
                $statuses     = ['pending','confirmed','on_the_way','delivered','cancelled'];
                $statusIcons  = ['pending'=>'fa-clock','confirmed'=>'fa-check','on_the_way'=>'fa-truck','delivered'=>'fa-box-open','cancelled'=>'fa-times'];
                $statusLabels = ['pending'=>'Pending','confirmed'=>'Confirmed','on_the_way'=>'On the Way','delivered'=>'Delivered','cancelled'=>'Cancelled'];
                $curIdx = array_search($order->delivery_status, $statuses);
                if ($curIdx === false) $curIdx = 0;
                $total = ($order->orderDetails ? $order->orderDetails->sum('price') : 0)
                       + ($order->orderDetails ? $order->orderDetails->sum('tax') : 0);
            @endphp

            {{-- Timeline --}}
            <div class="card-box">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;margin-bottom:16px;">
                    <span class="status-pill">
                        <span class="live-dot"></span>
                        {{ translate(ucfirst(str_replace('_',' ',$order->delivery_status))) }}
                    </span>
                    <span style="font-size:12px;color:var(--muted);">
                        <i class="fas fa-calendar-alt"></i> {{ date('d M Y, H:i', $order->date) }}
                    </span>
                </div>
                <div class="timeline-h">
                    @foreach($statuses as $i => $s)
                        <div class="tl-step {{ $i < $curIdx ? 'done' : ($i === $curIdx ? 'active' : '') }}">
                            <div class="tl-dot">
                                <i class="fas {{ $i <= $curIdx ? $statusIcons[$s] : 'fa-circle' }}"
                                   style="{{ $i > $curIdx ? 'font-size:5px;opacity:.3' : '' }}"></i>
                            </div>
                            <span class="tl-label">{{ translate($statusLabels[$s]) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Detalles + Dirección en dos columnas --}}
            <div class="row">
                <div class="col-md-7">
                    <div class="card-box">
                        <div class="card-title"><i class="fas fa-receipt"></i> {{ translate('Order Details') }}</div>
                        <div class="info-row">
                            <span class="info-label">{{ translate('Order Code') }}</span>
                            <span class="info-val" style="font-weight:700;">{{ $order->code }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">{{ translate('Date') }}</span>
                            <span class="info-val">{{ date('d M Y, H:i A', $order->date) }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">{{ translate('Customer') }}</span>
                            <span class="info-val">{{ $shippingAddress['name'] ?? 'N/A' }}</span>
                        </div>
                        @if($order->user_id && $order->user)
                        <div class="info-row">
                            <span class="info-label">{{ translate('Email') }}</span>
                            <span class="info-val">{{ $order->user->email }}</span>
                        </div>
                        @endif
                        <div class="info-row">
                            <span class="info-label">{{ translate('Total') }}</span>
                            <span class="info-val accent">{{ single_price($total) }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">{{ translate('Payment') }}</span>
                            <span class="info-val">
                                <span class="badge-pay">{{ translate(ucfirst(str_replace('_',' ',$order->payment_type))) }}</span>
                            </span>
                        </div>
                        @if($order->tracking_code)
                        <div class="info-row">
                            <span class="info-label">{{ translate('Tracking Code') }}</span>
                            <span class="info-val" style="font-weight:700;">{{ $order->tracking_code }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="card-box">
                        <div class="card-title"><i class="fas fa-map-marker-alt"></i> {{ translate('Shipping Address') }}</div>
                        <div class="addr-block">
                            <strong>{{ $shippingAddress['name'] ?? 'N/A' }}</strong><br>
                            {{ $shippingAddress['address'] ?? 'N/A' }}<br>
                            {{ $shippingAddress['city'] ?? '' }}{{ !empty($shippingAddress['zip']) ? ', '.$shippingAddress['zip'] : '' }}<br>
                            {{ $shippingAddress['country'] ?? 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Productos scroll horizontal --}}
            <div class="card-box">
                <div class="card-title">
                    <i class="fas fa-box-open"></i>
                    {{ translate('Products') }}
                    <span style="background:var(--accent);color:#fff;border-radius:20px;padding:1px 9px;font-size:11px;margin-left:2px;">
                        {{ $order->orderDetails->count() }}
                    </span>
                </div>
                <div class="products-scroll">
                    @forelse($order->orderDetails as $od)
                        @if($od->product)
                            <div class="prod-card">
                                @if($od->product->thumbnail_img)
                                    <img src="{{ uploaded_asset($od->product->thumbnail_img) }}"
                                         alt="{{ $od->product->getTranslation('name') }}">
                                @else
                                    <div class="prod-ph"><i class="fas fa-box"></i></div>
                                @endif
                                <div class="prod-body">
                                    <div class="prod-name">{{ $od->product->getTranslation('name') }}</div>
                                    <div class="prod-meta">
                                        <span>x{{ $od->quantity }}</span>
                                        <strong>{{ single_price($od->price) }}</strong>
                                    </div>
                                    @if($od->variation)
                                        <div style="font-size:11px;color:var(--muted);margin-top:3px;">{{ $od->variation }}</div>
                                    @endif
                                    @if($od->product->user)
                                        <div style="font-size:10px;color:var(--muted);margin-top:5px;border-top:1px solid var(--border);padding-top:5px;">
                                            <i class="fas fa-store" style="color:var(--accent);"></i> {{ $od->product->user->name }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @empty
                        <p style="color:var(--muted);font-size:13px;">{{ translate('No products found') }}</p>
                    @endforelse
                </div>
            </div>

        @endisset
    </div>
</div>
@endsection
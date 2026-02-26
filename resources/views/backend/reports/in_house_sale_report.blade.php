@extends('backend.layouts.app')

@section('content')

<style>
    :root {
        --bg: #e8edf2;
        --shadow-light: #ffffff;
        --shadow-dark: #c5cdd8;
        --text-primary: #3a4a5c;
        --text-secondary: #6b7a8d;
        --accent: #e07b3f;
        --accent-light: #f5a06a;
    }

    .neuro-page {
        background: var(--bg);
        min-height: 100vh;
        padding: 30px;
        font-family: 'Segoe UI', sans-serif;
    }

    .neuro-title {
        color: var(--text-primary);
        font-weight: 700;
        font-size: 1.4rem;
        letter-spacing: 0.5px;
        margin-bottom: 24px;
        text-shadow: 1px 1px 0 var(--shadow-light);
    }

    .neuro-panels-wrapper {
        display: flex;
        gap: 28px;
        align-items: flex-start;
    }

    .neuro-panel {
        flex: 0 0 calc(50% - 14px);
        max-width: calc(50% - 14px);
        background: var(--bg);
        border-radius: 20px;
        padding: 28px;
        box-shadow:
            8px 8px 18px var(--shadow-dark),
            -8px -8px 18px var(--shadow-light);
        overflow: visible !important;
    }

    .mobile-list { display: none; }

    /* ── Título panel con badge filtro activo ── */
    .panel-title-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .neuro-panel-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        padding-bottom: 12px;
        position: relative;
        margin-bottom: 0;
    }
    .neuro-panel-title::after {
        content: '';
        position: absolute;
        bottom: 0; left: 0;
        width: 40px; height: 3px;
        background: var(--accent);
        border-radius: 2px;
        box-shadow: 0 0 8px var(--accent-light);
    }

    /* ── Pill badge filtro activo ── */
    .filter-active-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--bg);
        border-radius: 20px;
        padding: 5px 12px 5px 10px;
        box-shadow:
            4px 4px 10px var(--shadow-dark),
            -4px -4px 10px var(--shadow-light);
        text-decoration: none;
        transition: box-shadow 0.2s ease, transform 0.2s ease;
        animation: fadeInBadge 0.3s ease;
    }
    .filter-active-badge:hover {
        box-shadow:
            2px 2px 6px var(--shadow-dark),
            -2px -2px 6px var(--shadow-light);
        transform: scale(0.97);
        text-decoration: none;
    }
    .filter-active-badge:active {
        box-shadow:
            inset 3px 3px 7px var(--shadow-dark),
            inset -3px -3px 7px var(--shadow-light);
    }
    .badge-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: var(--accent);
        box-shadow: 0 0 5px var(--accent-light);
        flex-shrink: 0;
        animation: pulse 1.5s infinite;
    }
    .badge-text {
        font-size: 0.68rem;
        font-weight: 700;
        color: var(--text-primary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
        max-width: 100px;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .badge-x {
        font-size: 0.7rem;
        font-weight: 900;
        color: var(--text-secondary);
        line-height: 1;
        flex-shrink: 0;
    }

    @keyframes fadeInBadge {
        from { opacity: 0; transform: scale(0.8); }
        to   { opacity: 1; transform: scale(1); }
    }
    @keyframes pulse {
        0%, 100% { box-shadow: 0 0 4px var(--accent-light); }
        50%       { box-shadow: 0 0 10px var(--accent); }
    }

    .neuro-form-group {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 24px;
        position: relative;
        z-index: 9999;
        flex-wrap: nowrap;
    }

    .neuro-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.6px;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .neuro-select {
        flex: 0 0 auto !important;
        width: auto !important;
        min-width: 160px;
        max-width: 200px !important;
        background: var(--bg);
        border: none;
        border-radius: 12px;
        padding: 10px 16px;
        color: var(--text-primary);
        font-size: 0.9rem;
        box-shadow:
            inset 4px 4px 10px var(--shadow-dark),
            inset -4px -4px 10px var(--shadow-light);
        outline: none;
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
    }

    /* ── bootstrap-select fix ── */
    .bootstrap-select {
        z-index: 9999 !important;
        position: relative !important;
        width: auto !important;
        min-width: 160px !important;
        max-width: 200px !important;
        flex: 0 0 auto !important;
        pointer-events: none !important;
    }
    .bootstrap-select > .dropdown-toggle,
    .bootstrap-select .dropdown-menu {
        pointer-events: auto !important;
    }
    .bootstrap-select .dropdown-menu {
        z-index: 9999 !important;
        border-radius: 12px !important;
        border: none !important;
        box-shadow:
            6px 6px 16px var(--shadow-dark),
            -6px -6px 16px var(--shadow-light) !important;
        background: var(--bg) !important;
        padding: 8px !important;
        min-width: 200px !important;
        overflow: hidden !important;
        overflow-y: auto !important;
    }
    .bootstrap-select .dropdown-menu .inner {
        overflow-x: hidden !important;
    }
    .bootstrap-select .dropdown-menu li a {
        border-radius: 8px !important;
        color: var(--text-primary) !important;
        font-size: 0.88rem !important;
        padding: 8px 12px !important;
        white-space: nowrap !important;
    }
    .bootstrap-select .dropdown-menu li a:hover,
    .bootstrap-select .dropdown-menu li.selected a {
        background: var(--shadow-dark) !important;
        color: var(--accent) !important;
    }
    .bootstrap-select > .dropdown-toggle {
        background: var(--bg) !important;
        border: none !important;
        border-radius: 12px !important;
        padding: 10px 16px !important;
        color: var(--text-primary) !important;
        font-size: 0.9rem !important;
        width: 100% !important;
        box-shadow:
            inset 4px 4px 10px var(--shadow-dark),
            inset -4px -4px 10px var(--shadow-light) !important;
    }
    .bootstrap-select > .dropdown-toggle:focus {
        outline: none !important;
        box-shadow:
            inset 4px 4px 10px var(--shadow-dark),
            inset -4px -4px 10px var(--shadow-light) !important;
    }

    .neuro-btn {
        background: var(--bg);
        border: none;
        border-radius: 12px;
        padding: 10px 22px;
        color: var(--accent);
        font-weight: 700;
        font-size: 0.88rem;
        cursor: pointer;
        flex-shrink: 0;
        box-shadow: 5px 5px 12px var(--shadow-dark), -5px -5px 12px var(--shadow-light);
        transition: all 0.15s ease;
    }
    .neuro-btn:hover  { box-shadow: 3px 3px 8px var(--shadow-dark), -3px -3px 8px var(--shadow-light); color: var(--accent-light); }
    .neuro-btn:active { box-shadow: inset 4px 4px 10px var(--shadow-dark), inset -4px -4px 10px var(--shadow-light); }

    .neuro-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 8px;
    }
    .neuro-table thead th {
        color: var(--text-secondary);
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        padding: 6px 14px;
        border: none;
        background: transparent;
    }
    .neuro-table tbody tr {
        background: var(--bg);
        box-shadow: 4px 4px 10px var(--shadow-dark), -4px -4px 10px var(--shadow-light);
        border-radius: 12px;
        transition: box-shadow 0.2s ease;
        cursor: pointer;
    }
    .neuro-table tbody tr:hover,
    .neuro-table tbody tr.active-row {
        box-shadow: 6px 6px 14px var(--shadow-dark), -6px -6px 14px var(--shadow-light);
    }
    .neuro-table tbody td {
        padding: 12px 14px;
        color: var(--text-primary);
        font-size: 0.9rem;
        border: none;
    }
    .neuro-table tbody td:first-child { border-radius: 12px 0 0 12px; color: var(--text-secondary); font-weight: 600; font-size: 0.8rem; }
    .neuro-table tbody td:last-child  { border-radius: 0 12px 12px 0; font-weight: 700; }

    .sale-badge {
        display: inline-block;
        background: var(--bg);
        border-radius: 8px;
        padding: 3px 10px;
        box-shadow: inset 3px 3px 7px var(--shadow-dark), inset -3px -3px 7px var(--shadow-light);
        font-size: 0.88rem;
    }

    .donut-left {
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }
    .donut-rank {
        font-size: 0.68rem;
        font-weight: 800;
        text-align: center;
        white-space: nowrap;
    }
    .donut-left-labels {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 4px;
        margin-top: -5px;
    }
    .donut-cat-btn {
        background: var(--bg);
        border: 1.5px solid;
        border-radius: 8px;
        padding: 3px 7px;
        font-size: 0.56rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        cursor: default;
        white-space: nowrap;
        box-shadow: 3px 3px 7px var(--shadow-dark), -3px -3px 7px var(--shadow-light);
        pointer-events: none;
        line-height: 1;
        height: fit-content;
    }
    .donut-stock-label {
        font-size: 0.56rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        white-space: nowrap;
        margin-left: 10px;
    }

    .donuts-container { position: relative; width: 100%; }
    .donut-card {
        position: absolute;
        left: 0; right: 0;
        background: var(--bg);
        border-radius: 14px;
        padding: 0 14px;
        box-shadow: inset 4px 4px 10px var(--shadow-dark), inset -4px -4px 10px var(--shadow-light);
        display: flex;
        align-items: center;
        gap: 12px;
        opacity: 0;
        transition: box-shadow 0.25s ease, transform 0.2s ease, opacity 0.4s ease;
        cursor: pointer;
        overflow: hidden;
    }
    .donut-card.visible { opacity: 1; }
    .donut-card:hover {
        box-shadow: 5px 5px 14px var(--shadow-dark), -5px -5px 14px var(--shadow-light);
        transform: translateX(3px);
    }

    .donut-wrapper { position: relative; flex-shrink: 0; }
    .donut-svg { transform: rotate(-90deg); display: block; }
    .donut-track    { fill: none; stroke: var(--shadow-dark); stroke-width: 6; }
    .donut-progress {
        fill: none; stroke-width: 6; stroke-linecap: round;
        stroke-dasharray: 138.23; stroke-dashoffset: 138.23;
        transition: stroke-dashoffset 1.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .donut-center-text {
        position: absolute; top: 50%; left: 50%;
        transform: translate(-50%,-50%); text-align: center;
    }
    .donut-number { display: block; font-size: 0.85rem; font-weight: 800; line-height: 1; }
    .donut-info { flex: 1; min-width: 0; display: flex; flex-direction: column; gap: 4px; }
    .donut-product-name { font-size: 0.83rem; font-weight: 600; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .donut-bar-track {
        width: 100%; height: 5px; border-radius: 10px;
        background: var(--bg);
        box-shadow: inset 2px 2px 4px var(--shadow-dark), inset -2px -2px 4px var(--shadow-light);
        overflow: hidden;
    }
    .donut-bar-fill { height: 100%; border-radius: 10px; width: 0%; transition: width 1.4s cubic-bezier(0.4, 0, 0.2, 1); }
    .donut-sales-label { font-size: 0.68rem; color: var(--text-secondary); font-weight: 600; }

    .neuro-pagination { margin-top: 16px; }

    /* =============================
       MÓVIL
    ============================= */
    @media (max-width: 900px) {
        .neuro-panels-wrapper { display: none; }
        .mobile-list { display: flex; flex-direction: column; gap: 16px; }

        .mobile-filter {
            background: var(--bg);
            border-radius: 20px;
            padding: 20px;
            box-shadow: 8px 8px 18px var(--shadow-dark), -8px -8px 18px var(--shadow-light);
            overflow: visible !important;
        }

        .neuro-form-group {
            flex-direction: column;
            align-items: stretch;
            gap: 10px;
        }
        .neuro-label { text-align: center; }
        .bootstrap-select,
        .neuro-select {
            max-width: 100% !important;
            width: 100% !important;
            min-width: unset !important;
        }
        .bootstrap-select > .dropdown-toggle { width: 100% !important; }
        .neuro-btn { width: 100%; text-align: center; justify-content: center; }

        .panel-title-row { flex-wrap: wrap; gap: 8px; }

        .mobile-product-block { display: flex; flex-direction: column; gap: 10px; }
        .mobile-donut-card {
            background: var(--bg);
            border-radius: 14px;
            padding: 14px 16px;
            box-shadow: inset 4px 4px 10px var(--shadow-dark), inset -4px -4px 10px var(--shadow-light);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .mobile-product-row {
            background: var(--bg);
            border-radius: 12px;
            padding: 12px 16px;
            box-shadow: 4px 4px 10px var(--shadow-dark), -4px -4px 10px var(--shadow-light);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .mobile-product-row .row-num { font-size: 0.78rem; font-weight: 700; color: var(--text-secondary); min-width: 22px; }
        .mobile-product-row .row-name { flex: 1; font-size: 0.88rem; font-weight: 600; color: var(--text-primary); padding: 0 10px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .mobile-product-row .row-badge {
            display: inline-block;
            background: var(--bg);
            border-radius: 8px;
            padding: 3px 10px;
            box-shadow: inset 3px 3px 7px var(--shadow-dark), inset -3px -3px 7px var(--shadow-light);
            font-size: 0.88rem;
            font-weight: 700;
        }
    }
</style>

@php
    $colorPalette  = ['#e07b3f','#4a9fd4','#5cba8a','#a06dd4','#d4506a','#d4b84a','#4ab8d4','#7ad45c','#d47a4a','#5c7ad4'];
    $maxSales      = $products->max('num_of_sale') ?: 1;
    $circumference = 138.23;

    /* Nombre de la categoría activa para el badge */
    $activeCategoryName = '';
    if ($sort_by) {
        $activeCat = \App\Models\Category::find($sort_by);
        if ($activeCat) $activeCategoryName = $activeCat->getTranslation('name');
    }
@endphp

<div class="neuro-page">
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h1 class="neuro-title">{{ translate('Inhouse Product sale report') }}</h1>
    </div>

    {{-- ===================== DESKTOP ===================== --}}
    <div class="neuro-panels-wrapper" id="panels-wrapper">

        <div class="neuro-panel" id="panel-table">

            {{-- Título + badge filtro activo --}}
            <div class="panel-title-row">
                <div class="neuro-panel-title">{{ translate('Product Sales') }}</div>
                @if($sort_by && $activeCategoryName)
                    <a href="{{ route('in_house_sale_report.index') }}" class="filter-active-badge">
                        <span class="badge-dot"></span>
                        <span class="badge-text">{{ $activeCategoryName }}</span>
                        <span class="badge-x">✕</span>
                    </a>
                @endif
            </div>

            <form action="{{ route('in_house_sale_report.index') }}" method="GET">
                <div class="neuro-form-group">
                    <span class="neuro-label">{{ translate('Sort by Category') }}</span>
                    <select class="neuro-select aiz-selectpicker" name="category_id">
                        <option value="">{{ translate('Choose Category') }}</option>
                        @foreach (\App\Models\Category::all() as $category)
                            <option value="{{ $category->id }}" @if($category->id == $sort_by) selected @endif>
                                {{ $category->getTranslation('name') }}
                            </option>
                        @endforeach
                    </select>
                    <button class="neuro-btn" type="submit">{{ translate('Filter') }}</button>
                </div>
            </form>

            <table class="neuro-table" id="products-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ translate('Product Name') }}</th>
                        <th>{{ translate('Num of Sale') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $key => $product)
                        @php $globalIdx = $key + ($products->currentPage() - 1) * $products->perPage(); @endphp
                        <tr data-donut="{{ $globalIdx }}" id="table-row-{{ $globalIdx }}">
                            <td>{{ $globalIdx + 1 }}</td>
                            <td>{{ $product->getTranslation('name') }}</td>
                            <td>
                                <span class="sale-badge" style="color:{{ $colorPalette[$globalIdx % count($colorPalette)] }}">
                                    {{ $product->num_of_sale }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="neuro-pagination mt-4">
                {{ $products->appends(request()->input())->links() }}
            </div>
        </div>

        <div class="neuro-panel" id="panel-donuts">
            <div class="panel-title-row">
                <div class="neuro-panel-title">{{ translate('Sales Chart') }}</div>
            </div>
            <div class="donuts-container" id="donuts-container">
                @foreach ($products as $key => $product)
                    @php
                        $globalIdx = $key + ($products->currentPage() - 1) * $products->perPage();
                        $color     = $colorPalette[$globalIdx % count($colorPalette)];
                        $pct       = $product->num_of_sale / $maxSales;
                        $offset    = round($circumference * (1 - $pct), 2);
                        $barPct    = round($pct * 100, 1);
                    @endphp
                    <div class="donut-card"
                         id="donut-card-{{ $globalIdx }}"
                         data-offset="{{ $offset }}"
                         data-bar="{{ $barPct }}"
                         data-idx="{{ $globalIdx }}"
                         data-row="table-row-{{ $globalIdx }}">

                        <div class="donut-left">
                            <span class="donut-rank" style="color:{{ $color }}">#{{ $globalIdx + 1 }}</span>
                            <div class="donut-left-labels">
                                <button class="donut-cat-btn" style="border-color:{{ $color }};color:{{ $color }}">
                                    {{ translate('Category') }}
                                </button>
                                <span class="donut-stock-label" style="color:{{ $color }}">Stock</span>
                            </div>
                        </div>

                        <div class="donut-wrapper" style="width:54px;height:54px">
                            <svg class="donut-svg" width="54" height="54" viewBox="0 0 54 54">
                                <circle class="donut-track"    cx="27" cy="27" r="22"/>
                                <circle class="donut-progress" cx="27" cy="27" r="22"
                                        id="donut-circle-{{ $globalIdx }}"
                                        style="stroke:{{ $color }};filter:drop-shadow(0 0 3px {{ $color }})"/>
                            </svg>
                            <div class="donut-center-text">
                                <span class="donut-number" style="color:{{ $color }}">{{ $product->num_of_sale }}</span>
                            </div>
                        </div>

                        <div class="donut-info">
                            <span class="donut-product-name" title="{{ $product->getTranslation('name') }}">
                                {{ $product->getTranslation('name') }}
                            </span>
                            <div class="donut-bar-track">
                                <div class="donut-bar-fill" id="donut-bar-{{ $globalIdx }}"
                                     style="background:{{ $color }};box-shadow:0 0 5px {{ $color }}"></div>
                            </div>
                            <span class="donut-sales-label">{{ $product->num_of_sale }} {{ translate('sales') }} &middot; {{ $barPct }}%</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ===================== MÓVIL ===================== --}}
    <div class="mobile-list">

        <div class="mobile-filter">
            <div class="panel-title-row">
                <div class="neuro-panel-title">{{ translate('Product Sales') }}</div>
                @if($sort_by && $activeCategoryName)
                    <a href="{{ route('in_house_sale_report.index') }}" class="filter-active-badge">
                        <span class="badge-dot"></span>
                        <span class="badge-text">{{ $activeCategoryName }}</span>
                        <span class="badge-x">✕</span>
                    </a>
                @endif
            </div>
            <form action="{{ route('in_house_sale_report.index') }}" method="GET">
                <div class="neuro-form-group">
                    <span class="neuro-label">{{ translate('Sort by Category') }}</span>
                    <select class="neuro-select aiz-selectpicker" name="category_id">
                        <option value="">{{ translate('Choose Category') }}</option>
                        @foreach (\App\Models\Category::all() as $category)
                            <option value="{{ $category->id }}" @if($category->id == $sort_by) selected @endif>
                                {{ $category->getTranslation('name') }}
                            </option>
                        @endforeach
                    </select>
                    <button class="neuro-btn" type="submit">{{ translate('Filter') }}</button>
                </div>
            </form>
        </div>

        @foreach ($products as $key => $product)
            @php
                $globalIdx = $key + ($products->currentPage() - 1) * $products->perPage();
                $color     = $colorPalette[$globalIdx % count($colorPalette)];
                $pct       = $product->num_of_sale / $maxSales;
                $offset    = round($circumference * (1 - $pct), 2);
                $barPct    = round($pct * 100, 1);
            @endphp

            <div class="mobile-product-block">
                <div class="mobile-donut-card"
                     data-mobile-offset="{{ $offset }}"
                     data-mobile-bar="{{ $barPct }}"
                     data-mobile-idx="{{ $globalIdx }}">

                    <div class="donut-left">
                        <span class="donut-rank" style="color:{{ $color }}">#{{ $globalIdx + 1 }}</span>
                        <div class="donut-left-labels">
                            <button class="donut-cat-btn" style="border-color:{{ $color }};color:{{ $color }}">
                                {{ translate('Category') }}
                            </button>
                            <span class="donut-stock-label" style="color:{{ $color }}">Stock</span>
                        </div>
                    </div>

                    <div class="donut-wrapper" style="width:54px;height:54px">
                        <svg class="donut-svg" width="54" height="54" viewBox="0 0 54 54">
                            <circle class="donut-track" cx="27" cy="27" r="22"/>
                            <circle class="donut-progress" cx="27" cy="27" r="22"
                                    id="mobile-circle-{{ $globalIdx }}"
                                    style="stroke:{{ $color }};filter:drop-shadow(0 0 3px {{ $color }})"/>
                        </svg>
                        <div class="donut-center-text">
                            <span class="donut-number" style="color:{{ $color }}">{{ $product->num_of_sale }}</span>
                        </div>
                    </div>

                    <div class="donut-info">
                        <span class="donut-product-name">{{ $product->getTranslation('name') }}</span>
                        <div class="donut-bar-track">
                            <div class="donut-bar-fill" id="mobile-bar-{{ $globalIdx }}"
                                 style="background:{{ $color }};box-shadow:0 0 5px {{ $color }}"></div>
                        </div>
                        <span class="donut-sales-label">{{ $product->num_of_sale }} {{ translate('sales') }} &middot; {{ $barPct }}%</span>
                    </div>
                </div>

                <div class="mobile-product-row">
                    <span class="row-num">{{ $globalIdx + 1 }}</span>
                    <span class="row-name">{{ $product->getTranslation('name') }}</span>
                    <span class="row-badge" style="color:{{ $color }}">{{ $product->num_of_sale }}</span>
                </div>
            </div>
        @endforeach

        <div class="neuro-pagination">
            {{ $products->appends(request()->input())->links() }}
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── Fix definitivo bootstrap-select ── */
    document.querySelectorAll('.bootstrap-select').forEach(function (bs) {
        var toggle = bs.querySelector('.dropdown-toggle');
        var menu   = bs.querySelector('.dropdown-menu');
        bs.addEventListener('click', function (e) {
            if (toggle && !toggle.contains(e.target) && menu && !menu.contains(e.target)) {
                e.stopPropagation();
                e.preventDefault();
            }
        }, true);
    });

    document.addEventListener('click', function (e) {
        document.querySelectorAll('.bootstrap-select.open').forEach(function (bs) {
            if (!bs.contains(e.target)) bs.classList.remove('open');
        });
    });

    function alignDonuts() {
        if (window.innerWidth <= 900) return;
        var container    = document.getElementById('donuts-container');
        var cards        = document.querySelectorAll('#donuts-container .donut-card');
        var containerTop = container.getBoundingClientRect().top + window.scrollY;
        var totalBottom  = 0;

        cards.forEach(function (card) {
            var row = document.getElementById(card.dataset.row);
            if (!row) return;
            var rowRect = row.getBoundingClientRect();
            var rowTop  = rowRect.top + window.scrollY - containerTop;
            var rowH    = rowRect.height;
            card.style.top    = rowTop + 'px';
            card.style.height = rowH + 'px';
            var bottom = rowTop + rowH;
            if (bottom > totalBottom) totalBottom = bottom;
        });

        container.style.height = (totalBottom + 8) + 'px';
        cards.forEach(function (card) { card.classList.add('visible'); });
    }

    requestAnimationFrame(function () {
        requestAnimationFrame(function () { alignDonuts(); });
    });
    window.addEventListener('resize', alignDonuts);

    /* Animar donas desktop */
    document.querySelectorAll('#donuts-container .donut-card').forEach(function (card, i) {
        var idx    = card.dataset.idx;
        var circle = document.getElementById('donut-circle-' + idx);
        var bar    = document.getElementById('donut-bar-' + idx);
        setTimeout(function () {
            if (circle) circle.style.strokeDashoffset = card.dataset.offset;
            if (bar)    bar.style.width = card.dataset.bar + '%';
        }, 200 + i * 100);
    });

    /* Animar donas móvil */
    document.querySelectorAll('.mobile-donut-card').forEach(function (card, i) {
        var idx    = card.dataset.mobileIdx;
        var circle = document.getElementById('mobile-circle-' + idx);
        var bar    = document.getElementById('mobile-bar-' + idx);
        setTimeout(function () {
            if (circle) circle.style.strokeDashoffset = card.dataset.mobileOffset;
            if (bar)    bar.style.width = card.dataset.mobileBar + '%';
        }, 200 + i * 100);
    });

    /* Hover cruzado desktop */
    document.querySelectorAll('.neuro-table tbody tr[data-donut]').forEach(function (row) {
        var card = document.getElementById('donut-card-' + row.dataset.donut);
        row.addEventListener('mouseenter', function () {
            if (card) card.style.boxShadow = '5px 5px 14px var(--shadow-dark), -5px -5px 14px var(--shadow-light)';
        });
        row.addEventListener('mouseleave', function () {
            if (card) card.style.boxShadow = '';
        });
        if (card) {
            card.addEventListener('mouseenter', function () { row.classList.add('active-row'); });
            card.addEventListener('mouseleave', function () { row.classList.remove('active-row'); });
        }
    });

});
</script>
@endsection

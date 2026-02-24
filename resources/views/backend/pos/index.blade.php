@extends('backend.layouts.app')

@section('content')
<section class="">
    <form class="" action="" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row gutters-5">
            <div class="col-md">

                {{-- BARRA SUPERIOR --}}
                <div class="pos-topbar mb-3">
                    <div class="pos-topbar-inner">

                        <div class="pos-field-wrap">
                            <span class="pos-field-icon">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                            </span>
                            <input class="pos-field-input" type="text" name="keyword" placeholder="{{ translate('Buscar por nombre/código') }}" onkeyup="filterProducts()">
                        </div>

                        <div class="pos-field-wrap">
                            <span class="pos-field-icon">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M4 6h16M4 12h10M4 18h6"/></svg>
                            </span>
                            <select name="poscategory" class="pos-field-select aiz-selectpicker" data-live-search="true" onchange="filterProducts()">
                                <option value="">{{ translate('Categorías') }}</option>
                                @foreach (\App\Models\Category::all() as $key => $category)
                                    <option value="category-{{ $category->id }}">{{ $category->getTranslation('name') }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="pos-field-wrap">
                            <span class="pos-field-icon">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><circle cx="7" cy="7" r="1.5" fill="currentColor"/></svg>
                            </span>
                            <select name="brand" class="pos-field-select aiz-selectpicker" data-live-search="true" onchange="filterProducts()">
                                <option value="">{{ translate('Marcas') }}</option>
                                @foreach (\App\Models\Brand::all() as $key => $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->getTranslation('name') }}</option>
                                @endforeach
                            </select>
                        </div>

                        <a href="#" class="pos-btn-ventas">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 17H5a2 2 0 0 0-2 2v0a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v0a2 2 0 0 0-2-2h-4"/><rect x="9" y="3" width="6" height="14" rx="1"/></svg>
                            {{ translate('Listado ventas') }}
                        </a>

                    </div>
                </div>

                {{-- LISTA PRODUCTOS --}}
                <div class="aiz-pos-product-list c-scrollbar-light">
                    <div class="d-flex flex-wrap" id="product-list"></div>
                   <div id="load-more" class="text-center mt-3">
                        <div class="neu-load-more btn fs-14 d-inline-block fw-600 c-pointer" onclick="loadMoreProduct()">
                            {{ translate('Loading..') }}
                        </div>
                    </div>

                </div>
            </div>

            {{-- PANEL DERECHO --}}
            <div class="col-md-auto w-md-350px w-lg-400px w-xl-500px">
                <div class="neu-card mb-3">
                    <div class="neu-card-body">
                        <div class="d-flex border-bottom pb-3">
                            <div class="flex-grow-1">
                                @php
                                    $userID = Session::has('pos.user_id') ? Session::get('pos.user_id') : null;
                                @endphp
                                <select name="user_id" class="neu-select aiz-selectpicker pos-customer" data-live-search="true" onchange="getShippingAddressUpdateCartData()" data-selected="{{ $userID }}">
                                    <option value="">{{ translate('Walk In Customer') }}</option>
                                    @foreach ($customers as $key => $customer)
                                        <option value="{{ $customer->id }}" data-contact="{{ $customer->email }}">
                                            {{ $customer->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="button" class="neu-icon-btn ml-3 mr-0" data-target="#new-customer" data-toggle="modal">
                                <i class="las la-truck"></i>
                            </button>
                        </div>

   <!-- SUBTOTAL -->
                        <div class="" id="cart-details">
                            <div class="aiz-pos-cart-list mb-4 mt-3 c-scrollbar-light">
                                @php
                                    $subtotal = 0;
                                    $tax = 0;
                                    $carts = get_pos_user_cart();
                                @endphp
                                @if (count($carts) > 0)
                                    <ul class="list-group list-group-flush">
                                    @forelse ($carts as $key => $cartItem)
                                        @php
                                            $product = $cartItem->product;
                                            $stock = $cartItem->product->stocks->where('variant', $cartItem['variation'])->first();
                                            $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
                                            $tax += cart_product_tax($cartItem, $product, false) * $cartItem['quantity'];
                                            $cartID = $cartItem['id'];
                                        @endphp
                                        <li class="list-group-item py-0 pl-2 neu-cart-item">
                                            <div class="row gutters-5 align-items-center">
                                                <div class="col-auto w-60px">
                                                    <div class="row no-gutters align-items-center flex-column aiz-plus-minus">
                                                        <button class="btn col-auto btn-icon btn-sm fs-15 neu-qty-btn" type="button" data-type="plus" data-field="qty-{{ $cartID }}" @if($product->digital == 1) disabled @endif>
                                                            <i class="las la-plus"></i>
                                                        </button>
                                                        <input type="text" name="qty-{{ $cartID }}" id="qty-{{ $cartID }}" class="col border-0 text-center flex-grow-1 fs-16 input-number neu-qty-input" placeholder="1" value="{{ $cartItem['quantity'] }}" min="{{ $product->min_qty }}" max="{{ $stock->qty }}" onchange="updateQuantity({{ $cartID }})">
                                                        <button class="btn col-auto btn-icon btn-sm fs-15 neu-qty-btn" type="button" data-type="minus" data-field="qty-{{ $cartID }}" @if($product->digital == 1) disabled @endif>
                                                            <i class="las la-minus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="text-truncate-2 neu-product-name">{{ $product->name }}</div>
                                                    <span class="badge badge-inline fs-12 neu-variant-badge">{{ $cartItem['variant'] }}</span>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="fs-12 opacity-60">{{ single_price($cartItem['price']) }} x {{ $cartItem['quantity'] }}</div>
                                                    <div class="fs-15 fw-600 neu-price">{{ single_price($cartItem['price']*$cartItem['quantity']) }}</div>
                                                </div>
                                                <div class="col-auto">
                                                    <button type="button" class="neu-delete-btn ml-2 mr-0" onclick="removeFromCart({{ $cartItem->id }})">
                                                        <i class="las la-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="list-group-item">
                                            <div class="text-center py-3">
                                                <i class="las la-frown la-3x neu-empty-icon"></i>
                                                <p class="neu-empty-text mt-2">{{ translate('No Product Added') }}</p>
                                            </div>
                                        </li>
                                    @endforelse
                                    </ul>
                                @else
                                    <div class="text-center py-3">
                                        <i class="las la-frown la-3x neu-empty-icon"></i>
                                        <p class="neu-empty-text mt-2">{{ translate('No Product Added') }}</p>
                                    </div>
                                @endif
                            </div>

                            {{-- TOTALES --}}
                            <div class="neu-totals">
                                <div class="d-flex justify-content-between neu-total-row">
                                    <span>{{ translate('Sub Total') }}</span>
                                    <span>{{ single_price($subtotal) }}</span>
                                </div>
                                <div class="d-flex justify-content-between neu-total-row">
                                    <span>{{ translate('Tax') }}</span>
                                    <span>{{ single_price($tax) }}</span>
                                </div>
                                <div class="d-flex justify-content-between neu-total-row">
                                    <span>{{ translate('Shipping') }}</span>
                                    <span>{{ single_price(Session::get('pos.shipping', 0)) }}</span>
                                </div>
                                <div class="d-flex justify-content-between neu-total-row">
                                    <span>{{ translate('Discount') }}</span>
                                    <span>{{ single_price(Session::get('pos.discount', 0)) }}</span>
                                </div>
                                <div class="d-flex justify-content-between neu-total-final">
                                    <span>{{ translate('Total') }}</span>
                                    <span>{{ single_price($subtotal + $tax + Session::get('pos.shipping', 0) - Session::get('pos.discount', 0)) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="pos-footer mar-btm">
                    <div class="d-flex flex-column flex-md-row justify-content-between">
                        <div class="d-flex">
                            <div class="dropdown mr-3 ml-0 dropup">
                                <button class="neu-footer-btn dropdown-toggle" type="button" data-toggle="dropdown">
                                    {{ translate('Shipping') }}
                                </button>
                                <div class="dropdown-menu p-3 dropdown-menu-lg">
                                    <div class="input-group">
                                        <input type="number" min="0" placeholder="Amount" name="shipping" class="form-control" value="{{ Session::get('pos.shipping', 0) }}" required onchange="setShipping()">
                                        <div class="input-group-append">
                                            <span class="input-group-text">{{ translate('Flat') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown dropup">
                                <button class="neu-footer-btn dropdown-toggle" type="button" data-toggle="dropdown">
                                    {{ translate('Discount') }}
                                </button>
                                <div class="dropdown-menu p-3 dropdown-menu-lg">
                                    <div class="input-group">
                                        <input type="number" min="0" placeholder="Amount" name="discount" class="form-control" value="{{ Session::get('pos.discount', 0) }}" required onchange="setDiscount()">
                                        <div class="input-group-append">
                                            <span class="input-group-text">{{ translate('Flat') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="my-2 my-md-0">
                            <button type="button" class="neu-place-order-btn btn-block" onclick="orderConfirmation()">
                                {{ translate('Place Order') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>

<style>
    /* ══ MODAL SHIPPING ADDRESS ══ */
#new-customer .modal-content {
    background    : var(--nb) !important;
    border-radius : 20px !important;
    border        : none !important;
    box-shadow    : 12px 12px 30px rgba(163,177,198,.6),
                    -8px -8px 24px rgba(255,255,255,.95) !important;
    overflow      : hidden !important;
}

#new-customer .modal-header {
    background    : var(--nb) !important;
    border-bottom : 1px solid rgba(163,177,198,.3) !important;
    padding       : 18px 24px !important;
}

#new-customer .modal-title {
    font-family   : 'Poppins', sans-serif !important;
    font-weight   : 700 !important;
    font-size     : 15px !important;
    color         : var(--text) !important;
}

#new-customer .modal-body {
    background    : var(--nb) !important;
    padding       : 20px 24px !important;
}

#new-customer .modal-footer {
    background    : var(--nb) !important;
    border-top    : 1px solid rgba(163,177,198,.3) !important;
    padding       : 14px 24px !important;
    gap           : 10px !important;
}

/* Labels */
#new-customer label {
    font-family   : 'Poppins', sans-serif !important;
    font-size     : 13px !important;
    font-weight   : 600 !important;
    color         : var(--text) !important;
}

/* Inputs y selects */
#new-customer .form-control {
    background    : var(--nb) !important;
    box-shadow    : var(--ni) !important;
    border        : none !important;
    border-radius : var(--rs) !important;
    color         : var(--text) !important;
    font-family   : 'Poppins', sans-serif !important;
    font-size     : 13px !important;
    padding       : 10px 14px !important;
    transition    : box-shadow .25s !important;
}
#new-customer .form-control:focus {
    box-shadow    : inset 5px 5px 12px rgba(163,177,198,0.6),
                    inset -5px -5px 12px rgba(255,255,255,0.9),
                    0 0 0 2px rgba(99,102,241,.2) !important;
    outline       : none !important;
}
#new-customer .form-control::placeholder {
    color         : var(--text-soft) !important;
}

/* Botón Cerrar */
#new-customer .btn-base-3 {
    padding       : 9px 18px !important;
    border-radius : var(--rs) !important;
    background    : var(--nb) !important;
    box-shadow    : var(--ns) !important;
    border        : none !important;
    color         : var(--text) !important;
    font-family   : 'Poppins', sans-serif !important;
    font-size     : 13px !important;
    font-weight   : 600 !important;
    transition    : all .25s !important;
}
#new-customer .btn-base-3:hover {
    box-shadow    : 8px 8px 20px rgba(163,177,198,.5),
                    -4px -4px 14px rgba(255,255,255,1) !important;
    transform     : translateY(-2px) !important;
}

/* Botón Confirmar */
#new-customer .btn-base-1 {
    padding       : 9px 18px !important;
    border-radius : var(--rs) !important;
    background    : linear-gradient(135deg, #f97316, #fb923c) !important;
    box-shadow    : 4px 4px 12px rgba(249,115,22,.35) !important;
    border        : none !important;
    color         : #fff !important;
    font-family   : 'Poppins', sans-serif !important;
    font-size     : 13px !important;
    font-weight   : 600 !important;
    transition    : all .25s !important;
}
#new-customer .btn-base-1:hover {
    box-shadow    : 6px 6px 16px rgba(249,115,22,.5) !important;
    transform     : translateY(-2px) !important;
}
#new-customer .btn-base-1:active {
    box-shadow    : inset 3px 3px 8px rgba(0,0,0,.15) !important;
    transform     : scale(0.98) !important;
}
:root {
    --nb        : #e8edf2;
    --ns        : 6px 6px 18px rgba(163,177,198,0.5), -6px -6px 18px rgba(255,255,255,0.9);
    --ni        : inset 4px 4px 10px rgba(163,177,198,0.45), inset -4px -4px 10px rgba(255,255,255,0.85);
    --accent    : #f97316;
    --accent2   : #6366f1;
    --text      : #4a5568;
    --text-soft : #8a9bb0;
    --r         : 14px;
    --rs        : 10px;
}

/* ══ TOPBAR ══ */
.pos-topbar {
    background    : var(--nb);
    border-radius : 16px;
    box-shadow    : var(--ns);
    padding       : 10px 14px;
    display       : inline-block;  /* solo ocupa lo necesario */
    width         : 100%;
}
.pos-topbar-inner {
    display       : flex;
    align-items   : center;
    gap           : 8px;
    flex-wrap     : nowrap;
}

/* Cada campo solo ocupa su contenido */
.pos-field-wrap {
    position      : relative;
    flex          : 0 0 auto;  /* NO se estira */
}
.pos-field-icon {
    position      : absolute;
    left          : 9px;
    top           : 50%;
    transform     : translateY(-50%);
    color         : var(--text-soft);
    pointer-events: none;
    display       : flex;
    z-index       : 1;
}
.pos-field-input {
    width         : 260px;  /* ancho fijo razonable para el buscador */
    padding       : 8px 8px 8px 26px;
    border        : 1.5px solid rgba(163,177,198,0.45);
    border-radius : var(--rs);
    background    : #fff;
    color         : var(--text);
    font-size     : 12px;
    font-family   : 'Poppins', sans-serif;
    outline       : none;
    transition    : border-color .2s;
}
.pos-field-input:focus { border-color: rgba(99,102,241,.5); }
.pos-field-input::placeholder { color: var(--text-soft); font-size: 11.5px; }

.pos-field-select {
    width         : 120px;  /* ancho fijo para categorías y marcas */
    padding       : 8px 8px 8px 26px;
    border        : 1.5px solid rgba(163,177,198,0.45);
    border-radius : var(--rs);
    background    : #fff;
    color         : var(--text);
    font-size     : 12px;
    font-family   : 'Poppins', sans-serif;
    outline       : none;
    appearance    : none;
    cursor        : pointer;
    transition    : border-color .2s;
}
.pos-field-select:focus { border-color: rgba(99,102,241,.5); }

/* Botón ver ventas */
.pos-btn-ventas {
    flex             : 0 0 auto;
    display          : inline-flex;
    align-items      : center;
    gap              : 5px;
    padding          : 8px 12px;
    border-radius    : var(--rs);
    background       : linear-gradient(135deg, #f97316, #fb923c);
    box-shadow       : 4px 4px 12px rgba(249,115,22,.35);
    color            : #fff !important;
    font-size        : 11.5px;
    font-weight      : 600;
    font-family      : 'Poppins', sans-serif;
    white-space      : nowrap;
    text-decoration  : none;
    transition       : all .25s;
}
.pos-btn-ventas:hover {
    box-shadow       : 6px 6px 16px rgba(249,115,22,.45);
    transform        : translateY(-2px);
    text-decoration  : none;
    color            : #fff !important;
}
.pos-btn-ventas:active {
    box-shadow       : inset 3px 3px 8px rgba(0,0,0,.15);
    transform        : scale(0.98);
}

/* ══ GRID PRODUCTOS ══ */
#product-list {
    display               : grid !important;
    grid-template-columns : repeat(4, 1fr) !important;
    gap                   : 16px;
    justify-content       : flex-start;
}

/* ══ CARGAR MÁS ══ */
.neu-load-more {
    padding       : 10px 28px;
    border-radius : var(--rs);
    background    : var(--nb);
    box-shadow    : var(--ns);
    color         : var(--accent2);
    font-family   : 'Poppins', sans-serif;
    font-size     : 13px;
    transition    : all .25s;
    display       : inline-block;
}
.neu-load-more:hover {
    box-shadow    : 8px 8px 22px rgba(163,177,198,.55), -6px -6px 18px rgba(255,255,255,1);
    transform     : translateY(-2px);
}

/* ══ PANEL DERECHO ══ */
.neu-card {
    background    : var(--nb);
    border-radius : var(--r);
    box-shadow    : var(--ns);
    border        : none;
}
.neu-card-body { padding: 1.25rem; }

.neu-select {
    width         : 100%;
    padding       : 10px 10px 10px 12px;
    border        : 1.5px solid rgba(163,177,198,0.4);
    border-radius : var(--rs);
    background    : #fff;
    color         : var(--text);
    font-size     : 13px;
    font-family   : 'Poppins', sans-serif;
    outline       : none;
    appearance    : none;
    cursor        : pointer;
    transition    : border-color .2s;
}
.neu-select:focus { border-color: rgba(99,102,241,.5); }

.neu-icon-btn {
    width            : 40px;
    height           : 40px;
    border-radius    : var(--rs);
    background       : var(--nb);
    box-shadow       : var(--ns);
    border           : none;
    display          : inline-flex;
    align-items      : center;
    justify-content  : center;
    color            : var(--text);
    font-size        : 17px;
    cursor           : pointer;
    transition       : all .25s;
    flex-shrink      : 0;
}
.neu-icon-btn:hover { box-shadow: 8px 8px 20px rgba(163,177,198,.5), -4px -4px 14px rgba(255,255,255,1); transform: translateY(-2px); }
.neu-icon-btn:active { box-shadow: var(--ni); }

/* ══ CARRITO ══ */
.neu-cart-item {
    background    : var(--nb) !important;
    border-radius : var(--rs) !important;
    box-shadow    : var(--ns) !important;
    margin-bottom : 10px !important;
    border        : none !important;
    padding       : 10px 12px !important;
}
.neu-qty-btn {
    background    : var(--nb) !important;
    box-shadow    : var(--ns) !important;
    border-radius : 8px !important;
    border        : none !important;
    color         : var(--text) !important;
    width         : 26px !important;
    height        : 26px !important;
    padding       : 0 !important;
    transition    : all .2s !important;
}
.neu-qty-btn:hover { box-shadow: 4px 4px 10px rgba(163,177,198,.5), -2px -2px 8px rgba(255,255,255,1) !important; }
.neu-qty-btn:active { box-shadow: var(--ni) !important; }
.neu-qty-input { background: transparent !important; color: var(--text) !important; font-weight: 600 !important; }
.neu-product-name { font-size: 13px; color: var(--text); font-family: 'Poppins', sans-serif; font-weight: 500; }
.neu-variant-badge {
    background    : var(--nb) !important;
    box-shadow    : var(--ni) !important;
    color         : var(--text-soft) !important;
    border        : none !important;
    font-size     : 10px !important;
    border-radius : 6px !important;
    padding       : 2px 8px !important;
}
.neu-price { color: var(--accent) !important; font-family: 'Poppins', sans-serif; }
.neu-delete-btn {
    width            : 30px;
    height           : 30px;
    border-radius    : 50%;
    background       : var(--nb);
    box-shadow       : var(--ns);
    border           : none;
    display          : inline-flex;
    align-items      : center;
    justify-content  : center;
    color            : #f43f5e;
    font-size        : 14px;
    cursor           : pointer;
    transition       : all .25s;
}
.neu-delete-btn:hover { box-shadow: 4px 4px 12px rgba(244,63,94,.25), -2px -2px 8px rgba(255,255,255,1); transform: scale(1.1); }
.neu-delete-btn:active { box-shadow: var(--ni); }
.neu-empty-icon { color: var(--text-soft); }
.neu-empty-text { color: var(--text-soft); font-family: 'Poppins', sans-serif; font-size: 13px; }

/* ══ TOTALES ══ */
.neu-totals {
    background    : var(--nb);
    border-radius : var(--r);
    box-shadow    : var(--ni);
    padding       : 14px 16px;
}
.neu-total-row { font-size: 13px; font-family: 'Poppins', sans-serif; color: var(--text-soft); font-weight: 600; margin-bottom: 8px; }
.neu-total-final {
    font-size     : 17px;
    font-family   : 'Poppins', sans-serif;
    font-weight   : 700;
    color         : var(--text);
    border-top    : 1px solid rgba(163,177,198,0.3);
    padding-top   : 10px;
    margin-top    : 4px;
}
.neu-total-final span:last-child { color: var(--accent); }

/* ══ FOOTER ══ */
.neu-footer-btn {
    padding       : 10px 16px;
    border-radius : var(--rs);
    background    : var(--nb);
    box-shadow    : var(--ns);
    border        : none;
    color         : var(--text);
    font-size     : 13px;
    font-weight   : 600;
    font-family   : 'Poppins', sans-serif;
    cursor        : pointer;
    transition    : all .25s;
}
.neu-footer-btn:hover { box-shadow: 8px 8px 20px rgba(163,177,198,.5), -4px -4px 14px rgba(255,255,255,1); transform: translateY(-2px); }
.neu-footer-btn:active { box-shadow: var(--ni); transform: scale(0.98); }

.neu-place-order-btn {
    padding       : 12px 24px;
    border-radius : var(--rs);
    background    : linear-gradient(135deg, #f97316, #fb923c);
    box-shadow    : 4px 4px 14px rgba(249,115,22,.4);
    border        : none;
    color         : #fff;
    font-size     : 14px;
    font-weight   : 700;
    font-family   : 'Poppins', sans-serif;
    cursor        : pointer;
    transition    : all .25s;
    width         : 100%;
}
.neu-place-order-btn:hover { box-shadow: 6px 6px 18px rgba(249,115,22,.5); transform: translateY(-2px); }
.neu-place-order-btn:active { box-shadow: inset 3px 3px 8px rgba(0,0,0,.15); transform: scale(0.98); }

/* ══ DARK MODE ══ */
body.dark-mode, .dark {
    --nb        : #2c2c2e;
    --ns        : 6px 6px 18px #1e1e20, -6px -6px 18px #3a3a3c;
    --ni        : inset 4px 4px 10px #1e1e20, inset -4px -4px 10px #3a3a3c;
    --text      : #d1d5db;
    --text-soft : #6b7280;
}
/* ══ TARJETAS DE PRODUCTO ══ */
#product-list .aiz-pos-product,
#product-list > div,
#product-list > li {
    border-radius : 16px !important;
    background    : var(--nb) !important;
    box-shadow    : var(--ns) !important;
    border        : none !important;
    overflow      : hidden !important;
    transition    : transform .3s cubic-bezier(.34,1.56,.64,1),
                    box-shadow .3s ease !important;
    cursor        : pointer !important;
}

#product-list .aiz-pos-product:hover,
#product-list > div:hover,
#product-list > li:hover {
    transform     : translateY(-6px) scale(1.02) !important;
    box-shadow    : 10px 10px 28px rgba(163,177,198,.6),
                    -6px -6px 20px rgba(255,255,255,1) !important;
}

#product-list .aiz-pos-product:active,
#product-list > div:active,
#product-list > li:active {
    transform     : scale(0.97) !important;
    box-shadow    : var(--ni) !important;
}

/* Imagen */
#product-list img {
    width         : 100% !important;
    height        : 180px !important;
    object-fit    : cover !important;
    transition    : transform .4s ease !important;
    display       : block !important;
}
#product-list > div:hover img,
#product-list > li:hover img {
    transform     : scale(1.08) !important;
}

/* Badge stock */
#product-list .badge {
    border-radius : 20px !important;
    font-size     : 10px !important;
    font-weight   : 700 !important;
    padding       : 3px 10px !important;
    letter-spacing: .3px !important;
}

/* Nombre producto */
#product-list p,
#product-list h6,
#product-list .product-name {
    font-family   : 'Poppins', sans-serif !important;
    font-weight   : 600 !important;
    font-size     : 12.5px !important;
    color         : var(--text) !important;
}

/* Precio */
#product-list .price,
#product-list strong,
#product-list b {
    color         : var(--accent) !important;
    font-family   : 'Poppins', sans-serif !important;
    font-weight   : 700 !important;
}

/* Precio tachado */
#product-list del,
#product-list s {
    color         : var(--text-soft) !important;
    font-size     : 11px !important;
}
</style>

































@endsection

@section('modal')
    <!-- Address Modal -->
    <div id="new-customer" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom" role="document">
            <div class="modal-content">
                <div class="modal-header bord-btm">
                    <h4 class="modal-title h6">{{translate('Shipping Address')}}</h4>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="shipping_form">
                    <div class="modal-body" id="shipping_address">


                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-styled btn-base-3" data-dismiss="modal" id="close-button">{{translate('Close')}}</button>
                    <button type="button" class="btn btn-primary btn-styled btn-base-1" id="confirm-address" data-dismiss="modal">{{translate('Confirm')}}</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Change Customer Confirmation Modal--}}
    <div id="change-customer" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom" role="document">
            <div class="modal-content">
                <div class="modal-header bord-btm">
                    <h4 class="modal-title h6">{{translate('Change Customer Confirmation')}}</h4>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="shipping_form">
                    <div class="modal-body">
                        <p class="mt-1 fs-14">{{translate('If you have cart data and change customers, cart data for the previous customer will be removed.')}}</p>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-styled btn-base-3" data-dismiss="modal" id="close-button">{{translate('Close')}}</button>
                    <button type="button" class="btn btn-primary btn-styled btn-base-1" onclick="updateSessionUserCartData()" data-dismiss="modal">{{translate('Confirm')}}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- new address modal -->
    <div id="new-address-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom" role="document">
            <div class="modal-content">
                <div class="modal-header bord-btm">
                    <h4 class="modal-title h6">{{translate('Shipping Address')}}</h4>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                </div>
                <form class="form-horizontal" action="{{ route('addresses.store') }}" method="POST" enctype="multipart/form-data">
                	@csrf
                    <div class="modal-body">
                        <input type="hidden" name="customer_id" id="set_customer_id" value="">
                        <div class="form-group">
                            <div class=" row">
                                <label class="col-sm-2 control-label" for="address">{{translate('Address')}}</label>
                                <div class="col-sm-10">
                                    <textarea placeholder="{{translate('Address')}}" id="address" name="address" class="form-control" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class=" row">
                                <label class="col-sm-2 control-label">{{translate('Country')}}</label>
                                <div class="col-sm-10">
                                    <select class="form-control aiz-selectpicker" data-live-search="true" data-placeholder="{{ translate('Select your country') }}" name="country_id" required>
                                        <option value="">{{ translate('Select your country') }}</option>
                                        @foreach (\App\Models\Country::where('status', 1)->get() as $key => $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-2 control-label">
                                    <label>{{ translate('State')}}</label>
                                </div>
                                <div class="col-sm-10">
                                    <select class="form-control mb-3 aiz-selectpicker" data-live-search="true" name="state_id" required>
                        
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-2">
                                    <label>{{ translate('City')}}</label>
                                </div>
                                <div class="col-sm-10">
                                    <select class="form-control mb-3 aiz-selectpicker" data-live-search="true" name="city_id" required>
                        
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class=" row">
                                <label class="col-sm-2 control-label" for="postal_code">{{translate('Postal code')}}</label>
                                <div class="col-sm-10">
                                    <input type="number" min="0" placeholder="{{translate('Postal code')}}" id="postal_code" name="postal_code" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class=" row">
                                <label class="col-sm-2 control-label" for="phone">{{translate('Phone')}}</label>
                                <div class="col-sm-10">
                                    <input type="number" min="0" placeholder="{{translate('Phone')}}" id="phone" name="phone" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-styled btn-base-3" data-dismiss="modal">{{translate('Close')}}</button>
                        <button type="submit" class="btn btn-primary btn-styled btn-base-1">{{translate('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>







<style>

/* RECUADRO */
        /* ══ MODAL ORDER SUMMARY ══ */
#order-confirm .modal-content {
    background    : var(--nb);
    border-radius : 20px;
    border        : none;
  
    overflow      : hidden;
}

#order-confirm .modal-header {
    background    : var(--nb);
    border-bottom : 1px solid rgba(163,177,198,.3);
    padding       : 18px 24px;
}

#order-confirm .modal-title {
    font-family   : 'Poppins', sans-serif;
    font-weight   : 700;
    font-size     : 15px;
    color         : var(--text);
}

#order-confirm .modal-body {
    background    : var(--nb);
    padding       : 20px 24px;
}

#order-confirm .modal-footer {
    background    : var(--nb);
    border-top    : 1px solid rgba(163,177,198,.3);
    padding       : 14px 24px;
    gap           : 10px;
}

/* Botón Cerrar */
#order-confirm .btn-secondary {
    padding       : 9px 18px;
    border-radius : var(--rs);
    background    : var(--nb) !important;
    box-shadow    : var(--ns) !important;
    border        : none !important;
    color         : var(--text) !important;
    font-family   : 'Poppins', sans-serif;
    font-size     : 13px;
    font-weight   : 600;
    transition    : all .25s;
}
#order-confirm .btn-secondary:hover {
    box-shadow    : 8px 8px 20px rgba(163,177,198,.5),
                    -4px -4px 14px rgba(255,255,255,1) !important;
    transform     : translateY(-2px);
}
#order-confirm .btn-secondary:active {
    box-shadow    : var(--ni) !important;
    transform     : scale(0.98);
}

/* Botón Offline Payment */
#order-confirm .btn-warning {
    padding       : 9px 18px;
    border-radius : var(--rs);
    background    : linear-gradient(135deg, #f59e0b, #fbbf24) !important;
    box-shadow    : 4px 4px 12px rgba(245,158,11,.35) !important;
    border        : none !important;
    color         : #fff !important;
    font-family   : 'Poppins', sans-serif;
    font-size     : 13px;
    font-weight   : 600;
    transition    : all .25s;
}
#order-confirm .btn-warning:hover {
    box-shadow    : 6px 6px 16px rgba(245,158,11,.5) !important;
    transform     : translateY(-2px);
}

/* Botón COD */
#order-confirm .btn-info {
    padding       : 9px 18px;
    border-radius : var(--rs);
    background    : linear-gradient(135deg, #6366f1, #818cf8) !important;
    box-shadow    : 4px 4px 12px rgba(99,102,241,.35) !important;
    border        : none !important;
    color         : #fff !important;
    font-family   : 'Poppins', sans-serif;
    font-size     : 13px;
    font-weight   : 600;
    transition    : all .25s;
}
#order-confirm .btn-info:hover {
    box-shadow    : 6px 6px 16px rgba(99,102,241,.5) !important;
    transform     : translateY(-2px);
}

/* Botón Cash */
#order-confirm .btn-success {
    padding       : 9px 18px;
    border-radius : var(--rs);
    background    : linear-gradient(135deg, #22c55e, #4ade80) !important;
    box-shadow    : 4px 4px 12px rgba(34,197,94,.35) !important;
    border        : none !important;
    color         : #fff !important;
    font-family   : 'Poppins', sans-serif;
    font-size     : 13px;
    font-weight   : 600;
    transition    : all .25s;
}
#order-confirm .btn-success:hover {
    box-shadow    : 6px 6px 16px rgba(34,197,94,.5) !important;
    transform     : translateY(-2px);
}

/* Spinner cargando */
#order-confirm .la-spinner {
    color         : var(--accent2);
}
#order-confirm .modal-body table,
#order-confirm .modal-body .order-summary-left,
#order-confirm #order-confirmation > div > div:first-child {
    width         : 100% !important;
    padding-right : 20px !important;
}

/* recuadro */
#order-confirm .modal-dialog {
    max-width     : 980px !important;
}

#order-confirm .list-group-item {
    padding       : 8px 12px !important;
    margin-bottom : 6px !important;
}

#order-confirm img.size-60px {
    width         : 45px !important;
    height        : 45px !important;
    min-width     : 45px !important;
}

#order-confirm .text-truncate-2 {
    font-size     : 12px !important;
}

#order-confirm .fs-14 {
    font-size     : 12px !important;
}
#order-confirm .list-group-item {
    border-radius : 16px !important;
    background    : #fff !important;
    box-shadow    : var(--ns) !important;
    border        : none !important;
    margin-bottom : 10px !important;
    padding       : 12px 16px !important;
}

#order-confirm .card {
    border-radius : 16px !important;
    background    : #fff !important;
    box-shadow    : var(--ns) !important;
    border        : none !important;
}

#order-confirm .card-header {
    border-radius : 16px 16px 0 0 !important;
    background    : transparent !important;
    border-bottom : 1px solid rgba(163,177,198,.3) !important;
}

#offlin_payment .modal-content {
    background    : var(--nb) !important;
    border-radius : 20px !important;
    border        : none !important;
    box-shadow    : 12px 12px 30px rgba(163,177,198,.6),
                    -8px -8px 24px rgba(255,255,255,.95) !important;
}

#offlin_payment .modal-body {
    background    : var(--nb) !important;
}

#offlin_payment .modal-header {
    background    : var(--nb) !important;
    border-bottom : 1px solid rgba(163,177,198,.3) !important;
}

#offlin_payment .modal-footer {
    background    : var(--nb) !important;
    border-top    : 1px solid rgba(163,177,198,.3) !important;
}

#offlin_payment .form-control {
    background    : var(--nb) !important;
    box-shadow    : var(--ni) !important;
    border        : none !important;
    border-radius : var(--rs) !important;
}
/* deslizante realizar pedido */
#order-confirm .list-group {
    max-height    : 500px;
    overflow-y    : auto;
    scrollbar-width : thin;
    scrollbar-color : rgba(163,177,198,.5) transparent;
}

#order-confirm .list-group::-webkit-scrollbar {
    width         : 5px;
}
#order-confirm .list-group::-webkit-scrollbar-track {
    background    : transparent;
}
#order-confirm .list-group::-webkit-scrollbar-thumb {
    background    : rgba(163,177,198,.5);
    border-radius : 10px;
}
</style>


    <div id="order-confirm" class="modal fade">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom modal-xl">
            <div class="modal-content" id="variants">
                <div class="modal-header bord-btm">
                    <h4 class="modal-title h6">{{translate('Order Summary')}}</h4>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" id="order-confirmation">
                    <div class="p-4 text-center">
                        <i class="las la-spinner la-spin la-3x"></i>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-base-3" data-dismiss="modal">{{translate('Close')}}</button>
                    <button type="button" onclick="oflinePayment()" class="btn btn-base-1 btn-warning">{{translate('Offline Payment')}}</button>
                    <button type="button" onclick="submitOrder('cash_on_delivery')" class="btn btn-base-1 btn-info">{{translate('Confirm with COD')}}</button>
                    <button type="button" onclick="submitOrder('cash')" class="btn btn-base-1 btn-success">{{translate('Confirm with Cash')}}</button>
                </div>
            </div>
        </div>
    </div>

<!-- RECUADRO -->






















    {{-- Offline Payment Modal --}}
    <div id="offlin_payment" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom" role="document">
            <div class="modal-content">
                <div class="modal-header bord-btm">
                    <h4 class="modal-title h6">{{translate('Offline Payment Info')}}</h4>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class=" row">
                            <label class="col-sm-3 control-label" for="offline_payment_method">{{translate('Payment Method')}}</label>
                            <div class="col-sm-9">
                                <input placeholder="{{translate('Name')}}" id="offline_payment_method" name="offline_payment_method" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class=" row">
                            <label class="col-sm-3 control-label" for="offline_payment_amount">{{translate('Amount')}}</label>
                            <div class="col-sm-9">
                                <input placeholder="{{translate('Amount')}}" id="offline_payment_amount" name="offline_payment_amount" class="form-control" readonly required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <label class="col-sm-3 control-label" for="trx_id">{{translate('Transaction ID')}}</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control mb-3" id="trx_id" name="trx_id" placeholder="{{ translate('Transaction ID') }}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ translate('Payment Proof') }}</label>
                        <div class="col-md-9">
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose image') }}</div>
                                <input type="hidden" name="payment_proof" class="selected-files">
                            </div>
                            <div class="file-preview box sm">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-base-3" data-dismiss="modal">{{translate('Close')}}</button>
                    <button type="button" onclick="submitOrder('offline_payment')" class="btn btn-styled btn-base-1 btn-success">{{translate('Confirm')}}</button>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('script')
    <script type="text/javascript">

        var products = null;

        $(document).ready(function(){
            $('body').addClass('side-menu-closed');
            $('#product-list').on('click','.add-plus:not(.c-not-allowed)',function(){
                var stock_id = $(this).data('stock-id');
                console.log('Clicked add-plus button. Stock ID:', stock_id);
                
                if (!stock_id) {
                    console.error('Stock ID is missing!');
                    AIZ.plugins.notify('danger', 'Error: Stock ID not found');
                    return;
                }
                
                var userId = $('select[name=user_id]').val();
                $.post('{{ route('pos.addToCart') }}',{_token:AIZ.data.csrf, stock_id:stock_id}, function(data){
                    console.log('addToCart response:', data);
                    console.log('Response type:', typeof data);
                    console.log('Response view length:', data.view ? data.view.length : 'no view');
                    
                    if(data.success == 1){
                        console.log('Success! Updating cart with view...');
                        updateCart(data.view);
                        AIZ.plugins.notify('success', 'Product added to cart');
                    }else{
                        AIZ.plugins.notify('danger', data.message);
                    }
                }).fail(function(error) {
                    console.error('addToCart failed:', error);
                    AIZ.plugins.notify('danger', 'Failed to add product to cart');
                });
            });
            filterProducts();
            getShippingAddress();
        });
        
        $("#confirm-address").click(function (){
            var data = new FormData($('#shipping_form')[0]);
            
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': AIZ.data.csrf
                },
                method: "POST",
                url: "{{route('pos.set-shipping-address')}}",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data, textStatus, jqXHR) {
                }
            })
        });

        function updateCart(data){
            console.log('updateCart called with data length:', data.length);
            console.log('Target element #cart-details exists:', $('#cart-details').length > 0);
            
            if (!data || data.length === 0) {
                console.error('Error: Cart view data is empty!');
                AIZ.plugins.notify('danger', 'Error: Empty cart view returned');
                return;
            }
            
            $('#cart-details').html(data);
            console.log('Cart HTML updated. Current HTML length:', $('#cart-details').html().length);
            
            AIZ.extra.plusMinus();
            console.log('plusMinus initialized');
        }

        function filterProducts(){
            var keyword = $('input[name=keyword]').val();
            var category = $('select[name=poscategory]').val();
            var brand = $('select[name=brand]').val();
            $.get('{{ route('pos.search_product') }}',{keyword:keyword, category:category, brand:brand}, function(data){
                products = data;
                $('#product-list').html(null);
                setProductList(data);
            });
        }

        function loadMoreProduct(){
            var keyword = $('input[name=keyword]').val();
            var category = $('select[name=poscategory]').val();
            var brand = $('select[name=brand]').val();
            if(products != null && products.links.next != null){
                $('#load-more').find('.btn').html('{{ translate('Loading..') }}');
                $.get(products.links.next,{keyword:keyword, category:category, brand:brand}, function(data){
                    products = data;
                    setProductList(data);
                });
            }
            // Al final de setProductList
if(products.links.next == null){
    $('#btn-load-more').hide();
    $('#btn-no-more').show();
} else {
    $('#btn-load-more').show();
    $('#btn-no-more').hide();
}
        }

        function setProductList(data){
            for (var i = 0; i < data.data.length; i++) {
                
                $('#product-list').append(
                    `<div class="w-140px w-xl-180px w-xxl-210px mx-2">
                        <div class="card bg-white c-pointer product-card hov-container">
                            <div class="position-relative">
                                ${data.data[i].digital == 0 
                                    ?
                                        `<span class="absolute-top-left mt-1 ml-1 mr-0">
                                            ${data.data[i].qty > 0
                                                ? `<span class="badge badge-inline badge-success fs-13">{{ translate('In stock') }}`
                                                : `<span class="badge badge-inline badge-danger fs-13">{{ translate('Out of stock') }}` }
                                            : ${data.data[i].qty}</span>
                                        </span>`
                                    : ''
                                }
                                ${data.data[i].variant != null
                                    ? `<span class="badge badge-inline badge-warning absolute-bottom-left mb-1 ml-1 mr-0 fs-13 text-truncate">${data.data[i].variant}</span>`
                                    : '' }
                                <img src="${data.data[i].thumbnail_image }" class="card-img-top img-fit h-120px h-xl-180px h-xxl-210px mw-100 mx-auto" >
                            </div>
                            <div class="card-body p-2 p-xl-3">
                                <div class="text-truncate fw-600 fs-14 mb-2">${data.data[i].name}</div>
                                <div class="">
                                    ${data.data[i].price != data.data[i].base_price
                                        ? `<del class="mr-2 ml-0">${data.data[i].base_price}</del><span>${data.data[i].price}</span>`
                                        : `<span>${data.data[i].base_price}</span>`
                                    }
                                </div>
                            </div>
                            <div class="add-plus absolute-full rounded overflow-hidden hov-box ${(data.data[i].digital == 0 && data.data[i].qty <= 0) ? 'c-not-allowed' : '' }" data-stock-id="${data.data[i].stock_id}">
                                <div class="absolute-full bg-dark opacity-50">
                                </div>
                                <i class="las la-plus absolute-center la-6x text-white"></i>
                            </div>
                        </div>
                    </div>`
                );
            }
            if (data.links.next != null) {
                $('#load-more').find('.btn').html('{{ translate('Load More.') }}');
            }
            else {
                $('#load-more').find('.btn').html('{{ translate('Nothing more found.') }}');
            }
        }

        function removeFromCart(id){
            $.post('{{ route('pos.removeFromCart') }}', {_token:AIZ.data.csrf, id:id}, function(data){
                updateCart(data);
            });
        }


        function updateQuantity(cartId){
            $.post('{{ route('pos.updateQuantity') }}',{_token:AIZ.data.csrf, cartId:cartId, quantity: $('#qty-'+cartId).val()}, function(data){
                if(data.success == 1){
                    updateCart(data.view);
                }else{
                    AIZ.plugins.notify('danger', data.message);
                }
            });
        }

        function setDiscount(){
            var discount = $('input[name=discount]').val();
            $.post('{{ route('pos.setDiscount') }}',{_token:AIZ.data.csrf, discount:discount}, function(data){
                updateCart(data);
            });
        }

        function setShipping(){
            var shipping = $('input[name=shipping]').val();
            $.post('{{ route('pos.setShipping') }}',{_token:AIZ.data.csrf, shipping:shipping}, function(data){
                updateCart(data);
            });
        }

        function getShippingAddressUpdateCartData(){
            getShippingAddress();
            var $userID = '{{ $userID }}';
            if(!$userID){
                updateSessionUserCartData();
            }
            else {
                $('#change-customer').modal('show');
            }
        }

        function getShippingAddress(){
            $.post('{{ route('pos.getShippingAddress') }}',{_token:AIZ.data.csrf, id:$('select[name=user_id]').val()}, function(data){
                $('#shipping_address').html(data);
            });
        }

        function updateSessionUserCartData(){
            $.post('{{ route('pos.updateSessionUserCartData') }}',{_token:AIZ.data.csrf, userId:$('select[name=user_id]').val()}, function(data){
                updateCart(data);
            });
        }

        function add_new_address(){
            var customer_id = $('#customer_id').val();
            $('#set_customer_id').val(customer_id);
            $('#new-address-modal').modal('show');
            $("#close-button").click();
        }

        function orderConfirmation(){
            $('#order-confirmation').html(`<div class="p-4 text-center"><i class="las la-spinner la-spin la-3x"></i></div>`);
            $('#order-confirm').modal('show');
            $.post('{{ route('pos.getOrderSummary') }}',{_token:AIZ.data.csrf}, function(data){
                $('#order-confirmation').html(data);
            });
        }

        function oflinePayment(){
            var totalPrice = $('#total_price').val();
            $('#offline_payment_amount').val(totalPrice);
            $('#offlin_payment').modal('show');
        }

        function submitOrder(payment_type){
            var user_id = $('select[name=user_id]').val();
            var shipping = $('input[name=shipping]:checked').val();
            var discount = $('input[name=discount]').val();
            var shipping_address = $('input[name=address_id]:checked').val();
            var offline_payment_method = $('input[name=offline_payment_method]').val();
            var offline_payment_amount = $('input[name=offline_payment_amount]').val();
            var offline_trx_id = $('input[name=trx_id]').val();
            var offline_payment_proof = $('input[name=payment_proof]').val();
            
            $.post('{{ route('pos.order_place') }}',{
                _token                  : AIZ.data.csrf, 
                user_id                 : user_id,
                shipping_address        : shipping_address, 
                payment_type            : payment_type, 
                shipping                : shipping, 
                discount                : discount,
                offline_payment_method  : offline_payment_method,
                offline_payment_amount  : offline_payment_amount,
                offline_trx_id          : offline_trx_id,
                offline_payment_proof   : offline_payment_proof
                
            }, function(data){
                if(data.success == 1){
                    AIZ.plugins.notify('success', data.message );
                    location.reload();
                }
                else{
                    AIZ.plugins.notify('danger', data.message );
                }
            });
        }


        //address
        $(document).on('change', '[name=country_id]', function() {
            var country_id = $(this).val();
            get_states(country_id);
        });

        $(document).on('change', '[name=state_id]', function() {
            var state_id = $(this).val();
            get_city(state_id);
        });
        
        function get_states(country_id) {
            $('[name="state"]').html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('get-state')}}",
                type: 'POST',
                data: {
                    country_id  : country_id
                },
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj != '') {
                        $('[name="state_id"]').html(obj);
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                }
            });
        }

        function get_city(state_id) {
            $('[name="city"]').html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('get-city')}}",
                type: 'POST',
                data: {
                    state_id: state_id
                },
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj != '') {
                        $('[name="city_id"]').html(obj);
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                }
            });
        }
    </script>
@endsection

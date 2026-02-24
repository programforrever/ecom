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
                        <button type="button" class="neu-delete-btn ml-2 mr-0" onclick="removeFromCart({{ $cartItem['id'] }})">
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
        <span>{{ single_price($subtotal+$tax+Session::get('pos.shipping', 0) - Session::get('pos.discount', 0)) }}</span>
    </div>
</div>
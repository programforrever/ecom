<style>
    .aiz-category-menu .category-nav-element {
        position: relative;
    }

    /* Mostrar submenu cuando se hace hover en el elemento padre O en el submenu mismo */
    .aiz-category-menu .category-nav-element:hover .sub-cat-menu,
    .aiz-category-menu .sub-cat-menu:hover {
        display: block !important;
        opacity: 1;
        visibility: visible;
        animation: slideRight 0.3s ease-out;
    }

    .aiz-category-menu .sub-cat-menu {
        position: absolute;
        top: 0;
        left: 100%;
        min-width: 300px;
        max-width: 400px;
        background: white;
        border: 1px solid #e9ecef !important;
        border-radius: 6px;
        padding: 0 !important;
        margin-left: 10px;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.15) !important;
        display: none;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease, all 0.2s ease;
        z-index: 1000;
        /* Crear zona invisible de conexión entre elemento y submenu */
        pointer-events: auto;
    }

    /* Agregar zona invisible para facilitar el paso del cursor */
    .aiz-category-menu .category-nav-element::after {
        content: '';
        position: absolute;
        top: 0;
        left: 100%;
        width: 15px;
        height: 100%;
        z-index: 999;
        pointer-events: all;
    }


    .aiz-category-menu .sub-cat-menu .card-columns {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0;
        padding: 15px;
    }

    .aiz-category-menu .sub-cat-menu .card {
        margin-bottom: 0 !important;
    }

    .aiz-category-menu .sub-cat-menu ul.list-unstyled {
        margin-bottom: 15px !important;
    }

    .aiz-category-menu .sub-cat-menu li.fs-14 {
        padding: 5px 0;
    }

    .aiz-category-menu .sub-cat-menu li a {
        font-size: 13px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
        padding: 3px 0;
    }

    .aiz-category-menu .sub-cat-menu li:first-child {
        font-weight: 700;
        color: #222;
        margin-bottom: 8px !important;
    }

    @keyframes slideRight {
        from {
            opacity: 0;
            transform: translateX(-10px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* Para categorías sin subcategorías, el submenu permanece oculto */
    .aiz-category-menu .sub-cat-menu:empty {
        display: none !important;
    }

    @media (max-width: 992px) {
        .aiz-category-menu .sub-cat-menu {
            position: static;
            opacity: 1;
            visibility: visible;
            margin-left: 0;
            margin-top: 0;
            box-shadow: none;
            border: none;
            display: none;
        }

        .aiz-category-menu .category-nav-element.active .sub-cat-menu {
            display: block;
        }

        .aiz-category-menu .sub-cat-menu .card-columns {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="aiz-category-menu bg-white rounded-0 border-top" id="category-sidebar" style="width:270px;">
    <ul class="list-unstyled categories no-scrollbar mb-0 text-left">
        @foreach (get_level_zero_categories()->take(9) as $key => $category)
            @php
                $category_name = $category->getTranslation('name');
                $has_children = $category->childrenCategories && $category->childrenCategories->count() > 0;
            @endphp
            <li class="category-nav-element border border-top-0 {{ $has_children ? 'cat-parent' : '' }}" data-id="{{ $category->id }}">
                <a href="{{ route('products.category', $category->slug) }}"
                    class="text-truncate text-dark px-4 fs-14 d-flex justify-content-start hov-column-gap-1">
                    <img class="cat-image lazyload mr-2 opacity-60" src="{{ static_asset('assets/img/placeholder.jpg') }}"
                        data-src="{{ isset($category->catIcon->file_name) ? my_asset($category->catIcon->file_name) : static_asset('assets/img/placeholder.jpg') }}" width="16" alt="{{ $category_name }}"
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                    <span class="cat-name has-transition">{{ $category_name }}</span>
                    @if($has_children)
                        <i class="las la-chevron-right ml-auto opacity-60"></i>
                    @endif
                </a>
                
                @if($has_children)
                    <div class="sub-cat-menu">
                        <div class="c-preloader text-center absolute-center" style="display:none;">
                            <i class="las la-spinner la-spin la-3x opacity-70"></i>
                        </div>
                        <div class="card-columns loaded">
                            @foreach ($category->childrenCategories as $sub_key => $sub_category)
                                <div class="card shadow-none border-0">
                                    <ul class="list-unstyled mb-3">
                                        <li class="fs-14 fw-700 mb-3">
                                            <a class="text-reset hov-text-primary" href="{{ route('products.category', $sub_category->slug) }}">
                                                {{ $sub_category->getTranslation('name') }}
                                            </a>
                                        </li>
                                        @if($sub_category->childrenCategories && $sub_category->childrenCategories->count())
                                            @foreach ($sub_category->childrenCategories as $child_key => $child_category)
                                                <li class="mb-2 fs-14 pl-2">
                                                    <a class="text-reset hov-text-primary animate-underline-primary" href="{{ route('products.category', $child_category->slug) }}">
                                                        {{ $child_category->getTranslation('name') }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </li>
        @endforeach
        <li class="category-nav-element border border-top-0">
            <a href="{{ route('categories.all') }}"
                class="text-truncate text-dark px-4 fs-14 d-flex justify-content-start hov-column-gap-1">
                <span class="d-none d-lg-inline-block hov-opacity-80">{{ translate('Categories') }} ({{ translate('See All') }})</span>
            </a>
        </li>
    </ul>
</div>

@extends('backend.layouts.app')

@section('content')

@php
    CoreComponentRepository::instantiateShopRepository();
    CoreComponentRepository::initializeCache();
@endphp

<div class="page-content">
    <div class="aiz-titlebar text-left mt-2 pb-2 px-3 px-md-2rem border-bottom border-gray">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3">{{ translate('Agregar Nuevo Producto - Asistente de Pasos') }}</h1>
            </div>
        </div>
    </div>

    <div class="p-3 p-md-2rem">
        <!-- Step Indicator -->
        <div class="step-indicator">
            <div class="step-item active" data-step="1">
                <div class="step-number">1</div>
                <div class="step-label">{{ translate('Información Básica') }}</div>
            </div>
            <div class="step-line"></div>
            <div class="step-item" data-step="2">
                <div class="step-number">2</div>
                <div class="step-label">{{ translate('Imágenes y Precio') }}</div>
            </div>
            <div class="step-line"></div>
            <div class="step-item" data-step="3">
                <div class="step-number">3</div>
                <div class="step-label">{{ translate('Configuración Avanzada') }}</div>
            </div>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{route('products.store')}}" method="POST" enctype="multipart/form-data" id="choice_form">
            @csrf

            <!-- STEP 1: Basic Information -->
            <div class="step-content active" data-step="1">
                <div class="bg-white p-4 rounded shadow-sm">
                    <h4 class="mb-4 pb-3 border-bottom" style="border-bottom: 2px solid #007bff !important;">
                        <span class="badge badge-primary mr-2">{{ translate('PASO 1') }}</span>
                        {{ translate('Información Básica del Producto') }}
                    </h4>

                    <div class="row">
                        <!-- LEFT COLUMN: All Fields -->
                        <div class="col-md-7">
                            <!-- Row 1: Name & Brand -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-600">
                                        {{ translate('Nombre del Producto') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                                        placeholder="{{ translate('Ingresa el nombre del producto') }}" onchange="update_sku()" required>
                                    <small class="text-muted">{{ translate('Título del producto') }}</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-600">{{ translate('Marca') }}</label>
                                    <select class="form-control aiz-selectpicker" name="brand_id" id="brand_id" data-live-search="true">
                                        <option value="">{{ translate('Selecciona Marca (Opcional)') }}</option>
                                        @foreach (\App\Models\Brand::all() as $brand)
                                        <option value="{{ $brand->id }}" @selected(old('brand_id') == $brand->id)>{{ $brand->getTranslation('name') }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">{{ translate('Marca (Opcional)') }}</small>
                                </div>
                            </div>

                            <!-- Row 2: Unit & Min Qty -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-600">
                                        {{ translate('Unidad') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" name="unit" value="{{ old('unit') }}"
                                        placeholder="{{ translate('ej. KG, Pc, Caja') }}" required>
                                    <small class="text-muted">{{ translate('Unidad de medida') }}</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-600">
                                        {{ translate('Cantidad Mínima de Compra') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control" name="min_qty" value="1" min="1" required>
                                    <small class="text-muted">{{ translate('Cantidad mínima por pedido') }}</small>
                                </div>
                            </div>

                            <!-- Row 3: Weight & Barcode -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-600">{{ translate('Peso (Kg)') }}</label>
                                    <input type="number" class="form-control" name="weight" step="0.01" value="0.00" placeholder="0.00">
                                    <small class="text-muted">{{ translate('Para envío') }}</small>
                                </div>
                                @if (addon_is_activated('pos_system'))
                                <div class="col-md-6">
                                    <label class="form-label fw-600">{{ translate('Código de Barras') }}</label>
                                    <input type="text" class="form-control" name="barcode" value="{{ old('barcode') }}" placeholder="{{ translate('Opcional') }}">
                                    <small class="text-muted">{{ translate('Para sistema POS') }}</small>
                                </div>
                                @else
                                <div class="col-md-6"></div>
                                @endif
                            </div>

                            <!-- Row 4: Tags & Refundable -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-600">
                                        {{ translate('Etiquetas') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control aiz-tag-input" name="tags[]"
                                        placeholder="{{ translate('Escribe y pulsa Enter') }}" required>
                                    <small class="text-muted">{{ translate('Palabras clave para búsqueda') }}</small>
                                </div>
                                @if (addon_is_activated('refund_request'))
                                <div class="col-md-6">
                                    <label class="form-label fw-600">{{ translate('Reembolsable') }}</label>
                                    <div style="margin-top: 5px;">
                                        <label class="aiz-switch aiz-switch-success mb-0 d-block">
                                            <input type="checkbox" name="refundable" checked value="1">
                                            <span></span>
                                        </label>
                                    </div>
                                    <small class="text-muted">{{ translate('Permitir reembolsos') }}</small>
                                </div>
                                @else
                                <div class="col-md-6"></div>
                                @endif
                            </div>

                            <!-- Full Row: Description -->
                            <div class="mb-3">
                                <label class="form-label fw-600">{{ translate('Descripción del Producto') }}</label>
                                <textarea class="aiz-text-editor" name="description">{{ old('description') }}</textarea>
                                <small class="text-muted d-block mt-2">{{ translate('Descripción detallada del producto') }}</small>
                            </div>
                        </div>

                        <!-- RIGHT COLUMN: Category Selection -->
                        <div class="col-md-5 pl-md-4">
                            <div class="card sticky-top" style="top: 20px;">
                                <div class="card-header bg-light border-bottom" style="border-bottom: 2px solid #ecf0f1 !important;">
                                    <h5 class="mb-0" style="font-weight: 800; color: #2c3e50;">
                                        <i class="fas fa-sitemap mr-2"></i>{{ translate('Categoría del Producto') }}
                                    </h5>
                                </div>
                                <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                                    <p class="small text-muted mb-3">{{ translate('Selecciona al menos una categoría') }} <span class="text-danger">*</span></p>
                                    <ul class="hummingbird-treeview-converter list-unstyled" data-checkbox-name="category_ids[]" data-radio-name="category_id">
                                        @foreach ($categories as $category)
                                        <li id="{{ $category->id }}">{{ $category->getTranslation('name') }}</li>
                                            @foreach ($category->childrenCategories as $childCategory)
                                                @include('backend.product.products.child_category', ['child_category' => $childCategory])
                                            @endforeach
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="card-footer bg-light text-muted small" style="border-top: 1px solid #ecf0f1;">
                                    <i class="fas fa-info-circle mr-1"></i>{{ translate('Selecciona categorías donde aparecerá tu producto') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 2: Images & Price -->
            <div class="step-content" data-step="2">
                <div class="bg-white p-4 rounded shadow-sm">
                    <h4 class="mb-4 pb-3 border-bottom" style="border-bottom: 2px solid #17a2b8 !important;">
                        <span class="badge badge-info mr-2">{{ translate('PASO 2') }}</span>
                        {{ translate('Imágenes y Precio') }}
                    </h4>

                    <!-- Row 1: Gallery & Thumbnail -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-600">{{ translate('Imágenes de Galería') }} <small>(600x600)</small></label>
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Examinar')}}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Seleccionar Archivos') }}</div>
                                <input type="hidden" name="photos" class="selected-files">
                            </div>
                            <div class="file-preview box sm"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600">{{ translate('Imagen en Miniatura') }} <small>(300x300)</small></label>
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Examinar')}}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Seleccionar Archivo') }}</div>
                                <input type="hidden" name="thumbnail_img" class="selected-files">
                            </div>
                            <div class="file-preview box sm"></div>
                        </div>
                    </div>

                    <!-- Row 2: Video Info -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-600">{{ translate('Proveedor de Video') }}</label>
                            <select class="form-control aiz-selectpicker" name="video_provider" id="video_provider">
                                <option value="youtube" @selected(old('video_provider') == 'youtube')>{{ translate('Youtube') }}</option>
                                <option value="dailymotion" @selected(old('video_provider') == 'dailymotion')>{{ translate('Dailymotion') }}</option>
                                <option value="vimeo" @selected(old('video_provider') == 'vimeo')>{{ translate('Vimeo') }}</option>
                            </select>
                            <small class="text-muted">{{ translate('Video de demostración') }}</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600">{{ translate('Enlace de Video') }}</label>
                            <input type="text" class="form-control" name="video_link" value="{{ old('video_link') }}" placeholder="{{ translate('Pega la URL') }}">
                            <small class="text-muted">{{ translate('URL del video') }}</small>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Row 3: Unit Price & Discount -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-600">
                                {{ translate('Precio Unitario') }}
                                <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control" name="unit_price" min="0" step="0.01" 
                                placeholder="0.00" onchange="update_sku()" required value="0">
                            <small class="text-muted">{{ translate('Precio de venta') }}</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-600">{{ translate('Descuento') }}</label>
                            <input type="number" class="form-control" name="discount" min="0" step="0.01" placeholder="0.00" value="0">
                            <small class="text-muted">{{ translate('Monto del descuento') }}</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-600">{{ translate('Tipo de Descuento') }}</label>
                            <select class="form-control aiz-selectpicker" name="discount_type">
                                <option value="amount" @selected(old('discount_type') == 'amount')>{{ translate('Monto Fijo') }}</option>
                                <option value="percent" @selected(old('discount_type') == 'percent')>{{ translate('Porcentaje') }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Row 4: Discount Date Range -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label fw-600">{{ translate('Período de Descuento') }}</label>
                            <input type="text" class="form-control aiz-date-range" name="date_range" placeholder="{{ translate('Selecciona fechas') }}" data-time-picker="true" data-format="DD-MM-Y HH:mm:ss" data-separator=" a ">
                            <small class="text-muted">{{ translate('Cuando el descuento está activo') }}</small>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Row 5: Stock & SKU & Low Stock Alert -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-600">
                                {{ translate('Cantidad de Stock') }}
                                <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control" name="current_stock" min="0" step="1" placeholder="0" required>
                            <small class="text-muted">{{ translate('Unidades disponibles') }}</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-600">{{ translate('Código SKU') }}</label>
                            <input type="text" class="form-control" name="sku" placeholder="{{ translate('Automático o personalizado') }}" value="{{ old('sku') }}">
                            <small class="text-muted">{{ translate('Código único de inventario') }}</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-600">{{ translate('Alerta de Stock Bajo') }}</label>
                            <input type="number" class="form-control" name="low_stock_quantity" min="0" step="1" value="{{ old('low_stock_quantity', 0) }}" placeholder="0">
                            <small class="text-muted">{{ translate('Alerta cuando el stock cae por debajo') }}</small>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Row 6: Colors & Attributes -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-600">{{ translate('Colores') }}</label>
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <select class="form-control aiz-selectpicker" data-live-search="true" data-selected-text-format="count" name="colors[]" id="colors" multiple disabled>
                                        @foreach (\App\Models\Color::orderBy('name', 'asc')->get() as $color)
                                        <option value="{{ $color->code }}" data-content="<span><span class='size-15px d-inline-block mr-2 rounded border' style='background:{{ $color->code }}'></span><span>{{ $color->name }}</span></span>"></option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="ml-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="colors_active" onchange="update_sku()">
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                            <small class="text-muted">{{ translate('Habilitar colores') }}</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600">{{ translate('Atributos') }}</label>
                            <select name="choice_attributes[]" id="choice_attributes" class="form-control aiz-selectpicker" data-selected-text-format="count" data-live-search="true" multiple>
                                @foreach (\App\Models\Attribute::all() as $attribute)
                                <option value="{{ $attribute->id }}">{{ $attribute->getTranslation('name') }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">{{ translate('Atributos del producto') }}</small>
                        </div>
                    </div>

                    <!-- Full Row: Variations -->
                    <div class="mb-3">
                        <div id="customer_choice_options"></div>
                        <div class="sku_combination" id="sku_combination"></div>
                    </div>
                </div>
            </div>

            <!-- STEP 3: Advanced Configuration -->
            <div class="step-content" data-step="3">
                <div class="bg-white p-4 rounded shadow-sm">
                    <h4 class="mb-4 pb-3 border-bottom" style="border-bottom: 2px solid #ffc107 !important;">
                        <span class="badge badge-warning mr-2">{{ translate('PASO 3') }}</span>
                        {{ translate('Configuración Avanzada') }}
                    </h4>

                    <!-- Row 1: Featured & Today's Deal & Flash Deal -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-600">{{ translate('Destacado') }}</label>
                            <div style="margin-top: 8px;">
                                <label class="aiz-switch aiz-switch-success mb-0 d-block">
                                    <input type="checkbox" name="featured" value="1">
                                    <span></span>
                                </label>
                            </div>
                            <small class="text-muted">{{ translate('Producto destacado') }}</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-600">{{ translate('Oferta del Día') }}</label>
                            <div style="margin-top: 8px;">
                                <label class="aiz-switch aiz-switch-success mb-0 d-block">
                                    <input type="checkbox" name="todays_deal" value="1">
                                    <span></span>
                                </label>
                            </div>
                            <small class="text-muted">{{ translate('Oferta especial') }}</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-600">{{ translate('Oferta Relámpago') }}</label>
                            <select class="form-control aiz-selectpicker" name="flash_deal_id">
                                <option value="">{{ translate('Ninguno') }}</option>
                                @foreach(\App\Models\FlashDeal::where("status", 1)->get() as $flash_deal)
                                    <option value="{{ $flash_deal->id}}">{{ $flash_deal->title }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">{{ translate('Venta relámpago') }}</small>
                        </div>
                    </div>

                    <!-- Row 2: Flash Discount -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-600">{{ translate('Descuento Relámpago') }}</label>
                            <input type="number" class="form-control" name="flash_discount" min="0" step="0.01" placeholder="0">
                            <small class="text-muted">{{ translate('Monto del descuento') }}</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600">{{ translate('Tipo de Descuento') }}</label>
                            <select class="form-control aiz-selectpicker" name="flash_discount_type">
                                <option value="amount">{{ translate('Monto Fijo') }}</option>
                                <option value="percent">{{ translate('Porcentaje') }}</option>
                            </select>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- SEO Section -->
                    <div class="mb-4">
                        <h5 class="mb-3" style="color: #495057;"><i class="fas fa-search mr-2"></i>{{ translate('Configuración SEO') }}</h5>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label fw-600">{{ translate('Título Meta') }}</label>
                                <input type="text" class="form-control" name="meta_title" value="{{ old('meta_title') }}" placeholder="{{ translate('Título de la página para búsqueda') }}">
                                <small class="text-muted">{{ translate('Para motores de búsqueda') }}</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label fw-600">{{ translate('Descripción Meta') }}</label>
                                <textarea class="form-control" name="meta_description" rows="2" placeholder="{{ translate('Descripción breve') }}">{{ old('meta_description') }}</textarea>
                                <small class="text-muted">{{ translate('Vista previa de resultados de búsqueda') }}</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label fw-600">{{ translate('Imagen Meta') }}</label>
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Examinar')}}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Seleccionar Archivo') }}</div>
                                    <input type="hidden" name="meta_img" class="selected-files">
                                </div>
                                <div class="file-preview box sm"></div>
                                <small class="text-muted">{{ translate('Compartir en redes sociales') }}</small>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Shipping Section -->
                    <div class="mb-4">
                        <h5 class="mb-3" style="color: #495057;"><i class="fas fa-truck mr-2"></i>{{ translate('Configuración de Envío') }}</h5>

                        @if (get_setting('cash_payment') == '1')
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label fw-600">{{ translate('Pago Contra Entrega') }}</label>
                                <label class="aiz-switch aiz-switch-success mb-0 d-block">
                                    <input type="checkbox" name="cash_on_delivery" value="1" checked>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        @endif

                        @if (get_setting('shipping_type') == 'product_wise_shipping')
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-600">{{ translate('Tipo de Envío') }}</label>
                                <div class="mt-2">
                                    <label class="form-check">
                                        <input type="radio" name="shipping_type" value="free" checked class="form-check-input">
                                        <span class="form-check-label">{{ translate('Envío Gratis') }}</span>
                                    </label>
                                    <label class="form-check">
                                        <input type="radio" name="shipping_type" value="flat_rate" class="form-check-input">
                                        <span class="form-check-label">{{ translate('Tarifa Plana') }}</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6 flat_rate_shipping_div" style="display: none;">
                                <label class="form-label fw-600">{{ translate('Costo de Envío') }}</label>
                                <input type="number" class="form-control" name="flat_shipping_cost" min="0" step="0.01" placeholder="0.00">
                                <small class="text-muted">{{ translate('Cantidad del costo') }}</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-600">{{ translate('Días de Envío') }}</label>
                                <input type="number" class="form-control" name="est_shipping_days" min="1" step="1" placeholder="1" value="1">
                                <small class="text-muted">{{ translate('Días estimados') }}</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-600">{{ translate('Multiplicar Envío por Cantidad') }}</label>
                                <label class="aiz-switch aiz-switch-success mb-0 d-block" style="margin-top: 8px;">
                                    <input type="checkbox" name="is_quantity_multiplied" value="1">
                                    <span></span>
                                </label>
                                <small class="text-muted">{{ translate('Por cantidad') }}</small>
                            </div>
                        </div>
                        @endif
                    </div>

                    <hr class="my-4">

                    <!-- Files & Links Section -->
                    <div class="mb-4">
                        <h5 class="mb-3" style="color: #495057;"><i class="fas fa-file mr-2"></i>{{ translate('Archivos Adicionales') }}</h5>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-600">{{ translate('Especificación PDF') }}</label>
                                <div class="input-group" data-toggle="aizuploader" data-type="document">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Examinar')}}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Selecciona') }}</div>
                                    <input type="hidden" name="pdf" class="selected-files">
                                </div>
                                <div class="file-preview box sm"></div>
                                <small class="text-muted">{{ translate('Hoja de especificaciones') }}</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-600">{{ translate('Enlace Externo') }}</label>
                                <input type="text" class="form-control" name="external_link" placeholder="{{ translate('URL') }}" value="{{ old('external_link') }}">
                                <small class="text-muted">{{ translate('Enlace del sitio web') }}</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label fw-600">{{ translate('Texto del Botón de Enlace') }}</label>
                                <input type="text" class="form-control" name="external_link_btn" placeholder="{{ translate('Haz clic aquí') }}" value="{{ old('external_link_btn') }}">
                                <small class="text-muted">{{ translate('Etiqueta del botón') }}</small>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- VAT & TAX Section -->
                    <div class="mb-4">
                        <h5 class="mb-3" style="color: #495057;"><i class="fas fa-percentage mr-2"></i>{{ translate('IVA e IMPUESTOS') }}</h5>
                        <div class="row">
                            @foreach(\App\Models\Tax::where('tax_status', 1)->get() as $tax)
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-600">{{ $tax->name }}</label>
                                    <div class="row no-gutters">
                                        <div class="col-md-7">
                                            <input type="hidden" value="{{ $tax->id }}" name="tax_id[]">
                                            <input type="number" class="form-control tax-input" name="tax[]" min="0" step="0.01" placeholder="0" value="0">
                                        </div>
                                        <div class="col-md-5 pl-2">
                                            <select class="form-control aiz-selectpicker tax-type" name="tax_type[]">
                                                <option value="amount">{{ translate('Monto Fijo') }}</option>
                                                <option value="percent">{{ translate('Porcentaje') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="mt-5 pt-4 border-top" style="border-top: 2px solid #f0f0f0 !important;">
                <div class="d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-secondary" id="prevBtn" onclick="changeStep(-1)">
                        <i class="fas fa-arrow-left mr-2"></i>{{ translate('Anterior') }}
                    </button>

                    <div>
                        <button type="button" class="btn btn-outline-secondary" id="nextBtn" onclick="changeStep(1)" disabled style="opacity: 0.5; cursor: not-allowed;">
                            <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true" style="display: none;"></span>
                            {{ translate('Siguiente') }}<i class="fas fa-arrow-right ml-2"></i>
                        </button>
                        
                        <div id="submitButtons" style="display: none;">
                            <button type="submit" name="button" value="unpublish" class="btn btn-light px-4">
                                <i class="fas fa-save mr-2"></i>{{ translate('Guardar y Despublicar') }}
                            </button>
                            <button type="submit" name="button" value="publish" class="btn btn-success ml-2 btn-pulse">
                                <i class="fas fa-check mr-2"></i>{{ translate('Guardar y Publicar') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Styles -->
<style>
    .step-indicator {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 2.5rem;
        padding: 1.5rem;
        background: linear-gradient(135deg, #f5f7fa 0%, #f9fafb 100%);
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }

    .step-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        opacity: 0.5;
        transition: all 0.3s ease;
    }

    .step-item.active {
        opacity: 1;
    }

    .step-number {
        width: 55px;
        height: 55px;
        border-radius: 50%;
        background: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.3rem;
        color: #6c757d;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .step-item.active .step-number {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(0, 123, 255, 0.35);
        transform: scale(1.1);
    }

    .step-label {
        margin-top: 0.75rem;
        font-size: 0.9rem;
        font-weight: 700;
        color: #6c757d;
        text-align: center;
    }

    .step-item.active .step-label {
        color: #007bff;
        font-weight: 800;
    }

    .step-line {
        width: 70px;
        height: 3px;
        background: #dee2e6;
        margin: 0 15px;
        margin-bottom: 30px;
        transition: all 0.3s ease;
    }

    .step-item.active + .step-line {
        background: #007bff;
    }

    .step-content {
        display: none;
        animation: fadeIn 0.4s ease;
    }

    .step-content.active {
        display: block;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(15px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-label {
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 0.6rem;
        font-size: 0.95rem;
    }

    .form-control, .aiz-selectpicker {
        border: 1.5px solid #dee2e6;
        border-radius: 6px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .aiz-selectpicker:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.15);
    }

    .form-control:hover, .aiz-selectpicker:hover {
        border-color: #b3d9ff;
    }

    .text-danger {
        color: #dc3545;
        font-weight: 800;
    }

    .text-muted {
        color: #86929b !important;
        font-size: 0.85rem;
        margin-top: 0.3rem;
    }

    .bg-white {
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .badge {
        padding: 0.45rem 0.75rem;
        font-size: 0.8rem;
        font-weight: 800;
        border-radius: 4px;
    }

    .badge-primary {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: white;
    }

    .badge-info {
        background: linear-gradient(135deg, #17a2b8 0%, #0c5460 100%);
        color: white;
    }

    .badge-warning {
        background: linear-gradient(135deg, #ffc107 0%, #ff6c00 100%);
        color: white;
    }

    .btn {
        border-radius: 6px;
        font-weight: 700;
        padding: 0.55rem 1.75rem;
        transition: all 0.3s ease;
        border: 1.5px solid transparent;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-success {
        background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
        border-color: #28a745;
        color: white;
    }

    /* Description Editor Mode Toggle */
    .description-mode-btn {
        font-size: 13px;
        padding: 5px 12px;
        border-radius: 4px;
        transition: all 0.3s ease;
    }

    .description-mode-btn.active {
        background: #007bff;
        color: white;
        border-color: #007bff;
    }

    .description-mode-btn:not(.active) {
        background: white;
        color: #007bff;
        border: 1px solid #dee2e6;
    }

    .description-mode-btn:not(.active):hover {
        border-color: #007bff;
        color: #0056b3;
    }

    #html-editor-container textarea {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        padding: 12px;
        border-radius: 4px;
        resize: vertical;
    }

    #html-editor-container textarea:focus {
        background: white;
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
    }

    .description-editor-container {
        transition: opacity 0.3s ease;
    }
    }

    .btn-success:hover {
        background: linear-gradient(135deg, #218838 0%, #1a6b2f 100%);
        border-color: #218838;
        color: white;
    }

    .btn-secondary {
        background: #e9ecef;
        border-color: #dee2e6;
        color: #495057;
    }

    .btn-secondary:hover {
        background: #dee2e6;
        border-color: #ccc;
        color: #2c3e50;
    }

    .btn-outline-secondary {
        border-color: #dee2e6;
        color: #495057;
        background: transparent;
    }

    .btn-outline-secondary:hover {
        background: #f8f9fa;
        border-color: #ccc;
    }

    #prevBtn {
        min-width: 140px;
    }

    .aiz-switch {
        display: inline-block;
    }

    .card {
        border: 1px solid #dee2e6;
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }

    .card-body {
        padding: 1rem;
    }

    .row {
        margin-bottom: 0;
    }

    hr {
        border: none;
        border-top: 2px solid #f0f0f0;
    }

    .input-group-text {
        border-radius: 6px 0 0 6px !important;
        border: 1.5px solid #dee2e6 !important;
        background: #f8f9fa;
        font-weight: 600;
        color: #495057;
    }

    .file-preview {
        margin-top: 0.75rem;
    }

    /* Section Headers with Icons */
    .step-content h5 {
        font-weight: 800;
        color: #2c3e50;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #f0f0f0;
        font-size: 1rem;
    }

    .step-content h5 i {
        color: #007bff;
        margin-right: 0.5rem;
    }

    /* Sticky Category Panel */
    .card.sticky-top {
        border: 1.5px solid #ecf0f1;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        background: #ffffff;
        transition: all 0.3s ease;
    }

    .card.sticky-top:hover {
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
    }

    .card-header {
        background: linear-gradient(135deg, #f5f7fa 0%, #f9fafb 100%) !important;
        border: none !important;
        padding: 1rem !important;
    }

    .card-header h5 {
        color: #2c3e50;
        font-size: 1rem;
        margin: 0;
        font-weight: 800;
    }

    .card-body {
        padding: 1rem;
    }

    .card-footer {
        padding: 0.75rem;
        background: linear-gradient(135deg, #f5f7fa 0%, #f9fafb 100%) !important;
        font-size: 0.85rem;
    }

    .hummingbird-treeview-converter {
        margin: 0;
    }

    .hummingbird-treeview-converter li {
        line-height: 2;
        padding: 0.25rem 0;
    }

    .hummingbird-treeview-converter li input[type="checkbox"],
    .hummingbird-treeview-converter li input[type="radio"] {
        margin-right: 0.5rem;
        cursor: pointer;
    }

    .hummingbird-treeview-converter li label {
        cursor: pointer;
        margin-bottom: 0;
        font-size: 0.95rem;
        color: #495057;
        font-weight: 500;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .step-indicator {
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .step-number {
            width: 45px;
            height: 45px;
            font-size: 1.1rem;
        }

        .step-label {
            font-size: 0.8rem;
        }

        .step-line {
            width: 40px;
            margin: 0 8px;
            margin-bottom: 20px;
        }

        .col-md-6 {
            margin-bottom: 0.5rem;
        }

        .row {
            margin-bottom: -0.5rem;
        }

        .form-label {
            margin-bottom: 0.4rem;
        }

        .btn {
            padding: 0.45rem 1rem;
            font-size: 0.9rem;
        }

        .p-3 {
            padding: 1rem !important;
        }

        .p-md-2rem {
            padding: 1rem !important;
        }

        .bg-white {
            padding: 1.25rem !important;
        }

        .col-md-7,
        .col-md-5 {
            flex: 0 0 100%;
            max-width: 100%;
        }

        .card.sticky-top {
            position: relative !important;
            top: 0 !important;
            margin-top: 1.5rem;
        }

        .pl-md-4 {
            padding-left: 0 !important;
        }
    }

    /* For Tablets */
    @media (min-width: 769px) and (max-width: 1024px) {
        .col-md-6 {
            margin-bottom: 0.75rem;
        }

        .col-md-4 {
            margin-bottom: 0.75rem;
        }

        .col-md-7 {
            flex: 0 0 66.666666%;
            max-width: 66.666666%;
        }

        .col-md-5 {
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }

        .pl-md-4 {
            padding-left: 1rem !important;
        }
    }

    /* Compact Form Display */
    .step-content .row {
        row-gap: 1rem;
    }

    .step-content .col-md-6,
    .step-content .col-md-4,
    .step-content .col-md-12 {
        padding-bottom: 0.75rem;
    }

    /* Better file preview styling */
    .file-preview.box {
        padding: 0.5rem;
    }

    /* AIZ Text Editor optimization */
    .aiz-text-editor {
        min-height: 150px !important;
    }

    /* Category selector optimization */
    .hummingbird-treeview-converter {
        margin: 0 -0.5rem;
    }

    /* Button States */
    #nextBtn {
        transition: all 0.3s ease;
        min-width: 140px;
    }

    #nextBtn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        background: #e9ecef;
        border-color: #dee2e6;
        color: #6c757d;
    }

    #nextBtn:not(:disabled) {
        animation: pulse-glow 2s infinite;
    }

    @keyframes pulse-glow {
        0% {
            box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.4);
            transform: scale(1);
        }
        50% {
            box-shadow: 0 0 0 10px rgba(13, 110, 253, 0);
            transform: scale(1.02);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(13, 110, 253, 0);
            transform: scale(1);
        }
    }

    .btn-pulse {
        animation: button-pulse 1.5s infinite;
    }

    @keyframes button-pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(40, 167, 69, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(40, 167, 69, 0);
        }
    }

    /* Validation Error Messages */
    .validation-error {
        display: none;
        padding: 0.75rem;
        background: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
        border-radius: 4px;
        margin-top: 0.5rem;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .validation-error.show {
        display: block;
    }

    .validation-message {
        margin: 0;
        padding: 0;
    }

</style>

@endsection

@section('script')

<!-- Treeview js -->
<script src="{{ static_asset('assets/js/hummingbird-treeview.js') }}"></script>

<script type="text/javascript">
    let currentStep = 1;
    const totalSteps = 3;

    // Validation Rules for each step
    const stepValidation = {
        1: {
            fields: ['name', 'unit', 'min_qty', 'tags[]'],
            rules: {
                'name': { required: true, minLength: 3 },
                'unit': { required: true },
                'min_qty': { required: true, min: 1 },
                'tags[]': { required: true }
            }
        },
        2: {
            fields: ['unit_price', 'current_stock'],
            rules: {
                'unit_price': { required: true, min: 0 },
                'current_stock': { required: true, min: 0 }
            }
        },
        3: {
            fields: [],
            rules: {}
        }
    };

    // Initialize
    $(document).ready(function() {
        // Initialize treeview
        setTimeout(() => {
            $("#treeview").hummingbird();
        }, 100);
        
        var main_id = '{{ old("category_id") }}';
        var selected_ids = [];
        @if(old("category_ids"))
            selected_ids = @json(old("category_ids"));
        @endif
        
        for (let i = 0; i < selected_ids.length; i++) {
            const element = selected_ids[i];
            $('#treeview input:checkbox#'+element).prop('checked',true);
            $('#treeview input:checkbox#'+element).parents("ul").css("display", "block");
            $('#treeview input:checkbox#'+element).parents("li").children('.las').removeClass("la-plus").addClass('la-minus');
        }
        if(main_id){
            $('#treeview input:radio[value='+main_id+']').prop('checked',true);
        }

        updateStepIndicator();
        
        // Initialize UI components
        if(typeof AIZ !== 'undefined') {
            AIZ.plugins.bootstrapSelect('init');
            AIZ.uploader.previewGenerate();
        }

        // Initialize Tag Input
        setTimeout(function() {
            $('.aiz-tag-input').tagsinput({
                tagClass: 'badge badge-light',
                allowDuplicates: false
            });
        }, 200);

        // Attach validators
        attachValidators();
        
        // Initial validation check
        validateStep(currentStep);
    });

    // Attach real-time validators
    function attachValidators() {
        console.log('✓ Validators attached - monitoring Step 1 and Step 2 fields');
        console.log('📋 Categories HTML detected:', $('input[name="category_ids[]"]').length, 'inputs');
        console.log('🏷️  Tags HTML detected:', $('input.aiz-tag-input, input[name="tags[]"]').length, 'inputs');
        
        // Step 1 validators
        $('input[name="name"]').on('input change', function() {
            console.log('📝 Name changed:', $(this).val());
            validateStep(1);
        });

        $('input[name="unit"]').on('input change', function() {
            console.log('📦 Unit changed:', $(this).val());
            validateStep(1);
        });

        $('input[name="min_qty"]').on('input change', function() {
            console.log('⚖️  Min Qty changed:', $(this).val());
            validateStep(1);
        });

        // Tags - watch for all possible events
        $(document).on('input change keyup', '.aiz-tag-input, input[name="tags[]"]', function() {
            console.log('🏷️  Tags changed:', $(this).val());
            validateStep(1);
        });

        // Watch for tag container changes
        $(document).on('change', '.aiz-tagsinput', function() {
            console.log('📌 Tag container changed');
            validateStep(1);
        });

        // Category checkbox changes
        $(document).on('change', 'input[name="category_ids[]"], .hummingbird-treeview-converter input[type="checkbox"], .hummingbird-treeview-converter input[type="radio"]', function() {
            console.log('📂 Category changed - Checked categories:', $('input[name="category_ids[]"]:checked').length);
            validateStep(1);
        });

        // Step 2 validators
        $('input[name="unit_price"]').on('input change', function() {
            console.log('💰 Unit Price changed:', $(this).val());
            validateStep(2);
        });

        $('input[name="current_stock"]').on('input change', function() {
            console.log('📊 Stock changed:', $(this).val());
            validateStep(2);
        });
    }

    // Validate current step
    function validateStep(step) {
        let isValid = true;
        const rules = stepValidation[step].rules;

        if (step === 1) {
            // Check product name
            const name = $('input[name="name"]').val();
            const nameValid = name && name.length >= 3;
            console.log('  ✓ Name:', nameValid ? '✔️ VALID (' + name.length + ' chars)' : '❌ INVALID (need 3+ chars)');
            if (!nameValid) isValid = false;

            // Check unit
            const unit = $('input[name="unit"]').val();
            const unitValid = !!unit;
            console.log('  ✓ Unit:', unitValid ? '✔️ VALID (' + unit + ')' : '❌ INVALID (required)');
            if (!unitValid) isValid = false;

            // Check min qty
            const minQty = $('input[name="min_qty"]').val();
            const minQtyValid = minQty && parseInt(minQty) >= 1;
            console.log('  ✓ Min Qty:', minQtyValid ? '✔️ VALID (' + minQty + ')' : '❌ INVALID (need ≥1)');
            if (!minQtyValid) isValid = false;

            // Check tags - Multiple ways to detect
            let tagsExists = false;
            
            // Method 1: Check aiz-tag-input
            const tagInputs = $('input.aiz-tag-input');
            if (tagInputs.length > 0) {
                const tagValue = tagInputs.val();
                if (tagValue && tagValue.trim() !== '') {
                    tagsExists = true;
                    console.log('  ✓ Tags (Method 1):', '✔️ VALID via aiz-tag-input');
                }
            }
            
            // Method 2: Check tags[] inputs
            if (!tagsExists) {
                const tagsArray = $('input[name="tags[]"]');
                if (tagsArray.length > 0) {
                    tagsArray.each(function() {
                        if ($(this).val() && $(this).val().trim() !== '') {
                            tagsExists = true;
                        }
                    });
                    if (tagsExists) {
                        console.log('  ✓ Tags (Method 2):', '✔️ VALID via tags[] inputs (' + tagsArray.length + ' found)');
                    }
                }
            }

            // Method 3: Check tag container
            if (!tagsExists) {
                const tagContainer = $('.aiz-tagsinput .tag');
                if (tagContainer.length > 0) {
                    tagsExists = true;
                    console.log('  ✓ Tags (Method 3):', '✔️ VALID via tag container (' + tagContainer.length + ' tags)');
                }
            }

            if (!tagsExists) {
                console.log('  ✓ Tags:', '❌ INVALID (required)');
                isValid = false;
            }

            // Check categories - at least one should be checked
            let categoriesChecked = $('input[name="category_ids[]"]:checked').length > 0;
            if (!categoriesChecked) {
                // Fallback: Try the class selector
                categoriesChecked = $('.hummingbird-treeview-converter input[type="checkbox"]:checked').length > 0;
            }
            const catCount = $('input[name="category_ids[]"]:checked').length;
            console.log('  ✓ Categories:', categoriesChecked ? '✔️ VALID (' + catCount + ' selected)' : '❌ INVALID (need ≥1)');
            if (!categoriesChecked) {
                isValid = false;
            }

            console.log('━━━ STEP 1 COMPLETE:', isValid ? '✅ ALL VALID - NEXT BUTTON WILL ENABLE' : '❌ MISSING FIELDS - NEXT BUTTON DISABLED');
        } 
        else if (step === 2) {
            // Check unit price
            const unitPrice = $('input[name="unit_price"]').val();
            const priceValid = unitPrice && parseFloat(unitPrice) > 0;
            console.log('  ✓ Unit Price:', priceValid ? '✔️ VALID (' + unitPrice + ')' : '❌ INVALID (need >0)');
            if (!priceValid) isValid = false;

            // Check stock quantity
            const stock = $('input[name="current_stock"]').val();
            const stockValid = stock !== undefined && stock !== null && stock !== '' && parseInt(stock) >= 0;
            console.log('  ✓ Current Stock:', stockValid ? '✔️ VALID (' + stock + ')' : '❌ INVALID (required)');
            if (!stockValid) isValid = false;

            console.log('━━━ STEP 2 COMPLETE:', isValid ? '✅ ALL VALID - NEXT BUTTON WILL ENABLE' : '❌ MISSING FIELDS - NEXT BUTTON DISABLED');
        }

        // Update button state
        updateNextButton(isValid);
    }

    // Update Next Button State
    function updateNextButton(isValid) {
        const nextBtn = $('#nextBtn');
        
        if (isValid && currentStep < totalSteps) {
            nextBtn.prop('disabled', false);
            nextBtn.css('opacity', '1');
            nextBtn.css('cursor', 'pointer');
            nextBtn.removeClass('btn-outline-secondary').addClass('btn-primary');
            console.log('🎯 BUTTON STATE: ✅ ENABLED - You can click Next!');
        } else if (currentStep < totalSteps) {
            nextBtn.prop('disabled', true);
            nextBtn.css('opacity', '0.5');
            nextBtn.css('cursor', 'not-allowed');
            nextBtn.removeClass('btn-primary').addClass('btn-outline-secondary');
            console.log('🔒 BUTTON STATE: ❌ DISABLED - Complete all required fields');
        }
    }

    // Step Navigation
    function changeStep(direction) {
        // Validate current step before moving forward
        if (direction > 0) {
            if (!validateBeforeNext(currentStep)) {
                showValidationError(currentStep);
                return;
            }
        }

        currentStep += direction;
        
        if (currentStep < 1) currentStep = 1;
        if (currentStep > totalSteps) currentStep = totalSteps;
        
        showStep(currentStep);
    }

    function validateBeforeNext(step) {
        if (step === 1) {
            const name = $('input[name="name"]').val();
            if (!name || name.length < 3) {
                console.log('Validation failed: Name too short');
                return false;
            }

            const unit = $('input[name="unit"]').val();
            if (!unit) {
                console.log('Validation failed: No unit');
                return false;
            }

            const minQty = $('input[name="min_qty"]').val();
            if (!minQty || parseInt(minQty) < 1) {
                console.log('Validation failed: Invalid min qty');
                return false;
            }

            // Check tags with multiple methods
            let tagsExists = false;
            
            const tagInputs = $('input.aiz-tag-input');
            if (tagInputs.length > 0) {
                const tagValue = tagInputs.val();
                if (tagValue && tagValue.trim() !== '') {
                    tagsExists = true;
                }
            }
            
            if (!tagsExists) {
                const tagsArray = $('input[name="tags[]"]');
                if (tagsArray.length > 0) {
                    tagsArray.each(function() {
                        if ($(this).val() && $(this).val().trim() !== '') {
                            tagsExists = true;
                        }
                    });
                }
            }

            if (!tagsExists) {
                const tagContainer = $('.aiz-tagsinput .tag');
                if (tagContainer.length > 0) {
                    tagsExists = true;
                }
            }

            if (!tagsExists) {
                console.log('Validation failed: No tags');
                return false;
            }

            let categoriesChecked = $('input[name="category_ids[]"]:checked').length > 0;
            if (!categoriesChecked) {
                // Fallback: Try the class selector
                categoriesChecked = $('.hummingbird-treeview-converter input[type="checkbox"]:checked').length > 0;
            }
            if (!categoriesChecked) {
                console.log('Validation failed: No categories');
                return false;
            }

            return true;
        } 
        else if (step === 2) {
            const unitPrice = $('input[name="unit_price"]').val();
            if (!unitPrice || parseFloat(unitPrice) < 0) {
                console.log('Validation failed: Invalid unit price');
                return false;
            }

            const stock = $('input[name="current_stock"]').val();
            if (!stock || parseInt(stock) < 0) {
                console.log('Validation failed: Invalid stock');
                return false;
            }

            return true;
        }

        return true;
    }

    function showValidationError(step) {
        let message = '';

        if (step === 1) {
            message = '{{ translate("Por favor completa los campos requeridos: Nombre (mín. 3 caracteres), Unidad, Cantidad Mínima y selecciona al menos una Categoría") }}';
        } else if (step === 2) {
            message = '{{ translate("Por favor ingresa un Precio Unitario y Cantidad de Stock válidos") }}';
        }

        if (message) {
            alert(message);
        }
    }

    function showStep(n) {
        const steps = document.querySelectorAll('.step-content');
        const indicators = document.querySelectorAll('.step-item');
        
        steps.forEach(step => step.classList.remove('active'));
        indicators.forEach(indicator => indicator.classList.remove('active'));
        
        steps[n - 1].classList.add('active');
        indicators[n - 1].classList.add('active');
        
        updateStepIndicator();
        
        // Ensure tag input is initialized when showing step 1
        if (n === 1) {
            setTimeout(function() {
                if ($('.aiz-tag-input').length > 0 && !$('.aiz-tag-input').hasClass('tagsinput')) {
                    $('.aiz-tag-input').tagsinput({
                        tagClass: 'badge badge-light',
                        allowDuplicates: false
                    });
                }
            }, 100);
        }
        
        validateStep(n);
        window.scrollTo(0, 0);
    }

    function updateStepIndicator() {
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const submitButtons = document.getElementById('submitButtons');
        
        prevBtn.style.display = currentStep === 1 ? 'none' : 'block';
        nextBtn.style.display = currentStep === totalSteps ? 'none' : 'block';
        submitButtons.style.display = currentStep === totalSteps ? 'block' : 'none';
    }

    // Shipping Type Toggle
    $("[name=shipping_type]").on("change", function() {
        $(".flat_rate_shipping_div").hide();
        if($(this).val() == 'flat_rate'){
            $(".flat_rate_shipping_div").show();
        }
    });

    // Colors Toggle
    $('input[name="colors_active"]').on('change', function() {
        if(!$('input[name="colors_active"]').is(':checked')) {
            $('#colors').prop('disabled', true);
            if(typeof AIZ !== 'undefined') {
                AIZ.plugins.bootstrapSelect('refresh');
            }
        } else {
            $('#colors').prop('disabled', false);
            if(typeof AIZ !== 'undefined') {
                AIZ.plugins.bootstrapSelect('refresh');
            }
        }
        update_sku();
    });

    // Attribute Changes
    $(document).on("change", ".attribute_choice", function() {
        update_sku();
    });

    $('#colors').on('change', function() {
        update_sku();
    });

    $('input[name="unit_price"]').on('keyup', function() {
        update_sku();
    });

    $('input[name="name"]').on('keyup', function() {
        update_sku();
    });

    // Choice Attributes
    $('#choice_attributes').on('change', function() {
        $('#customer_choice_options').html(null);
        $.each($("#choice_attributes option:selected"), function(){
            add_more_customer_choice_option($(this).val(), $(this).text());
        });
        update_sku();
    });

    function add_more_customer_choice_option(i, name){
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:"POST",
            url:'{{ route('products.add-more-choice-option') }}',
            data:{
               attribute_id: i
            },
            success: function(data) {
                var obj = JSON.parse(data);
                $('#customer_choice_options').append('\
                <div class="form-group row">\
                    <div class="col-md-3">\
                        <input type="hidden" name="choice_no[]" value="'+i+'">\
                        <input type="text" class="form-control" name="choice[]" value="'+name+'" placeholder="{{ translate('Choice Title') }}" readonly>\
                    </div>\
                    <div class="col-md-8">\
                        <select class="form-control aiz-selectpicker attribute_choice" data-live-search="true" name="choice_options_'+ i +'[]" multiple>\
                            '+obj+'\
                        </select>\
                    </div>\
                </div>');
                if(typeof AIZ !== 'undefined') {
                    AIZ.plugins.bootstrapSelect('refresh');
                }
           }
       });
    }

    function update_sku(){
        $.ajax({
           type:"POST",
           url:'{{ route('products.sku_combination') }}',
           data:$('#choice_form').serialize(),
           success: function(data) {
                $('#sku_combination').html(data);
                if(typeof AIZ !== 'undefined') {
                    AIZ.uploader.previewGenerate();
                    AIZ.plugins.fooTable();
                }
           }
        });
    }

    // Form Submit - Clean and Validate Data
    $('form').bind('submit', function (e) {
        // Ensure all numeric fields have valid values
        $('.tax-input').each(function() {
            if($(this).val() === '' || $(this).val() === null) {
                $(this).val('0');
            }
        });

        // Set default 0 for discount if empty
        if($('input[name="discount"]').val() === '' || $('input[name="discount"]').val() === null) {
            $('input[name="discount"]').val('0');
        }

        // Set default 0 for flash_discount if empty
        if($('input[name="flash_discount"]').val() === '' || $('input[name="flash_discount"]').val() === null) {
            $('input[name="flash_discount"]').val('0');
        }

        // Set default 0 for weight if empty
        if($('input[name="weight"]').val() === '' || $('input[name="weight"]').val() === null) {
            $('input[name="weight"]').val('0');
        }

        // Set default 0 for flat_shipping_cost if empty
        if($('input[name="flat_shipping_cost"]').val() === '' || $('input[name="flat_shipping_cost"]').val() === null) {
            $('input[name="flat_shipping_cost"]').val('0');
        }

        if ( $(".action-btn").attr('attempted') == 'true' ) {
            e.preventDefault();
        } else {
            $(".action-btn").attr("attempted", 'true');
        }
    });

    // Initialize on document ready
    showStep(1);

</script>

@endsection

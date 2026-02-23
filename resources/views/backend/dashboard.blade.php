@extends('backend.layouts.app')

@section('content')
@if(auth()->user()->can('smtp_settings') && env('MAIL_USERNAME') == null && env('MAIL_PASSWORD') == null)
    <div class="">
        <div class="alert alert-info d-flex align-items-center">
            {{translate('Please Configure SMTP Setting to work all email sending functionality')}},
            <a class="alert-link ml-2" href="{{ route('smtp_settings.index') }}">{{ translate('Configure Now') }}</a>
        </div>
    </div>
@endif
@can('admin_dashboard')
{{-- ── Barra de acceso rápido ── --}}
<style>
    .quick-access-bar {
        display        : flex;
        align-items    : center;
        gap            : 10px;
        flex-wrap      : wrap;
        padding        : 12px 16px;
        margin-bottom  : 16px;
        background     : #e8edf2;
        border-radius  : 16px;
        box-shadow     :
            6px 6px 14px rgba(163,177,198,0.55),
           -5px -5px 12px rgba(255,255,255,0.88);
    }

    .quick-access-bar .qa-label {
        font-size      : 11px;
        font-weight    : 600;
        color          : #8a9bb5;
        font-family    : 'Poppins', sans-serif;
        letter-spacing : 1px;
        text-transform : uppercase;
        margin-right   : 4px;
        white-space    : nowrap;
    }

    .qa-btn {
        display        : flex;
        align-items    : center;
        gap            : 6px;
        padding        : 7px 14px;
        border-radius  : 10px;
        font-size      : 12px;
        font-weight    : 600;
        font-family    : 'Poppins', sans-serif;
        text-decoration: none !important;
        border         : none;
        cursor         : pointer;
        transition     : all 0.25s ease;
        background     : #e8edf2;
        box-shadow     :
            4px 4px 9px rgba(163,177,198,0.50),
           -3px -3px 7px rgba(255,255,255,0.85);
        white-space    : nowrap;
    }

    .qa-btn:hover {
        box-shadow     :
            inset 3px 3px 7px rgba(163,177,198,0.45),
            inset -2px -2px 5px rgba(255,255,255,0.75);
        transform      : translateY(1px);
        text-decoration: none !important;
    }

    .qa-btn i { font-size: 15px; }

    .qa-purple { color: #7c3aed; }
    .qa-blue   { color: #2563eb; }
    .qa-pink   { color: #db2777; }
    .qa-orange { color: #ea580c; }
    .qa-green  { color: #16a34a; }
    .qa-teal   { color: #0d9488; }

    .qa-divider {
        width      : 1px;
        height     : 28px;
        background : rgba(163,177,198,0.4);
        flex-shrink: 0;
    }
</style>

<div class="quick-access-bar">
    <span class="qa-label"><i class="las la-bolt"></i> Acceso rápido</span>
    <div class="qa-divider"></div>

    <a href="{{ url('admin/products') }}" class="qa-btn qa-purple">
        <i class="las la-box"></i> Vender en pos
    </a>

    <a href="{{ url('admin/orders') }}" class="qa-btn qa-blue">
        <i class="las la-shopping-cart"></i> Agregar productos
    </a>

    <a href="{{ url('admin/customers') }}" class="qa-btn qa-pink">
        <i class="las la-users"></i> Ver pedidos online
    </a>

    <a href="{{ url('admin/categories') }}" class="qa-btn qa-orange">
        <i class="las la-th-large"></i> Crear blogs
    </a>

    <a href="{{ url('admin/brands') }}" class="qa-btn qa-green">
        <i class="las la-tag"></i> Pedidos

    <div class="qa-divider"></div>

    <a href="{{ url('admin/sellers') }}" class="qa-btn qa-teal">
        <i class="las la-store"></i> Informes
    </a>
</div>





<!-- CUADROS -->
<div class="row gutters-10">
<div class="col-lg-6">
<style>
   
    .neuro-stat-card {
        position      : relative;
        min-height    : 175px;
        border-radius : 20px;
        overflow      : hidden;
        transition    : all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        background    : #e8edf2;
        box-shadow    :
            8px 8px 18px rgba(163,177,198,0.65),
           -6px -6px 14px rgba(255,255,255,0.90);
    }

    .neuro-stat-card:hover {
        transform  : translateY(-10px) scale(1.02);
        box-shadow :
            14px 14px 28px rgba(163,177,198,0.55),
           -8px -8px 18px rgba(255,255,255,0.95);
    }

    /* ── Contenido ── */
    .neuro-stat-content {
        position : relative;
        z-index  : 2;
        padding  : 1.4rem 1.4rem 5rem;
    }

    .neuro-stat-label {
        font-size   : 10px;
        font-weight : 300;
        font-family : 'Poppins', sans-serif;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        opacity     : 0.85;
        display     : block;
        margin-bottom: 2px;
    }

    .neuro-stat-title {
        font-size   : 13px;
        font-weight : 600;
        font-family : 'Poppins', sans-serif;
        opacity     : 0.9;
    }

    .neuro-stat-number {
        font-size    : 48px;
        font-weight  : 800;
        font-family  : 'Poppins', sans-serif;
        line-height  : 1;
        margin-top   : 6px;
        /* Número en relieve neumórfico */
        text-shadow  :
            2px 2px 4px rgba(0,0,0,0.15),
           -1px -1px 3px rgba(255,255,255,0.5);
    }

    /* ── Ola SVG ── */
    .neuro-wave {
        position       : absolute;
        bottom         : 0;
        left           : 0;
        width          : 300%;
        height         : 55%;
        z-index        : 1;
        pointer-events : none;
        will-change    : transform;
    }

    /* ── Icono decorativo ── */
    .neuro-stat-icon {
        position      : absolute;
        top           : 16px;
        right         : 16px;
        width         : 38px;
        height        : 38px;
        border-radius : 12px;
        display       : flex;
        align-items   : center;
        justify-content: center;
        z-index       : 3;
        background    : rgba(255,255,255,0.25);
        box-shadow    :
            3px 3px 8px rgba(0,0,0,0.15),
           -2px -2px 6px rgba(255,255,255,0.35);
        backdrop-filter: blur(4px);
    }

    .neuro-stat-icon i {
        font-size : 18px;
        color     : rgba(255,255,255,0.95);
    }

    /* ── Animaciones de ola ── */
    .wave-normal  .neuro-wave { animation: waveFlow  9s  linear infinite; }
    .wave-slow    .neuro-wave { animation: waveFlow  22s linear infinite; }
    .wave-fast    .neuro-wave { animation: waveFlow  6s  linear infinite; }
    .wave-reverse .neuro-wave { animation: waveFlowR 14s linear infinite; }

    @keyframes waveFlow {
        0%   { transform: translateX(0%);       }
        100% { transform: translateX(-33.333%); }
    }
    @keyframes waveFlowR {
        0%   { transform: translateX(-33.333%); }
        100% { transform: translateX(0%);       }
    }

    /* ── Animación de entrada por card ── */
    @keyframes cardEntrance {
        0%   { opacity:0; transform: translateY(30px) scale(0.92); }
        60%  { transform: translateY(-6px) scale(1.02);             }
        100% { opacity:1; transform: translateY(0) scale(1);        }
    }

    .neuro-stat-card {
        animation        : cardEntrance 0.7s cubic-bezier(0.34,1.56,0.64,1) both;
        animation-fill-mode: both;
    }

    /* Delay escalonado para cada card */
    .col-6:nth-child(1) .neuro-stat-card { animation-delay: 0.0s; }
    .col-6:nth-child(2) .neuro-stat-card { animation-delay: 0.15s; }
    .col-6:nth-child(3) .neuro-stat-card { animation-delay: 0.30s; }
    .col-6:nth-child(4) .neuro-stat-card { animation-delay: 0.45s; }

    /* ── Colores ── */
    /* Clientes: violeta/índigo */
    .neuro-card-1 { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; }
    /* Órdenes: azul/cian */
    .neuro-card-2 { background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%) !important; }
    /* Categorías: magenta/rosa */
    .neuro-card-3 { background: linear-gradient(135deg, #f953c6 0%, #b91d73 100%) !important; }
    /* Marcas: naranja/amarillo */
    .neuro-card-4 { background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%) !important; }

    /* ── Brillo interior neumórfico sobre gradiente ── */
    .neuro-stat-card::before {
        content       : '';
        position      : absolute;
        top           : 0; left: 0;
        right         : 0; bottom: 0;
        border-radius : 20px;
        background    : linear-gradient(135deg,
            rgba(255,255,255,0.22) 0%,
            rgba(255,255,255,0)   60%);
        z-index       : 1;
        pointer-events: none;
    }

    .row > .col-6 { margin-bottom: 1rem; }
</style>

    <div class="row g-3">

        <!-- Clientes -->
        <div class="col-6">
            <div class="neuro-stat-card neuro-card-1 wave-normal text-white">
                <div class="neuro-stat-icon">
                    <i class="las la-users"></i>
                </div>
                <div class="neuro-stat-content">
                    <span class="neuro-stat-label">Total</span>
                    <span class="neuro-stat-title">Clientes</span>
                    <div class="neuro-stat-number">
                        {{ \App\Models\User::where('user_type','customer')->where('email_verified_at','!=',null)->count() }}
                    </div>
                </div>
                <svg class="neuro-wave" preserveAspectRatio="none" viewBox="0 0 1440 320" xmlns="http://www.w3.org/2000/svg">
                    <path fill="rgba(255, 255, 255, 0.44)" d="M0,160L48,176C96,192,192,224,288,213.3C384,203,480,149,576,149.3C672,149,768,203,864,213.3C960,224,1056,192,1152,181.3C1248,171,1344,181,1392,186.7L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"/>
                    <path fill="rgba(255, 255, 255, 0.27)" d="M0,224L48,213.3C96,203,192,181,288,192C384,203,480,245,576,240C672,235,768,181,864,165.3C960,149,1056,171,1152,186.7C1248,203,1344,213,1392,218.7L1440,224L1440,320L0,320Z"/>
                </svg>
            </div>
        </div>

        <!-- Órdenes -->
        <div class="col-6">
            <div class="neuro-stat-card neuro-card-2 wave-slow text-white">
                <div class="neuro-stat-icon">
                    <i class="las la-shopping-cart"></i>
                </div>
                <div class="neuro-stat-content">
                    <span class="neuro-stat-label">Total</span>
                    <span class="neuro-stat-title">Órdenes</span>
                    <div class="neuro-stat-number">
                        {{ \App\Models\Order::count() }}
                    </div>
                </div>
                <svg class="neuro-wave" preserveAspectRatio="none" viewBox="0 0 1440 320" xmlns="http://www.w3.org/2000/svg">
                    <path fill="rgba(255, 255, 255, 0.44)" d="M0,96L48,112C96,128,192,160,288,176C384,192,480,192,576,176C672,160,768,128,864,128C960,128,1056,160,1152,176C1248,192,1344,192,1392,192L1440,192L1440,320L0,320Z"/>
                    <path fill="rgba(255, 255, 255, 0.27)" d="M0,192L60,181.3C120,171,240,149,360,154.7C480,160,600,192,720,197.3C840,203,960,181,1080,170.7C1200,160,1320,160,1380,160L1440,160L1440,320L0,320Z"/>
                </svg>
            </div>
        </div>

        <!-- Categorías -->
        <div class="col-6">
            <div class="neuro-stat-card neuro-card-3 wave-fast text-white">
                <div class="neuro-stat-icon">
                    <i class="las la-th-large"></i>
                </div>
                <div class="neuro-stat-content">
                    <span class="neuro-stat-label">Total</span>
                    <span class="neuro-stat-title">Categorías</span>
                    <div class="neuro-stat-number">
                        {{ \App\Models\Category::count() }}
                    </div>
                </div>
                <svg class="neuro-wave" preserveAspectRatio="none" viewBox="0 0 1440 320" xmlns="http://www.w3.org/2000/svg">
                    <path fill="rgba(255, 255, 255, 0.44)" d="M0,192L48,176C96,160,192,128,288,138.7C384,149,480,203,576,213.3C672,224,768,192,864,170.7C960,149,1056,139,1152,154.7C1248,171,1344,213,1392,234.7L1440,256L1440,320L0,320Z"/>
                    <path fill="rgba(255, 255, 255, 0.27)" d="M0,256L48,245.3C96,235,192,213,288,208C384,203,480,213,576,224C672,235,768,245,864,234.7C960,224,1056,192,1152,181.3C1248,171,1344,181,1392,186.7L1440,192L1440,320L0,320Z"/>
                </svg>
            </div>
        </div>

        <!-- Marcas -->
        <div class="col-6">
            <div class="neuro-stat-card neuro-card-4 wave-reverse text-white">
                <div class="neuro-stat-icon">
                    <i class="las la-tag"></i>
                </div>
                <div class="neuro-stat-content">
                    <span class="neuro-stat-label">Total</span>
                    <span class="neuro-stat-title">Marcas</span>
                    <div class="neuro-stat-number">
                        {{ \App\Models\Brand::count() }}
                    </div>
                </div>
                <svg class="neuro-wave" preserveAspectRatio="none" viewBox="0 0 1440 320" xmlns="http://www.w3.org/2000/svg">
                    <path fill="rgba(255, 255, 255, 0.44)" d="M0,128L48,149.3C96,171,192,213,288,202.7C384,192,480,128,576,117.3C672,107,768,149,864,176C960,203,1056,213,1152,197.3C1248,181,1344,139,1392,117.3L1440,96L1440,320L0,320Z"/>
                    <path fill="rgba(255, 255, 255, 0.27)" d="M0,64L48,85.3C96,107,192,149,288,160C384,171,480,149,576,138.7C672,128,768,128,864,149.3C960,171,1056,213,1152,213.3C1248,213,1344,171,1392,149.3L1440,128L1440,320L0,320Z"/>
                </svg>
            </div>
        </div>

    </div>
</div>






<!-- DONA -->
<style>
.col-lg-6 .row.gutters-10 .card {
    background    : #e8edf2;
    border-radius : 20px;
    border        : none;
    transition    : all 0.4s ease;
    overflow      : hidden;
}

.col-lg-6 .row.gutters-10 .card:hover {
    box-shadow :
        6px 6px 20px rgba(163,177,198,0.4),
       -6px -6px 20px rgba(255,255,255,1);
    transform  : translateY(-4px);
}

.col-lg-6 .row.gutters-10 .card .card-header {
    background    : transparent;
    border-bottom : none;
    padding       : 1rem 1.5rem;
}

.col-lg-6 .row.gutters-10 .card .card-body {
    background : transparent;
    padding    : 1rem 1.5rem 1.5rem;
}

.pie-canvas-wrap {
    display         : flex;
    justify-content : center;
    align-items     : center;
    max-width       : 280px;
    margin          : 0 auto;
}

/* ── Animación de entrada ── */
@keyframes donutEntrance {
    0%   { opacity: 0; transform: scale(0.2) rotate(-180deg); }
    60%  { opacity: 1; transform: scale(1.06) rotate(8deg);   }
    80%  { transform: scale(0.97) rotate(-3deg);               }
    100% { opacity: 1; transform: scale(1) rotate(0deg);       }
}

#pie-1 {
    animation      : donutEntrance 1.8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    transform-origin: center center;
}

body.dark-mode .col-lg-6 .row.gutters-10 .card,
.dark .col-lg-6 .row.gutters-10 .card {
    background : #2c2c2e;
    box-shadow :
        10px 10px 30px #1e1e20,
       -10px -10px 30px #3a3a3c;
}

body.dark-mode .col-lg-6 .row.gutters-10 .card:hover,
.dark .col-lg-6 .row.gutters-10 .card:hover {
    box-shadow :
        6px 6px 20px #18181a,
       -6px -6px 20px #404042;
}
</style>
</style>
    <div class="col-lg-6">
        <div class="row gutters-10">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0 fs-14">{{ translate('Products') }}</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="pie-1" class="w-100" height="280"></canvas>
                    </div>
                </div>
            </div>
        
        </div>
    </div>
</div>

<!-- dona -->











<!-- BARRAS-->
<style>
   
    .neuro-card {
        background    : #e8edf2 !important;
        border-radius : 24px !important;
        border        : none !important;
        box-shadow    :
            8px 8px 18px rgba(163,177,198,0.65),
           -6px -6px 14px rgba(255,255,255,0.90) !important;
        overflow      : hidden;
    }

 
    .neuro-header-blue {
        background : linear-gradient(145deg, #dde8f5 0%, #e8edf2 100%);
        border-bottom: 1px solid rgba(163,177,198,0.2);
    }

  
    .neuro-header-pink {
        background : linear-gradient(145deg, #f5dded 0%, #e8edf2 100%);
        border-bottom: 1px solid rgba(163,177,198,0.2);
    }

 
    .neuro-body {
        background : #e8edf2 !important;
    }

   
    .neuro-icon-blue {
        width       : 44px;
        height      : 44px;
        border-radius: 14px;
        background  : linear-gradient(145deg, #b8cef5, #7ea8e8);
        box-shadow  :
            4px 4px 10px rgba(100,130,200,0.45),
           -3px -3px  8px rgba(255,255,255,0.80);
        display     : flex;
        align-items : center;
        justify-content: center;
    }

    
    .neuro-icon-pink {
        width       : 44px;
        height      : 44px;
        border-radius: 14px;
        background  : linear-gradient(145deg, #f0a8cc, #e060a0);
        box-shadow  :
            4px 4px 10px rgba(200,80,140,0.45),
           -3px -3px  8px rgba(255,255,255,0.80);
        display     : flex;
        align-items : center;
        justify-content: center;
    }

    /* ── Badge neumórfico azul ── */
    .neuro-badge-blue {
        background   : #e8edf2;
        color        : #4a6fa5;
        border-radius: 10px;
        padding      : 5px 14px;
        font-size    : 11px;
        font-family  : 'Poppins', sans-serif;
        box-shadow   :
            3px 3px 7px rgba(163,177,198,0.55),
           -2px -2px 6px rgba(255,255,255,0.85);
    }

  
    .neuro-badge-pink {
        background   : #e8edf2;
        color        : #a0456e;
        border-radius: 10px;
        padding      : 5px 14px;
        font-size    : 11px;
        font-family  : 'Poppins', sans-serif;
        box-shadow   :
            3px 3px 7px rgba(163,177,198,0.55),
           -2px -2px 6px rgba(255,255,255,0.85);
    }

    .neuro-canvas-wrap {
        background   : #e8edf2;
        border-radius: 16px;
        padding      : 12px;
        box-shadow   :
            inset 4px 4px 10px rgba(163,177,198,0.45),
            inset -3px -3px  8px rgba(255,255,255,0.80);
    }


    .neuro-title-blue {
        font-size  : 15px;
        font-weight: 700;
        color      : #2c4a7c;
        font-family: 'Poppins', sans-serif;
    }


    .neuro-title-pink {
        font-size  : 15px;
        font-weight: 700;
        color      : #7c2c50;
        font-family: 'Poppins', sans-serif;
    }

    .neuro-subtitle {
        color      : #8a9bb5;
        font-size  : 12px;
        font-family: 'Poppins', sans-serif;
    }
</style>


<div class="row gutters-10">

    <!-- ── Gráfico 1: Ventas por categoría ── -->
    <div class="col-md-6 mb-4">
        <div class="card neuro-card">

            <div class="card-header neuro-header-blue border-0 pb-0 pt-4 px-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="neuro-icon-blue">
                        <i class="las la-chart-bar" style="color:#fff; font-size:20px;"></i>
                    </div>
                    <div>
                        <p class="mb-0 neuro-title-blue">
                            {{ translate('Category wise product sale') }}
                        </p>
                        <small class="neuro-subtitle">
                            {{ translate('Rendimiento por categoría') }}
                        </small>
                    </div>
                </div>
                <div class="d-flex gap-3 pb-3">
                    <span class="neuro-badge-blue">
                        <i class="las la-arrow-up"></i>
                        {{ translate('Ventas activas') }}
                    </span>
                </div>
            </div>

            <div class="card-body neuro-body px-4 pb-4 pt-3">
                <div class="neuro-canvas-wrap">
                    <canvas id="graph-1" class="w-100" height="360"></canvas>
                </div>
            </div>

        </div>
    </div>

    <!-- ── Gráfico 2: Stock por categoría ── -->
    <div class="col-md-6 mb-4">
        <div class="card neuro-card">

            <div class="card-header neuro-header-pink border-0 pb-0 pt-4 px-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="neuro-icon-pink">
                        <i class="las la-boxes" style="color:#fff; font-size:20px;"></i>
                    </div>
                    <div>
                        <p class="mb-0 neuro-title-pink">
                            {{ translate('Category wise product stock') }}
                        </p>
                        <small class="neuro-subtitle">
                            {{ translate('Inventario por categoría') }}
                        </small>
                    </div>
                </div>
                <div class="d-flex gap-3 pb-3">
                    <span class="neuro-badge-pink">
                        <i class="las la-warehouse"></i>
                        {{ translate('Stock disponible') }}
                    </span>
                </div>
            </div>

            <div class="card-body neuro-body px-4 pb-4 pt-3">
                <div class="neuro-canvas-wrap">
                    <canvas id="graph-2" class="w-100" height="360"></canvas>
                </div>
            </div>

        </div>
    </div>

</div>
<!-- BARRAS-->





 <!-- imagen-->
 
<style>
    /* ── Card principal ── */
    .neuro-top-card {
        background    : #e8edf2 !important;
        border-radius : 24px !important;
        border        : none !important;
        box-shadow    :
            8px 8px 18px rgba(163,177,198,0.65),
           -6px -6px 14px rgba(255,255,255,0.90) !important;
    }

    /* ── Header ── */
    .neuro-top-header {
        background    : linear-gradient(145deg, #dde8f5 0%, #e8edf2 100%);
        border-bottom : 1px solid rgba(163,177,198,0.2) !important;
        border-radius : 24px 24px 0 0 !important;
        padding       : 18px 24px !important;
        display       : flex;
        align-items   : center;
        gap           : 10px;
    }

    .neuro-top-header-icon {
        width          : 38px;
        height         : 38px;
        border-radius  : 12px;
        background     : linear-gradient(145deg, #b8cef5, #7ea8e8);
        box-shadow     :
            4px 4px 10px rgba(100,130,200,0.45),
           -3px -3px  8px rgba(255,255,255,0.80);
        display        : flex;
        align-items    : center;
        justify-content: center;
        flex-shrink    : 0;
    }

    .neuro-top-header h6 {
        font-size   : 15px;
        font-weight : 700;
        color       : #2c4a7c;
        font-family : 'Poppins', sans-serif;
        margin      : 0;
    }

    .neuro-top-header small {
        color       : #8a9bb5;
        font-size   : 11px;
        font-family : 'Poppins', sans-serif;
    }

    /* ── Body ── */
    .neuro-top-body {
        background    : #e8edf2 !important;
        padding       : 20px !important;
    }


    .neuro-product-card {
        background    : #e8edf2 !important;
        border        : none !important;
        border-radius : 18px !important;
        box-shadow    :
            6px 6px 14px rgba(163,177,198,0.60),
           -5px -5px 12px rgba(255,255,255,0.88) !important;
        transition    : all 0.3s ease !important;
        overflow      : hidden;
        margin-bottom : 8px;
    }

    .neuro-product-card:hover {
        box-shadow    :
            3px 3px 8px rgba(163,177,198,0.50),
           -2px -2px 6px rgba(255,255,255,0.80),
            inset 2px 2px 6px rgba(163,177,198,0.25),
            inset -2px -2px 5px rgba(255,255,255,0.70) !important;
        transform     : translateY(2px);
    }

    /* ── Imagen con sombra interna ── */
    .neuro-product-img-wrap {
        background    : #e8edf2;
        padding       : 10px;
    }

    .neuro-product-img-wrap a {
        display       : block;
        border-radius : 12px;
        overflow      : hidden;
        box-shadow    :
            inset 3px 3px 8px rgba(163,177,198,0.40),
            inset -2px -2px 6px rgba(255,255,255,0.75);
    }

    .neuro-product-img-wrap img {
        border-radius : 12px;
        transition    : transform 0.4s ease;
    }

    .neuro-product-card:hover .neuro-product-img-wrap img {
        transform     : scale(1.04);
    }

    /* ── Info del producto ── */
    .neuro-product-info {
        padding       : 12px 14px 14px;
        background    : #e8edf2;
    }

    /* ── Precio ── */
    .neuro-price-wrap {
        display       : flex;
        align-items   : center;
        gap           : 6px;
        margin-bottom : 5px;
    }

    .neuro-price-old {
        font-size     : 11px;
        color         : #a0aec0;
        text-decoration: line-through;
        font-family   : 'Poppins', sans-serif;
    }

    .neuro-price-new {
        font-size     : 14px;
        font-weight   : 700;
        font-family   : 'Poppins', sans-serif;
        color         : #4a6fa5;
        background    : #e8edf2;
        padding       : 2px 10px;
        border-radius : 8px;
        box-shadow    :
            3px 3px 7px rgba(163,177,198,0.50),
           -2px -2px 5px rgba(255,255,255,0.85);
    }

    /* ── Rating ── */
    .neuro-rating {
        margin-bottom : 5px;
    }

    /* ── Nombre producto ── */
    .neuro-product-name {
        font-size     : 12px;
        font-weight   : 600;
        color         : #3d4f6e;
        font-family   : 'Poppins', sans-serif;
        line-height   : 1.4;
        display       : -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow      : hidden;
        margin        : 0;
    }

    .neuro-product-name a {
        color         : #3d4f6e !important;
        text-decoration: none;
    }

    .neuro-product-name a:hover {
        color         : #4a6fa5 !important;
    }

    /* ── Badge TOP ── */
    .neuro-rank-badge {
        position      : absolute;
        top           : 14px;
        left          : 14px;
        width         : 26px;
        height        : 26px;
        border-radius : 8px;
        background    : #e8edf2;
        box-shadow    :
            3px 3px 7px rgba(163,177,198,0.55),
           -2px -2px 5px rgba(255,255,255,0.85);
        display       : flex;
        align-items   : center;
        justify-content: center;
        font-size     : 10px;
        font-weight   : 700;
        color         : #4a6fa5;
        font-family   : 'Poppins', sans-serif;
        z-index       : 2;
    }

    /* ── Flechas del carrusel ── */
    .neuro-top-card .slick-prev,
    .neuro-top-card .slick-next {
        background    : #e8edf2 !important;
        border-radius : 50% !important;
        width         : 36px !important;
        height        : 36px !important;
        box-shadow    :
            4px 4px 10px rgba(163,177,198,0.55),
           -3px -3px  8px rgba(255,255,255,0.85) !important;
    }

    .neuro-top-card .slick-prev:hover,
    .neuro-top-card .slick-next:hover {
        box-shadow    :
            inset 3px 3px 7px rgba(163,177,198,0.45),
            inset -2px -2px 5px rgba(255,255,255,0.75) !important;
    }
    .neuro-top-header {
    justify-content: flex-start !important;
    text-align: left !important;
}
</style>


<div class="card neuro-top-card">

    {{-- Header --}}
    <div class="card-header neuro-top-header border-0">
        <div class="neuro-top-header-icon">
            <i class="las la-trophy" style="color:#fff; font-size:18px;"></i>
        </div>
        <div>
            <h6>{{ translate('Top 12 Products') }}</h6>
            <small>{{ translate('Productos más vendidos') }}</small>
        </div>
    </div>

    {{-- Body --}}
    <div class="card-body neuro-top-body">
        <div class="aiz-carousel gutters-10 half-outside-arrow"
             data-items="6"
             data-xl-items="5"
             data-lg-items="4"
             data-md-items="3"
             data-sm-items="2"
             data-arrows='true'>

            @foreach (filter_products(\App\Models\Product::where('published', 1)->orderBy('num_of_sale', 'desc'))->limit(12)->get() as $key => $product)
                <div class="carousel-box">
                    <div class="neuro-product-card has-transition">

                        {{-- Imagen --}}
                        <div class="neuro-product-img-wrap position-relative">

                            {{-- Badge de ranking --}}
                            <div class="neuro-rank-badge">#{{ $key + 1 }}</div>

                            <a href="{{ route('product', $product->slug) }}">
                                <img
                                    class="img-fit lazyload mx-auto h-210px"
                                    src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                    data-src="{{ uploaded_asset($product->thumbnail_img) }}"
                                    alt="{{ $product->getTranslation('name') }}"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                >
                            </a>
                        </div>

                        {{-- Info --}}
                        <div class="neuro-product-info">

                            {{-- Precio --}}
                            <div class="neuro-price-wrap">
                                @if(home_base_price($product) != home_discounted_base_price($product))
                                    <span class="neuro-price-old">{{ home_base_price($product) }}</span>
                                @endif
                                <span class="neuro-price-new">{{ home_discounted_base_price($product) }}</span>
                            </div>

                            {{-- Rating --}}
                            <div class="neuro-rating rating rating-sm">
                                {{ renderStarRating($product->rating) }}
                            </div>

                            {{-- Nombre --}}
                            <h3 class="neuro-product-name">
                                <a href="{{ route('product', $product->slug) }}">
                                    {{ $product->getTranslation('name') }}
                                </a>
                            </h3>

                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</div>





@endcan




@endsection
@section('script')
<script type="text/javascript">

function adjustColor(color, amount) {
    if (!color || color === 'transparent') return color;
    var hex = color;
    if (color.indexOf('rgb') === 0) {
        var parts = color.match(/\d+/g);
        if (!parts) return color;
        hex = '#' +
            ('0' + parseInt(parts[0]).toString(16)).slice(-2) +
            ('0' + parseInt(parts[1]).toString(16)).slice(-2) +
            ('0' + parseInt(parts[2]).toString(16)).slice(-2);
    }
    if (hex[0] !== '#') return color;
    var num = parseInt(hex.slice(1), 16);
    var r   = Math.min(255, Math.max(0, (num >> 16)         + amount));
    var g   = Math.min(255, Math.max(0, ((num >> 8) & 0xff) + amount));
    var b   = Math.min(255, Math.max(0, (num & 0xff)        + amount));
    return '#' +
        ('0' + r.toString(16)).slice(-2) +
        ('0' + g.toString(16)).slice(-2) +
        ('0' + b.toString(16)).slice(-2);
}


var hoveredDatasetIndex = -1;
var hoveredIndex        = -1;


Chart.pluginService.register({
    afterDatasetsDraw: function(chart) {
        if (chart.config.type !== 'doughnut') return;
        if (hoveredIndex === -1 || hoveredDatasetIndex === -1) return;

        var ctx  = chart.ctx;
        var meta = chart.getDatasetMeta(hoveredDatasetIndex);
        if (!meta || meta.hidden) return;

        var arc = meta.data[hoveredIndex];
        if (!arc) return;

        var vm         = arc._view;
        var startAngle = vm.startAngle;
        var endAngle   = vm.endAngle;

        ctx.save();
        ctx.translate(vm.x, vm.y);
        ctx.scale(1.08, 1.08);
        ctx.translate(-vm.x, -vm.y);

        ctx.shadowColor   = 'rgba(0,0,0,0.30)';
        ctx.shadowBlur    = 20;
        ctx.shadowOffsetX = 0;
        ctx.shadowOffsetY = 6;

        ctx.beginPath();
        ctx.arc(vm.x, vm.y, vm.outerRadius, startAngle, endAngle);
        ctx.arc(vm.x, vm.y, vm.innerRadius,  endAngle, startAngle, true);
        ctx.closePath();

        var grad = ctx.createRadialGradient(
            vm.x, vm.y, vm.innerRadius,
            vm.x, vm.y, vm.outerRadius
        );
        grad.addColorStop(0,   adjustColor(vm.backgroundColor, 70));
        grad.addColorStop(0.5, adjustColor(vm.backgroundColor, 20));
        grad.addColorStop(1,   adjustColor(vm.backgroundColor, -30));

        ctx.fillStyle   = grad;
        ctx.fill();
        ctx.shadowColor = 'transparent';
        ctx.strokeStyle = 'rgba(255,255,255,0.95)';
        ctx.lineWidth   = 2.5;
        ctx.stroke();
        ctx.restore();
    }
});



AIZ.plugins.chart('#pie-1', {
    type: 'doughnut',
    data: {
        labels: [
            '{{ translate('Total published products') }}',
            '{{ translate('Total sellers products') }}',
            '{{ translate('Total admin products') }}'
        ],
        datasets: [
            {
                data: [
                    {{ \App\Models\Product::where('published', 1)->count() }},
                    {{ \App\Models\Product::where('published', 1)->where('added_by', 'seller')->count() }},
                    {{ \App\Models\Product::where('published', 1)->where('added_by', 'admin')->count() }}
                ],
                backgroundColor: ['#eb0000', '#48ec8c', '#f97316'], //CAMBIO EL COLOR DE DONA (CURSOR)
                borderWidth: 3,
                borderColor: '#e8edf2',
                weight:      50,
                hoverOffset: 0
            },
            {
                data: [
                    {{ \App\Models\Product::where('published', 1)->count() }},
                    {{ \App\Models\Product::where('published', 1)->where('added_by', 'seller')->count() }},
                    {{ \App\Models\Product::where('published', 1)->where('added_by', 'admin')->count() }}
                ],
                backgroundColor: ['#c2281d', '#f472b6', '#fb923c'], //CAMBIO EL COLOR DE DONA (CURSOR)
                borderWidth: 3,
                borderColor: '#e8edf2',
                weight:      35,
                hoverOffset: 0
            },
            {
                data: [
                    {{ \App\Models\Product::where('published', 1)->count() }},
                    {{ \App\Models\Product::where('published', 1)->where('added_by', 'seller')->count() }},
                    {{ \App\Models\Product::where('published', 1)->where('added_by', 'admin')->count() }}
                ],
                backgroundColor: ['#df5151', '#fbcfe8', '#fed7aa'], //CAMBIO EL COLOR DE DONA (CURSOR)
                borderWidth: 3,
                borderColor: '#e8edf2',
                weight:      22,
                hoverOffset: 0
            }
        ]
    },
    options: {
        responsive:          true,
        maintainAspectRatio: true,
        rotation:            -90,
        circumference:       360,
        layout: {
            padding: { top: 20, bottom: 20, left: 20, right: 20 }
        },
        animation: {
            duration:      2500,
            easing:        'easeOutElastic',
            animateRotate: true,
            animateScale:  true,
            onProgress: function(animation) {
                var progress = animation.currentStep / animation.numSteps;
                var canvas   = animation.chart.canvas;
                canvas.style.opacity         = Math.min(1, progress * 1.5);
                canvas.style.transform       = 'scale(' + (0.3 + progress * 0.7) + ')';
                canvas.style.transformOrigin = 'center center';
            },
            onComplete: function() {
                var canvas          = this.chart.canvas;
                canvas.style.opacity    = '1';
                canvas.style.transform  = 'scale(1)';
                canvas.style.transition = 'none';
            }
        },
        hover: {
            animationDuration: 0,
            mode:             'dataset'
        },
        legend:   { display: false },
        tooltips: {
            backgroundColor: 'rgba(232,237,242,0.98)',
            titleFontFamily: 'Poppins',
            titleFontSize:    12,
            titleFontColor:  '#3d4f6e',
            bodyFontFamily:  'Poppins',
            bodyFontSize:     11,
            bodyFontColor:   '#5a6a85',
            borderColor:     'rgba(163,177,198,0.4)',
            borderWidth:      1,
            cornerRadius:     10,
            xPadding:         12,
            yPadding:          8,
            displayColors:    false,
            callbacks: {
                label: function(tooltipItem, data) {
                    var index = tooltipItem.index;
                    var value = data.datasets[0].data[index];
                    var total = data.datasets[0].data.reduce(function(a, b) { return a + b; }, 0);
                    var pct   = total > 0 ? Math.round((value / total) * 100) : 0;
                    return data.labels[index] + ': ' + value + ' (' + pct + '%)';
                }
            }
        },
        elements: {
            arc: { borderAlign: 'center', hoverBorderWidth: 0 }
        }
    }
});



(function setupHover() {
    var canvas = document.getElementById('pie-1');
    if (!canvas) return;

    canvas.style.opacity         = '0';
    canvas.style.transform       = 'scale(0.3)';
    canvas.style.transformOrigin = 'center center';

    setTimeout(function() {
        var chartInstance = null;
        Object.keys(Chart.instances).forEach(function(k) {
            if (Chart.instances[k].canvas.id === 'pie-1') {
                chartInstance = Chart.instances[k];
            }
        });
        if (!chartInstance) return;

        canvas.addEventListener('mousemove', function(e) {
            var elements = chartInstance.getElementsAtEventForMode(
                e, 'dataset', { intersect: true }
            );
            if (elements.length) {
                hoveredDatasetIndex = elements[0]._datasetIndex;
                hoveredIndex        = elements[0]._index;
            } else {
                hoveredDatasetIndex = -1;
                hoveredIndex        = -1;
            }
            chartInstance.update(0);
        });

        canvas.addEventListener('mouseleave', function() {
            hoveredDatasetIndex = -1;
            hoveredIndex        = -1;
            chartInstance.update(0);
        });

    }, 500);
})();


// ── Leyenda manual estática ──
(function buildLegend() {
    var canvas = document.getElementById('pie-1');
    var labels = [
        { color: '#6366f1', text: '{{ translate('Total published products') }}' },
        { color: '#ec4899', text: '{{ translate('Total sellers products') }}' },
        { color: '#f97316', text: '{{ translate('Total admin products') }}' }
    ];

    var wrap = document.createElement('div');
    wrap.style.cssText = [
        'display:flex', 'flex-wrap:wrap', 'justify-content:center',
        'gap:12px', 'margin-top:10px',
        'font-family:Poppins,sans-serif', 'font-size:11px'
    ].join(';');

    labels.forEach(function(l) {
        var item = document.createElement('div');
        item.style.cssText = 'display:flex;align-items:center;gap:6px;color:#5a6a85;';
        var dot = document.createElement('span');
        dot.style.cssText = [
            'width:10px', 'height:10px', 'border-radius:50%',
            'background:' + l.color, 'flex-shrink:0',
            'box-shadow:2px 2px 5px rgba(0,0,0,0.15)'
        ].join(';');
        item.appendChild(dot);
        item.appendChild(document.createTextNode(l.text));
        wrap.appendChild(item);
    });

    if (canvas && canvas.parentNode) {
        canvas.parentNode.appendChild(wrap);
    }
})();























   
     // BARRAS


var stripeOffset = 0;

(function animateStripes() {
    stripeOffset = (stripeOffset + 0.4) % 14;

    // Accede directamente al registro interno de Chart.js v2
    Object.keys(Chart.instances).forEach(function(key) {
        try {
            Chart.instances[key].update(0);
        } catch(e) {}
    });

    requestAnimationFrame(animateStripes);
})();



Chart.helpers.extend(Chart.elements.Rectangle.prototype, {
    draw: function() {
        var ctx       = this._chart.ctx;
        var vm        = this._view;
        var x         = vm.x;
        var width     = vm.width;
        var left      = x - width / 2;
        var right     = x + width / 2;
        var top       = vm.y;
        var bottom    = vm.base;
        var barHeight = Math.abs(bottom - top);
        var radius    = Math.min(width / 2, barHeight / 2);

        function pillarPath() {
            ctx.moveTo(left + radius, bottom);
            ctx.lineTo(right - radius, bottom);
            ctx.quadraticCurveTo(right, bottom, right, bottom - radius);
            ctx.lineTo(right, top + radius);
            ctx.quadraticCurveTo(right, top, right - radius, top);
            ctx.lineTo(left + radius, top);
            ctx.quadraticCurveTo(left, top, left, top + radius);
            ctx.lineTo(left, bottom - radius);
            ctx.quadraticCurveTo(left, bottom, left + radius, bottom);
            ctx.closePath();
        }

        // --- 1. Sombra neumórfica + relleno base ---
        ctx.save();
        ctx.shadowColor   = 'rgba(163,177,198,0.6)';
        ctx.shadowBlur    = 8;
        ctx.shadowOffsetX = 3;
        ctx.shadowOffsetY = 3;
        ctx.beginPath();
        pillarPath();
        ctx.fillStyle = vm.backgroundColor;
        ctx.fill();
        ctx.restore();

        // --- 2. Rayas diagonales ANIMADAS ---
        ctx.save();
        ctx.beginPath();
        pillarPath();
        ctx.clip();

        ctx.strokeStyle = 'rgba(255,255,255,0.20)';
        ctx.lineWidth   = 5;
        var step = 14;
        for (var i = left - barHeight + stripeOffset; i < right + barHeight; i += step) {
            ctx.beginPath();
            ctx.moveTo(i,             bottom + 10);
            ctx.lineTo(i + barHeight, top    - 10);
            ctx.stroke();
        }
        ctx.restore();

        // --- 3. Brillo lateral izquierdo ---
        ctx.save();
        ctx.beginPath();
        pillarPath();
        ctx.clip();
        var hlGrad = ctx.createLinearGradient(left, 0, right, 0);
        hlGrad.addColorStop(0,    'rgba(255,255,255,0.40)');
        hlGrad.addColorStop(0.35, 'rgba(255,255,255,0.12)');
        hlGrad.addColorStop(1,    'rgba(255,255,255,0)');
        ctx.fillStyle = hlGrad;
        ctx.beginPath();
        pillarPath();
        ctx.fill();
        ctx.restore();
    }
});



AIZ.plugins.chart('#graph-1', {
    type: 'bar',
    data: {
        labels: [
            @foreach ($root_categories as $key => $category)
            '{{ $category->getTranslation('name') }}',
            @endforeach
        ],
        datasets: [{
            label: '{{ translate('Ventas por categoría') }}',
            data: [{{ $cached_graph_data['num_of_sale_data'] }}],
            backgroundColor: function(context) {
                var chart     = context.chart;
                var ctx       = chart.ctx;
                var chartArea = chart.chartArea;
                if (!chartArea) return 'rgba(99,120,220,0.85)';
                var g = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                g.addColorStop(0,   'rgba(79, 98,210, 1)');
                g.addColorStop(0.5, 'rgba(118,140,230, 0.95)');
                g.addColorStop(1,   'rgba(165,185,255, 1)');
                return g;
            },
            borderWidth:        0,
            barPercentage:      0.5,
            categoryPercentage: 0.75,
            hoverBackgroundColor: function(context) {
                var chart     = context.chart;
                var ctx       = chart.ctx;
                var chartArea = chart.chartArea;
                if (!chartArea) return 'rgba(79,98,210,1)';
                var g = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                g.addColorStop(0, 'rgba(55, 75,200,1)');
                g.addColorStop(1, 'rgba(140,160,255,1)');
                return g;
            }
        }]
    },
    options: {
        responsive:          true,
        maintainAspectRatio: false,
        animation: { duration: 1800, easing: 'easeOutElastic' },
        scales: {
            yAxes: [{
                gridLines: {
                    color:         'rgba(163,177,198,0.25)',
                    zeroLineColor: 'rgba(163,177,198,0.25)',
                    drawBorder:    false,
                    drawTicks:     false,
                    borderDash:    [4, 6]
                },
                ticks: {
                    fontColor:     '#8a9bb5',
                    fontFamily:    'Poppins',
                    fontSize:       11,
                    beginAtZero:   true,
                    padding:        16,
                    maxTicksLimit:  6
                }
            }],
            xAxes: [{
                gridLines: { display: false },
                ticks: {
                    fontColor:     '#8a9bb5',
                    fontFamily:    'Poppins',
                    fontSize:       10,
                    padding:        10,
                    maxRotation:    35,
                    minRotation:    35,
                    autoSkip:       false
                }
            }]
        },
        legend: {
            display:  true,
            position: 'top',
            align:    'end',
            labels: {
                fontFamily:    'Poppins',
                fontSize:       12,
                fontColor:     '#5a6a85',
                boxWidth:       8,
                usePointStyle: true,
                padding:        20
            },
            onClick: function() { return ''; }
        },
        tooltips: {
            backgroundColor: 'rgba(232,237,242,0.98)',
            titleFontFamily: 'Poppins',
            titleFontSize:    13,
            titleFontColor:  '#3d4f6e',
            bodyFontFamily:  'Poppins',
            bodyFontSize:     12,
            bodyFontColor:   '#5a6a85',
            borderColor:     'rgba(163,177,198,0.4)',
            borderWidth:      1,
            cornerRadius:     12,
            xPadding:         14,
            yPadding:         10,
            displayColors:    false
        },
        layout: {
            padding: { top: 15, bottom: 10, left: 10, right: 10 }
        }
    }
});


// ============================================================
// Gráfico 2: Stock por categoría — Rosa neumórfico
// ============================================================
AIZ.plugins.chart('#graph-2', {
    type: 'bar',
    data: {
        labels: [
            @foreach ($root_categories as $key => $category)
            '{{ $category->getTranslation('name') }}',
            @endforeach
        ],
        datasets: [{
            label: '{{ translate('Stock por categoría') }}',
            data: [{{ $cached_graph_data['qty_data'] }}],
            backgroundColor: function(context) {
                var chart     = context.chart;
                var ctx       = chart.ctx;
                var chartArea = chart.chartArea;
                if (!chartArea) return 'rgba(236,72,153,0.85)';
                var g = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                g.addColorStop(0,   'rgb(255, 0, 0)');
                g.addColorStop(0.5, 'rgba(235, 107, 22, 0.95)');
                g.addColorStop(1,   'rgb(247, 213, 174)');
                return g;
            },
            borderWidth:        0,
            barPercentage:      0.5,
            categoryPercentage: 0.75,
            hoverBackgroundColor: function(context) {
                var chart     = context.chart;
                var ctx       = chart.ctx;
                var chartArea = chart.chartArea;
                if (!chartArea) return 'rgba(219,39,119,1)';
                var g = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                g.addColorStop(0, 'rgba(190, 24, 93,1)');
                g.addColorStop(1, 'rgba(244,114,182,1)');
                return g;
            }
        }]
    },
    options: {
        responsive:          true,
        maintainAspectRatio: false,
        animation: { duration: 1800, easing: 'easeOutElastic' },
        scales: {
            yAxes: [{
                gridLines: {
                    color:         'rgba(163,177,198,0.25)',
                    zeroLineColor: 'rgba(163,177,198,0.25)',
                    drawBorder:    false,
                    drawTicks:     false,
                    borderDash:    [4, 6]
                },
                ticks: {
                    fontColor:     '#8a9bb5',
                    fontFamily:    'Poppins',
                    fontSize:       11,
                    beginAtZero:   true,
                    padding:        16,
                    maxTicksLimit:  6
                }
            }],
            xAxes: [{
                gridLines: { display: false },
                ticks: {
                    fontColor:     '#8a9bb5',
                    fontFamily:    'Poppins',
                    fontSize:       10,
                    padding:        10,
                    maxRotation:    35,
                    minRotation:    35,
                    autoSkip:       false
                }
            }]
        },
        legend: {
            display:  true,
            position: 'top',
            align:    'end',
            labels: {
                fontFamily:    'Poppins',
                fontSize:       12,
                fontColor:     '#5a6a85',
                boxWidth:       8,
                usePointStyle: true,
                padding:        20
            },
            onClick: function() { return ''; }
        },
        tooltips: {
            backgroundColor: 'rgba(232,237,242,0.98)',
            titleFontFamily: 'Poppins',
            titleFontSize:    13,
            titleFontColor:  '#3d4f6e',
            bodyFontFamily:  'Poppins',
            bodyFontSize:     12,
            bodyFontColor:   '#5a6a85',
            borderColor:     'rgba(163,177,198,0.4)',
            borderWidth:      1,
            cornerRadius:     12,
            xPadding:         14,
            yPadding:         10,
            displayColors:    false
        },
        layout: {
            padding: { top: 15, bottom: 10, left: 10, right: 10 }
        }
    }
});
     // BARRAS
</script>
@endsection

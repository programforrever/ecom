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
<div class="row gutters-10">
<div class="col-lg-6">
    <style>
        .stat-card {
            position: relative;
            min-height: 180px;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.35s ease;
            box-shadow: 0 6px 20px rgba(0,0,0,0.12);
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.18);
        }

        .content {
            position: relative;
            z-index: 2;
            padding: 1.5rem 1.5rem 5rem 1.5rem;
        }

        .wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 300%;                /* clave para reducir el salto visible */
            height: 55%;
            z-index: 1;
            pointer-events: none;
            will-change: transform;
        }
.row > .col-6 {
    margin-bottom: 1rem;  /* o 3rem si quieres más */
}
        /* Animaciones con diferentes velocidades */
        .animate-wave         .wave { animation: waveFlow  9s linear infinite; }
        .animate-wave-slow    .wave { animation: waveFlow  32s linear infinite; }
        .animate-wave-fast    .wave { animation: waveFlow  13s linear infinite; }
        .animate-wave-reverse .wave { animation: waveFlowReverse 40s linear infinite; }

        @keyframes waveFlow {
            0%   { transform: translateX(0%); }
            100% { transform: translateX(-33.333%); }
        }

        @keyframes waveFlowReverse {
            0%   { transform: translateX(-33.333%); }
            100% { transform: translateX(0%); }
        }

        /* Mejora visual al pasar el mouse */
        .stat-card:hover {
            filter: brightness(1.08);
        }

        .stat-card .small {
            font-weight: 400;
            opacity: 0.8;
        }

        .stat-card h2 {
            font-weight: 800;
            margin: 0;
            line-height: 1;
        }
    </style>

    <!-- Línea clave: cambia gutters-10 por g-5 (o g-4 si g-5 es demasiado) -->
    <div class="row g-5">

        <!-- Clientes -->
        <div class="col-6">
            <div class="stat-card bg-grad-2 text-white animate-wave">
                <div class="content">
                    <div class="small mb-1">
                        <span class="fs-12 d-block fw-light">TOTAL</span>
                        Clientes
                    </div>
                    <h2 class="fw-bold">
                        {{ \App\Models\User::where('user_type', 'customer')->where('email_verified_at', '!=', null)->count() }}
                    </h2>
                </div>
                <svg class="wave" preserveAspectRatio="none" viewBox="0 0 1440 320" xmlns="http://www.w3.org/2000/svg">
                    <path fill="rgba(255,255,255,0.25)" d="M0,160L48,176C96,192,192,224,288,213.3C384,203,480,149,576,149.3C672,149,768,203,864,213.3C960,224,1056,192,1152,181.3C1248,171,1344,181,1392,186.7L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
                </svg>
            </div>
        </div>

        <!-- Órdenes -->
        <div class="col-6">
            <div class="stat-card bg-grad-3 text-white animate-wave-slow">
                <div class="content">
                    <div class="small mb-1">
                        <span class="fs-12 d-block fw-light">TOTAL</span>
                        Órdenes
                    </div>
                    <h2 class="fw-bold">{{ \App\Models\Order::count() }}</h2>
                </div>
                <svg class="wave" preserveAspectRatio="none" viewBox="0 0 1440 320" xmlns="http://www.w3.org/2000/svg">
                    <path fill="rgba(255,255,255,0.28)" d="M0,96L48,112C96,128,192,160,288,176C384,192,480,192,576,176C672,160,768,128,864,128C960,128,1056,160,1152,176C1248,192,1344,192,1392,192L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
                </svg>
            </div>
        </div>

        <!-- Categorías -->
        <div class="col-6">
            <div class="stat-card bg-grad-1 text-white animate-wave-fast">
                <div class="content">
                    <div class="small mb-1">
                        <span class="fs-12 d-block fw-light">TOTAL</span>
                        Categoría de producto
                    </div>
                    <h2 class="fw-bold">{{ \App\Models\Category::count() }}</h2>
                </div>
                <svg class="wave" preserveAspectRatio="none" viewBox="0 0 1440 320" xmlns="http://www.w3.org/2000/svg">
                    <path fill="rgba(255,255,255,0.3)" d="M0,192L48,176C96,160,192,128,288,138.7C384,149,480,203,576,213.3C672,224,768,192,864,170.7C960,149,1056,139,1152,154.7C1248,171,1344,213,1392,234.7L1440,256L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
                </svg>
            </div>
        </div>

        <!-- Marcas -->
        <div class="col-6">
            <div class="stat-card bg-grad-4 text-white animate-wave-reverse">
                <div class="content">
                    <div class="small mb-1">
                        <span class="fs-12 d-block fw-light">TOTAL</span>
                        Producto de marca
                    </div>
                    <h2 class="fw-bold">{{ \App\Models\Brand::count() }}</h2>
                </div>
                <svg class="wave" preserveAspectRatio="none" viewBox="0 0 1440 320" xmlns="http://www.w3.org/2000/svg">
                    <path fill="rgba(255,255,255,0.26)" d="M0,128L48,149.3C96,171,192,213,288,202.7C384,192,480,128,576,117.3C672,107,768,149,864,176C960,203,1056,213,1152,197.3C1248,181,1344,139,1392,117.3L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
                </svg>
            </div>
        </div>

    </div>
</div>






<!-- DONA -->
<style>

.col-lg-6 .row.gutters-10 .card {
    background: #e0e0e0; 
    border-radius: 20px; 
    border: none;         
    box-shadow: 
        10px 10px 30px rgba(163, 177, 198, 0.5),  
        -10px -10px 30px rgba(255, 255, 255, 0.9); 
    transition: all 0.4s ease; 
    overflow: hidden;     
}


.col-lg-6 .row.gutters-10 .card:hover {
    box-shadow: 
        6px 6px 20px rgba(163, 177, 198, 0.4),
        -6px -6px 20px rgba(255, 255, 255, 1);
    transform: translateY(-4px); 
}

/* Limpieza interna para header y body */
.col-lg-6 .row.gutters-10 .card .card-header {
    background: transparent;
    border-bottom: none;  
    padding: 1rem 1.5rem;
}

.col-lg-6 .row.gutters-10 .card .card-body {
    background: transparent;
    padding: 1.5rem;
}


body.dark-mode .col-lg-6 .row.gutters-10 .card,
.dark .col-lg-6 .row.gutters-10 .card {
    background: #2c2c2e;
    box-shadow: 
        10px 10px 30px #1e1e20,
        -10px -10px 30px #3a3a3c;
}

body.dark-mode .col-lg-6 .row.gutters-10 .card:hover,
.dark .col-lg-6 .row.gutters-10 .card:hover {
    box-shadow: 
        6px 6px 20px #18181a,
        -6px -6px 20px #404042;
}

</style>
    <div class="col-lg-6">
        <div class="row gutters-10">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0 fs-14">{{ translate('Products') }}</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="pie-1" class="w-100" height="305"></canvas>
                    </div>
                </div>
            </div>
        
        </div>
    </div>
</div>

<!-- dona -->

<!-- BARRAS-->
 <style>
    .neumorphic-card {
        background: #f8fafc;
        border-radius: 20px;
        border: none;
        box-shadow: 
            10px 10px 20px #d1d9e6,
            -10px -10px 20px #ffffff;
        transition: all 0.4s ease;
        overflow: hidden;
    }

    .neumorphic-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 
            6px 6px 16px #c4d0e0,
            -6px -6px 16px #ffffff;
    }

    .card-body canvas {
        border-radius: 20px;
        filter: drop-shadow(0 12px 24px rgba(0,0,0,0.15));
    }
</style>

<div class="row gutters-10">
    <div class="col-md-6">
        <div class="card neumorphic-card">
            <div class="card-header">
                <h6 class="mb-0 fs-14">{{ translate('Category wise product sale') }}</h6>
            </div>
            <div class="card-body">
                <canvas id="graph-1" class="w-100" height="500"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card neumorphic-card">
            <div class="card-header">
                <h6 class="mb-0 fs-14">{{ translate('Category wise product stock') }}</h6>
            </div>
            <div class="card-body">
                <canvas id="graph-2" class="w-100" height="500"></canvas>
            </div>
        </div>
    </div>
</div>
<!-- BARRAS-->





 <!-- imagen-->
<div class="card">
    <div class="card-header">
        <h6 class="mb-0">{{ translate('Top 12 Products') }}</h6>
    </div>
    <div class="card-body">
        <div class="aiz-carousel gutters-10 half-outside-arrow" data-items="6" data-xl-items="5" data-lg-items="4" data-md-items="3" data-sm-items="2" data-arrows='true'>
            @foreach (filter_products(\App\Models\Product::where('published', 1)->orderBy('num_of_sale', 'desc'))->limit(12)->get() as $key => $product)
                <div class="carousel-box">
                    <div class="aiz-card-box border border-light rounded shadow-sm hov-shadow-md mb-2 has-transition bg-white">
                        <div class="position-relative">
                            <a href="{{ route('product', $product->slug) }}" class="d-block">
                                <img
                                    class="img-fit lazyload mx-auto h-210px"
                                    src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                    data-src="{{ uploaded_asset($product->thumbnail_img) }}"
                                    alt="{{  $product->getTranslation('name')  }}"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                >
                            </a>
                        </div>
                        <div class="p-md-3 p-2 text-left">
                            <div class="fs-15">
                                @if(home_base_price($product) != home_discounted_base_price($product))
                                    <del class="fw-600 opacity-50 mr-1">{{ home_base_price($product) }}</del>
                                @endif
                                <span class="fw-700 text-primary">{{ home_discounted_base_price($product) }}</span>
                            </div>
                            <div class="rating rating-sm mt-1">
                                {{ renderStarRating($product->rating) }}
                            </div>
                            <h3 class="fw-600 fs-13 text-truncate-2 lh-1-4 mb-0">
                                <a href="{{ route('product', $product->slug) }}" class="d-block text-reset">{{ $product->getTranslation('name') }}</a>
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
AIZ.plugins.chart('#pie-1', {
    type: 'doughnut',
    data: {
        labels: [
            '{{translate('Total published products')}}',
            '{{translate('Total sellers products')}}',
            '{{translate('Total admin products')}}'
        ],
        datasets: [
            // Capa exterior (más grande) - ejemplo: tonos morados/violetas
            {
                data: [
                    {{ \App\Models\Product::where('published', 1)->count() }},
                    {{ \App\Models\Product::where('published', 1)->where('added_by', 'seller')->count() }},
                    {{ \App\Models\Product::where('published', 1)->where('added_by', 'admin')->count() }}
                ],
                backgroundColor: [
                "#ff7300",   // naranja fuerte BARRAS
                    "#c7631b",   // naranja medio
                    "#dd9857"    // naranja claro / amarillo suave
                ],
                borderWidth: 0,
                weight: 45,              // más grueso (exterior)
                cutout: '55%'            // deja espacio para capas internas
            },
            // Capa media - tonos rosas/fucsia
            {
                data: [
                    {{ \App\Models\Product::where('published', 1)->count() }},
                    {{ \App\Models\Product::where('published', 1)->where('added_by', 'seller')->count() }},
                    {{ \App\Models\Product::where('published', 1)->where('added_by', 'admin')->count() }}
                ],
                backgroundColor: [
                    "#ff8635",   // fucsia fuerte
                    "#f472b6",   // rosa medio
                    "#ce5600"    // naranja de inicio
                ],
                borderWidth: 0,
                weight: 35,              // medio
                cutout: '70%'
            },
            // Capa interior - tonos naranjas/amarillos
            {
                data: [
                    {{ \App\Models\Product::where('published', 1)->count() }},
                    {{ \App\Models\Product::where('published', 1)->where('added_by', 'seller')->count() }},
                    {{ \App\Models\Product::where('published', 1)->where('added_by', 'admin')->count() }}
                ],
                backgroundColor: [
                    "#f83a00",   // naranja fuerte
                    "#e46911",   // naranja medio
                    "#ff7300"    // naranja claro inicio
                ],
                borderWidth: 0,
                weight: 25,              // más delgado (interior)
                cutout: '82%'
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,

        // Opcional: arco parcial como en la imagen (deja abierto abajo)
        rotation: -135,          // empieza en diagonal
        circumference: 270,      // 270° → deja abierto ~90°

        animation: {
            duration: 2200,
            easing: 'easeOutElastic',
            animateRotate: true,
            animateScale: true
        },

        hover: {
            animationDuration: 400,
            mode: 'nearest'
        },

        plugins: {
            legend: {
                display: true,
                position: 'bottom',
                labels: {
                    fontFamily: 'Poppins',
                    boxWidth: 12,
                    usePointStyle: true,
                    padding: 20,
                    fontColor: '#333'
                },
                onClick: function () { return ''; }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const index = context.dataIndex;
                        const value = context.raw;
                        const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                        const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                        return context.chart.data.labels[index] + ': ' + value + ' (' + percentage + '%)';
                    }
                }
            }
        },

        elements: {
            arc: {
                borderAlign: 'inner',
                hoverBorderWidth: 0,     // sin borde extra al hover para mantener capas limpias
                hoverOffset: 12          // leve expansión al hover
            }
        }
    }
});





   
     // BARRAS

   // Gráfico 1: Ventas por categoría - forma cilíndrica azul
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
            data: [
                {{ $cached_graph_data['num_of_sale_data'] }}
            ],
            // Degradado vertical para efecto cilindro 3D
            backgroundColor: function(context) {
                var chart = context.chart;
                var ctx = chart.ctx;
                var gradient = ctx.createLinearGradient(0, chart.chartArea.bottom, 0, chart.chartArea.top);
                gradient.addColorStop(0, 'rgba(59, 130, 246, 0.95)');   // azul oscuro abajo
                gradient.addColorStop(0.45, 'rgba(59, 130, 246, 0.85)');
                gradient.addColorStop(1, 'rgba(147, 197, 253, 0.95)');  // azul claro arriba
                return gradient;
            },
            borderColor: 'rgba(147, 197, 253, 0.6)',
            borderWidth: 1,
            borderRadius: 999,               // muy alto → forma casi cilíndrica/píldora
            borderSkipped: false,
            hoverBackgroundColor: function(context) {
                var chart = context.chart;
                var ctx = chart.ctx;
                var gradient = ctx.createLinearGradient(0, chart.chartArea.bottom, 0, chart.chartArea.top);
                gradient.addColorStop(0, 'rgba(59, 130, 246, 1)');
                gradient.addColorStop(0.45, 'rgba(59, 130, 246, 0.95)');
                gradient.addColorStop(1, 'rgba(147, 197, 253, 1)');
                return gradient;
            },
            hoverBorderColor: '#ffffff',
            hoverBorderWidth: 3
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,

        animation: {
            duration: 1800,
            easing: 'easeOutBounce'
        },

        scales: {
            yAxes: [{
                gridLines: {
                    color: '#e5e7eb',
                    zeroLineColor: '#e5e7eb',
                    drawBorder: false,
                    drawTicks: false
                },
                ticks: {
                    fontColor: "#4b5563",
                    fontFamily: 'Poppins',
                    fontSize: 12,
                    beginAtZero: true,
                    padding: 20
                }
            }],
            xAxes: [{
                gridLines: {
                    display: false
                },
                ticks: {
                    fontColor: "#4b5563",
                    fontFamily: 'Poppins',
                    fontSize: 12,
                    padding: 15,
                    maxRotation: 45,
                    minRotation: 45
                }
            }]
        },

        plugins: {
            legend: {
                display: true,
                position: 'top',
                labels: {
                    fontFamily: 'Poppins',
                    boxWidth: 14,
                    usePointStyle: true,
                    padding: 20,
                    fontColor: '#1f2937'
                },
                onClick: function () { return ''; }
            }
        },

        layout: {
            padding: {
                top: 40,
                bottom: 40,
                left: 15,
                right: 15
            }
        },

        elements: {
            bar: {
                borderRadius: 999,
                borderSkipped: false
            }
        }
    }
});

// Gráfico 2: Stock por categoría - forma cilíndrica rosa
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
            data: [
                {{ $cached_graph_data['qty_data'] }}
            ],
            // Degradado vertical rosa
            backgroundColor: function(context) {
                var chart = context.chart;
                var ctx = chart.ctx;
                var gradient = ctx.createLinearGradient(0, chart.chartArea.bottom, 0, chart.chartArea.top);
                gradient.addColorStop(0, 'rgba(236, 72, 153, 0.95)');   // rosa fuerte abajo
                gradient.addColorStop(0.45, 'rgba(244, 114, 182, 0.85)');
                gradient.addColorStop(1, 'rgba(249, 168, 212, 0.95)');  // rosa claro arriba
                return gradient;
            },
            borderColor: 'rgba(249, 168, 212, 0.6)',
            borderWidth: 1,
            borderRadius: 999,
            borderSkipped: false,
            hoverBackgroundColor: function(context) {
                var chart = context.chart;
                var ctx = chart.ctx;
                var gradient = ctx.createLinearGradient(0, chart.chartArea.bottom, 0, chart.chartArea.top);
                gradient.addColorStop(0, 'rgba(236, 72, 153, 1)');
                gradient.addColorStop(0.45, 'rgba(244, 114, 182, 0.95)');
                gradient.addColorStop(1, 'rgba(249, 168, 212, 1)');
                return gradient;
            },
            hoverBorderColor: '#ffffff',
            hoverBorderWidth: 3
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,

        animation: {
            duration: 1800,
            easing: 'easeOutBounce'
        },

        scales: {
            yAxes: [{
                gridLines: {
                    color: '#e5e7eb',
                    zeroLineColor: '#e5e7eb',
                    drawBorder: false,
                    drawTicks: false
                },
                ticks: {
                    fontColor: "#4b5563",
                    fontFamily: 'Poppins',
                    fontSize: 12,
                    beginAtZero: true,
                    padding: 20
                }
            }],
            xAxes: [{
                gridLines: {
                    display: false
                },
                ticks: {
                    fontColor: "#4b5563",
                    fontFamily: 'Poppins',
                    fontSize: 12,
                    padding: 15,
                    maxRotation: 45,
                    minRotation: 45
                }
            }]
        },

        plugins: {
            legend: {
                display: true,
                position: 'top',
                labels: {
                    fontFamily: 'Poppins',
                    boxWidth: 14,
                    usePointStyle: true,
                    padding: 20,
                    fontColor: '#1f2937'
                },
                onClick: function () { return ''; }
            }
        },

        layout: {
            padding: {
                top: 40,
                bottom: 40,
                left: 15,
                right: 15
            }
        },

        elements: {
            bar: {
                borderRadius: 999,
                borderSkipped: false
            }
        }
    }
});
    
     // BARRAS
</script>
@endsection


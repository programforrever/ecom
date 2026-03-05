@php
    $enable_marquee = get_setting('enable_marquee_banner');
    $marquee_text = get_setting('marquee_text');
    $marquee_speed = get_setting('marquee_speed', 5);
    $marquee_font_size = get_setting('marquee_font_size', 14);
    $marquee_font_weight = get_setting('marquee_font_weight', 'normal');
    $marquee_text_color = get_setting('marquee_text_color', '#ffffff');
    $marquee_bg_color = get_setting('marquee_bg_color', '#e74c3c');
    $marquee_animation = get_setting('marquee_animation', 'scroll');
    $marquee_padding = get_setting('marquee_padding', 12);
    
    // Calcular duración en milisegundos
    // Valores menores = más lento
    // Fórmula: 100000 / velocidad
    $duration = (100000 / floatval($marquee_speed));
@endphp

@if($enable_marquee == 'on' && $marquee_text)

<style>
    .marquee-banner {
        background-color: {{ $marquee_bg_color }};
        padding: {{ $marquee_padding }}px 0;
        overflow: hidden;
        width: 100%;
        position: relative;
    }

    .marquee-container {
        display: flex;
        width: 100%;
        overflow: hidden;
        position: relative;
    }

    .marquee-content {
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
        font-size: {{ $marquee_font_size }}px;
        font-weight: {{ $marquee_font_weight }};
        color: {{ $marquee_text_color }};
        animation: marquee-{{ $marquee_animation }} {{ $duration }}ms linear infinite;
        padding-right: 0;
        will-change: transform;
    }

    .marquee-icon {
        margin: 0 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 1em;
        font-size: inherit;
    }

    /* Colores para cada icono */
    .marquee-icon.icon-fire { color: #FF5722; } /* Naranja rojo */
    .marquee-icon.icon-star { color: #FFD700; } /* Dorado */
    .marquee-icon.icon-gift { color: #E91E63; } /* Rosa/Magenta */
    .marquee-icon.icon-truck { color: #2196F3; } /* Azul */
    .marquee-icon.icon-heart { color: #FF1744; } /* Rojo vivo */
    .marquee-icon.icon-bell { color: #FF9800; } /* Naranja */
    .marquee-icon.icon-info-circle { color: #00BCD4; } /* Cyan */
    .marquee-icon.icon-tag { color: #673AB7; } /* Púrpura */
    .marquee-icon.icon-phone { color: #4CAF50; } /* Verde */
    .marquee-icon.icon-clock { color: #9C27B0; } /* Violeta */
    .marquee-icon.icon-check-circle { color: #8BC34A; } /* Verde claro */
    .marquee-icon.icon-rocket { color: #F44336; } /* Rojo */
    .marquee-icon.icon-smile { color: #FBC02D; } /* Amarillo */
    .marquee-icon.icon-thumbs-up { color: #03A9F4; } /* Azul claro */
    .marquee-icon.icon-exclamation-circle { color: #FF5252; } /* Rojo brillante */
    .marquee-icon.icon-bolt { color: #FFCA28; } /* Amarillo oro */

    @keyframes marquee-scroll {
        0% {
            transform: translateX(calc(100vw));
        }
        100% {
            transform: translateX(calc(-100% - 100vw));
        }
    }

    @keyframes marquee-slide {
        0% {
            transform: translateX(calc(100vw));
        }
        50% {
            transform: translateX(calc(-100% - 50vw));
        }
        100% {
            transform: translateX(calc(100vw));
        }
    }

    @keyframes marquee-bounce {
        0% {
            transform: translateX(calc(100vw));
        }
        50% {
            transform: translateX(calc(-100% - 50vw));
        }
        100% {
            transform: translateX(calc(-100% - 100vw));
        }
    }

    @keyframes marquee-fade {
        0% {
            opacity: 0;
            transform: translateX(calc(100vw));
        }
        10% {
            opacity: 1;
        }
        90% {
            opacity: 1;
        }
        100% {
            opacity: 0;
            transform: translateX(calc(-100% - 100vw));
        }
    }

    @media (max-width: 768px) {
        .marquee-content {
            font-size: calc({{ $marquee_font_size }}px - 2px);
        }
    }
</style>

<div class="marquee-banner">
    <div class="marquee-container">
        <div class="marquee-content">
            @php
                // Procesar el texto para convertir [icon:nombre] en iconos Line Awesome coloreados
                $text = $marquee_text;
                
                // Mapping de iconos a sus clases de color
                $icon_colors = array(
                    'fire' => 'icon-fire',
                    'star' => 'icon-star',
                    'gift' => 'icon-gift',
                    'truck' => 'icon-truck',
                    'heart' => 'icon-heart',
                    'bell' => 'icon-bell',
                    'info-circle' => 'icon-info-circle',
                    'tag' => 'icon-tag',
                    'phone' => 'icon-phone',
                    'clock' => 'icon-clock',
                    'check-circle' => 'icon-check-circle',
                    'rocket' => 'icon-rocket',
                    'smile' => 'icon-smile',
                    'thumbs-up' => 'icon-thumbs-up',
                    'exclamation-circle' => 'icon-exclamation-circle',
                    'bolt' => 'icon-bolt'
                );
                
                // Usar una expresión regular para encontrar y reemplazar [icon:nombre]
                $output = preg_replace_callback(
                    '/\[icon:([^\]]+)\]/',
                    function($matches) use ($icon_colors) {
                        $icon_name = $matches[1];
                        $color_class = isset($icon_colors[$icon_name]) ? $icon_colors[$icon_name] : 'icon-' . str_replace('-', '', $icon_name);
                        return '<i class="la la-' . htmlspecialchars($icon_name) . ' marquee-icon ' . $color_class . '"></i>';
                    },
                    htmlspecialchars($text)
                );
            @endphp
            
            {!! $output !!}
        </div>
    </div>
</div>
@endif

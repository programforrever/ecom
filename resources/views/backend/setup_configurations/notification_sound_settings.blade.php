@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ translate('Configuración de Sonidos de Notificaciones') }}</h5>
                </div>
                <div class="card-body">

                    <div class="alert alert-info">
                        {{ translate('Configura los sonidos de notificación que se reproducen cuando los usuarios reciben notificaciones en tu sistema.') }}
                    </div>

                    <form action="{{ route('notification.sound.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Enable/Disable Sound -->
                        <div class="form-group">
                            <label for="notification_sound_enabled" class="form-label">
                                {{ translate('Activar Sonidos de Notificación') }}
                            </label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="notification_sound_enabled" name="notification_sound_enabled" value="1" 
                                    @if(get_setting('notification_sound_enabled') == 1) checked @endif>
                                <label class="form-check-label" for="notification_sound_enabled">
                                    {{ translate('Activar sonidos de notificación para todos los usuarios') }}
                                </label>
                            </div>
                            <small class="text-muted d-block mt-2">
                                {{ translate('Cuando esté activado, los usuarios escucharán un sonido cuando reciban notificaciones. Los usuarios pueden desactivar esto en sus configuraciones.') }}
                            </small>
                        </div>

                        <!-- Default Sound Selection -->
                        <div class="form-group mt-4">
                            <label for="notification_sound_type" class="form-label">
                                {{ translate('Tipo de Sonido de Notificación') }}
                            </label>
                            <select class="form-control" id="notification_sound_type" name="notification_sound_type">
                                <option value="default" @if(get_setting('notification_sound_type') == 'default' || !get_setting('notification_sound_type')) selected @endif>
                                    {{ translate('Sonido Predeterminado Incorporado') }}
                                </option>
                                <option value="custom" @if(get_setting('notification_sound_type') == 'custom') selected @endif>
                                    {{ translate('Cargar Personalizado') }}
                                </option>
                            </select>
                            <small class="text-muted d-block mt-2">
                                {{ translate('Elige entre el sonido de notificación incorporado o carga un archivo de audio personalizado.') }}
                            </small>
                        </div>

                        <!-- Preset Sounds (if available) -->
                        <div class="form-group mt-4">
                            <label for="preset_sound" class="form-label">
                                {{ translate('Sonidos Preestablecidos') }}
                            </label>
                            <select class="form-control" id="preset_sound" name="preset_sound">
                                <optgroup label="{{ translate('Sonidos Clásicos') }}">
                                    <option value="ding" @if(get_setting('preset_sound') == 'ding') selected @endif>
                                        Ding ({{ translate('Campana') }})
                                    </option>
                                    <option value="chime" @if(get_setting('preset_sound') == 'chime') selected @endif>
                                        Chime ({{ translate('Suave') }})
                                    </option>
                                    <option value="bell" @if(get_setting('preset_sound') == 'bell') selected @endif>
                                        Bell ({{ translate('Alto') }})
                                    </option>
                                </optgroup>
                                <optgroup label="{{ translate('Sonidos Modernos') }}">
                                    <option value="ping" @if(get_setting('preset_sound') == 'ping') selected @endif>
                                        Ping ({{ translate('Corto') }})
                                    </option>
                                    <option value="alert" @if(get_setting('preset_sound') == 'alert') selected @endif>
                                        Alert ({{ translate('Alerta') }})
                                    </option>
                                </optgroup>
                            </select>
                            <small class="text-muted d-block mt-2">
                                {{ translate('Selecciona un sonido preestablecido para usar en las notificaciones.') }}
                            </small>
                            <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="testPresetSound()">
                                <i class="las la-play"></i> {{ translate('Probar Sonido') }}
                            </button>
                        </div>

                        <!-- Custom Audio Upload -->
                        <div class="form-group mt-4">
                            <label for="custom_notification_sound" class="form-label">
                                {{ translate('Cargar Sonido Personalizado') }}
                            </label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="custom_notification_sound" name="custom_notification_sound" accept="audio/*">
                                <label class="custom-file-label" for="custom_notification_sound">
                                    {{ translate('Elige un archivo de audio') }}
                                </label>
                            </div>
                            <small class="text-muted d-block mt-2">
                                {{ translate('Formatos soportados: MP3, WAV, OGG, WEBM (Máx 5MB)') }}
                            </small>
                            
                            @if(get_setting('custom_notification_sound'))
                                @php
                                    $soundFile = get_setting('custom_notification_sound');
                                    $ext = strtolower(pathinfo($soundFile, PATHINFO_EXTENSION));
                                    $mimeTypes = [
                                        'mp3' => 'audio/mpeg',
                                        'wav' => 'audio/wav',
                                        'ogg' => 'audio/ogg',
                                        'webm' => 'audio/webm',
                                        'aac' => 'audio/aac',
                                        'flac' => 'audio/flac',
                                        'm4a' => 'audio/mp4',
                                        'mp4' => 'audio/mp4'
                                    ];
                                    $mimeType = $mimeTypes[$ext] ?? 'audio/mpeg';
                                @endphp
                                <div class="mt-3 p-3" style="background-color: #f8f9fa; border-radius: 5px;">
                                    <p class="mb-2">
                                        <strong>{{ translate('Sonido Actual Guardado:') }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            📁 {{ basename(get_setting('custom_notification_sound')) }}
                                        </small>
                                    </p>
                                    <audio id="current-sound-player" controls class="w-100" style="max-width: 400px;">
                                        <source src="{{ uploaded_asset(get_setting('custom_notification_sound')) }}" type="{{ $mimeType }}">
                                        {{ translate('Tu navegador no soporta el elemento de audio.') }}
                                    </audio>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-secondary" onclick="document.getElementById('current-sound-player').play()">
                                            <i class="las la-play"></i> {{ translate('Reproducir') }}
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="toggleRemoveConfirm()">
                                            <i class="las la-trash"></i> {{ translate('Eliminar Sonido') }}
                                        </button>
                                    </div>
                                    <div id="remove-confirm" style="display:none;" class="alert alert-warning mt-2">
                                        <input type="checkbox" id="remove-custom-sound-check" name="remove_custom_sound" value="1">
                                        <label for="remove-custom-sound-check">
                                            {{ translate('Sí, eliminar el sonido personalizado') }}
                                        </label>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info mt-3">
                                    <i class="las la-info-circle"></i> {{ translate('No hay sonido personalizado guardado') }}
                                </div>
                            @endif
                            
                            <!-- Preview de archivo seleccionado -->
                            <div id="audio-preview-container" style="display:none;" class="mt-3 p-3" style="background-color: #e7f3ff; border-radius: 5px;">
                                <p class="mb-2">
                                    <strong>{{ translate('Vista Previa del Nuevo Archivo:') }}</strong>
                                </p>
                                <audio id="new-audio-player" controls class="w-100" style="max-width: 400px;">
                                    {{ translate('Tu navegador no soporta el elemento de audio.') }}
                                </audio>
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="las la-info-circle"></i> {{ translate('Haz clic en Reproducir para probar el archivo') }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Sound Volume -->
                        <div class="form-group mt-4">
                            <label for="notification_sound_volume" class="form-label">
                                {{ translate('Volumen Predeterminado') }}
                            </label>
                            <div class="d-flex align-items-center">
                                <input type="range" class="form-range" id="notification_sound_volume" name="notification_sound_volume" 
                                    min="0" max="100" value="{{ (float)get_setting('notification_sound_volume', 70) }}" style="flex: 1;">
                                <span class="ml-3" id="volume_value">{{ (int)get_setting('notification_sound_volume', 70) }}%</span>
                            </div>
                            <small class="text-muted d-block mt-2">
                                {{ translate('Establece el volumen predeterminado para los sonidos de notificación (0-100%).') }}
                            </small>
                        </div>

                        <!-- Allow User Control -->
                        <div class="form-group mt-4">
                            <label for="allow_user_sound_control" class="form-label">
                                {{ translate('Preferencias del Usuario') }}
                            </label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="allow_user_sound_control" name="allow_user_sound_control" value="1"
                                    @if(get_setting('allow_user_sound_control') == 1) checked @endif>
                                <label class="form-check-label" for="allow_user_sound_control">
                                    {{ translate('Permitir que los usuarios desactiven los sonidos de notificación en sus configuraciones') }}
                                </label>
                            </div>
                            <small class="text-muted d-block mt-2">
                                {{ translate('Cuando esté activado, los usuarios pueden activar/desactivar los sonidos de notificación en sus preferencias de cuenta.') }}
                            </small>
                        </div>

                        <!-- Notification Types -->
                        <div class="form-group mt-4">
                            <label class="form-label">
                                {{ translate('Reproducir Sonido Para:') }}
                            </label>
                            <div class="form-group">
                                @php
                                    $soundOnTypes = [];
                                    $settingValue = get_setting('sound_on_types');
                                    
                                    if ($settingValue) {
                                        $decoded = json_decode($settingValue, true);
                                        $soundOnTypes = is_array($decoded) ? $decoded : [];
                                    }
                                    
                                    // If no settings exist, default to 'order'
                                    if (empty($soundOnTypes)) {
                                        $soundOnTypes = ['order'];
                                    }
                                @endphp
                                
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="sound_on_order" name="sound_types[]" value="order" 
                                        @if(in_array('order', $soundOnTypes)) checked @endif>
                                    <label class="form-check-label" for="sound_on_order">
                                        {{ translate('Nuevos Pedidos') }} <i class="las la-shopping-cart"></i>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="sound_on_message" name="sound_types[]" value="message"
                                        @if(in_array('message', $soundOnTypes)) checked @endif>
                                    <label class="form-check-label" for="sound_on_message">
                                        {{ translate('Mensajes') }} <i class="las la-envelope"></i>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="sound_on_system" name="sound_types[]" value="system"
                                        @if(in_array('system', $soundOnTypes)) checked @endif>
                                    <label class="form-check-label" for="sound_on_system">
                                        {{ translate('Notificaciones del Sistema') }} <i class="las la-bell"></i>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="sound_on_all" name="sound_types[]" value="all"
                                        @if(in_array('all', $soundOnTypes)) checked @endif>
                                    <label class="form-check-label" for="sound_on_all">
                                        {{ translate('Todas las Notificaciones') }} <i class="las la-star"></i>
                                    </label>
                                </div>
                            </div>
                            <small class="text-muted d-block mt-2">
                                {{ translate('Selecciona para qué tipos de notificación debe haber sonido.') }}
                            </small>
                        </div>

                        <div class="mt-5">
                            <button type="submit" class="btn btn-primary">
                                <i class="las la-save"></i> {{ translate('Guardar Configuración') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // SoundGenerator se carga desde el layout backend, solo inicializar
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                if (typeof SoundGenerator !== 'undefined') {
                    console.log('✅ SoundGenerator disponible en DOMContentLoaded');
                }
            });
        }
        
        // Update volume display
        document.getElementById('notification_sound_volume').addEventListener('input', function() {
            document.getElementById('volume_value').textContent = this.value + '%';
        });

        // Función para obtener MIME type según extensión
        function getMimeType(filename) {
            const ext = filename.split('.').pop().toLowerCase();
            const mimeTypes = {
                'mp3': 'audio/mpeg',
                'wav': 'audio/wav',
                'ogg': 'audio/ogg',
                'webm': 'audio/webm',
                'aac': 'audio/aac',
                'flac': 'audio/flac',
                'm4a': 'audio/mp4',
                'mp4': 'audio/mp4'
            };
            return mimeTypes[ext] || 'audio/mpeg';
        }

        // Función para togglear el confirmar eliminación
        function toggleRemoveConfirm() {
            const confirmDiv = document.getElementById('remove-confirm');
            const checkbox = document.getElementById('remove-custom-sound-check');
            if (confirmDiv) {
                if (confirmDiv.style.display === 'none') {
                    confirmDiv.style.display = 'block';
                } else {
                    confirmDiv.style.display = 'none';
                    checkbox.checked = false;
                }
            }
        }
        
        // Manejar carga de archivo
        document.getElementById('custom_notification_sound').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                console.log('📁 Archivo seleccionado:', file.name);
                console.log('📊 Tamaño:', (file.size / 1024 / 1024).toFixed(2) + ' MB');
                console.log('🎵 Tipo MIME:', file.type);
                
                // Validar tipo de archivo
                const validTypes = ['audio/mpeg', 'audio/wav', 'audio/ogg', 'audio/webm', 'audio/aac', 'audio/flac', 'audio/mp4', 'video/mp4'];
                const isValidType = validTypes.some(type => file.type.includes(type)) || 
                                  ['mp3', 'wav', 'ogg', 'webm', 'aac', 'flac', 'm4a', 'mp4'].some(ext => file.name.toLowerCase().endsWith('.' + ext));
                
                if (!isValidType) {
                    alert('{{ translate("Formato de archivo no soportado. Usa: MP3, WAV, OGG, WEBM, AAC, FLAC, M4A, MP4") }}');
                    console.error('❌ Formato no soportado:', file.type);
                    e.target.value = '';
                    document.getElementById('audio-preview-container').style.display = 'none';
                    return;
                }
                
                if (file.size > 5 * 1024 * 1024) {
                    alert('{{ translate("El archivo no debe exceder 5MB") }}');
                    console.error('❌ Archivo muy grande');
                    e.target.value = '';
                    document.getElementById('audio-preview-container').style.display = 'none';
                    return;
                }
                
                // Crear preview
                const reader = new FileReader();
                reader.onload = function(event) {
                    const audioPlayer = document.getElementById('new-audio-player');
                    audioPlayer.src = event.target.result;
                    
                    // Detectar y asignar el MIME type correcto
                    const mimeType = getMimeType(file.name);
                    const sourceElement = audioPlayer.querySelector('source') || document.createElement('source');
                    sourceElement.type = mimeType;
                    if (!audioPlayer.querySelector('source')) {
                        audioPlayer.appendChild(sourceElement);
                    }
                    
                    console.log('🎵 MIME type detectado:', mimeType);
                    document.getElementById('audio-preview-container').style.display = 'block';
                    
                    // Intentar reproducir automáticamente
                    setTimeout(() => {
                        const playPromise = audioPlayer.play();
                        if (playPromise !== undefined) {
                            playPromise.then(() => {
                                console.log('🎵 ¡Reproduciendo vista previa!');
                            }).catch(error => {
                                console.log('⏸️ Auto-play bloqueado (click para reproducir):', error);
                            });
                        }
                    }, 100);
                };
                reader.onerror = function() {
                    console.error('❌ Error al leer el archivo');
                    alert('{{ translate("Error al leer el archivo") }}');
                };
                reader.readAsDataURL(file);
            }
        });

        // Test sound function
        function testPresetSound() {
            const soundType = document.getElementById('preset_sound').value;
            const volume = (document.getElementById('notification_sound_volume').value || 70) / 100;
            
            console.log('🔊 Test: Reproduciendo sonido:', soundType, 'Volumen:', volume);
            
            if (typeof SoundGenerator === 'undefined') {
                console.error('❌ SoundGenerator no disponible');
                alert('⚠️ SoundGenerator no está disponible aún. Por favor recarga la página.');
                return;
            }
            
            if (!SoundGenerator.audioContext) {
                console.log('🔨 Inicializando AudioContext...');
                SoundGenerator.init();
            }
            
            try {
                SoundGenerator.play(soundType, volume);
                console.log('✅ Sonido reproducido exitosamente');
            } catch (error) {
                console.error('❌ Error reproduciendo sonido:', error);
            }
        }

        // Update file input label
        const fileInput = document.getElementById('custom_notification_sound');
        if (fileInput) {
            fileInput.addEventListener('change', function() {
                const fileName = this.files[0]?.name || "{{ translate('Elige un archivo de audio') }}";
                if (this.nextElementSibling) {
                    this.nextElementSibling.textContent = fileName;
                }
            });
        }

        // Toggle between default and custom sound
        document.getElementById('notification_sound_type').addEventListener('change', function() {
            const customUpload = document.getElementById('custom_notification_sound').closest('.form-group');
            if (this.value === 'custom') {
                customUpload.style.display = 'block';
            } else {
                customUpload.style.display = 'none';
            }
        });

        // Trigger change event on page load
        document.getElementById('notification_sound_type').dispatchEvent(new Event('change'));
    </script>
@endsection

/**
 * Notification Sound Manager
 * Reproduce sonidos de notificación
 */

const NotificationSoundManager = {
    // Sonido predeterminado
    soundEnabled: localStorage.getItem('notificationSoundEnabled') !== 'false',
    soundVolume: (() => {
        const vol = parseFloat(localStorage.getItem('notificationSoundVolume')) || 0.7;
        // Normalizar si el valor está fuera del rango [0, 1]
        return vol > 1 ? vol / 100 : vol;
    })(),
    
    // Audio element
    audioElement: null,
    
    /**
     * Inicializar el manager
     */
    init: function() {
        // Crear elemento de audio
        this.audioElement = document.createElement('audio');
        this.audioElement.id = 'notification-sound';
        this.audioElement.volume = this.soundVolume;
        this.audioElement.preload = 'auto';
        document.body.appendChild(this.audioElement);
        console.log('🔊 NotificationSoundManager inicializado. Habilitado:', this.soundEnabled, 'Volumen:', this.soundVolume);
    },

    /**
     * Reproducir sonido de notificación
     * @param {string} soundUrl - URL del audio (opcional)
     */
    play: function(soundUrl) {
        if (!this.soundEnabled) {
            console.log('🔇 Notificación de sonido deshabilitada');
            return;
        }

        try {
            // Determinar URL del sonido a reproducir
            let url = soundUrl;
            const customSoundUrl = localStorage.getItem('notificationSoundUrl');
            
            // Si hay sonido personalizado, usar ese
            if (customSoundUrl) {
                url = customSoundUrl;
                this.playAudioFile(url);
            } else {
                // Usar sonido preestablecido con SoundGenerator
                const presetSound = localStorage.getItem('notificationPresetSound') || 'ding';
                
                // Verificar múltiples veces si SoundGenerator está disponible
                if (typeof SoundGenerator !== 'undefined' && SoundGenerator.audioContext) {
                    console.log('🎵 Reproduciendo sonido preestablecido con SoundGenerator:', presetSound);
                    SoundGenerator.play(presetSound, this.soundVolume);
                } else if (typeof SoundGenerator !== 'undefined') {
                    console.log('🎵 SoundGenerator disponible, inicializando...');
                    SoundGenerator.init();
                    SoundGenerator.play(presetSound, this.soundVolume);
                } else {
                    // Esperar a que SoundGenerator esté disponible
                    console.warn('⏳ SoundGenerator no disponible aún, esperando...');
                    let waitCount = 0;
                    const waitForGenerator = setInterval(() => {
                        waitCount++;
                        if (typeof SoundGenerator !== 'undefined') {
                            clearInterval(waitForGenerator);
                            console.log('✅ SoundGenerator disponible ahora, reproduciendo...');
                            SoundGenerator.init();
                            SoundGenerator.play(presetSound, this.soundVolume);
                        } else if (waitCount > 10) {
                            clearInterval(waitForGenerator);
                            console.error('❌ SoundGenerator no está disponible después de varios intentos');
                        }
                    }, 100);
                }
            }
        } catch (error) {
            console.error('❌ Error playing notification sound:', error);
        }
    },

    /**
     * Reproducir archivo de audio
     */
    playAudioFile: function(url) {
        if (!this.audioElement) {
            console.log('audioElement no disponible');
            return;
        }

        try {
            // Asegurar que la URL es absoluta si es necesaria
            if (url && !url.startsWith('http') && !url.startsWith('/')) {
                url = '/' + url;
            }
            
            console.log('🎵 Reproduciendo archivo:', url, 'Volumen:', this.soundVolume);
            this.audioElement.src = url;
            this.audioElement.volume = this.soundVolume;
            
            // Reproducir el audio con manejo de errores
            const playPromise = this.audioElement.play();
            
            if (playPromise !== undefined) {
                playPromise.then(() => {
                    console.log('✅ Archivo de audio reproduciéndose');
                }).catch(error => {
                    console.error('❌ Error reproducing audio file:', error);
                });
            }
        } catch (error) {
            console.error('❌ Error al reproducir archivo:', error);
        }
    },

    /**
     * Obtener URL del sonido predeterminado
     */
    getDefaultSoundUrl: function() {
        // Generar sonido predeterminado si no existe
        if (typeof NotificationSoundUtility !== 'undefined') {
            let defaultUrl = localStorage.getItem('defaultSoundUrl');
            if (!defaultUrl) {
                defaultUrl = NotificationSoundUtility.generateDefaultSound();
                localStorage.setItem('defaultSoundUrl', defaultUrl);
            }
            return defaultUrl;
        }
        
        // Fallback si NotificationSoundUtility no está disponible
        return '/assets/audio/notification-sound.mp3';
    },

    /**
     * Detener el sonido
     */
    stop: function() {
        if (this.audioElement) {
            this.audioElement.pause();
            this.audioElement.currentTime = 0;
        }
    },

    /**
     * Cambiar volumen
     * @param {number} volume - Volumen entre 0 y 1 (o 0-100)
     */
    setVolume: function(volume) {
        // Normalizar si el valor está fuera del rango [0, 1]
        let normalizedVolume = parseFloat(volume);
        if (normalizedVolume > 1) {
            normalizedVolume = normalizedVolume / 100;
        }
        this.soundVolume = Math.max(0, Math.min(1, normalizedVolume));
        localStorage.setItem('notificationSoundVolume', this.soundVolume);
        if (this.audioElement) {
            this.audioElement.volume = this.soundVolume;
        }
    },

    /**
     * Activar/Desactivar sonido
     * @param {boolean} enabled
     */
    setEnabled: function(enabled) {
        this.soundEnabled = enabled;
        localStorage.setItem('notificationSoundEnabled', enabled ? 'true' : 'false');
    },

    /**
     * Toggle sonido
     */
    toggle: function() {
        this.setEnabled(!this.soundEnabled);
        return this.soundEnabled;
    },

    /**
     * Obtener estado
     */
    isEnabled: function() {
        return this.soundEnabled;
    }
};

// Inicializar cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        NotificationSoundManager.init();
    });
} else {
    NotificationSoundManager.init();
}


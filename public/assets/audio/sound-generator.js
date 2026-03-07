/**
 * Sound Generator - Genera sonidos sintéticos usando Web Audio API
 * No requiere archivos MP3, todo se genera en tiempo real
 */

const SoundGenerator = {
    audioContext: null,
    
    /**
     * Inicializar el contexto de audio
     */
    init: function() {
        if (!this.audioContext) {
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            this.audioContext = new AudioContext();
        }
        return this.audioContext;
    },

    /**
     * Reproducir un sonido simple
     * @param {number} frequency - Frecuencia en Hz
     * @param {number} duration - Duración en segundos
     * @param {number} volume - Volumen 0-1
     * @param {string} type - Tipo de onda: 'sine', 'square', 'sawtooth', 'triangle'
     */
    playTone: function(frequency = 440, duration = 0.3, volume = 0.5, type = 'sine') {
        const ctx = this.init();
        
        // Crear nodos
        const oscillator = ctx.createOscillator();
        const gainNode = ctx.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(ctx.destination);
        
        oscillator.type = type;
        oscillator.frequency.value = frequency;
        
        // Volumen
        gainNode.gain.setValueAtTime(volume, ctx.currentTime);
        
        // Fade out
        gainNode.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + duration);
        
        // Reproducir
        oscillator.start(ctx.currentTime);
        oscillator.stop(ctx.currentTime + duration);
    },

    /**
     * Sonido Ding (campana)
     * Frecuencia alta, corto y agradable
     */
    playDing: function(volume = 0.5) {
        console.log('🔔 Reproduciendo: Ding');
        // Nota Do (C4)
        this.playTone(523.25, 0.2, volume, 'sine');
        
        // Segundo tono ligeramente más alto
        setTimeout(() => {
            this.playTone(659.25, 0.15, volume * 0.7, 'sine');
        }, 150);
    },

    /**
     * Sonido Chime (suave)
     * Múltiples notas para efecto de campanas
     */
    playChime: function(volume = 0.5) {
        console.log('🎵 Reproduciendo: Chime');
        const frequencies = [523.25, 587.33, 659.25];
        const delays = [0, 100, 200];
        
        delays.forEach((delay, index) => {
            setTimeout(() => {
                this.playTone(frequencies[index], 0.3, volume * 0.6, 'sine');
            }, delay);
        });
    },

    /**
     * Sonido Bell (alto y resonante)
     * Sonido más fuerte y con más presencia
     */
    playBell: function(volume = 0.5) {
        console.log('🔊 Reproduciendo: Bell');
        // Nota La (A4)
        this.playTone(880, 0.3, volume, 'sine');
        
        // Nota Mi (E5) - más alta
        setTimeout(() => {
            this.playTone(659.25, 0.4, volume * 0.8, 'sine');
        }, 50);
        
        // Nota La nuevamente
        setTimeout(() => {
            this.playTone(880, 0.2, volume * 0.5, 'sine');
        }, 250);
    },

    /**
     * Sonido Ping (corto y agudo)
     * Perfecto para alertas rápidas
     */
    playPing: function(volume = 0.5) {
        console.log('📱 Reproduciendo: Ping');
        // Sonido agudo y corto
        this.playTone(1000, 0.1, volume, 'square');
    },

    /**
     * Sonido Alert (alerta)
     * Repetido para llamar atención
     */
    playAlert: function(volume = 0.5) {
        console.log('⚠️ Reproduciendo: Alert');
        const frequencies = [800, 1000];
        
        for (let i = 0; i < 3; i++) {
            setTimeout(() => {
                this.playTone(frequencies[i % 2], 0.1, volume, 'square');
            }, i * 150);
        }
    },

    /**
     * Reproducir sonido por nombre
     */
    play: function(soundName, volume = 0.5) {
        const method = 'play' + soundName.charAt(0).toUpperCase() + soundName.slice(1);
        
        if (typeof this[method] === 'function') {
            try {
                this[method](volume);
                return true;
            } catch (error) {
                console.error('Error reproduciendo ' + soundName + ':', error);
                return false;
            }
        } else {
            console.error('Sonido no encontrado: ' + soundName);
            return false;
        }
    }
};

// Inicializar cuando la página cargue - MÚLTIPLES FORMAS de asegurar disponibilidad
(function() {
    console.log('🎵 [sound-generator.js] Script cargado, inicializando SoundGenerator...');
    
    function initSoundGenerator() {
        try {
            if (typeof SoundGenerator !== 'undefined') {
                SoundGenerator.init();
                console.log('✅ [sound-generator.js] SoundGenerator inicializado exitosamente');
                console.log('✅ [sound-generator.js] AudioContext disponible:', SoundGenerator.audioContext !== null);
                return true;
            }
        } catch (error) {
            console.error('❌ [sound-generator.js] Error inicializando SoundGenerator:', error);
        }
        return false;
    }

    // Estrategia 1: Intentar inmediatamente
    if (initSoundGenerator()) {
        return;
    }

    // Estrategia 2: Si no funciona inmediatamente, intentar cuando el DOM esté listo
    if (document.readyState === 'loading') {
        console.log('⏳ [sound-generator.js] DOM cargando, esperando DOMContentLoaded...');
        document.addEventListener('DOMContentLoaded', () => {
            console.log('🔄 [sound-generator.js] DOMContentLoaded disparado, inicializando...');
            initSoundGenerator();
        });
    } else {
        // Estrategia 3: Si el DOM ya está listo, intentar en el siguiente frame
        console.log('🔄 [sound-generator.js] DOM ya está listo, inicializando en próximo frame...');
        requestAnimationFrame(() => {
            initSoundGenerator();
        });
    }

    // Estrategia 4: Asegurar inicialización después de un corto delay
    setTimeout(() => {
        if (!SoundGenerator.audioContext) {
            console.log('🔧 [sound-generator.js] Forzando inicialización...');
            SoundGenerator.init();
        }
    }, 500);
})();

// Asegurar que está disponible globalmente
window.SoundGenerator = SoundGenerator;
setTimeout(() => {
    console.log('🎵 [sound-generator.js] Final check - SoundGenerator disponible:', typeof SoundGenerator !== 'undefined');
    console.log('🎵 [sound-generator.js] SoundGenerator.audioContext disponible:', typeof SoundGenerator !== 'undefined' && SoundGenerator.audioContext !== null);
}, 1000);

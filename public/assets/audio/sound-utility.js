/**
 * Notification Sound Utility
 * Herramientas para generar, descargar y utilizar sonidos de notificación
 */

const NotificationSoundUtility = {
    /**
     * Generar un sonido predeterminado simple
     * Retorna un data URI de audio
     */
    generateDefaultSound: function() {
        // Crear un sonido simple usando Web Audio API
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const duration = 0.5; // 500ms
        const sampleRate = audioContext.sampleRate;
        const length = sampleRate * duration;
        
        // Crear buffer de audio mono
        const audioBuffer = audioContext.createBuffer(1, length, sampleRate);
        const data = audioBuffer.getChannelData(0);
        
        // Tonos: 800Hz (200ms) + 1200Hz (300ms)
        for (let i = 0; i < length; i++) {
            const t = i / sampleRate;
            let value = 0;
            
            // Primer tono
            if (t < 0.2) {
                const freq = 800;
                const envelope = 1 - Math.pow(t / 0.2 - 1, 2);
                value = 0.3 * Math.sin(2 * Math.PI * freq * t) * envelope;
            }
            // Segundo tono
            else if (t >= 0.25) {
                const freq = 1200;
                const t2 = t - 0.25;
                const envelope = 1 - Math.pow(t2 / 0.25 - 1, 2);
                value = 0.3 * Math.sin(2 * Math.PI * freq * t2) * envelope;
            }
            
            data[i] = value;
        }
        
        // Convertir a WAV y luego a data URI
        return this.audioBufferToWav(audioBuffer);
    },

    /**
     * Convertir AudioBuffer a WAV data URI
     */
    audioBufferToWav: function(audioBuffer) {
        const numberOfChannels = audioBuffer.numberOfChannels;
        const sampleRate = audioBuffer.sampleRate;
        const format = 1; // PCM
        const bitDepth = 16;
        
        // Obtener datos de todos los canales
        const channelData = [];
        for (let i = 0; i < numberOfChannels; i++) {
            channelData.push(audioBuffer.getChannelData(i));
        }
        
        // Intercalar datos si hay múltiples canales
        const interleaved = this.interleaveChannels(channelData);
        const dataLength = interleaved.length * (bitDepth / 8);
        
        // Crear ArrayBuffer con encabezado WAV
        const arrayBuffer = new ArrayBuffer(44 + dataLength);
        const view = new DataView(arrayBuffer);
        
        // Escribir encabezado WAV
        this.writeWavHeader(view, numberOfChannels, sampleRate, dataLength);
        
        // Escribir datos de audio
        let offset = 44;
        for (let i = 0; i < interleaved.length; i++) {
            view.setInt16(offset, Math.max(-32768, Math.min(32767, interleaved[i] * 32767)), true);
            offset += 2;
        }
        
        // Convertir a Blob
        const blob = new Blob([arrayBuffer], { type: 'audio/wav' });
        return URL.createObjectURL(blob);
    },

    /**
     * Escribir encabezado WAV
     */
    writeWavHeader: function(view, channels, sampleRate, dataLength) {
        const bytesPerSecond = sampleRate * channels * 2;
        const blockAlign = channels * 2;
        
        // RIFF chunk
        view.setUint8(0, 0x52); // 'R'
        view.setUint8(1, 0x49); // 'I'
        view.setUint8(2, 0x46); // 'F'
        view.setUint8(3, 0x46); // 'F'
        view.setUint32(4, 36 + dataLength, true);
        
        // WAVE header
        view.setUint8(8, 0x57); // 'W'
        view.setUint8(9, 0x41); // 'A'
        view.setUint8(10, 0x56); // 'V'
        view.setUint8(11, 0x45); // 'E'
        
        // fmt subchunk
        view.setUint8(12, 0x66); // 'f'
        view.setUint8(13, 0x6d); // 'm'
        view.setUint8(14, 0x74); // 't'
        view.setUint8(15, 0x20); // ' '
        view.setUint32(16, 16, true); // Chunk size
        view.setUint16(20, 1, true); // PCM
        view.setUint16(22, channels, true);
        view.setUint32(24, sampleRate, true);
        view.setUint32(28, bytesPerSecond, true);
        view.setUint16(32, blockAlign, true);
        view.setUint16(34, 16, true); // Bits per sample
        
        // data subchunk
        view.setUint8(36, 0x64); // 'd'
        view.setUint8(37, 0x61); // 'a'
        view.setUint8(38, 0x74); // 't'
        view.setUint8(39, 0x61); // 'a'
        view.setUint32(40, dataLength, true);
    },

    /**
     * Intercalar datos de canales
     */
    interleaveChannels: function(channels) {
        const length = channels[0].length;
        const result = new Float32Array(length * channels.length);
        let index = 0;
        
        for (let i = 0; i < length; i++) {
            for (let ch = 0; ch < channels.length; ch++) {
                result[index++] = channels[ch][i];
            }
        }
        
        return result;
    },

    /**
     * Probar el sonido predeterminado
     */
    testDefaultSound: function() {
        const audioUrl = this.generateDefaultSound();
        const audio = new Audio(audioUrl);
        audio.volume = 0.7;
        audio.play().catch(error => {
            console.error('Error playing test sound:', error);
            alert('Error reproducing sound. Check browser permissions.');
        });
    },

    /**
     * Descargar sonido predeterminado como archivo WAV
     */
    downloadDefaultSound: function() {
        const audioUrl = this.generateDefaultSound();
        const link = document.createElement('a');
        link.href = audioUrl;
        link.download = 'notification-sound.wav';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
};

// Pre-generar sonido predeterminado al cargar
if (typeof NotificationSoundManager !== 'undefined') {
    document.addEventListener('DOMContentLoaded', function() {
        // Generar y guardar URL del sonido predeterminado
        if (!localStorage.getItem('notificationSoundUrl')) {
            const defaultSoundUrl = NotificationSoundUtility.generateDefaultSound();
            localStorage.setItem('notificationSoundUrl', defaultSoundUrl);
        }
    });
}

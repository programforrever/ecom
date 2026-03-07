/**
 * Default Notification Sound Generator
 * Genera un sonido de notificación predeterminado usando Web Audio API
 */

const DefaultNotificationSound = {
    /**
     * Generar y descargar sonido predeterminado WAV
     */
    generateAndDownload: function() {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const duration = 0.5; // 500ms
        const sampleRate = audioContext.sampleRate;
        const length = sampleRate * duration;
        
        const audioBuffer = audioContext.createBuffer(1, length, sampleRate);
        const data = audioBuffer.getChannelData(0);
        
        // Crear sonido de notificación (dos tonos cortos)
        // Primer tono: 800Hz por 200ms
        const f1 = 800;
        // Segundo tono: 1200Hz por 300ms
        const f2 = 1200;
        
        for (let i = 0; i < length; i++) {
            const t = i / sampleRate;
            let value = 0;
            
            // Primer tono (0-0.2s)
            if (t < 0.2) {
                const freq = f1;
                value = 0.3 * Math.sin(2 * Math.PI * freq * t);
                // Envelope
                value *= (1 - Math.pow(t / 0.2 - 1, 2));
            }
            // Segundo tono (0.25-0.5s)
            else if (t >= 0.25) {
                const freq = f2;
                const t2 = t - 0.25;
                value = 0.3 * Math.sin(2 * Math.PI * freq * t2);
                // Envelope
                value *= (1 - Math.pow(t2 / 0.25 - 1, 2));
            }
            
            data[i] = value;
        }
        
        // Convertir a WAV
        const wav = this.encodeWAV(audioBuffer);
        return wav;
    },

    /**
     * Generar sonido y convertir a Blob
     */
    generateBlob: function() {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const duration = 0.5;
        const sampleRate = audioContext.sampleRate;
        const length = sampleRate * duration;
        
        const audioBuffer = audioContext.createBuffer(1, length, sampleRate);
        const data = audioBuffer.getChannelData(0);
        
        // Simple notification sound
        for (let i = 0; i < length; i++) {
            const t = i / sampleRate;
            let value = 0;
            
            if (t < 0.2) {
                value = 0.3 * Math.sin(2 * Math.PI * 800 * t) * (1 - Math.pow(t / 0.2 - 1, 2));
            } else if (t >= 0.25) {
                const t2 = t - 0.25;
                value = 0.3 * Math.sin(2 * Math.PI * 1200 * t2) * (1 - Math.pow(t2 / 0.25 - 1, 2));
            }
            
            data[i] = value;
        }
        
        const wav = this.encodeWAV(audioBuffer);
        return new Blob([wav], { type: 'audio/wav' });
    },

    /**
     * Codificar AudioBuffer a WAV
     */
    encodeWAV: function(audioBuffer) {
        const numberOfChannels = audioBuffer.numberOfChannels;
        const sampleRate = audioBuffer.sampleRate;
        const format = 1; // PCM
        const bitDepth = 16;
        
        const channelData = [];
        for (let i = 0; i < numberOfChannels; i++) {
            channelData.push(audioBuffer.getChannelData(i));
        }
        
        const interleaved = this.interleave(channelData);
        const dataLength = interleaved.length * (bitDepth / 8);
        const bufferLength = 36 + dataLength;
        
        const arrayBuffer = new ArrayBuffer(44 + dataLength);
        const view = new DataView(arrayBuffer);
        
        // RIFF chunk
        this.writeString(view, 0, 'RIFF');
        view.setUint32(4, 36 + dataLength, true);
        this.writeString(view, 8, 'WAVE');
        
        // fmt subchunk
        this.writeString(view, 12, 'fmt ');
        view.setUint32(16, 16, true);
        view.setUint16(20, format, true);
        view.setUint16(22, numberOfChannels, true);
        view.setUint32(24, sampleRate, true);
        view.setUint32(28, sampleRate * 2 * numberOfChannels, true);
        view.setUint16(32, numberOfChannels * 2, true);
        view.setUint16(34, bitDepth, true);
        
        // data subchunk
        this.writeString(view, 36, 'data');
        view.setUint32(40, dataLength, true);
        
        let offset = 44;
        const volume = 0.8;
        for (let i = 0; i < interleaved.length; i++) {
            view.setInt16(offset, interleaved[i] * (0x7FFF * volume), true);
            offset += 2;
        }
        
        return arrayBuffer;
    },

    /**
     * Intercalar canales de audio
     */
    interleave: function(channelData) {
        const length = channelData[0].length;
        const result = new Float32Array(length * channelData.length);
        let index = 0;
        let inputIndex = 0;
        
        while (inputIndex < length) {
            for (let i = 0; i < channelData.length; i++) {
                result[index++] = channelData[i][inputIndex];
            }
            inputIndex++;
        }
        
        return result;
    },

    /**
     * Escribir string en DataView
     */
    writeString: function(view, offset, string) {
        for (let i = 0; i < string.length; i++) {
            view.setUint8(offset + i, string.charCodeAt(i));
        }
    }
};

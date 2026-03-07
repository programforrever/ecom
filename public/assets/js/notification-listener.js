/**
 * Notification Sound Listener - VERSIÓN MEJORADA
 * Escucha notificaciones nuevas y reproduce sonido automáticamente
 * Funciona en todas las páginas del ecommerce
 */

const NotificationListener = {
    lastUnreadCount: null, // Inicializar como null para detectar primer cambio
    checkInterval: 2000, // Verificar cada 2 segundos (MÁS RÁPIDO)
    isRunning: false,
    soundSettings: null,
    isCheckingNow: false,
    failedAttempts: 0,
    maxFailedAttempts: 5,
    pollingInterval: null,

    /**
     * Iniciar el listener de notificaciones
     */
    start: function() {
        if (this.isRunning) {
            console.log('⚠️ NotificationListener ya está en ejecución');
            return;
        }

        // Verificar que estamos autenticados
        const token = document.querySelector('meta[name="csrf-token"]');
        if (!token) {
            console.log('📌 Usuario no autenticado - NotificationListener no iniciado');
            return;
        }

        this.isRunning = true;
        console.log('🚀 NotificationListener INICIANDO...');
        console.log(`⏱️ Intervalo: ${this.checkInterval}ms`);
        console.log('📡 Escuchando notificaciones en toda la aplicación...');
        
        // Cargar configuración de sonido primero
        this.loadSoundSettings().then(() => {
            console.log('⚙️ Configuración de sonido cargada, iniciando verificación...');
            // Hacer verificación inicial
            this.checkForNewNotifications();
            // Luego iniciar el polling
            this.startPolling();
            console.log('✅ NotificationListener activo - esperando notificaciones');
        }).catch(error => {
            console.error('❌ Error al iniciar listener:', error);
            this.isRunning = false;
        });
    },

    /**
     * Iniciar el polling continuo
     */
    startPolling: function() {
        this.pollingInterval = setInterval(() => {
            this.checkForNewNotifications();
        }, this.checkInterval);
    },

    /**
     * Cargar configuración de sonido desde la API
     */
    loadSoundSettings: async function() {
        try {
            const response = await fetch('/api/notification-sound-settings', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }
            
            this.soundSettings = await response.json();
            console.log('✅ Settings cargados:', {
                enabled: this.soundSettings.enabled,
                type: this.soundSettings.type,
                preset_sound: this.soundSettings.preset_sound,
                volume: this.soundSettings.volume
            });

            // Aplicar configuración al NotificationSoundManager
            if (typeof NotificationSoundManager !== 'undefined') {
                NotificationSoundManager.setEnabled(this.soundSettings.enabled === 'on');
                NotificationSoundManager.setVolume(this.soundSettings.volume);
                
                // Guardar en localStorage para rápido acceso
                const soundUrl = this.soundSettings.type === 'custom' 
                    ? this.soundSettings.custom_sound_url 
                    : null;
                if (soundUrl) {
                    localStorage.setItem('notificationSoundUrl', soundUrl);
                } else {
                    localStorage.removeItem('notificationSoundUrl');
                }
                
                if (this.soundSettings.type === 'default') {
                    localStorage.setItem('notificationPresetSound', this.soundSettings.preset_sound);
                }
            }
        } catch (error) {
            console.warn('⚠️ Error cargando settings:', error);
        }
    },

    /**
     * Detener el listener
     */
    stop: function() {
        this.isRunning = false;
        console.log('🛑 NotificationListener detenido');
    },

    /**
     * Verificar si hay nuevas notificaciones por polling
     */
    checkForNewNotifications: async function() {
        if (!this.isRunning || this.isCheckingNow) {
            return;
        }

        this.isCheckingNow = true;

        try {
            const response = await fetch('/api/unread-notifications', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            });

            if (!response.ok) {
                this.failedAttempts++;
                if (this.failedAttempts >= this.maxFailedAttempts) {
                    console.error('❌ Demasiados intentos fallidos');
                    this.isCheckingNow = false;
                    return;
                }
                throw new Error(`HTTP ${response.status}`);
            }

            const data = await response.json();
            const currentCount = data.unread_count || 0;

            // Debug logging
            if (this.lastUnreadCount === null) {
                console.log('📬 Inicializando contador. Notificaciones actuales:', currentCount);
                this.lastUnreadCount = currentCount;
            } else {
                // Se detectó nueva(s) notificación(es)
                if (currentCount > this.lastUnreadCount) {
                    const newCount = currentCount - this.lastUnreadCount;
                    console.log(`🔔 ¡¡${newCount} NUEVA(S) NOTIFICACIÓN(ES) DETECTADA(S)!! Reproduciendo sonido...`);
                    this.playNotificationSound();
                    this.lastUnreadCount = currentCount;
                } else if (currentCount < this.lastUnreadCount) {
                    console.log('📉 Contador bajó (usuario leyó notificaciones)');
                    this.lastUnreadCount = currentCount;
                }
            }

            // Reset de intentos fallidos
            this.failedAttempts = 0;

        } catch (error) {
            this.failedAttempts++;
            console.error('❌ Error en polling:', error.message);
        } finally {
            this.isCheckingNow = false;
        }
    },

    /**
     * Reproducir sonido de notificación
     */
    playNotificationSound: function() {
        console.log('▶️ Intentando reproducir sonido...');

        if (typeof NotificationSoundManager === 'undefined') {
            console.warn('⚠️ NotificationSoundManager no disponible');
            return;
        }

        try {
            NotificationSoundManager.play();
            console.log('✅ ¡Sonido reproducido exitosamente!');
        } catch (error) {
            console.error('❌ Error reproduciendo sonido:', error);
        }
    },

    /**
     * Detener el listener
     */
    stop: function() {
        this.isRunning = false;
        if (this.pollingInterval) {
            clearInterval(this.pollingInterval);
        }
        console.log('🛑 NotificationListener detenido');
    }
};

/**
 * Iniciar automáticamente cuando el documento esté listo
 */
(function() {
    console.log('🔍 Inicializando NotificationListener...');
    
    function initListener() {
        // Solo iniciar si el usuario está autenticado
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token && NotificationListener) {
            NotificationListener.start();
            return true;
        }
        return false;
    }

    // Intentar inmediatamente
    if (initListener()) {
        console.log('✅ Listener iniciado inmediatamente');
        return;
    }

    // Si no funciona inmediatamente, esperar al DOMContentLoaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            console.log('📄 DOMContentLoaded - iniciando listener');
            initListener();
        });
    } else {
        // Si el DOM ya está cargado
        setTimeout(() => {
            console.log('⏳ DOM ya cargado - iniciando listener después del delay');
            initListener();
        }, 500);
    }
})();

/**
 * Funciones globales para testing
 */
window.testNotificationSound = function() {
    console.log('🔔 Función test global llamada');
    if (NotificationListener) {
        NotificationListener.testNotification();
    }
};

window.getNotificationStatus = function() {
    console.log('=== ESTADO DEL LISTENER ===');
    console.log('Ejecutando:', NotificationListener.isRunning);
    console.log('Contador anterior:', NotificationListener.lastUnreadCount);
    console.log('Verificando:', NotificationListener.isCheckingNow);
    console.log('Intentos fallidos:', NotificationListener.failedAttempts);
    console.log('Settings:', NotificationListener.soundSettings);
    console.log('===========================');
};

// Logging inicial
console.log('✨ notification-listener.js cargado y listo');


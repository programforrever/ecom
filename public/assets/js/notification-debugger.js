/**
 * Notification System Debugger
 * Añade funciones de debug al console para verificar que todo funciona
 */

window.NotificationDebugger = {
    /**
     * Verificar estado general del sistema
     */
    checkSystemStatus: async function() {
        console.log('%c===== NOTIFICATION SYSTEM DEBUG =====', 'color: blue; font-size: 16px; font-weight: bold;');
        
        // 1. Verificar autenticación
        const token = document.querySelector('meta[name="csrf-token"]');
        console.log('✓ Usuario autenticado:', !!token);
        
        // 2. Verificar scripts cargados
        console.log('✓ SoundGenerator disponible:', typeof SoundGenerator !== 'undefined');
        console.log('✓ NotificationSoundManager disponible:', typeof NotificationSoundManager !== 'undefined');
        console.log('✓ NotificationListener disponible:', typeof NotificationListener !== 'undefined');
        
        // 3. Verificar localStorage
        console.log('%cLocalStorage settings:', 'color: green; font-weight: bold;');
        console.log('  - notificationSoundEnabled:', localStorage.getItem('notificationSoundEnabled'));
        console.log('  - notificationSoundVolume:', localStorage.getItem('notificationSoundVolume'));
        console.log('  - notificationPresetSound:', localStorage.getItem('notificationPresetSound'));
        console.log('  - notificationSoundUrl:', localStorage.getItem('notificationSoundUrl'));
        
        // 4. Probar API endpoints
        console.log('%cTesting API Endpoints:', 'color: green; font-weight: bold;');
        
        // Test 1: Unread notifications
        try {
            const unreadResponse = await fetch('/api/unread-notifications', {
                credentials: 'same-origin'
            });
            const unreadData = await unreadResponse.json();
            console.log('✓ /api/unread-notifications:', unreadData);
        } catch (error) {
            console.error('✗ /api/unread-notifications error:', error.message);
        }
        
        // Test 2: Settings
        try {
            const settingsResponse = await fetch('/api/notification-sound-settings', {
                credentials: 'same-origin'
            });
            const settingsData = await settingsResponse.json();
            console.log('✓ /api/notification-sound-settings:', settingsData);
        } catch (error) {
            console.error('✗ /api/notification-sound-settings error:', error.message);
        }
        
        // 5. Verificar estado del listener
        if (typeof NotificationListener !== 'undefined') {
            console.log('%cNotificationListener Status:', 'color: green; font-weight: bold;');
            console.log('  - isRunning:', NotificationListener.isRunning);
            console.log('  - lastUnreadCount:', NotificationListener.lastUnreadCount);
            console.log('  - isCheckingNow:', NotificationListener.isCheckingNow);
            console.log('  - failedAttempts:', NotificationListener.failedAttempts);
        }
        
        console.log('%c===== END DEBUG =====', 'color: blue; font-size: 14px;');
    },

    /**
     * Reproducir un sonido de prueba manually
     */
    playTestSound: function(soundName = 'ding') {
        console.log(`🔔 Intentando reproducir sonido: ${soundName}`);
        
        if (typeof SoundGenerator !== 'undefined') {
            try {
                SoundGenerator.play(soundName, 0.7);
                console.log('✓ Sonido reproducido exitosamente');
            } catch (error) {
                console.error('✗ Error reproduciendo sonido:', error);
            }
        } else {
            console.error('✗ SoundGenerator no disponible');
        }
    },

    /**
     * Simular una notificación nueva
     */
    simulateNewNotification: async function() {
        console.log('🧪 Simulando notificación nueva...');
        
        try {
            const token = document.querySelector('meta[name="csrf-token"]');
            if (!token) {
                console.error('✗ CSRF token no encontrado');
                return;
            }

            const response = await fetch('/api/test-notification', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token.getAttribute('content'),
                    'Accept': 'application/json'
                },
                credentials: 'same-origin',
                body: JSON.stringify({})
            });

            const data = await response.json();
            console.log('✓ Notificación creada:', data);
            console.log('⏳ El sonido debería reproducirse en los próximos 2-3 segundos...');
            
            // Forzar una verificación inmediata
            if (typeof NotificationListener !== 'undefined') {
                setTimeout(() => {
                    console.log('🔍 Forzando verificación inmediata...');
                    NotificationListener.checkForNewNotifications();
                }, 500);
            }
        } catch (error) {
            console.error('✗ Error creando notificación:', error);
        }
    },

    /**
     * Ver todos los sonidos disponibles
     */
    listAvailableSounds: function() {
        console.log('%cSonidos disponibles:', 'color: green; font-weight: bold;');
        const sounds = ['ding', 'chime', 'bell', 'ping', 'alert'];
        sounds.forEach(sound => {
            console.log(`  - ${sound}`);
        });
    }
};

// Mostrar mensaje de bienvenida
console.log('%c✨ NotificationDebugger cargado', 'color: cyan; font-size: 14px; font-weight: bold;');
console.log('%cComandos disponibles:', 'color: cyan; font-weight: bold;');
console.log('  - NotificationDebugger.checkSystemStatus()');
console.log('  - NotificationDebugger.playTestSound("ding")');
console.log('  - NotificationDebugger.simulateNewNotification()');
console.log('  - NotificationDebugger.listAvailableSounds()');

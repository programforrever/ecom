<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()->paginate(15);

        // Don't auto-mark as read - let notifications stay unread so sound plays when new ones arrive
        // auth()->user()->unreadNotifications->markAsRead();

        if (Auth::user()->user_type == 'admin') {
            return view('backend.notification.index', compact('notifications'));
        }

        if (Auth::user()->user_type == 'seller') {
            return view('seller.notification.index', compact('notifications'));
        }

        if (Auth::user()->user_type == 'customer') {
            return view('frontend.user.customer.notification.index', compact('notifications'));
        }
    }

    /**
     * API endpoint para obtener notificaciones no leídas (para reproducir sonido)
     */
    public function getUnreadNotifications()
    {
        $unreadCount = auth()->user()->unreadNotifications->count();
        return response()->json([
            'unread_count' => $unreadCount,
            'has_unread' => $unreadCount > 0
        ]);
    }

    /**
     * API endpoint para obtener configuración de sonido de notificaciones
     */
    public function getNotificationSoundSettings()
    {
        $settings = \App\Models\BusinessSetting::where('type', 'like', 'notification_sound%')
            ->pluck('value', 'type')
            ->toArray();

        return response()->json([
            'enabled' => $settings['notification_sound_enabled'] ?? 'off',
            'type' => $settings['notification_sound_type'] ?? 'preset',
            'preset_sound' => $settings['preset_sound'] ?? 'ding',
            'volume' => $settings['notification_sound_volume'] ?? 70,
            'custom_sound_url' => $settings['custom_notification_sound'] ?? null,
            'allow_user_control' => $settings['allow_user_sound_control'] ?? 'off',
            'sound_on_types' => json_decode($settings['sound_on_types'] ?? '[]', true)
        ]);
    }

    /**
     * Endpoint DEBUG - Ver todas las configuraciones de sonido en BD
     */
    public function debugSoundSettings()
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        $settings = \App\Models\BusinessSetting::where('type', 'like', 'notification_sound%')
            ->orWhere('type', 'custom_notification_sound')
            ->get();

        $debug = [];
        foreach ($settings as $setting) {
            $debug[$setting->type] = [
                'value' => $setting->value,
                'exists' => !empty($setting->value),
            ];

            // Si es un archivo, verificar si existe en storage
            if (strpos($setting->type, 'sound') !== false && !empty($setting->value)) {
                $filePath = 'public/' . $setting->value;
                $exists = \Storage::exists($filePath);
                $debug[$setting->type]['file_exists'] = $exists;
                $debug[$setting->type]['full_path'] = storage_path('app/' . $filePath);
                $debug[$setting->type]['url'] = $exists ? asset('storage/' . $setting->value) : 'NO EXISTE';
            }
        }

        // Listar archivos en directorio
        $soundsDir = storage_path('app/public/notification_sounds');
        $debug['directory_info'] = [
            'path' => $soundsDir,
            'exists' => file_exists($soundsDir),
            'files' => []
        ];

        if (file_exists($soundsDir)) {
            $files = array_diff(scandir($soundsDir), ['.', '..']);
            foreach ($files as $file) {
                $filePath = $soundsDir . '/' . $file;
                $debug['directory_info']['files'][] = [
                    'name' => $file,
                    'size' => filesize($filePath),
                    'url' => asset('storage/notification_sounds/' . $file)
                ];
            }
        }

        return response()->json($debug);
    }

    /**
     * Endpoint DEBUG - Eliminar sonido personalizado
     */
    public function debugRemoveCustomSound()
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        try {
            $currentSound = \App\Models\BusinessSetting::where('type', 'custom_notification_sound')->first();
            if ($currentSound && !empty($currentSound->value)) {
                $filePath = 'public/' . $currentSound->value;
                
                // Intentar eliminar archivo
                if (\Storage::exists($filePath)) {
                    \Storage::delete($filePath);
                    \Log::info('✅ Archivo eliminado: ' . $filePath);
                }
                
                // Eliminar registro
                $currentSound->delete();
                \Log::info('✅ Setting eliminado de BD');
                
                return response()->json([
                    'success' => true,
                    'message' => 'Sonido personalizado eliminado correctamente'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay sonido personalizado para eliminar'
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('❌ Error eliminando sonido: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear notificación de prueba
     */
    public function testCreateNotification()
    {
        try {
            $user = auth()->user();
            if (!$user) {
                return response()->json(['error' => 'Usuario no autenticado'], 401);
            }

            // Crear una notificación de prueba
            $notification = new \Illuminate\Notifications\DatabaseNotification();
            $notification->id = \Illuminate\Support\Str::uuid();
            $notification->notifiable_id = $user->id;
            $notification->notifiable_type = get_class($user);
            $notification->type = 'App\Notifications\TestNotification';
            $notification->data = json_encode([
                'title' => 'Test Notification 🔔',
                'message' => 'This is a test notification to verify sound is working',
                'type' => 'test'
            ]);
            $notification->read_at = null;
            $notification->created_at = now();
            $notification->updated_at = now();
            $notification->save();

            return response()->json([
                'success' => true,
                'message' => 'Notificación de prueba creada. Deberías escuchar un sonido en 5 segundos.',
                'unread_count' => $user->unreadNotifications->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error creating test notification: ' . $e->getMessage()
            ], 500);
        }
    }
}


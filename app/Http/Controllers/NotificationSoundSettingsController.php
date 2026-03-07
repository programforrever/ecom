<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusinessSetting;
use Artisan;

class NotificationSoundSettingsController extends Controller
{
    public function __construct() {
        $this->middleware(['permission:general_settings'])->only(['index', 'update']);
    }

    /**
     * Mostrar la página de configuración de sonidos de notificación
     */
    public function index()
    {
        return view('backend.setup_configurations.notification_sound_settings');
    }

    /**
     * Guardar la configuración de sonidos de notificación
     */
    public function update(Request $request)
    {
        try {
            // Validar entrada
            $this->validate($request, [
                'notification_sound_volume' => 'required|numeric|min:0|max:100',
                'custom_notification_sound' => 'nullable|file|mimes:mp3,wav,ogg,webm,aac,flac,m4a,mp4|max:5120',
            ]);

            // Guardar sonido habilitado
            $enabled = $request->has('notification_sound_enabled') ? 'on' : 'off';
            $this->saveSetting('notification_sound_enabled', $enabled);

            // Guardar tipo de sonido
            $soundType = $request->input('notification_sound_type', 'default');
            $this->saveSetting('notification_sound_type', $soundType);

            // Guardar sonido preestablecido
            if ($soundType === 'default') {
                $this->saveSetting('preset_sound', $request->input('preset_sound', 'ding'));
            }

            // Eliminar sonido personalizado si se solicita
            if ($request->has('remove_custom_sound') && $request->input('remove_custom_sound') == '1') {
                $currentSound = BusinessSetting::where('type', 'custom_notification_sound')->first();
                if ($currentSound && !empty($currentSound->value)) {
                    try {
                        $filePath = 'public/' . $currentSound->value;
                        if (\Storage::exists($filePath)) {
                            \Storage::delete($filePath);
                            \Log::info('Archivo de sonido eliminado: ' . $filePath);
                        }
                    } catch (\Exception $e) {
                        \Log::error('Error eliminando archivo: ' . $e->getMessage());
                    }
                    $currentSound->delete();
                    \Log::info('Setting de sonido personalizado eliminado de BD');
                }
            }

            // Manejar sonido personalizado
            if ($request->hasFile('custom_notification_sound')) {
                $file = $request->file('custom_notification_sound');
                
                // Generar nombre único
                $fileName = 'notification_' . uniqid() . '.' . $file->getClientOriginalExtension();
                
                // Crear directorio si no existe
                \Storage::makeDirectory('public/notification_sounds');
                
                // Guardar archivo
                try {
                    $stored = $file->storeAs('public/notification_sounds', $fileName);
                    \Log::info('Archivo guardado en: ' . $stored);
                    
                    // Guardar ruta en BD (sin "public/")
                    $this->saveSetting('custom_notification_sound', 'notification_sounds/' . $fileName);
                    \Log::info('Ruta guardada en BD: notification_sounds/' . $fileName);
                    
                } catch (\Exception $e) {
                    \Log::error('Error guardando archivo: ' . $e->getMessage());
                    throw new \Exception('Error al guardar el archivo: ' . $e->getMessage());
                }
            }

            // Guardar volumen (se guarda en escala 0-100)
            $volume = (int)$request->input('notification_sound_volume', 70);
            $this->saveSetting('notification_sound_volume', (string)$volume);

            // Guardar control de usuario
            $this->saveSetting('allow_user_sound_control', $request->has('allow_user_sound_control') ? 'on' : 'off');

            // Guardar tipos de sonido
            $soundTypes = $request->input('sound_types', ['order']);
            $this->saveSetting('sound_on_types', json_encode($soundTypes));

            // Limpiar cache
            Artisan::call('cache:clear');

            flash(translate('Configuración de sonido guardada exitosamente'))->success();
            return redirect()->back();

        } catch (\Exception $e) {
            \Log::error('Error en NotificationSoundSettingsController: ' . $e->getMessage());
            flash(translate('Error al guardar la configuración') . ': ' . $e->getMessage())->error();
            return redirect()->back();
        }
    }

    /**
     * Guardar setting en BD
     */
    private function saveSetting($type, $value)
    {
        $setting = BusinessSetting::where('type', $type)->first();

        if ($setting) {
            $setting->value = $value;
            $setting->save();
        } else {
            BusinessSetting::create([
                'type' => $type,
                'value' => $value,
            ]);
        }
    }
}

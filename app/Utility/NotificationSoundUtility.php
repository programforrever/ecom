<?php

namespace App\Utility;

use App\Models\GeneralSetting;

class NotificationSoundUtility
{
    /**
     * Get notification sound types that should have sound
     */
    public static function getSoundTypes()
    {
        $soundTypes = get_setting('sound_on_types');
        
        if ($soundTypes) {
            $types = json_decode($soundTypes, true);
            return is_array($types) ? $types : ['order'];
        }
        
        return ['order']; // Default
    }

    /**
     * Check if sound should play for a specific type
     */
    public static function shouldPlayFor($type)
    {
        $soundTypes = self::getSoundTypes();
        
        // If 'all' is selected, play for everything
        if (in_array('all', $soundTypes)) {
            return true;
        }
        
        return in_array($type, $soundTypes);
    }

    /**
     * Determine sound URL to use
     */
    public static function getSoundUrl()
    {
        $soundType = get_setting('notification_sound_type', 'default');
        
        if ($soundType === 'custom' && get_setting('custom_notification_sound')) {
            return uploaded_asset(get_setting('custom_notification_sound'));
        }
        
        // Return default or null
        return null; // Client will generate default sound
    }

    /**
     * Check if notification sounds are enabled
     */
    public static function isEnabled()
    {
        return get_setting('notification_sound_enabled', 1) == 1;
    }

    /**
     * Get preset sound selection
     */
    public static function getPresetSound()
    {
        return get_setting('preset_sound', 'ding');
    }

    /**
     * Get default volume
     */
    public static function getDefaultVolume()
    {
        return (int)get_setting('notification_sound_volume', 70);
    }
}

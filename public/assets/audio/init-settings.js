/**
 * Initialize default notification sound settings in the UI
 * This script ensures default values are set when the page loads
 */

document.addEventListener('DOMContentLoaded', function() {
    // Set default values for sound settings if they don't exist
    initializeNotificationSoundSettings();
});

function initializeNotificationSoundSettings() {
    // Ensure localStorage has default values
    if (!localStorage.getItem('notificationSoundEnabled')) {
        localStorage.setItem('notificationSoundEnabled', 'true');
    }
    
    if (!localStorage.getItem('notificationSoundVolume')) {
        localStorage.setItem('notificationSoundVolume', '70');
    }
    
    // Generate default sound if not already generated
    if (typeof NotificationSoundUtility !== 'undefined' && !localStorage.getItem('defaultSoundUrl')) {
        try {
            const soundUrl = NotificationSoundUtility.generateDefaultSound();
            localStorage.setItem('defaultSoundUrl', soundUrl);
        } catch (error) {
            console.log('Could not generate default sound:', error);
        }
    }
}

/**
 * Function to test the currently selected sound
 */
function testNotificationSound() {
    if (typeof NotificationSoundManager !== 'undefined') {
        NotificationSoundManager.play();
    }
}

/**
 * Enable/Disable notification sounds globally
 */
function toggleNotificationSound(enabled) {
    if (typeof NotificationSoundManager !== 'undefined') {
        NotificationSoundManager.setEnabled(enabled);
    }
}

/**
 * Set notification sound volume
 */
function setNotificationSoundVolume(volume) {
    if (typeof NotificationSoundManager !== 'undefined') {
        NotificationSoundManager.setVolume(volume);
    }
}

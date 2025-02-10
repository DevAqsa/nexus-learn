<?php
if (!defined('ABSPATH')) exit;

$user_id = get_current_user_id();
$user_info = get_userdata($user_id);
$notification_preferences = get_user_meta($user_id, 'nl_notification_preferences', true) ?: [];
$language_preference = get_user_meta($user_id, 'nl_language_preference', true) ?: 'en';
$time_zone = get_user_meta($user_id, 'nl_time_zone', true) ?: 'UTC';
$display_mode = get_user_meta($user_id, 'nl_display_mode', true) ?: 'light';
?>

<div class="nl-settings-container">
    <!-- Settings Header -->
    <div class="nl-section-header">
        <h2><?php _e('General Settings', 'nexuslearn'); ?></h2>
        <p class="nl-subtitle"><?php _e('Manage your account preferences and settings', 'nexuslearn'); ?></p>
    </div>

    <!-- Settings Form -->
    <form id="nl-student-settings-form" class="nl-settings-form">
        <!-- Profile Settings Section -->
        <div class="nl-settings-section">
            <h3><?php _e('Profile Settings', 'nexuslearn'); ?></h3>
            
            <div class="nl-form-group">
                <label for="display_name"><?php _e('Display Name', 'nexuslearn'); ?></label>
                <input type="text" id="display_name" name="display_name" 
                       value="<?php echo esc_attr($user_info->display_name); ?>">
            </div>

            <div class="nl-form-group">
                <label for="email"><?php _e('Email Address', 'nexuslearn'); ?></label>
                <input type="email" id="email" name="email" 
                       value="<?php echo esc_attr($user_info->user_email); ?>">
            </div>
        </div>

        <!-- Notification Settings Section -->
        <div class="nl-settings-section">
            <h3><?php _e('Notification Settings', 'nexuslearn'); ?></h3>
            
            <div class="nl-form-group">
                <label class="nl-checkbox">
                    <input type="checkbox" name="notifications[]" value="course_updates"
                           <?php checked(in_array('course_updates', $notification_preferences)); ?>>
                    <?php _e('Course Updates', 'nexuslearn'); ?>
                </label>
            </div>

            <div class="nl-form-group">
                <label class="nl-checkbox">
                    <input type="checkbox" name="notifications[]" value="assignment_reminders"
                           <?php checked(in_array('assignment_reminders', $notification_preferences)); ?>>
                    <?php _e('Assignment Reminders', 'nexuslearn'); ?>
                </label>
            </div>

            <div class="nl-form-group">
                <label class="nl-checkbox">
                    <input type="checkbox" name="notifications[]" value="quiz_results"
                           <?php checked(in_array('quiz_results', $notification_preferences)); ?>>
                    <?php _e('Quiz Results', 'nexuslearn'); ?>
                </label>
            </div>
        </div>

        <!-- Preferences Section -->
        <div class="nl-settings-section">
            <h3><?php _e('Preferences', 'nexuslearn'); ?></h3>
            
            <div class="nl-form-group">
                <label for="language"><?php _e('Language', 'nexuslearn'); ?></label>
                <select id="language" name="language">
                    <option value="en" <?php selected($language_preference, 'en'); ?>>English</option>
                    <option value="es" <?php selected($language_preference, 'es'); ?>>Español</option>
                    <option value="fr" <?php selected($language_preference, 'fr'); ?>>Français</option>
                </select>
            </div>

            <div class="nl-form-group">
                <label for="timezone"><?php _e('Time Zone', 'nexuslearn'); ?></label>
                <select id="timezone" name="timezone">
                    <?php
                    $timezones = DateTimeZone::listIdentifiers();
                    foreach ($timezones as $timezone) {
                        echo '<option value="' . esc_attr($timezone) . '" ' . 
                             selected($time_zone, $timezone, false) . '>' . 
                             esc_html($timezone) . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="nl-form-group">
                <label for="display_mode"><?php _e('Display Mode', 'nexuslearn'); ?></label>
                <select id="display_mode" name="display_mode">
                    <option value="light" <?php selected($display_mode, 'light'); ?>>
                        <?php _e('Light Mode', 'nexuslearn'); ?>
                    </option>
                    <option value="dark" <?php selected($display_mode, 'dark'); ?>>
                        <?php _e('Dark Mode', 'nexuslearn'); ?>
                    </option>
                </select>
            </div>
        </div>

        <!-- Privacy Settings Section -->
        <div class="nl-settings-section">
            <h3><?php _e('Privacy Settings', 'nexuslearn'); ?></h3>
            
            <div class="nl-form-group">
                <label class="nl-checkbox">
                    <input type="checkbox" name="privacy[]" value="show_profile"
                           <?php checked(get_user_meta($user_id, 'nl_show_profile', true)); ?>>
                    <?php _e('Show my profile to other students', 'nexuslearn'); ?>
                </label>
            </div>

            <div class="nl-form-group">
                <label class="nl-checkbox">
                    <input type="checkbox" name="privacy[]" value="show_progress"
                           <?php checked(get_user_meta($user_id, 'nl_show_progress', true)); ?>>
                    <?php _e('Show my progress in course discussions', 'nexuslearn'); ?>
                </label>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="nl-form-actions">
            <button type="submit" class="nl-button nl-button-primary">
                <?php _e('Save Changes', 'nexuslearn'); ?>
            </button>
        </div>
    </form>
</div>

<script>
jQuery(document).ready(function($) {
    $('#nl-student-settings-form').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('action', 'nl_update_student_settings');
        formData.append('nonce', nlDashboard.nonce);

        $.ajax({
            url: nlDashboard.ajaxUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    alert('Settings saved successfully!');
                } else {
                    alert('Failed to save settings. Please try again.');
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
            }
        });
    });
});
</script>
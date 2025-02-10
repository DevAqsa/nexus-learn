<?php
namespace NexusLearn\Frontend\Components;

class StudentSettings {
    public function __construct() {
        add_action('wp_ajax_nl_update_student_settings', [$this, 'update_settings']);
    }

    public function update_settings() {
        check_ajax_referer('nl_dashboard_nonce', 'nonce');
        
        $user_id = get_current_user_id();
        
        // Update display name
        if (!empty($_POST['display_name'])) {
            wp_update_user([
                'ID' => $user_id,
                'display_name' => sanitize_text_field($_POST['display_name'])
            ]);
        }
        
        // Update email
        if (!empty($_POST['email']) && is_email($_POST['email'])) {
            wp_update_user([
                'ID' => $user_id,
                'user_email' => sanitize_email($_POST['email'])
            ]);
        }
        
        // Update notification preferences
        if (isset($_POST['notifications'])) {
            $notifications = array_map('sanitize_text_field', $_POST['notifications']);
            update_user_meta($user_id, 'nl_notification_preferences', $notifications);
        }
        
        // Update language preference
        if (!empty($_POST['language'])) {
            update_user_meta($user_id, 'nl_language_preference', 
                sanitize_text_field($_POST['language']));
        }
        
        // Update timezone
        if (!empty($_POST['timezone'])) {
            update_user_meta($user_id, 'nl_time_zone', 
                sanitize_text_field($_POST['timezone']));
        }
        
        // Update display mode
        if (!empty($_POST['display_mode'])) {
            update_user_meta($user_id, 'nl_display_mode', 
                sanitize_text_field($_POST['display_mode']));
        }
        
        // Update privacy settings
        $privacy_settings = isset($_POST['privacy']) ? $_POST['privacy'] : [];
        update_user_meta($user_id, 'nl_show_profile', 
            in_array('show_profile', $privacy_settings));
        update_user_meta($user_id, 'nl_show_progress', 
            in_array('show_progress', $privacy_settings));
        
        wp_send_json_success(['message' => __('Settings updated successfully', 'nexuslearn')]);
    }
}
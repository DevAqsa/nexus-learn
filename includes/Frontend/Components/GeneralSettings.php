<?php
namespace NexusLearn\Frontend\Components;

class GeneralSettings {
    public function __construct() {
        add_action('wp_ajax_nl_save_settings', [$this, 'save_settings']);
    }

    public function render_settings_section($user_id) {
        $settings = $this->get_user_settings($user_id);
        ob_start();
        ?>
        <div class="nl-settings-section">
            <div class="nl-section-header">
                <h2><?php _e('General Settings', 'nexuslearn'); ?></h2>
            </div>

            <form id="nl-settings-form" class="nl-settings-form">
                <!-- Display Preferences -->
                <div class="nl-settings-group">
                    <h3><?php _e('Display Preferences', 'nexuslearn'); ?></h3>
                    
                    <div class="nl-form-row">
                        <label for="theme_mode">
                            <?php _e('Theme Mode', 'nexuslearn'); ?>
                        </label>
                        <select name="theme_mode" id="theme_mode">
                            <option value="light" <?php selected($settings['theme_mode'], 'light'); ?>>
                                <?php _e('Light', 'nexuslearn'); ?>
                            </option>
                            <option value="dark" <?php selected($settings['theme_mode'], 'dark'); ?>>
                                <?php _e('Dark', 'nexuslearn'); ?>
                            </option>
                            <option value="system" <?php selected($settings['theme_mode'], 'system'); ?>>
                                <?php _e('System Default', 'nexuslearn'); ?>
                            </option>
                        </select>
                    </div>

                    <div class="nl-form-row">
                        <label for="font_size">
                            <?php _e('Font Size', 'nexuslearn'); ?>
                        </label>
                        <select name="font_size" id="font_size">
                            <option value="small" <?php selected($settings['font_size'], 'small'); ?>>
                                <?php _e('Small', 'nexuslearn'); ?>
                            </option>
                            <option value="medium" <?php selected($settings['font_size'], 'medium'); ?>>
                                <?php _e('Medium', 'nexuslearn'); ?>
                            </option>
                            <option value="large" <?php selected($settings['font_size'], 'large'); ?>>
                                <?php _e('Large', 'nexuslearn'); ?>
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Notification Settings -->
                <div class="nl-settings-group">
                    <h3><?php _e('Notification Settings', 'nexuslearn'); ?></h3>
                    
                    <div class="nl-form-row">
                        <label class="nl-checkbox">
                            <input type="checkbox" name="email_notifications" 
                                   <?php checked($settings['email_notifications'], 1); ?>>
                            <?php _e('Email Notifications', 'nexuslearn'); ?>
                        </label>
                    </div>

                    <div class="nl-form-row">
                        <label class="nl-checkbox">
                            <input type="checkbox" name="browser_notifications" 
                                   <?php checked($settings['browser_notifications'], 1); ?>>
                            <?php _e('Browser Notifications', 'nexuslearn'); ?>
                        </label>
                    </div>
                </div>

                <!-- Privacy Settings -->
                <div class="nl-settings-group">
                    <h3><?php _e('Privacy Settings', 'nexuslearn'); ?></h3>
                    
                    <div class="nl-form-row">
                        <label class="nl-checkbox">
                            <input type="checkbox" name="profile_visibility" 
                                   <?php checked($settings['profile_visibility'], 1); ?>>
                            <?php _e('Make profile visible to other students', 'nexuslearn'); ?>
                        </label>
                    </div>

                    <div class="nl-form-row">
                        <label class="nl-checkbox">
                            <input type="checkbox" name="show_progress" 
                                   <?php checked($settings['show_progress'], 1); ?>>
                            <?php _e('Show my progress to other students', 'nexuslearn'); ?>
                        </label>
                    </div>
                </div>

                <div class="nl-form-actions">
                    <button type="submit" class="nl-button nl-button-primary">
                        <?php _e('Save Settings', 'nexuslearn'); ?>
                    </button>
                </div>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }

    private function get_user_settings($user_id) {
        $defaults = [
            'theme_mode' => 'system',
            'font_size' => 'medium',
            'email_notifications' => 1,
            'browser_notifications' => 1,
            'profile_visibility' => 1,
            'show_progress' => 1
        ];

        $saved_settings = get_user_meta($user_id, 'nl_user_settings', true);
        return wp_parse_args($saved_settings, $defaults);
    }

    public function save_settings() {
        check_ajax_referer('nl_dashboard_nonce', 'nonce');
        
        $user_id = get_current_user_id();
        $settings = [
            'theme_mode' => sanitize_text_field($_POST['theme_mode']),
            'font_size' => sanitize_text_field($_POST['font_size']),
            'email_notifications' => isset($_POST['email_notifications']) ? 1 : 0,
            'browser_notifications' => isset($_POST['browser_notifications']) ? 1 : 0,
            'profile_visibility' => isset($_POST['profile_visibility']) ? 1 : 0,
            'show_progress' => isset($_POST['show_progress']) ? 1 : 0
        ];
        
        $result = update_user_meta($user_id, 'nl_user_settings', $settings);
        
        if ($result) {
            wp_send_json_success(['message' => __('Settings saved successfully', 'nexuslearn')]);
        } else {
            wp_send_json_error(['message' => __('Failed to save settings', 'nexuslearn')]);
        }
    }
}
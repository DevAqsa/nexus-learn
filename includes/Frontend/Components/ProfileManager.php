<?php
namespace NexusLearn\Frontend\Components;

class ProfileManager {
    public function __construct() {
        add_action('wp_ajax_nl_update_profile', [$this, 'update_profile']);
    }

    public function render_profile_section($user_id) {
        $user_data = get_userdata($user_id);
        $profile_meta = get_user_meta($user_id);
        ob_start();
        ?>
        <div class="nl-profile-section">
            <h2><?php _e('Profile Management', 'nexuslearn'); ?></h2>
            <form id="nl-profile-form" class="nl-profile-form">
                <div class="nl-form-group">
                    <label><?php _e('Full Name', 'nexuslearn'); ?></label>
                    <input type="text" name="full_name" 
                           value="<?php echo esc_attr($profile_meta['full_name'][0] ?? ''); ?>">
                </div>
                <div class="nl-form-group">
                    <label><?php _e('Bio', 'nexuslearn'); ?></label>
                    <textarea name="bio"><?php echo esc_textarea($profile_meta['bio'][0] ?? ''); ?></textarea>
                </div>
                <div class="nl-form-group">
                    <label><?php _e('Preferred Language', 'nexuslearn'); ?></label>
                    <select name="language">
                        <option value="en" <?php selected($profile_meta['language'][0] ?? '', 'en'); ?>>English</option>
                        <option value="es" <?php selected($profile_meta['language'][0] ?? '', 'es'); ?>>Spanish</option>
                        <option value="fr" <?php selected($profile_meta['language'][0] ?? '', 'fr'); ?>>French</option>
                    </select>
                </div>
                <div class="nl-form-group">
                    <label><?php _e('Notification Preferences', 'nexuslearn'); ?></label>
                    <div class="nl-checkbox-group">
                        <label>
                            <input type="checkbox" name="notifications[]" value="email"
                                <?php checked(in_array('email', $profile_meta['notifications'] ?? [])); ?>>
                            <?php _e('Email Notifications', 'nexuslearn'); ?>
                        </label>
                        <label>
                            <input type="checkbox" name="notifications[]" value="site"
                                <?php checked(in_array('site', $profile_meta['notifications'] ?? [])); ?>>
                            <?php _e('Site Notifications', 'nexuslearn'); ?>
                        </label>
                    </div>
                </div>
                <button type="submit" class="nl-submit-btn">
                    <?php _e('Save Changes', 'nexuslearn'); ?>
                </button>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }

    public function update_profile() {
        check_ajax_referer('nl_profile_update', 'nonce');
        
        $user_id = get_current_user_id();
        $fields = ['full_name', 'bio', 'language', 'notifications'];
        
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_user_meta($user_id, $field, sanitize_text_field($_POST[$field]));
            }
        }
        
        wp_send_json_success(['message' => 'Profile updated successfully']);
    }
}
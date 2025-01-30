<?php

namespace NexusLearn\Admin;

class CourseManager {
    public function __construct() {
        add_action('add_meta_boxes', [$this, 'add_course_meta_boxes']);
        add_action('save_post_nl_course', [$this, 'save_course_meta']);
    }

    public function add_course_meta_boxes() {
        add_meta_box(
            'nl_course_details',
            __('Course Details', 'nexuslearn'),
            [$this, 'render_course_meta_box'],
            'nl_course'
        );
    }   

    public function render_course_meta_box($post) {
        $duration = get_post_meta($post->ID, '_nl_course_duration', true);
        $level = get_post_meta($post->ID, '_nl_course_level', true);
        wp_nonce_field('nl_course_meta', 'nl_course_meta_nonce');
        ?>
        <p>
            <label><?php _e('Duration (hours):', 'nexuslearn'); ?></label>
            <input type="number" name="nl_course_duration" value="<?php echo esc_attr($duration); ?>">
        </p>
        <p>
            <label><?php _e('Level:', 'nexuslearn'); ?></label>
            <select name="nl_course_level">
                <option value="beginner" <?php selected($level, 'beginner'); ?>><?php _e('Beginner', 'nexuslearn'); ?></option>
                <option value="intermediate" <?php selected($level, 'intermediate'); ?>><?php _e('Intermediate', 'nexuslearn'); ?></option>
                <option value="advanced" <?php selected($level, 'advanced'); ?>><?php _e('Advanced', 'nexuslearn'); ?></option>
            </select>
        </p>
        <?php
    }

    public function save_course_meta($post_id) {
        $security = \NexusLearn\Core\SecurityHandler::getInstance();
    
        if (!isset($_POST['nl_course_meta_nonce']) || 
            !$security->verify_nonce($_POST['nl_course_meta_nonce'], 'nl_course_meta')) {
            return;
        }
    
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
    
        if (!$security->check_capabilities('edit_post', $post_id)) {
            return;
        }
    
        if (isset($_POST['nl_course_duration'])) {
            update_post_meta($post_id, '_nl_course_duration', 
                $security->sanitize_text($_POST['nl_course_duration']));
        }
    
        if (isset($_POST['nl_course_level'])) {
            update_post_meta($post_id, '_nl_course_level', 
                $security->sanitize_text($_POST['nl_course_level']));
        }
    }
}

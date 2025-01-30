<?php
namespace NexusLearn\Admin;

class CourseTemplateHandler {
    private $template;

    public function __construct() {
        $this->template = new Views\CourseTemplate();
        add_action('admin_menu', [$this, 'add_custom_add_course_page']);
        add_action('admin_post_nl_add_course', [$this, 'handle_course_submission']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    public function add_custom_add_course_page() {
        add_submenu_page(
            null,
            __('Add New Course', 'nexuslearn'),
            __('Add New Course', 'nexuslearn'),
            'edit_posts',
            'nl-add-course',
            [$this->template, 'render']
        );

        // Override the default "Add New" link
        global $submenu;
        if (isset($submenu['edit.php?post_type=nl_course'])) {
            foreach ($submenu['edit.php?post_type=nl_course'] as $key => $item) {
                if ($item[2] === 'post-new.php?post_type=nl_course') {
                    $submenu['edit.php?post_type=nl_course'][$key][2] = 'admin.php?page=nl-add-course';
                    break;
                }
            }
        }
    }

    public function enqueue_scripts($hook) {
        if ($hook !== 'admin_page_nl-add-course') {
            return;
        }

        wp_enqueue_media();
        wp_enqueue_style('dashicons');
        
        wp_enqueue_script(
            'nl-course-template',
            NEXUSLEARN_PLUGIN_URL . 'assets/js/course-template.js',
            ['jquery'],
            NEXUSLEARN_VERSION,
            true
        );

        wp_enqueue_style(
            'nl-course-template',
            NEXUSLEARN_PLUGIN_URL . 'assets/css/course-template.css',
            [],
            NEXUSLEARN_VERSION
        );
    }

    public function handle_course_submission() {
        if (!isset($_POST['nl_course_nonce']) || 
            !wp_verify_nonce($_POST['nl_course_nonce'], 'nl_add_course')) {
            wp_die(__('Security check failed', 'nexuslearn'));
        }

        if (!current_user_can('edit_posts')) {
            wp_die(__('You do not have sufficient permissions', 'nexuslearn'));
        }

        // Basic course data
        $course_data = [
            'post_title'   => sanitize_text_field($_POST['course_title']),
            'post_content' => wp_kses_post($_POST['course_description']),
            'post_status'  => 'draft',
            'post_type'    => 'nl_course'
        ];

        $course_id = wp_insert_post($course_data);

        if (!is_wp_error($course_id)) {
            // Save all course metadata
            $this->save_basic_info($course_id);
            $this->save_course_details($course_id);
            $this->save_curriculum($course_id);
            $this->save_pricing_details($course_id);
            $this->save_taxonomies($course_id);

            // Redirect to course editor
            wp_redirect(admin_url('post.php?post=' . $course_id . '&action=edit'));
            exit;
        }

        wp_redirect(admin_url('edit.php?post_type=nl_course&error=1'));
        exit;
    }

    private function save_basic_info($course_id) {
        // Subtitle
        if (!empty($_POST['course_subtitle'])) {
            update_post_meta($course_id, '_nl_course_subtitle', 
                sanitize_text_field($_POST['course_subtitle']));
        }

        // Featured Image
        if (!empty($_POST['course_featured_image'])) {
            set_post_thumbnail($course_id, intval($_POST['course_featured_image']));
        }
    }

    private function save_course_details($course_id) {
        // Course Level
        if (!empty($_POST['course_level'])) {
            update_post_meta($course_id, '_nl_course_level', 
                sanitize_text_field($_POST['course_level']));
        }

        // Duration
        $duration_hours = isset($_POST['course_duration_hours']) ? intval($_POST['course_duration_hours']) : 0;
        $duration_minutes = isset($_POST['course_duration_minutes']) ? intval($_POST['course_duration_minutes']) : 0;
        $total_duration = ($duration_hours * 60) + $duration_minutes;
        update_post_meta($course_id, '_nl_course_duration', $total_duration);

        // Language
        if (!empty($_POST['course_language'])) {
            update_post_meta($course_id, '_nl_course_language', 
                sanitize_text_field($_POST['course_language']));
        }

        // Student Capacity
        if (!empty($_POST['course_capacity'])) {
            update_post_meta($course_id, '_nl_course_capacity', 
                intval($_POST['course_capacity']));
        }

        // Prerequisites
        if (!empty($_POST['course_prerequisites'])) {
            update_post_meta($course_id, '_nl_course_prerequisites', 
                sanitize_textarea_field($_POST['course_prerequisites']));
        }

        // Learning Outcomes
        if (!empty($_POST['course_outcomes'])) {
            $outcomes = array_map('sanitize_text_field', $_POST['course_outcomes']);
            $outcomes = array_filter($outcomes); // Remove empty values
            update_post_meta($course_id, '_nl_course_outcomes', $outcomes);
        }
    }

    private function save_curriculum($course_id) {
        if (empty($_POST['sections'])) {
            return;
        }

        $curriculum = [];
        foreach ($_POST['sections'] as $section) {
            if (empty($section['title'])) {
                continue;
            }

            $section_data = [
                'title' => sanitize_text_field($section['title']),
                'lessons' => []
            ];

            if (!empty($section['lessons'])) {
                foreach ($section['lessons'] as $lesson) {
                    if (!empty($lesson)) {
                        $section_data['lessons'][] = sanitize_text_field($lesson);
                    }
                }
            }

            $curriculum[] = $section_data;
        }

        update_post_meta($course_id, '_nl_course_curriculum', $curriculum);
    }

    private function save_pricing_details($course_id) {
        // Pricing Type
        if (!empty($_POST['course_pricing_type'])) {
            update_post_meta($course_id, '_nl_course_pricing_type', 
                sanitize_text_field($_POST['course_pricing_type']));
        }

        // Regular Price
        if (!empty($_POST['course_price'])) {
            update_post_meta($course_id, '_nl_course_price', 
                floatval($_POST['course_price']));
        }

        // Sale Price
        if (!empty($_POST['course_sale_price'])) {
            update_post_meta($course_id, '_nl_course_sale_price', 
                floatval($_POST['course_sale_price']));
        }

        // Subscription Interval
        if (!empty($_POST['course_subscription_interval'])) {
            update_post_meta($course_id, '_nl_course_subscription_interval', 
                sanitize_text_field($_POST['course_subscription_interval']));
        }
    }

    private function save_taxonomies($course_id) {
        // Categories
        if (!empty($_POST['course_categories'])) {
            $categories = array_map('intval', $_POST['course_categories']);
            wp_set_object_terms($course_id, $categories, 'course_category');
        }

        // Tags
        if (!empty($_POST['course_tags'])) {
            $tags = explode(',', $_POST['course_tags']);
            $tags = array_map('trim', $tags);
            $tags = array_map('sanitize_text_field', $tags);
            wp_set_object_terms($course_id, $tags, 'course_tag');
        }
    }
}
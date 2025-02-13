<?php
namespace NexusLearn\Frontend\Components;

class ContentViewer {
    public function render_course_content($course_id) {
        if (!is_user_logged_in()) {
            return;
        }

        $user_id = get_current_user_id();
        $enrolled_courses = get_user_meta($user_id, 'nl_enrolled_courses', true) ?: [];

        // Check if user is enrolled
        if (!in_array($course_id, $enrolled_courses)) {
            wp_redirect(add_query_arg('view', 'courses', get_permalink()));
            exit;
        }

        // Include the course content view template
        include NEXUSLEARN_PLUGIN_DIR . 'templates/frontend/dashboard/course-content-view.php';
    }
}
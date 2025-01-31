<?php
// Helper functions for the dashboard

function get_user_course_progress($user_id, $course_id) {
    $progress = get_user_meta($user_id, "nl_course_{$course_id}_progress", true);
    return !empty($progress) ? (int)$progress : 0;
}

function get_user_completed_lessons($user_id, $course_id) {
    $completed = get_user_meta($user_id, "nl_course_{$course_id}_completed_lessons", true);
    return !empty($completed) ? count($completed) : 0;
}

function get_course_lesson_count($course_id) {
    $lessons = get_posts([
        'post_type' => 'nl_lesson',
        'post_parent' => $course_id,
        'posts_per_page' => -1,
        'fields' => 'ids'
    ]);
    return count($lessons);
}

function get_user_last_accessed($user_id, $course_id) {
    return get_user_meta($user_id, "nl_course_{$course_id}_last_accessed", true);
}

function get_course_resume_link($course_id) {
    $resume_lesson = get_post_meta($course_id, '_last_accessed_lesson', true);
    if (!$resume_lesson) {
        // Get first lesson if no last accessed lesson
        $lessons = get_posts([
            'post_type' => 'nl_lesson',
            'post_parent' => $course_id,
            'posts_per_page' => 1,
            'orderby' => 'menu_order',
            'order' => 'ASC'
        ]);
        $resume_lesson = !empty($lessons) ? $lessons[0]->ID : 0;
    }
    return get_permalink($resume_lesson);
}

function get_user_courses_progress($user_id) {
    $enrolled_courses = get_user_meta($user_id, 'nl_enrolled_courses', true) ?: [];
    $progress_data = [];
    
    foreach ($enrolled_courses as $course_id) {
        $progress_data[$course_id] = [
            'percentage' => get_user_course_progress($user_id, $course_id),
            'completed_lessons' => get_user_completed_lessons($user_id, $course_id),
            'total_lessons' => get_course_lesson_count($course_id),
            'time_spent' => get_user_course_time_spent($user_id, $course_id),
            'last_activity' => get_user_last_accessed($user_id, $course_id),
            'recent_activities' => get_user_course_activities($user_id, $course_id)
        ];
    }
    
    return $progress_data;
}

function get_user_course_time_spent($user_id, $course_id) {
    return (int)get_user_meta($user_id, "nl_course_{$course_id}_time_spent", true);
}

function get_user_course_activities($user_id, $course_id, $limit = 5) {
    global $wpdb;
    $table = $wpdb->prefix . 'nl_activity_log';
    
    return $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM {$table} 
        WHERE user_id = %d AND course_id = %d 
        ORDER BY timestamp DESC 
        LIMIT %d",
        $user_id,
        $course_id,
        $limit
    ), ARRAY_A);
}
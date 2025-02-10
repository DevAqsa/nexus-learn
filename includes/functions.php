<?php
/**
 * Helper functions for the NexusLearn LMS
 */

if (!defined('ABSPATH')) {
    exit;
}

/************************
 * Progress Functions
 ************************/

/**
 * Get user's course progress data
 */
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

/**
 * Get user's course progress percentage
 */
function get_user_course_progress($user_id, $course_id) {
    $progress = get_user_meta($user_id, "nl_course_{$course_id}_progress", true);
    return !empty($progress) ? (int)$progress : 0;
}

/**
 * Update user course progress
 */
function update_user_course_progress($user_id, $course_id, $progress) {
    $progress = min(100, max(0, (int)$progress));
    
    update_user_meta($user_id, "nl_course_{$course_id}_progress", $progress);
    update_user_meta($user_id, "nl_course_{$course_id}_last_updated", current_time('mysql'));
    
    if ($progress === 100) {
        log_user_activity(
            $user_id,
            $course_id,
            'course_completed',
            sprintf(__('Completed the course: %s', 'nexuslearn'), get_the_title($course_id))
        );
    }
    
    return $progress;
}

/**
 * Get course completion status
 */
function get_user_course_completion_status($user_id, $course_id) {
    $progress = get_user_course_progress($user_id, $course_id);
    if ($progress >= 100) {
        return 'completed';
    } elseif ($progress > 0) {
        return 'in-progress';
    }
    return 'not-started';
}

/**
 * Get average progress across all courses
 */
function get_average_progress($progress_data) {
    if (empty($progress_data)) {
        return 0;
    }
    $total = array_sum(array_column($progress_data, 'percentage'));
    return round($total / count($progress_data));
}

/**
 * Count completed courses
 */
function count_completed_courses($progress_data) {
    return count(array_filter($progress_data, function($progress) {
        return $progress['percentage'] >= 100;
    }));
}

/************************
 * Time Tracking Functions
 ************************/

/**
 * Get user's time spent on a course
 */
function get_user_course_time_spent($user_id, $course_id) {
    return (int)get_user_meta($user_id, "nl_course_{$course_id}_time_spent", true);
}

/**
 * Format time duration
 */
function format_time_spent($minutes) {
    if ($minutes < 60) {
        return sprintf(_n('%d minute', '%d minutes', $minutes, 'nexuslearn'), $minutes);
    }
    
    $hours = floor($minutes / 60);
    $remaining_minutes = $minutes % 60;
    
    if ($remaining_minutes === 0) {
        return sprintf(_n('%d hour', '%d hours', $hours, 'nexuslearn'), $hours);
    }
    
    return sprintf(
        __('%d hours %d minutes', 'nexuslearn'),
        $hours,
        $remaining_minutes
    );
}

/**
 * Get total learning hours
 */
function get_total_learning_hours($user_id) {
    $total_minutes = get_user_meta($user_id, 'nl_total_learning_minutes', true);
    return !empty($total_minutes) ? round($total_minutes / 60) : 0;
}

/************************
 * Lesson & Course Functions
 ************************/

/**
 * Get user's completed lessons for a course
 */
function get_user_completed_lessons($user_id, $course_id) {
    $completed = get_user_meta($user_id, "nl_course_{$course_id}_completed_lessons", true);
    return !empty($completed) ? count($completed) : 0;
}

/**
 * Get total lessons in a course
 */
function get_course_lesson_count($course_id) {
    $lessons = get_posts([
        'post_type' => 'nl_lesson',
        'post_parent' => $course_id,
        'posts_per_page' => -1,
        'fields' => 'ids'
    ]);
    return count($lessons);
}

/**
 * Get user's last accessed time for a course
 */
function get_user_last_accessed($user_id, $course_id) {
    return get_user_meta($user_id, "nl_course_{$course_id}_last_accessed", true);
}

/**
 * Get course resume link
 */
function get_course_resume_link($course_id) {
    $last_lesson_id = get_user_meta(get_current_user_id(), "nl_course_{$course_id}_last_lesson", true);
    
    if (!$last_lesson_id) {
        // Get first lesson if no last accessed lesson
        $lessons = get_posts([
            'post_type' => 'nl_lesson',
            'post_parent' => $course_id,
            'posts_per_page' => 1,
            'orderby' => 'menu_order',
            'order' => 'ASC'
        ]);
        
        $last_lesson_id = !empty($lessons) ? $lessons[0]->ID : 0;
    }
    
    return $last_lesson_id ? get_permalink($last_lesson_id) : get_permalink($course_id);
}

/************************
 * Activity & Enrollment Functions
 ************************/

/**
 * Get user's recent activities
 */
function get_user_recent_activities($user_id, $limit = 5) {
    global $wpdb;
    $table = $wpdb->prefix . 'nl_activity_log';
    
    $activities = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM {$table} 
        WHERE user_id = %d 
        ORDER BY timestamp DESC 
        LIMIT %d",
        $user_id,
        $limit
    ), ARRAY_A);

    return $activities ?: [];
}

/**
 * Get user's course activities
 */
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
    ), ARRAY_A) ?: [];
}

/**
 * Log user activity
 */
function log_user_activity($user_id, $course_id, $activity_type, $description = '') {
    global $wpdb;
    
    return $wpdb->insert(
        $wpdb->prefix . 'nl_activity_log',
        [
            'user_id' => $user_id,
            'course_id' => $course_id,
            'activity_type' => $activity_type,
            'description' => $description,
            'timestamp' => current_time('mysql')
        ],
        ['%d', '%d', '%s', '%s', '%s']
    );
}

/**
 * Get activity icon
 */
function get_activity_icon($activity_type) {
    $icons = [
        'lesson_completed' => 'dashicons-yes-alt',
        'quiz_completed' => 'dashicons-awards',
        'course_completed' => 'dashicons-flag',
        'course_started' => 'dashicons-welcome-learn-more',
        'assignment_submitted' => 'dashicons-clipboard',
        'discussion_posted' => 'dashicons-format-chat'
    ];
    
    return isset($icons[$activity_type]) 
        ? $icons[$activity_type] 
        : 'dashicons-marker';
}

/**
 * Check if user has access to course
 */
function user_has_course_access($user_id, $course_id) {
    $enrolled_courses = get_user_meta($user_id, 'nl_enrolled_courses', true) ?: [];
    return in_array($course_id, $enrolled_courses);
}

/**
 * Enroll user in course
 */
function enroll_user_in_course($user_id, $course_id) {
    if (user_has_course_access($user_id, $course_id)) {
        return false;
    }
    
    $enrolled_courses = get_user_meta($user_id, 'nl_enrolled_courses', true) ?: [];
    $enrolled_courses[] = $course_id;
    
    update_user_meta($user_id, 'nl_enrolled_courses', array_unique($enrolled_courses));
    
    log_user_activity(
        $user_id,
        $course_id,
        'course_started',
        sprintf(__('Started the course: %s', 'nexuslearn'), get_the_title($course_id))
    );
    
    return true;
}
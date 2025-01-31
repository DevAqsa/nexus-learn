<?php
$user_id = get_current_user_id();
$enrolled_courses = get_user_meta($user_id, 'nl_enrolled_courses', true) ?: [];
$total_courses = count($enrolled_courses);
$completed_courses = count(array_filter($enrolled_courses, function($course_id) use ($user_id) {
    return get_user_course_progress($user_id, $course_id) >= 100;
}));
$avg_progress = $total_courses ? array_sum(array_map(function($course_id) use ($user_id) {
    return get_user_course_progress($user_id, $course_id);
}, $enrolled_courses)) / $total_courses : 0;
?>

<!-- Stats Overview -->
<div class="nl-stats-grid">
    <div class="nl-stat-card">
        <span class="nl-stat-icon book">📚</span>
        <div class="nl-stat-value"><?php echo esc_html($total_courses); ?></div>
        <div class="nl-stat-label"><?php _e('Enrolled Courses', 'nexuslearn'); ?></div>
    </div>
    <div class="nl-stat-card">
        <span class="nl-stat-icon check">✓</span>
        <div class="nl-stat-value"><?php echo esc_html($completed_courses); ?></div>
        <div class="nl-stat-label"><?php _e('Completed Courses', 'nexuslearn'); ?></div>
    </div>
    <div class="nl-stat-card">
        <span class="nl-stat-icon chart">📈</span>
        <div class="nl-stat-value"><?php echo esc_html(round($avg_progress)); ?>%</div>
        <div class="nl-stat-label"><?php _e('Average Progress', 'nexuslearn'); ?></div>
    </div>
</div>

<!-- Recent Activities -->
<div class="nl-content-section">
    <h2><?php _e('Recent Activities', 'nexuslearn'); ?></h2>
    <?php
    $recent_activities = get_user_recent_activities($user_id);
    if (!empty($recent_activities)): ?>
        <div class="nl-activities-list">
            <?php foreach ($recent_activities as $activity): ?>
                <div class="nl-activity-item">
                    <div class="nl-activity-icon">
                        <?php echo get_activity_icon($activity['type']); ?>
                    </div>
                    <div class="nl-activity-content">
                        <div class="nl-activity-description">
                            <?php echo esc_html($activity['description']); ?>
                        </div>
                        <div class="nl-activity-meta">
                            <span class="nl-activity-course">
                                <?php echo esc_html(get_the_title($activity['course_id'])); ?>
                            </span>
                            <span class="nl-activity-time">
                                <?php echo human_time_diff($activity['timestamp'], current_time('timestamp')); ?>
                                <?php _e('ago', 'nexuslearn'); ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="nl-empty-state">
            <div class="nl-empty-state-icon">📅</div>
            <h3><?php _e('No Recent Activities', 'nexuslearn'); ?></h3>
            <p><?php _e('Start learning to see your activities here', 'nexuslearn'); ?></p>
        </div>
    <?php endif; ?>
</div>

<!-- Continue Learning -->
<div class="nl-content-section">
    <h2><?php _e('Continue Learning', 'nexuslearn'); ?></h2>
    <?php
    $in_progress_courses = array_filter($enrolled_courses, function($course_id) use ($user_id) {
        $progress = get_user_course_progress($user_id, $course_id);
        return $progress > 0 && $progress < 100;
    });

    if (!empty($in_progress_courses)):
        $latest_course = array_key_first($in_progress_courses);
        $course_progress = get_user_course_progress($user_id, $latest_course);
        ?>
        <div class="nl-continue-learning-card">
            <h3><?php echo get_the_title($latest_course); ?></h3>
            <div class="nl-progress-bar">
                <div class="nl-progress-fill" style="width: <?php echo esc_attr($course_progress); ?>%"></div>
            </div>
            <div class="nl-progress-details">
                <span class="nl-progress-text">
                    <?php echo esc_html($course_progress); ?>% <?php _e('Complete', 'nexuslearn'); ?>
                </span>
                <a href="<?php echo get_course_resume_link($latest_course); ?>" 
                   class="nl-button nl-button-primary">
                    <?php _e('Continue', 'nexuslearn'); ?>
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="nl-empty-state">
            <div class="nl-empty-state-icon">🎯</div>
            <h3><?php _e('No Courses In Progress', 'nexuslearn'); ?></h3>
            <p><?php _e('Browse our courses and start learning', 'nexuslearn'); ?></p>
            <a href="<?php echo get_post_type_archive_link('nl_course'); ?>" 
               class="nl-button nl-button-primary">
                <?php _e('Browse Courses', 'nexuslearn'); ?>
            </a>
        </div>
    <?php endif; ?>
</div>

<?php
// Helper function for overview page
function get_user_recent_activities($user_id, $limit = 5) {
    global $wpdb;
    $table = $wpdb->prefix . 'nl_activity_log';
    
    return $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM {$table} 
        WHERE user_id = %d 
        ORDER BY timestamp DESC 
        LIMIT %d",
        $user_id,
        $limit
    ), ARRAY_A);
}

function get_activity_icon($type) {
    $icons = [
        'lesson_completed' => '📖',
        'quiz_completed' => '✍️',
        'course_completed' => '🎓',
        'assignment_submitted' => '📝',
        'discussion_posted' => '💬',
    ];
    
    return isset($icons[$type]) ? $icons[$type] : '📌';
}
?>
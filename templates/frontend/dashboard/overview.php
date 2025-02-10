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
        <div class="nl-stat-icon courses">
            <i class="dashicons dashicons-welcome-learn-more"></i>
        </div>
        <div class="nl-stat-value"><?php echo esc_html($total_courses); ?></div>
        <div class="nl-stat-label"><?php _e('Enrolled Courses', 'nexuslearn'); ?></div>
    </div>

    <div class="nl-stat-card">
        <div class="nl-stat-icon completed">
            <i class="dashicons dashicons-yes-alt"></i>
        </div>
        <div class="nl-stat-value"><?php echo esc_html($completed_courses); ?></div>
        <div class="nl-stat-label"><?php _e('Completed Courses', 'nexuslearn'); ?></div>
    </div>

    <div class="nl-stat-card">
        <div class="nl-stat-icon progress">
            <i class="dashicons dashicons-chart-bar"></i>
        </div>
        <div class="nl-stat-value"><?php echo esc_html(round($avg_progress)); ?>%</div>
        <div class="nl-stat-label"><?php _e('Average Progress', 'nexuslearn'); ?></div>
    </div>
</div>

<!-- Recent Courses -->
<div class="nl-section">
    <h2 class="nl-section-title"><?php _e('Recent Courses', 'nexuslearn'); ?></h2>
    <?php
    $recent_courses = get_posts([
        'post_type' => 'nl_course',
        'posts_per_page' => 3,
        'post__in' => $enrolled_courses,
        'orderby' => 'date',
        'order' => 'DESC'
    ]);

    if ($recent_courses): ?>
        <div class="nl-courses-grid">
            <?php foreach ($recent_courses as $course): 
                $progress = get_user_course_progress($user_id, $course->ID);
                ?>
                <div class="nl-course-card">
                    <h3><?php echo esc_html($course->post_title); ?></h3>
                    <div class="nl-progress-bar">
                        <div class="nl-progress-fill" style="width: <?php echo esc_attr($progress); ?>%"></div>
                    </div>
                    <div class="nl-course-meta">
                        <span><?php echo esc_html($progress); ?>% <?php _e('Complete', 'nexuslearn'); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="nl-empty-state">
            <?php _e('No courses found', 'nexuslearn'); ?>
        </div>
    <?php endif; ?>
</div>

<!-- Recent Activities -->
<div class="nl-section">
    <h2 class="nl-section-title"><?php _e('Recent Activities', 'nexuslearn'); ?></h2>
    <?php
    $recent_activities = get_user_recent_activities($user_id);
    if (!empty($recent_activities)): ?>
        <div class="nl-activities-list">
            <?php foreach ($recent_activities as $activity): ?>
                <div class="nl-activity-item">
                    <span class="nl-activity-text"><?php echo esc_html($activity['description']); ?></span>
                    <span class="nl-activity-time">
                        <?php echo human_time_diff($activity['timestamp'], current_time('timestamp')); ?> <?php _e('ago', 'nexuslearn'); ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="nl-empty-state">
            <?php _e('No recent activities', 'nexuslearn'); ?>
        </div>
    <?php endif; ?>
</div>
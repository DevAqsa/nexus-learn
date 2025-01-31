<?php

$user_id = get_current_user_id();
$enrolled_courses = get_user_meta($user_id, 'nl_enrolled_courses', true) ?: [];
$progress_data = get_user_courses_progress($user_id);
?>

<div class="nl-progress-section nl-content-section">
    <div class="nl-section-header">
        <h2><?php _e('Learning Progress', 'nexuslearn'); ?></h2>
        <div class="nl-section-actions">
            <button class="nl-button nl-button-secondary" id="nl-export-progress">
                <?php endif; ?>
</div>

<?php
// Helper functions for progress display
function get_average_progress($progress_data) {
    if (empty($progress_data)) {
        return 0;
    }
    $total = array_sum(array_column($progress_data, 'percentage'));
    return round($total / count($progress_data));
}

function count_completed_courses($progress_data) {
    return count(array_filter($progress_data, function($progress) {
        return $progress['percentage'] >= 100;
    }));
}

function get_total_learning_hours($user_id) {
    $total_minutes = (int) get_user_meta($user_id, 'nl_total_learning_minutes', true);
    return round($total_minutes / 60);
}

function format_time_spent($minutes) {
    $hours = floor($minutes / 60);
    $remaining_minutes = $minutes % 60;
    
    if ($hours > 0) {
        return sprintf(
            __('%d hours %d minutes', 'nexuslearn'),
            $hours,
            $remaining_minutes
        );
    }
    return sprintf(__('%d minutes', 'nexuslearn'), $minutes);
}
?>php _e('Export Report', 'nexuslearn'); ?>
            </button>
        </div>
    </div>

    <?php if (!empty($progress_data)): ?>
        <!-- Overall Progress Summary -->
        <div class="nl-progress-summary">
            <div class="nl-progress-stat">
                <div class="nl-stat-label"><?php _e('Average Progress', 'nexuslearn'); ?></div>
                <div class="nl-stat-value"><?php echo get_average_progress($progress_data); ?>%</div>
            </div>
            <div class="nl-progress-stat">
                <div class="nl-stat-label"><?php _e('Completed Courses', 'nexuslearn'); ?></div>
                <div class="nl-stat-value"><?php echo count_completed_courses($progress_data); ?></div>
            </div>
            <div class="nl-progress-stat">
                <div class="nl-stat-label"><?php _e('Total Learning Hours', 'nexuslearn'); ?></div>
                <div class="nl-stat-value"><?php echo get_total_learning_hours($user_id); ?></div>
            </div>
        </div>

        <!-- Detailed Progress List -->
        <div class="nl-progress-list">
            <?php foreach ($progress_data as $course_id => $progress): ?>
                <div class="nl-progress-item" data-course-id="<?php echo esc_attr($course_id); ?>">
                    <div class="nl-progress-header">
                        <h3><?php echo get_the_title($course_id); ?></h3>
                        <div class="nl-progress-percentage">
                            <?php echo esc_html($progress['percentage']); ?>%
                        </div>
                    </div>

                    <div class="nl-progress-details">
                        <div class="nl-progress-bar">
                            <div class="nl-progress-fill" 
                                 style="width: <?php echo esc_attr($progress['percentage']); ?>%">
                            </div>
                        </div>
                        
                        <div class="nl-progress-stats">
                            <div class="nl-stat">
                                <span class="nl-stat-label"><?php _e('Lessons Completed', 'nexuslearn'); ?></span>
                                <span class="nl-stat-value">
                                    <?php echo esc_html($progress['completed_lessons']); ?>/<?php echo esc_html($progress['total_lessons']); ?>
                                </span>
                            </div>
                            <div class="nl-stat">
                                <span class="nl-stat-label"><?php _e('Time Spent', 'nexuslearn'); ?></span>
                                <span class="nl-stat-value">
                                    <?php echo format_time_spent($progress['time_spent']); ?>
                                </span>
                            </div>
                            <div class="nl-stat">
                                <span class="nl-stat-label"><?php _e('Last Activity', 'nexuslearn'); ?></span>
                                <span class="nl-stat-value">
                                    <?php echo human_time_diff($progress['last_activity'], current_time('timestamp')); ?> ago
                                </span>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($progress['recent_activities'])): ?>
                        <div class="nl-recent-activities">
                            <h4><?php _e('Recent Activities', 'nexuslearn'); ?></h4>
                            <ul>
                                <?php foreach ($progress['recent_activities'] as $activity): ?>
                                    <li>
                                        <span class="nl-activity-type"><?php echo esc_html($activity['type']); ?></span>
                                        <span class="nl-activity-description"><?php echo esc_html($activity['description']); ?></span>
                                        <span class="nl-activity-date"><?php echo human_time_diff($activity['timestamp'], current_time('timestamp')); ?> ago</span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="nl-empty-state">
            <div class="nl-empty-state-icon">📊</div>
            <h3><?php _e('No Progress Data', 'nexuslearn'); ?></h3>
            <p><?php _e('Start a course to track your progress', 'nexuslearn'); ?></p>
            <a href="<?php echo esc_url(get_post_type_archive_link('nl_course')); ?>" 
               class="nl-button nl-button-primary">
                <?php _e('Browse Courses', 'nexuslearn'); ?>
            </a>
        </div>
    <?
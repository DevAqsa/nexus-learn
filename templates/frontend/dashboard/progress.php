<?php
$user_id = get_current_user_id();
$enrolled_courses = get_user_meta($user_id, 'nl_enrolled_courses', true) ?: [];
$progress_data = get_user_courses_progress($user_id);
?>

<div class="nl-progress-section nl-content-section">
    <!-- Overall Progress Summary -->
    <div class="nl-progress-summary">
        <div class="nl-stats-grid">
            <div class="nl-stat-card">
                <div class="nl-stat-icon progress">
                    <i class="dashicons dashicons-chart-bar"></i>
                </div>
                <div class="nl-stat-value">
                    <?php echo esc_html(get_average_progress($progress_data)); ?>%
                </div>
                <div class="nl-stat-label"><?php _e('Average Progress', 'nexuslearn'); ?></div>
            </div>

            <div class="nl-stat-card">
                <div class="nl-stat-icon completed">
                    <i class="dashicons dashicons-yes-alt"></i>
                </div>
                <div class="nl-stat-value">
                    <?php echo esc_html(count_completed_courses($progress_data)); ?>
                </div>
                <div class="nl-stat-label"><?php _e('Completed Courses', 'nexuslearn'); ?></div>
            </div>

            <div class="nl-stat-card">
                <div class="nl-stat-icon time">
                    <i class="dashicons dashicons-clock"></i>
                </div>
                <div class="nl-stat-value">
                    <?php 
                    $hours = get_total_learning_hours($user_id);
                    echo esc_html($hours);
                    ?>
                </div>
                <div class="nl-stat-label">
                    <?php echo _n('Learning Hour', 'Learning Hours', $hours, 'nexuslearn'); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Progress List -->
    <?php if (!empty($progress_data)): ?>
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
                            <?php if (!empty($progress['last_activity'])): ?>
                                <div class="nl-stat">
                                    <span class="nl-stat-label"><?php _e('Last Activity', 'nexuslearn'); ?></span>
                                    <span class="nl-stat-value">
                                        <?php echo human_time_diff($progress['last_activity'], current_time('timestamp')); ?> <?php _e('ago', 'nexuslearn'); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="nl-empty-state">
            <div class="nl-empty-icon">
                <i class="dashicons dashicons-chart-bar"></i>
            </div>
            <h3><?php _e('No Progress Data', 'nexuslearn'); ?></h3>
            <p><?php _e('Start learning to see your progress here', 'nexuslearn'); ?></p>
            <a href="<?php echo esc_url(get_post_type_archive_link('nl_course')); ?>" 
               class="nl-button nl-button-primary">
                <?php _e('Browse Courses', 'nexuslearn'); ?>
            </a>
        </div>
    <?php endif; ?>
</div>
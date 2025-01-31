<?php
$user_id = get_current_user_id();
$enrolled_courses = count_user_posts($user_id, 'nl_course');
$completed_courses = get_user_meta($user_id, 'nl_completed_courses', true) ?: 0;
$average_progress = get_user_meta($user_id, 'nl_average_progress', true) ?: 0;
?>

<div class="nl-dashboard">
    <!-- Navigation Menu -->
    <nav class="nl-dashboard-nav">
        <a href="?view=dashboard" class="nl-nav-link active">
            <span class="dashicons dashicons-dashboard"></span> 
            <?php _e('Dashboard', 'nexuslearn'); ?>
        </a>
        <a href="?view=courses" class="nl-nav-link">
            <span class="dashicons dashicons-welcome-learn-more"></span> 
            <?php _e('My Courses', 'nexuslearn'); ?>
        </a>
        <a href="?view=certificates" class="nl-nav-link">
            <span class="dashicons dashicons-awards"></span> 
            <?php _e('Certificates', 'nexuslearn'); ?>
        </a>
        <a href="?view=profile" class="nl-nav-link">
            <span class="dashicons dashicons-admin-users"></span> 
            <?php _e('Profile', 'nexuslearn'); ?>
        </a>
    </nav>

    <!-- Stats Overview -->
    <div class="nl-stats-grid">
        <div class="nl-stat-card">
            <h3><?php _e('Enrolled Courses', 'nexuslearn'); ?></h3>
            <p><?php echo esc_html($enrolled_courses); ?></p>
        </div>
        <div class="nl-stat-card">
            <h3><?php _e('Completed Courses', 'nexuslearn'); ?></h3>
            <p><?php echo esc_html($completed_courses); ?></p>
        </div>
        <div class="nl-stat-card">
            <h3><?php _e('Average Progress', 'nexuslearn'); ?></h3>
            <p><?php echo esc_html($average_progress); ?>%</p>
        </div>
    </div>

    <!-- Recent Courses -->
    <div class="nl-content-section">
        <h2><?php _e('Recent Courses', 'nexuslearn'); ?></h2>
        <?php
        $recent_courses = get_posts([
            'post_type' => 'nl_course',
            'posts_per_page' => 5,
            'author' => $user_id
        ]);

        if ($recent_courses): ?>
            <div class="nl-courses-list">
                <?php foreach ($recent_courses as $course): 
                    $progress = get_post_meta($course->ID, '_user_progress_' . $user_id, true) ?: 0;
                ?>
                    <div class="nl-course-item">
                        <h4><?php echo esc_html($course->post_title); ?></h4>
                        <div class="nl-progress-bar">
                            <div class="nl-progress-bar-fill" style="width: <?php echo esc_attr($progress); ?>%"></div>
                        </div>
                        <p class="nl-progress-text"><?php echo esc_html($progress); ?>% <?php _e('Complete', 'nexuslearn'); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="nl-courses-list">
                <div class="nl-course-item">
                    <p class="nl-empty-message"><?php _e('No courses found', 'nexuslearn'); ?></p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Upcoming Assignments -->
    <div class="nl-content-section">
        <h2><?php _e('Upcoming Assignments', 'nexuslearn'); ?></h2>
        <div class="nl-assignments">
            <p class="nl-empty-message"><?php _e('No upcoming assignments', 'nexuslearn'); ?></p>
        </div>
    </div>
</div>
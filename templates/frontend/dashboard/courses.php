<?php
$user_id = get_current_user_id();
$enrolled_courses = get_user_meta($user_id, 'nl_enrolled_courses', true) ?: [];

// Get user's courses with additional data
$courses = get_posts([
    'post_type' => 'nl_course',
    'posts_per_page' => -1,
    'post__in' => $enrolled_courses,
    'orderby' => 'title',
    'order' => 'ASC'
]);
?>

<!-- Courses Filter -->
<div class="nl-courses-filter">
    <select class="nl-filter-select" id="courseFilter">
        <option value="all"><?php _e('All Courses', 'nexuslearn'); ?></option>
        <option value="in-progress"><?php _e('In Progress', 'nexuslearn'); ?></option>
        <option value="completed"><?php _e('Completed', 'nexuslearn'); ?></option>
        <option value="not-started"><?php _e('Not Started', 'nexuslearn'); ?></option>
    </select>
</div>

<!-- Courses Grid -->
<div class="nl-courses-grid">
    <?php if ($courses): ?>
        <?php foreach ($courses as $course):
            $progress = get_user_course_progress($user_id, $course->ID);
            $status = $progress >= 100 ? 'completed' : ($progress > 0 ? 'in-progress' : 'not-started');
            $last_accessed = get_user_last_accessed($user_id, $course->ID);
            ?>
            <div class="nl-course-card" data-status="<?php echo esc_attr($status); ?>">
                <?php if (has_post_thumbnail($course->ID)): ?>
                    <div class="nl-course-image">
                        <?php echo get_the_post_thumbnail($course->ID, 'medium'); ?>
                    </div>
                <?php endif; ?>

                <div class="nl-course-content">
                    <h3 class="nl-course-title"><?php echo esc_html($course->post_title); ?></h3>
                    
                    <div class="nl-progress-bar">
                        <div class="nl-progress-fill" style="width: <?php echo esc_attr($progress); ?>%"></div>
                    </div>
                    
                    <div class="nl-course-meta">
                        <span class="nl-progress-text">
                            <?php echo esc_html($progress); ?>% <?php _e('Complete', 'nexuslearn'); ?>
                        </span>
                        <?php if ($last_accessed): ?>
                            <span class="nl-last-accessed">
                                <?php 
                                printf(
                                    __('Last accessed: %s ago', 'nexuslearn'),
                                    human_time_diff($last_accessed, current_time('timestamp'))
                                );
                                ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="nl-course-actions">
                        <a href="<?php echo get_course_resume_link($course->ID); ?>" 
                           class="nl-button nl-button-primary">
                            <?php 
                            if ($status === 'completed') {
                                _e('Review', 'nexuslearn');
                            } elseif ($status === 'in-progress') {
                                _e('Continue', 'nexuslearn');
                            } else {
                                _e('Start Course', 'nexuslearn');
                            }
                            ?>
                        </a>
                        <a href="?view=course-content&course_id=<?php echo get_the_ID(); ?>" 
           class="nl-button nl-button-primary">
            <?php _e('View Content', 'nexuslearn'); 
            $view_content_url = add_query_arg([
                'view' => 'course-content',
                'course_id' => $course->ID
            ], get_permalink());
            
            '</a>';?>
            
        </a>
        
                        <!-- <button class="nl-course-menu" aria-label="<?php _e('Course Options', 'nexuslearn'); ?>">
                            <i class="dashicons dashicons-ellipsis"></i>
                        </button> -->
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="nl-empty-state">
            <div class="nl-empty-icon">
                <i class="dashicons dashicons-welcome-learn-more"></i>
            </div>
            <h3><?php _e('No Courses Found', 'nexuslearn'); ?></h3>
            <p><?php _e('You haven\'t enrolled in any courses yet.', 'nexuslearn'); ?></p>
            <a href="<?php echo get_post_type_archive_link('nl_course'); ?>" 
               class="nl-button nl-button-primary">
                <?php _e('Browse Courses', 'nexuslearn'); ?>
            </a>
        </div>
    <?php endif; ?>
</div>

<!-- Course Options Menu Template -->
<div class="nl-course-menu-popup" style="display: none;">
    <ul class="nl-menu-options">
        <li>
            <a href="#" class="nl-menu-option nl-view-details">
                <i class="dashicons dashicons-info"></i>
                <?php _e('View Details', 'nexuslearn'); ?>
            </a>
        </li>
        <li>
            <a href="#" class="nl-menu-option nl-download-certificate">
                <i class="dashicons dashicons-awards"></i>
                <?php _e('Download Certificate', 'nexuslearn'); ?>
            </a>
        </li>
        <li>
            <a href="#" class="nl-menu-option nl-unenroll">
                <i class="dashicons dashicons-dismiss"></i>
                <?php _e('Unenroll', 'nexuslearn'); ?>
            </a>
        </li>
    </ul>
</div>
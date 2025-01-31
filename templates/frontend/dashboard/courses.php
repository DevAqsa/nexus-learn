<?php

$user_id = get_current_user_id();
$enrolled_courses = get_user_meta($user_id, 'nl_enrolled_courses', true) ?: [];
$courses_query = new WP_Query([
    'post_type' => 'nl_course',
    'post__in' => $enrolled_courses,
    'posts_per_page' => -1
]);
?>

<div class="nl-courses-section nl-content-section">
    <div class="nl-section-header">
        <h2><?php _e('My Courses', 'nexuslearn'); ?></h2>
        <div class="nl-section-actions">
            <div class="nl-course-filters">
                <select id="nl-course-status-filter">
                    <option value="all"><?php _e('All Courses', 'nexuslearn'); ?></option>
                    <option value="in-progress"><?php _e('In Progress', 'nexuslearn'); ?></option>
                    <option value="completed"><?php _e('Completed', 'nexuslearn'); ?></option>
                </select>
            </div>
        </div>
    </div>

    <?php if ($courses_query->have_posts()): ?>
        <div class="nl-courses-grid">
            <?php while ($courses_query->have_posts()): $courses_query->the_post(); 
                $course_id = get_the_ID();
                $progress = get_user_course_progress($user_id, $course_id);
                $status = $progress >= 100 ? 'completed' : 'in-progress';
            ?>
                <div class="nl-course-card" data-status="<?php echo esc_attr($status); ?>">
                    <div class="nl-course-header">
                        <?php if (has_post_thumbnail()): ?>
                            <div class="nl-course-thumbnail">
                                <?php the_post_thumbnail('medium'); ?>
                            </div>
                        <?php endif; ?>
                        <div class="nl-course-progress">
                            <div class="nl-progress-bar">
                                <div class="nl-progress-fill" style="width: <?php echo esc_attr($progress); ?>%"></div>
                            </div>
                            <span class="nl-progress-text"><?php echo esc_html($progress); ?>% <?php _e('Complete', 'nexuslearn'); ?></span>
                        </div>
                    </div>

                    <div class="nl-course-content">
                        <h3 class="nl-course-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                        <div class="nl-course-meta">
                            <?php
                            $lessons_completed = get_user_completed_lessons($user_id, $course_id);
                            $total_lessons = get_course_lesson_count($course_id);
                            ?>
                            <span class="nl-lessons-count">
                                <?php printf(
                                    __('%d/%d Lessons', 'nexuslearn'),
                                    $lessons_completed,
                                    $total_lessons
                                ); ?>
                            </span>
                            <span class="nl-last-accessed">
                                <?php
                                $last_accessed = get_user_last_accessed($user_id, $course_id);
                                if ($last_accessed) {
                                    printf(
                                        __('Last accessed: %s', 'nexuslearn'),
                                        human_time_diff($last_accessed, current_time('timestamp'))
                                    );
                                }
                                ?>
                            </span>
                        </div>
                    </div>

                    <div class="nl-course-actions">
                        <a href="<?php echo get_course_resume_link($course_id); ?>" 
                           class="nl-button nl-button-primary">
                            <?php echo $progress > 0 ? __('Resume', 'nexuslearn') : __('Start', 'nexuslearn'); ?>
                        </a>
                        <button class="nl-course-menu-toggle">
                            <i class="dashicons dashicons-ellipsis"></i>
                        </button>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <?php wp_reset_postdata(); ?>
    <?php else: ?>
        <div class="nl-empty-state">
            <div class="nl-empty-state-icon">📚</div>
            <h3><?php _e('No Courses Enrolled', 'nexuslearn'); ?></h3>
            <p><?php _e('Start your learning journey today', 'nexuslearn'); ?></p>
            <a href="<?php echo esc_url(get_post_type_archive_link('nl_course')); ?>" 
               class="nl-button nl-button-primary">
                <?php _e('Browse Courses', 'nexuslearn'); ?>
            </a>
        </div>
    <?php endif; ?>
</div>
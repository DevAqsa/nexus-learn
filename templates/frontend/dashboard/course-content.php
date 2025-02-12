<?php
if (!defined('ABSPATH')) exit;

$user_id = get_current_user_id();

// Get enrolled courses
$enrolled_courses = get_user_meta($user_id, 'nl_enrolled_courses', true) ?: [];
$courses_query = new WP_Query([
    'post_type' => 'nl_course',
    'posts_per_page' => -1,
    'orderby' => 'title',
    'order' => 'ASC'
]);

// Get user statistics
$completed_courses = count(array_filter($enrolled_courses, function($course_id) use ($user_id) {
    return get_user_course_progress($user_id, $course_id) >= 100;
}));

$total_courses = $courses_query->found_posts;
$avg_progress = $total_courses > 0 ? array_sum(array_map(function($course_id) use ($user_id) {
    return get_user_course_progress($user_id, $course_id);
}, $enrolled_courses)) / $total_courses : 0;
?>

<div class="nl-dashboard-overview">
    <!-- Stats Overview -->
    <div class="nl-stats-grid">
        <div class="nl-stat-card">
            <span class="nl-stat-icon">📚</span>
            <div class="nl-stat-value"><?php echo esc_html($total_courses); ?></div>
            <div class="nl-stat-label"><?php _e('Available Courses', 'nexuslearn'); ?></div>
        </div>
        <div class="nl-stat-card">
            <span class="nl-stat-icon">✅</span>
            <div class="nl-stat-value"><?php echo esc_html($completed_courses); ?></div>
            <div class="nl-stat-label"><?php _e('Completed Courses', 'nexuslearn'); ?></div>
        </div>
        <div class="nl-stat-card">
            <span class="nl-stat-icon">📊</span>
            <div class="nl-stat-value"><?php echo round($avg_progress); ?>%</div>
            <div class="nl-stat-label"><?php _e('Average Progress', 'nexuslearn'); ?></div>
        </div>
    </div>

    <!-- Available Courses -->
    <div class="nl-courses-section">
        <h2><?php _e('Available Courses', 'nexuslearn'); ?></h2>
        
        <?php if ($courses_query->have_posts()): ?>
            <div class="nl-courses-grid">
                <?php while ($courses_query->have_posts()): $courses_query->the_post(); 
                    $course_id = get_the_ID();
                    $progress = get_user_course_progress($user_id, $course_id);
                    $is_enrolled = in_array($course_id, $enrolled_courses);
                ?>
                    <div class="nl-course-card">
                        <?php if (has_post_thumbnail()): ?>
                            <div class="nl-course-thumbnail">
                                <?php the_post_thumbnail('medium'); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="nl-course-content">
                            <h3><?php the_title(); ?></h3>
                            <div class="nl-course-excerpt">
                                <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                            </div>
                            
                            <?php if ($is_enrolled): ?>
                                <div class="nl-course-progress">
                                    <div class="nl-progress-bar">
                                        <div class="nl-progress-fill" style="width: <?php echo esc_attr($progress); ?>%"></div>
                                    </div>
                                    <span class="nl-progress-text">
                                        <?php echo esc_html($progress); ?>% <?php _e('Complete', 'nexuslearn'); ?>
                                    </span>
                                </div>
                                
                                <div class="nl-course-actions">
                                    <a href="<?php echo esc_url(add_query_arg(['view' => 'course-content', 'course_id' => $course_id], get_permalink())); ?>" 
                                       class="nl-button nl-button-primary">
                                        <?php echo $progress > 0 ? __('Continue', 'nexuslearn') : __('Start Course', 'nexuslearn'); ?>
                                    </a>
                                    <a href="<?php echo esc_url(add_query_arg(['view' => 'course-content', 'course_id' => $course_id], get_permalink())); ?>" 
                                       class="nl-button nl-button-secondary">
                                        <?php _e('View Content', 'nexuslearn'); ?>
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="nl-course-actions">
                                    <form method="post" class="nl-enroll-form">
                                        <?php wp_nonce_field('nl_enroll_course', 'nl_enroll_nonce'); ?>
                                        <input type="hidden" name="course_id" value="<?php echo esc_attr($course_id); ?>">
                                        <button type="submit" class="nl-button nl-button-primary">
                                            <?php _e('Enroll Now', 'nexuslearn'); ?>
                                        </button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            <?php wp_reset_postdata(); ?>
        <?php else: ?>
            <div class="nl-empty-state">
                <div class="nl-empty-icon">📚</div>
                <h3><?php _e('No Courses Available', 'nexuslearn'); ?></h3>
                <p><?php _e('Check back later for new courses.', 'nexuslearn'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.nl-dashboard-overview {
    padding: 20px;
}

.nl-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.nl-stat-card {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    text-align: center;
}

.nl-stat-icon {
    font-size: 24px;
    margin-bottom: 10px;
}

.nl-stat-value {
    font-size: 24px;
    font-weight: bold;
    color: #4f46e5;
    margin-bottom: 5px;
}

.nl-courses-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.nl-course-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    overflow: hidden;
}

.nl-course-thumbnail img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.nl-course-content {
    padding: 20px;
}

.nl-course-content h3 {
    margin: 0 0 10px 0;
    font-size: 1.25rem;
}

.nl-course-excerpt {
    color: #6b7280;
    margin-bottom: 15px;
}

.nl-progress-bar {
    height: 8px;
    background: #e5e7eb;
    border-radius: 4px;
    overflow: hidden;
    margin-bottom: 5px;
}

.nl-progress-fill {
    height: 100%;
    background: #4f46e5;
    transition: width 0.3s ease;
}

.nl-course-actions {
    display: flex;
    gap: 10px;
    margin-top: 15px;
}

.nl-button {
    padding: 8px 16px;
    border-radius: 6px;
    font-weight: 500;
    text-decoration: none;
    text-align: center;
    transition: all 0.2s ease;
}

.nl-button-primary {
    background: #4f46e5;
    color: white;
    border: none;
}

.nl-button-primary:hover {
    background: #4338ca;
}

.nl-button-secondary {
    background: #f3f4f6;
    color: #4b5563;
    border: 1px solid #e5e7eb;
}

.nl-button-secondary:hover {
    background: #e5e7eb;
}

.nl-empty-state {
    text-align: center;
    padding: 40px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.nl-empty-icon {
    font-size: 48px;
    margin-bottom: 20px;
}

@media (max-width: 768px) {
    .nl-courses-grid {
        grid-template-columns: 1fr;
    }
    
    .nl-course-actions {
        flex-direction: column;
    }
    
    .nl-button {
        width: 100%;
    }
}
</style>
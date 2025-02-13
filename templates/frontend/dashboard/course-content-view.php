<?php
if (!defined('ABSPATH')) exit;

$course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;
$course = get_post($course_id);

if (!$course || $course->post_type !== 'nl_course') {
    wp_redirect(add_query_arg('view', 'courses', get_permalink()));
    exit;
}

// Get course lessons
$lessons = get_posts([
    'post_type' => 'nl_lesson',
    'post_parent' => $course_id,
    'orderby' => 'menu_order',
    'order' => 'ASC',
    'posts_per_page' => -1
]);

// Get instructor info
$instructor_id = get_post_meta($course_id, '_instructor_id', true);
$instructor = get_userdata($instructor_id);

?>

<div class="nl-course-content-view">
    <!-- Left Sidebar -->
    <div class="nl-course-sidebar">
        <!-- Instructor Info -->
        <div class="nl-instructor-info">
            <?php echo get_avatar($instructor_id, 120); ?>
            <div class="nl-instructor-details">
                <h3><?php echo esc_html($instructor->display_name); ?></h3>
                <p class="nl-instructor-title">
                    <?php echo esc_html(get_user_meta($instructor_id, '_academic_title', true)); ?>
                </p>
                <p class="nl-instructor-institute">
                    <?php echo esc_html(get_user_meta($instructor_id, '_institute', true)); ?>
                </p>
            </div>
        </div>

        <!-- Course Navigation -->
        <div class="nl-course-nav">
            <h4><?php _e('Index / Lesson', 'nexuslearn'); ?></h4>
            <div class="nl-lesson-list">
                <?php 
                $current_user_id = get_current_user_id();
                foreach ($lessons as $index => $lesson):
                    $lesson_number = $index + 1;
                    $is_completed = get_user_meta($current_user_id, "_lesson_{$lesson->ID}_completed", true);
                    $has_resources = get_post_meta($lesson->ID, '_has_resources', true);
                    $has_slides = get_post_meta($lesson->ID, '_has_slides', true);
                ?>
                    <div class="nl-lesson-item <?php echo $is_completed ? 'completed' : ''; ?>">
                        <span class="nl-lesson-number"><?php echo $lesson_number; ?></span>
                        <a href="<?php echo get_permalink($lesson->ID); ?>" class="nl-lesson-link">
                            <?php echo esc_html($lesson->post_title); ?>
                        </a>
                        <div class="nl-lesson-resources">
                            <?php if ($has_resources): ?>
                                <span class="nl-resource-icon" title="<?php _e('Resources Available', 'nexuslearn'); ?>">
                                    📄
                                </span>
                            <?php endif; ?>
                            <?php if ($has_slides): ?>
                                <span class="nl-slides-icon" title="<?php _e('Slides Available', 'nexuslearn'); ?>">
                                    📊
                                </span>
                            <?php endif; ?>
                        </div>
                        <span class="nl-lesson-status">
                            <?php 
                            if ($is_completed) {
                                echo '<span class="nl-status-completed">✓</span>';
                            } else {
                                echo '<span class="nl-status-duration">' . get_post_meta($lesson->ID, '_duration', true) . '</span>';
                            }
                            ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="nl-content-area">
        <div class="nl-content-header">
            <h2><?php echo esc_html($course->post_title); ?></h2>
            <a href="<?php echo wp_get_referer(); ?>" class="nl-back-button">
                <?php _e('Back', 'nexuslearn'); ?>
            </a>
        </div>

        <div class="nl-course-description">
            <?php echo wp_kses_post($course->post_content); ?>
        </div>

        <!-- Course Resources -->
        <?php if ($resources = get_post_meta($course_id, '_course_resources', true)): ?>
            <div class="nl-course-resources">
                <h3><?php _e('Course Resources', 'nexuslearn'); ?></h3>
                <ul>
                    <?php foreach ($resources as $resource): ?>
                        <li>
                            <a href="<?php echo esc_url($resource['url']); ?>" target="_blank">
                                <?php echo esc_html($resource['title']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.nl-course-content-view {
    display: flex;
    min-height: calc(100vh - 100px);
}

.nl-course-sidebar {
    width: 300px;
    background: #f8fafc;
    border-right: 1px solid #e2e8f0;
    padding: 20px;
}

.nl-instructor-info {
    text-align: center;
    padding-bottom: 20px;
    border-bottom: 1px solid #e2e8f0;
    margin-bottom: 20px;
}

.nl-instructor-info img {
    border-radius: 50%;
    margin-bottom: 10px;
}

.nl-instructor-details h3 {
    margin: 0 0 5px 0;
    color: #1e293b;
}

.nl-instructor-title,
.nl-instructor-institute {
    color: #64748b;
    margin: 0;
    font-size: 0.875rem;
}

.nl-lesson-list {
    margin-top: 15px;
}

.nl-lesson-item {
    display: flex;
    align-items: center;
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 5px;
    transition: background 0.2s;
}

.nl-lesson-item:hover {
    background: #e2e8f0;
}

.nl-lesson-item.completed {
    background: #f0fdf4;
}

.nl-lesson-number {
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #e2e8f0;
    border-radius: 50%;
    margin-right: 10px;
    font-size: 0.875rem;
}

.nl-lesson-link {
    flex: 1;
    color: #1e293b;
    text-decoration: none;
}

.nl-lesson-resources {
    display: flex;
    gap: 5px;
    margin-right: 10px;
}

.nl-content-area {
    flex: 1;
    padding: 30px;
}

.nl-content-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.nl-back-button {
    padding: 8px 16px;
    background: #4f46e5;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-size: 0.875rem;
}

.nl-back-button:hover {
    background: #4338ca;
}

@media (max-width: 768px) {
    .nl-course-content-view {
        flex-direction: column;
    }

    .nl-course-sidebar {
        width: 100%;
        border-right: none;
        border-bottom: 1px solid #e2e8f0;
    }
}
</style>
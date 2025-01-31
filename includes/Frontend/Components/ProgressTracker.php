<?php
namespace NexusLearn\Frontend\Components;

class ProgressTracker {
    public function render_progress_section($user_id) {
        $courses = $this->get_user_courses($user_id);
        ob_start();
        ?>
        <div class="nl-progress-section">
            <h2><?php _e('Course Progress', 'nexuslearn'); ?></h2>
            <?php if (!empty($courses)): ?>
                <div class="nl-progress-list">
                    <?php foreach ($courses as $course): ?>
                        <div class="nl-progress-item">
                            <div class="nl-progress-header">
                                <h3><?php echo esc_html($course['title']); ?></h3>
                                <span class="nl-progress-percentage">
                                    <?php echo esc_html($course['progress']); ?>%
                                </span>
                            </div>
                            <div class="nl-progress-bar">
                                <div class="nl-progress-fill" 
                                     style="width: <?php echo esc_attr($course['progress']); ?>%">
                                </div>
                            </div>
                            <div class="nl-progress-details">
                                <span><?php echo esc_html($course['completed_lessons']); ?> / 
                                      <?php echo esc_html($course['total_lessons']); ?> 
                                      <?php _e('lessons completed', 'nexuslearn'); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="nl-empty-state"><?php _e('No courses enrolled.', 'nexuslearn'); ?></p>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    private function get_user_courses($user_id) {
        // Get user's enrolled courses with progress
        return get_user_meta($user_id, 'nl_course_progress', true) ?: [];
    }
}

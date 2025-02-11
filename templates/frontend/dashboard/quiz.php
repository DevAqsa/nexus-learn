<?php
if (!defined('ABSPATH')) exit;

$user_id = get_current_user_id();

// Get user's quizzes (both pending and completed)
function get_user_quizzes($user_id) {
    global $wpdb;
    $enrolled_courses = get_user_meta($user_id, 'nl_enrolled_courses', true) ?: [];
    
    if (empty($enrolled_courses)) {
        return [];
    }

    $quizzes = [];
    foreach ($enrolled_courses as $course_id) {
        // Get course quizzes
        $course_quizzes = get_posts([
            'post_type' => 'nl_quiz',
            'meta_query' => [
                [
                    'key' => '_course_id',
                    'value' => $course_id
                ]
            ],
            'posts_per_page' => -1
        ]);

        foreach ($course_quizzes as $quiz) {
            // Get quiz attempts
            $attempts = $wpdb->get_results($wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}nl_quiz_attempts 
                WHERE quiz_id = %d AND user_id = %d 
                ORDER BY attempt_date DESC",
                $quiz->ID,
                $user_id
            ), ARRAY_A);

            $quiz_data = [
                'id' => $quiz->ID,
                'title' => $quiz->post_title,
                'course_id' => $course_id,
                'course_title' => get_the_title($course_id),
                'attempts' => $attempts,
                'settings' => get_post_meta($quiz->ID, '_quiz_settings', true) ?: [],
                'status' => empty($attempts) ? 'pending' : 'completed'
            ];

            $quizzes[] = $quiz_data;
        }
    }

    return $quizzes;
}

$quizzes = get_user_quizzes($user_id);
$pending_quizzes = array_filter($quizzes, function($quiz) {
    return $quiz['status'] === 'pending';
});
$completed_quizzes = array_filter($quizzes, function($quiz) {
    return $quiz['status'] === 'completed';
});
?>

<div class="nl-quiz-section nl-content-section">
    <div class="nl-section-header">
        <h2><?php _e('My Quizzes', 'nexuslearn'); ?></h2>
        <div class="nl-section-actions">
            <select id="nl-quiz-filter" class="nl-select">
                <option value="all"><?php _e('All Quizzes', 'nexuslearn'); ?></option>
                <option value="pending"><?php _e('Pending', 'nexuslearn'); ?></option>
                <option value="completed"><?php _e('Completed', 'nexuslearn'); ?></option>
            </select>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="nl-stats-grid">
        <div class="nl-stat-card">
            <span class="nl-stat-icon pending">📝</span>
            <div class="nl-stat-value"><?php echo count($pending_quizzes); ?></div>
            <div class="nl-stat-label"><?php _e('Pending Quizzes', 'nexuslearn'); ?></div>
        </div>
        <div class="nl-stat-card">
            <span class="nl-stat-icon completed">✓</span>
            <div class="nl-stat-value"><?php echo count($completed_quizzes); ?></div>
            <div class="nl-stat-label"><?php _e('Completed Quizzes', 'nexuslearn'); ?></div>
        </div>
        <div class="nl-stat-card">
            <span class="nl-stat-icon average">📊</span>
            <div class="nl-stat-value">
                <?php
                $scores = array_map(function($quiz) {
                    if (empty($quiz['attempts'])) return 0;
                    return $quiz['attempts'][0]['score'];
                }, $completed_quizzes);
                echo !empty($scores) ? round(array_sum($scores) / count($scores)) : 0;
                ?>%
            </div>
            <div class="nl-stat-label"><?php _e('Average Score', 'nexuslearn'); ?></div>
        </div>
    </div>

    <!-- Quiz List -->
    <div class="nl-quizzes-list">
        <?php if (!empty($quizzes)): ?>
            <?php foreach ($quizzes as $quiz): ?>
                <div class="nl-quiz-card" data-status="<?php echo esc_attr($quiz['status']); ?>">
                    <div class="nl-quiz-header">
                        <h3><?php echo esc_html($quiz['title']); ?></h3>
                        <span class="nl-course-name">
                            <?php echo esc_html($quiz['course_title']); ?>
                        </span>
                    </div>

                    <div class="nl-quiz-meta">
                        <?php if (!empty($quiz['settings'])): ?>
                            <div class="nl-quiz-details">
                                <?php if (isset($quiz['settings']['time_limit'])): ?>
                                    <span class="nl-time-limit">
                                        <i class="dashicons dashicons-clock"></i>
                                        <?php printf(
                                            __('%d minutes', 'nexuslearn'),
                                            $quiz['settings']['time_limit']
                                        ); ?>
                                    </span>
                                <?php endif; ?>

                                <?php if (isset($quiz['settings']['passing_score'])): ?>
                                    <span class="nl-passing-score">
                                        <i class="dashicons dashicons-chart-bar"></i>
                                        <?php printf(
                                            __('Pass: %d%%', 'nexuslearn'),
                                            $quiz['settings']['passing_score']
                                        ); ?>
                                    </span>
                                <?php endif; ?>

                                <?php if (isset($quiz['settings']['max_attempts'])): ?>
                                    <span class="nl-attempts">
                                        <i class="dashicons dashicons-update"></i>
                                        <?php printf(
                                            __('Attempts: %d/%d', 'nexuslearn'),
                                            count($quiz['attempts']),
                                            $quiz['settings']['max_attempts']
                                        ); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($quiz['attempts'])): ?>
                            <div class="nl-last-attempt">
                                <span class="nl-score">
                                    <?php printf(
                                        __('Last Score: %d%%', 'nexuslearn'),
                                        $quiz['attempts'][0]['score']
                                    ); ?>
                                </span>
                                <span class="nl-attempt-date">
                                    <?php printf(
                                        __('Attempted: %s', 'nexuslearn'),
                                        date_i18n(
                                            get_option('date_format'),
                                            strtotime($quiz['attempts'][0]['attempt_date'])
                                        )
                                    ); ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="nl-quiz-actions">
                        <?php if ($quiz['status'] === 'pending'): ?>
                            <a href="<?php echo esc_url(add_query_arg(['quiz_id' => $quiz['id']], get_permalink())); ?>" 
                               class="nl-button nl-button-primary">
                                <?php _e('Start Quiz', 'nexuslearn'); ?>
                            </a>
                        <?php else: ?>
                            <a href="<?php echo esc_url(add_query_arg(
                                ['quiz_id' => $quiz['id'], 'view' => 'review'],
                                get_permalink()
                            )); ?>" 
                               class="nl-button nl-button-secondary">
                                <?php _e('Review', 'nexuslearn'); ?>
                            </a>
                            <?php if (
                                !isset($quiz['settings']['max_attempts']) || 
                                count($quiz['attempts']) < $quiz['settings']['max_attempts']
                            ): ?>
                                <a href="<?php echo esc_url(add_query_arg(['quiz_id' => $quiz['id']], get_permalink())); ?>" 
                                   class="nl-button nl-button-primary">
                                    <?php _e('Retake', 'nexuslearn'); ?>
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="nl-empty-state">
                <div class="nl-empty-icon">📝</div>
                <h3><?php _e('No Quizzes Found', 'nexuslearn'); ?></h3>
                <p><?php _e('You don\'t have any quizzes available at the moment.', 'nexuslearn'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.nl-quiz-section {
    padding: 2rem;
}

.nl-quiz-card {
    background: white;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.nl-quiz-header {
    margin-bottom: 1rem;
}

.nl-quiz-header h3 {
    margin: 0 0 0.5rem 0;
    color: #1f2937;
}

.nl-course-name {
    color: #6b7280;
    font-size: 0.875rem;
}

.nl-quiz-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.nl-quiz-details {
    display: flex;
    gap: 1rem;
    color: #4b5563;
    font-size: 0.875rem;
}

.nl-quiz-details i {
    margin-right: 0.25rem;
}

.nl-last-attempt {
    text-align: right;
    font-size: 0.875rem;
}

.nl-score {
    display: block;
    font-weight: 600;
    color: #059669;
}

.nl-attempt-date {
    color: #6b7280;
}

.nl-quiz-actions {
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
}

.nl-button {
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
}

.nl-button-primary {
    background: #3b82f6;
    color: white;
}

.nl-button-secondary {
    background: #e5e7eb;
    color: #374151;
}

.nl-empty-state {
    text-align: center;
    padding: 3rem;
}

.nl-empty-icon {
    font-size: 2.5rem;
    margin-bottom: 1rem;
}

@media (max-width: 768px) {
    .nl-quiz-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }

    .nl-quiz-details {
        flex-direction: column;
        gap: 0.5rem;
    }

    .nl-last-attempt {
        text-align: left;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    $('#nl-quiz-filter').on('change', function() {
        const status = $(this).val();
        if (status === 'all') {
            $('.nl-quiz-card').show();
        } else {
            $('.nl-quiz-card').hide();
            $(`.nl-quiz-card[data-status="${status}"]`).show();
        }
    });
});
</script>
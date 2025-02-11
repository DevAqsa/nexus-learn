<?php
if (!defined('ABSPATH')) exit;

$current_user_id = get_current_user_id();
$current_semester = isset($_GET['semester']) ? sanitize_text_field($_GET['semester']) : 'FALL 2024';

// Dummy data for quizzes - Replace with actual database queries
$quizzes = [
    [
        'course_code' => 'CS501',
        'course_name' => 'Advanced Programming',
        'quiz_name' => 'Quiz 1 - Data Structures',
        'due_date' => '2024-03-15',
        'status' => 'completed',
        'score' => 85,
        'total_marks' => 100,
        'attempt_time' => '45 minutes',
        'feedback' => 'Good understanding of core concepts.'
    ],
    [
        'course_code' => 'MTH401',
        'course_name' => 'Applied Mathematics',
        'quiz_name' => 'Quiz 2 - Linear Algebra',
        'due_date' => '2024-03-20',
        'status' => 'pending',
        'score' => null,
        'total_marks' => 50,
        'attempt_time' => '30 minutes',
        'feedback' => null
    ]
];

// Calculate quiz statistics
$completed_quizzes = array_filter($quizzes, function($quiz) {
    return $quiz['status'] === 'completed';
});

$average_score = count($completed_quizzes) > 0 
    ? array_sum(array_column($completed_quizzes, 'score')) / count($completed_quizzes) 
    : 0;
?>

<div class="nl-quizzes-section">
    <!-- Quiz Statistics Overview -->
    <div class="nl-quiz-stats">
        <div class="nl-stats-grid">
            <div class="nl-stat-card">
                <span class="nl-stat-icon">📝</span>
                <div class="nl-stat-value"><?php echo count($quizzes); ?></div>
                <div class="nl-stat-label"><?php _e('Total Quizzes', 'nexuslearn'); ?></div>
            </div>
            <div class="nl-stat-card">
                <span class="nl-stat-icon">✓</span>
                <div class="nl-stat-value"><?php echo count($completed_quizzes); ?></div>
                <div class="nl-stat-label"><?php _e('Completed', 'nexuslearn'); ?></div>
            </div>
            <div class="nl-stat-card">
                <span class="nl-stat-icon">📊</span>
                <div class="nl-stat-value"><?php echo round($average_score, 1); ?>%</div>
                <div class="nl-stat-label"><?php _e('Average Score', 'nexuslearn'); ?></div>
            </div>
        </div>
    </div>

    <!-- Upcoming Quizzes Alert -->
    <?php
    $upcoming_quizzes = array_filter($quizzes, function($quiz) {
        return $quiz['status'] === 'pending' && strtotime($quiz['due_date']) > time();
    });
    if (!empty($upcoming_quizzes)): 
    ?>
        <div class="nl-upcoming-quizzes">
            <h3>
                <i class="dashicons dashicons-warning"></i>
                <?php _e('Upcoming Quizzes', 'nexuslearn'); ?>
            </h3>
            <div class="nl-upcoming-list">
                <?php foreach ($upcoming_quizzes as $quiz): ?>
                    <div class="nl-upcoming-item">
                        <div class="nl-quiz-name"><?php echo esc_html($quiz['quiz_name']); ?></div>
                        <div class="nl-quiz-due">
                            <?php printf(
                                __('Due: %s', 'nexuslearn'),
                                date_i18n(get_option('date_format'), strtotime($quiz['due_date']))
                            ); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Quiz List -->
    <div class="nl-quizzes-list">
        <div class="nl-section-header">
            <h2><?php _e('Quiz History', 'nexuslearn'); ?></h2>
            <div class="nl-filters">
                <select id="course-filter" class="nl-select">
                    <option value=""><?php _e('All Courses', 'nexuslearn'); ?></option>
                    <?php
                    $courses = array_unique(array_column($quizzes, 'course_code'));
                    foreach ($courses as $course) {
                        echo '<option value="' . esc_attr($course) . '">' . esc_html($course) . '</option>';
                    }
                    ?>
                </select>
                <select id="status-filter" class="nl-select">
                    <option value=""><?php _e('All Status', 'nexuslearn'); ?></option>
                    <option value="completed"><?php _e('Completed', 'nexuslearn'); ?></option>
                    <option value="pending"><?php _e('Pending', 'nexuslearn'); ?></option>
                </select>
            </div>
        </div>

        <?php if (!empty($quizzes)): ?>
            <div class="nl-quiz-cards">
                <?php foreach ($quizzes as $quiz): ?>
                    <div class="nl-quiz-card" data-course="<?php echo esc_attr($quiz['course_code']); ?>" 
                                           data-status="<?php echo esc_attr($quiz['status']); ?>">
                        <div class="nl-quiz-header">
                            <div class="nl-course-code"><?php echo esc_html($quiz['course_code']); ?></div>
                            <span class="nl-status-badge status-<?php echo esc_attr($quiz['status']); ?>">
                                <?php echo esc_html(ucfirst($quiz['status'])); ?>
                            </span>
                        </div>
                        
                        <div class="nl-quiz-content">
                            <h3><?php echo esc_html($quiz['quiz_name']); ?></h3>
                            <div class="nl-quiz-meta">
                                <div class="nl-meta-item">
                                    <i class="dashicons dashicons-calendar-alt"></i>
                                    <?php echo date_i18n(get_option('date_format'), strtotime($quiz['due_date'])); ?>
                                </div>
                                <div class="nl-meta-item">
                                    <i class="dashicons dashicons-clock"></i>
                                    <?php echo esc_html($quiz['attempt_time']); ?>
                                </div>
                            </div>
                            
                            <?php if ($quiz['status'] === 'completed'): ?>
                                <div class="nl-quiz-score">
                                    <div class="nl-score-label"><?php _e('Score:', 'nexuslearn'); ?></div>
                                    <div class="nl-score-value">
                                        <?php echo esc_html($quiz['score']); ?>/<?php echo esc_html($quiz['total_marks']); ?>
                                        (<?php echo round(($quiz['score'] / $quiz['total_marks']) * 100); ?>%)
                                    </div>
                                </div>
                                <?php if (!empty($quiz['feedback'])): ?>
                                    <div class="nl-quiz-feedback">
                                        <div class="nl-feedback-label"><?php _e('Feedback:', 'nexuslearn'); ?></div>
                                        <div class="nl-feedback-text"><?php echo esc_html($quiz['feedback']); ?></div>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="nl-quiz-actions">
                                    <a href="#" class="nl-button nl-button-primary start-quiz" 
                                       data-quiz-id="<?php echo esc_attr($quiz['id'] ?? ''); ?>">
                                        <?php _e('Start Quiz', 'nexuslearn'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="nl-empty-state">
                <div class="nl-empty-icon">📝</div>
                <h3><?php _e('No Quizzes Found', 'nexuslearn'); ?></h3>
                <p><?php _e('There are no quizzes available for this semester.', 'nexuslearn'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
/* Quizzes Section Styles */
.nl-quizzes-section {
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
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    text-align: center;
}

.nl-stat-icon {
    font-size: 24px;
    margin-bottom: 10px;
}

.nl-upcoming-quizzes {
    background: #fff7ed;
    border-left: 4px solid #f97316;
    padding: 15px 20px;
    margin-bottom: 30px;
    border-radius: 4px;
}

.nl-upcoming-quizzes h3 {
    color: #9a3412;
    margin: 0 0 10px 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.nl-quiz-cards {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.nl-quiz-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    overflow: hidden;
}

.nl-quiz-header {
    padding: 15px;
    background: #f8fafc;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.nl-quiz-content {
    padding: 20px;
}

.nl-quiz-meta {
    display: flex;
    gap: 15px;
    margin: 10px 0;
    color: #64748b;
    font-size: 0.875rem;
}

.nl-meta-item {
    display: flex;
    align-items: center;
    gap: 5px;
}

.nl-quiz-score {
    background: #f8fafc;
    padding: 10px;
    border-radius: 4px;
    margin: 15px 0;
}

.nl-score-label {
    font-weight: 500;
    margin-bottom: 5px;
}

.nl-quiz-feedback {
    border-left: 3px solid #6366f1;
    padding-left: 10px;
    margin: 15px 0;
}

.nl-status-badge {
    padding: 4px 8px;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

.nl-status-badge.status-completed {
    background: #dcfce7;
    color: #166534;
}

.nl-status-badge.status-pending {
    background: #fff7ed;
    color: #9a3412;
}

/* Responsive Design */
@media (max-width: 768px) {
    .nl-stats-grid {
        grid-template-columns: 1fr;
    }
    
    .nl-quiz-cards {
        grid-template-columns: 1fr;
    }
    
    .nl-section-header {
        flex-direction: column;
        gap: 10px;
    }
    
    .nl-filters {
        width: 100%;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Filter functionality
    function filterQuizzes() {
        const courseFilter = $('#course-filter').val();
        const statusFilter = $('#status-filter').val();
        
        $('.nl-quiz-card').each(function() {
            const $card = $(this);
            const course = $card.data('course');
            const status = $card.data('status');
            
            const courseMatch = !courseFilter || course === courseFilter;
            const statusMatch = !statusFilter || status === statusFilter;
            
            $card.toggle(courseMatch && statusMatch);
        });
    }
    
    $('#course-filter, #status-filter').on('change', filterQuizzes);
    
    // Start Quiz button handler
    $('.start-quiz').on('click', function(e) {
        e.preventDefault();
        const quizId = $(this).data('quiz-id');
        // Add your quiz start logic here
    });
});
</script>
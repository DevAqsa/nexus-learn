<?php
namespace NexusLearn\Analytics\Views;

class QuestionPerformanceView {
    private $analytics;

    public function __construct() {
        $this->analytics = new \NexusLearn\Analytics\QuestionAnalytics();
    }

    public function render_question_performance_section($quiz_id = null) {
        $performance_data = $this->analytics->get_question_performance($quiz_id);
        ?>
        <div class="nl-chart-container">
            <div class="nl-chart-header">
                <h2 class="nl-chart-title"><?php _e('Question Performance', 'nexuslearn'); ?></h2>
            </div>
            <table class="nl-analytics-table">
                <thead>
                    <tr>
                        <th><?php _e('Question', 'nexuslearn'); ?></th>
                        <th><?php _e('Total Attempts', 'nexuslearn'); ?></th>
                        <th><?php _e('Correct Answers', 'nexuslearn'); ?></th>
                        <th><?php _e('Success Rate', 'nexuslearn'); ?></th>
                        <th><?php _e('Avg. Time', 'nexuslearn'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($performance_data as $question): ?>
                        <tr>
                            <td><?php echo esc_html($question->question_text); ?></td>
                            <td><?php echo number_format($question->total_attempts); ?></td>
                            <td><?php echo number_format($question->correct_answers); ?></td>
                            <td><?php echo number_format($question->success_rate, 1) . '%'; ?></td>
                            <td><?php echo number_format($question->avg_time_taken, 1) . 's'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
}
<?php

if (!defined('ABSPATH')) exit;

$quiz_id = isset($_GET['quiz_id']) ? intval($_GET['quiz_id']) : null;
$analytics = new NexusLearn\Analytics\QuizAnalytics();
$stats = $analytics->get_quiz_statistics($quiz_id);
?>

<div class="wrap nl-analytics-wrap">
    <div class="nl-analytics-header">
        <h1><?php _e('Quiz Analytics', 'nexuslearn'); ?></h1>
        <div class="nl-analytics-filters">
            <select id="quiz-selector">
                <option value=""><?php _e('All Quizzes', 'nexuslearn'); ?></option>
                <?php
                $quizzes = get_posts(['post_type' => 'nl_quiz', 'numberposts' => -1]);
                foreach ($quizzes as $quiz) {
                    printf(
                        '<option value="%d" %s>%s</option>',
                        $quiz->ID,
                        selected($quiz_id, $quiz->ID, false),
                        esc_html($quiz->post_title)
                    );
                }
                ?>
            </select>
            
            <select id="date-range">
                <option value="7"><?php _e('Last 7 days', 'nexuslearn'); ?></option>
                <option value="30" selected><?php _e('Last 30 days', 'nexuslearn'); ?></option>
                <option value="90"><?php _e('Last 90 days', 'nexuslearn'); ?></option>
                <option value="365"><?php _e('Last year', 'nexuslearn'); ?></option>
            </select>
        </div>
    </div>

    <div class="nl-analytics-grid">
        <!-- Overview Cards -->
        <div class="nl-card">
            <h3><?php _e('Total Attempts', 'nexuslearn'); ?></h3>
            <div class="nl-card-value"><?php echo number_format($stats['total_attempts']); ?></div>
            <div class="nl-card-trend positive">
                <span>↑ 12%</span>
                <span>vs last period</span>
            </div>
        </div>
        
        <div class="nl-card">
            <h3><?php _e('Average Score', 'nexuslearn'); ?></h3>
            <div class="nl-card-value"><?php echo number_format($stats['average_score'], 1); ?>%</div>
            <div class="nl-card-trend positive">
                <span>↑ 5%</span>
                <span>vs last period</span>
            </div>
        </div>
        
        <div class="nl-card">
            <h3><?php _e('Completion Rate', 'nexuslearn'); ?></h3>
            <div class="nl-card-value"><?php echo number_format($stats['completion_rate'], 1); ?>%</div>
            <div class="nl-card-trend negative">
                <span>↓ 3%</span>
                <span>vs last period</span>
            </div>
        </div>

        <div class="nl-card">
    <h3><?php _e('Average Time', 'nexuslearn'); ?></h3>
    <div class="nl-card-value">
        <?php 
        $average_time = isset($stats['time_statistics']) && is_object($stats['time_statistics']) 
            ? number_format($stats['time_statistics']->average_time / 60, 1) 
            : '0.0';
        echo esc_html($average_time) . ' min';
        ?>
    </div>
    <div class="nl-card-trend">
        <span>→ No change</span>
        <span>vs last period</span>
    </div>
</div>
    </div>

    <div class="nl-charts-grid">
        <!-- Score Distribution Chart -->
        <div class="nl-chart-container">
            <div class="nl-chart-header">
                <h2 class="nl-chart-title"><?php _e('Score Distribution', 'nexuslearn'); ?></h2>
            </div>
            <canvas id="scoreDistribution"></canvas>
        </div>
        
        <!-- Question Analysis Chart -->
        <div class="nl-chart-container">
            <div class="nl-chart-header">
                <h2 class="nl-chart-title"><?php _e('Question Analysis', 'nexuslearn'); ?></h2>
            </div>
            <canvas id="questionAnalysis"></canvas>
        </div>
    </div>

    <!-- Detailed Questions Table -->
    <div class="nl-chart-container" style="margin-top: 25px;">
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
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stats['question_analysis'] as $question): ?>
                    <tr>
                        <td><?php echo esc_html($question->question_text); ?></td>
                        <td><?php echo number_format($question->total_attempts); ?></td>
                        <td><?php echo number_format($question->correct_answers); ?></td>
                        <td>
                            <?php 
                            $success_rate = $question->total_attempts > 0 
                                ? ($question->correct_answers / $question->total_attempts) * 100 
                                : 0;
                            echo number_format($success_rate, 1) . '%';
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>



</div>
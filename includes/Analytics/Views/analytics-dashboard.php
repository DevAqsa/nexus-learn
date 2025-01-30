<?php
// File: includes/Analytics/Views/analytics-dashboard.php

if (!defined('ABSPATH')) exit;

$quiz_id = isset($_GET['quiz_id']) ? intval($_GET['quiz_id']) : null;
$analytics = new NexusLearn\Analytics\QuizAnalytics();
$stats = $analytics->get_quiz_statistics($quiz_id);
?>

<div class="wrap nl-analytics-wrap">
    <!-- Header and Filters -->
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

    <!-- Stats Cards -->
    <div class="nl-analytics-grid">
        <div class="nl-card">
            <h3><?php _e('Total Attempts', 'nexuslearn'); ?></h3>
            <div class="nl-card-value"><?php echo number_format($stats['total_attempts']); ?></div>
            <div class="nl-card-trend positive">↑ 12% vs last period</div>
        </div>
        
        <div class="nl-card">
            <h3><?php _e('Average Score', 'nexuslearn'); ?></h3>
            <div class="nl-card-value"><?php echo number_format($stats['average_score'], 1); ?>%</div>
            <div class="nl-card-trend positive">↑ 5% vs last period</div>
        </div>
        
        <div class="nl-card">
            <h3><?php _e('Completion Rate', 'nexuslearn'); ?></h3>
            <div class="nl-card-value"><?php echo number_format($stats['completion_rate'], 1); ?>%</div>
            <div class="nl-card-trend negative">↓ 3% vs last period</div>
        </div>

        <div class="nl-card">
            <h3><?php _e('Average Time', 'nexuslearn'); ?></h3>
            <div class="nl-card-value"><?php 
                $average_time = isset($stats['time_statistics']) && is_object($stats['time_statistics']) 
                    ? number_format($stats['time_statistics']->average_time / 60, 1) 
                    : '0.0';
                echo esc_html($average_time) . ' min';
            ?></div>
            <div class="nl-card-trend neutral">→ No change</div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="nl-data-visualization">
        <!-- First Row: Distribution Charts -->
        <div class="nl-chart-row">
            <div class="nl-chart-box">
                <h3><?php _e('Score Distribution', 'nexuslearn'); ?></h3>
                <div class="nl-chart-wrapper">
                    <canvas id="scoreDistribution"></canvas>
                </div>
            </div>

            <div class="nl-chart-box">
                <h3><?php _e('Question Analysis', 'nexuslearn'); ?></h3>
                <div class="nl-chart-wrapper">
                    <canvas id="questionAnalysis"></canvas>
                </div>
            </div>
        </div>

        <!-- Question Performance Table -->
        <div class="nl-table-section">
            <h3><?php _e('Question Performance', 'nexuslearn'); ?></h3>
            <div class="nl-table-wrapper">
                <table class="nl-data-table">
                    <thead>
                        <tr>
                            <th>Question</th>
                            <th>Total Attempts</th>
                            <th>Correct Answers</th>
                            <th>Success Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stats['question_analysis'] as $question): ?>
                            <tr>
                                <td><?php echo esc_html($question->question_text); ?></td>
                                <td><?php echo number_format($question->total_attempts); ?></td>
                                <td><?php echo number_format($question->correct_answers); ?></td>
                                <td><?php 
                                    $success_rate = $question->total_attempts > 0 
                                        ? ($question->correct_answers / $question->total_attempts) * 100 
                                        : 0;
                                    echo number_format($success_rate, 1) . '%'; 
                                ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.nl-data-visualization {
    margin-top: 20px;
}
.nl-chart-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 20px;
}
.nl-chart-box {
    background: #fff;
    padding: 20px;
    border-radius: 4px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}
.nl-chart-wrapper {
    height: 300px;
    position: relative;
}
.nl-table-section {
    background: #fff;
    padding: 20px;
    border-radius: 4px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    margin-top: 20px;
}
.nl-data-table {
    width: 100%;
    border-collapse: collapse;
}
.nl-data-table th,
.nl-data-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #e2e4e7;
}
.nl-data-table th {
    background-color: #f8f9fa;
    font-weight: 600;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Score Distribution Chart
    const scoreCtx = document.getElementById('scoreDistribution');
    if (scoreCtx) {
        new Chart(scoreCtx, {
            type: 'bar',
            data: {
                labels: ['0-10%', '11-20%', '21-30%', '31-40%', '41-50%', '51-60%', '61-70%', '71-80%', '81-90%', '91-100%'],
                datasets: [{
                    label: 'Number of Students',
                    data: [5, 8, 12, 15, 20, 25, 30, 25, 15, 10],
                    backgroundColor: '#2271b1'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }

    // Question Analysis Chart
    const analysisCtx = document.getElementById('questionAnalysis');
    if (analysisCtx) {
        new Chart(analysisCtx, {
            type: 'bar',
            data: {
                labels: ['Multiple Choice', 'True/False', 'Essay', 'Matching', 'Fill Blanks'],
                datasets: [{
                    label: 'Success Rate (%)',
                    data: [75, 85, 65, 70, 60],
                    backgroundColor: '#2271b1'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    }
});
</script>
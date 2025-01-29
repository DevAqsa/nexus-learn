<?php
namespace NexusLearn\Admin\Views;

class TrackingPage {
    public function render() {
        ?>
        <div class="wrap">
            <h1><?php _e('Progress Tracking', 'nexuslearn'); ?></h1>
            
            <div class="nl-tracking-stats">
                <div class="nl-stat-box">
                    <h3><?php _e('Course Completion', 'nexuslearn'); ?></h3>
                    <?php $this->render_completion_stats(); ?>
                </div>
                
                <div class="nl-stat-box">
                    <h3><?php _e('Quiz Performance', 'nexuslearn'); ?></h3>
                    <?php $this->render_quiz_stats(); ?>
                </div>
                
                <div class="nl-stat-box">
                    <h3><?php _e('Time Analysis', 'nexuslearn'); ?></h3>
                    <?php $this->render_time_stats(); ?>
                </div>
            </div>

            <div class="nl-analytics-dashboard">
                <h2><?php _e('Analytics Dashboard', 'nexuslearn'); ?></h2>
                
                <div class="nl-analytics-grid">
                    <!-- Student Progress Overview -->
                    <div class="nl-analytics-card nl-card-full">
                        <h3><?php _e('Student Progress Overview', 'nexuslearn'); ?></h3>
                        <div class="nl-chart-container">
                            <canvas id="studentProgressChart"></canvas>
                        </div>
                    </div>

                    <!-- Course Completion Distribution -->
                    <div class="nl-analytics-card">
                        <h3><?php _e('Completion Distribution', 'nexuslearn'); ?></h3>
                        <div class="nl-chart-container">
                            <canvas id="completionDistChart"></canvas>
                        </div>
                    </div>

                    <!-- Popular Courses -->
                    <div class="nl-analytics-card">
                        <h3><?php _e('Popular Courses', 'nexuslearn'); ?></h3>
                        <?php $this->render_popular_courses(); ?>
                    </div>

                    <!-- Weekly Activity -->
                    <div class="nl-analytics-card nl-card-full">
                        <h3><?php _e('Weekly Activity', 'nexuslearn'); ?></h3>
                        <div class="nl-chart-container">
                            <canvas id="weeklyActivityChart"></canvas>
                        </div>
                    </div>

                    <!-- Quiz Performance -->
                    <div class="nl-analytics-card">
                        <h3><?php _e('Quiz Performance Trends', 'nexuslearn'); ?></h3>
                        <div class="nl-chart-container">
                            <canvas id="quizTrendsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
        jQuery(document).ready(function($) {
            // Student Progress Chart
            const progressCtx = document.getElementById('studentProgressChart').getContext('2d');
            new Chart(progressCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Active Students',
                        data: [65, 75, 85, 95, 110, 120],
                        borderColor: '#2271b1',
                        tension: 0.4
                    }, {
                        label: 'Course Completions',
                        data: [10, 25, 35, 45, 60, 75],
                        borderColor: '#46b450',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Completion Distribution Chart
            const completionCtx = document.getElementById('completionDistChart').getContext('2d');
            new Chart(completionCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Completed', 'In Progress', 'Not Started'],
                    datasets: [{
                        data: [45, 35, 20],
                        backgroundColor: ['#46b450', '#ffb900', '#dc3232']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Weekly Activity Chart
            const activityCtx = document.getElementById('weeklyActivityChart').getContext('2d');
            new Chart(activityCtx, {
                type: 'bar',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Hours Spent',
                        data: [4.5, 5.2, 3.8, 4.9, 3.5, 2.1, 1.8],
                        backgroundColor: '#2271b1'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Quiz Trends Chart
            const quizCtx = document.getElementById('quizTrendsChart').getContext('2d');
            new Chart(quizCtx, {
                type: 'line',
                data: {
                    labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                    datasets: [{
                        label: 'Average Score',
                        data: [75, 82, 78, 85],
                        borderColor: '#2271b1',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },
                    scales: {
                        y: {
                            min: 0,
                            max: 100
                        }
                    }
                }
            });
        });
        </script>
        <?php
    }

    private function render_completion_stats() {
        global $wpdb;
        $progress_table = $wpdb->prefix . 'nl_progress';
        
        $stats = $wpdb->get_row("
            SELECT 
                COUNT(DISTINCT user_id) as total_students,
                COUNT(DISTINCT CASE WHEN completion_status = 'completed' THEN user_id END) as completed_students
            FROM $progress_table
        ");
        
        $total_students = $stats ? intval($stats->total_students) : 0;
        $completed_students = $stats ? intval($stats->completed_students) : 0;
        $completion_rate = $total_students > 0 ? round(($completed_students / $total_students) * 100, 1) : 0;
        
        ?>
        <div class="nl-stat">
            <span class="nl-stat-label">Completion Rate</span>
            <span class="nl-stat-value"><?php echo $completion_rate; ?>%</span>
        </div>
        <div class="nl-progress-bar">
            <div class="nl-progress-bar-fill" style="width: <?php echo $completion_rate; ?>%"></div>
        </div>
        <div class="nl-stat">
            <span class="nl-stat-label">Total Students</span>
            <span class="nl-stat-value"><?php echo $total_students; ?></span>
        </div>
        <div class="nl-stat">
            <span class="nl-stat-label">Completed</span>
            <span class="nl-stat-value"><?php echo $completed_students; ?></span>
        </div>
        <?php
    }

    private function render_quiz_stats() {
        global $wpdb;
        $attempts_table = $wpdb->prefix . 'nl_quiz_attempts';
        
        $stats = $wpdb->get_row("
            SELECT 
                COUNT(*) as total_attempts,
                AVG(score) as average_score,
                MAX(score) as highest_score,
                COUNT(DISTINCT user_id) as unique_students
            FROM $attempts_table
        ");
        
        $total_attempts = $stats ? intval($stats->total_attempts) : 0;
        $average_score = $stats ? round($stats->average_score, 1) : 0;
        $highest_score = $stats ? round($stats->highest_score, 1) : 0;
        
        ?>
        <div class="nl-stat">
            <span class="nl-stat-label">Average Score</span>
            <span class="nl-stat-value"><?php echo $average_score; ?>%</span>
        </div>
        <div class="nl-progress-bar">
            <div class="nl-progress-bar-fill" style="width: <?php echo $average_score; ?>%"></div>
        </div>
        <div class="nl-stat">
            <span class="nl-stat-label">Total Attempts</span>
            <span class="nl-stat-value"><?php echo $total_attempts; ?></span>
        </div>
        <div class="nl-stat">
            <span class="nl-stat-label">Highest Score</span>
            <span class="nl-stat-value"><?php echo $highest_score; ?>%</span>
        </div>
        <?php
    }

    private function render_time_stats() {
        global $wpdb;
        $progress_table = $wpdb->prefix . 'nl_progress';
        
        $stats = $wpdb->get_row("
            SELECT 
                COALESCE(SUM(time_spent), 0) as total_time,
                COALESCE(AVG(time_spent), 0) as avg_time,
                COUNT(DISTINCT user_id) as active_users
            FROM $progress_table
            WHERE last_accessed >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        ");
        
        $total_hours = round($stats->total_time / 3600, 1);
        $avg_minutes = round($stats->avg_time / 60, 1);
        $active_users = intval($stats->active_users);
        
        ?>
        <div class="nl-stat">
            <span class="nl-stat-label">Total Time Spent</span>
            <span class="nl-stat-value"><?php echo $total_hours; ?> hours</span>
        </div>
        <div class="nl-stat">
            <span class="nl-stat-label">Avg. Time per Lesson</span>
            <span class="nl-stat-value"><?php echo $avg_minutes; ?> min</span>
        </div>
        <div class="nl-stat">
            <span class="nl-stat-label">Active Users (30d)</span>
            <span class="nl-stat-value"><?php echo $active_users; ?></span>
        </div>
        <?php
    }

    private function render_popular_courses() {
        global $wpdb;
        $progress_table = $wpdb->prefix . 'nl_progress';
        
        $courses = $wpdb->get_results("
            SELECT 
                p.course_id,
                c.post_title as title,
                COUNT(DISTINCT p.user_id) as student_count,
                COUNT(CASE WHEN p.completion_status = 'completed' THEN 1 END) * 100.0 / COUNT(*) as completion_rate
            FROM {$progress_table} p
            JOIN {$wpdb->posts} c ON p.course_id = c.ID
            GROUP BY p.course_id, c.post_title
            ORDER BY student_count DESC
            LIMIT 5
        ");
        
        if ($courses): ?>
            <div class="nl-popular-courses">
                <?php foreach ($courses as $index => $course): ?>
                    <div class="nl-course-item">
                        <span class="nl-course-rank"><?php echo $index + 1; ?></span>
                        <div class="nl-course-info">
                            <h4><?php echo esc_html($course->title); ?></h4>
                            <div class="nl-course-stats">
                                <span><?php echo round($course->completion_rate, 1); ?>% completion</span>
                                <span><?php echo $course->student_count; ?> students</span>
                            </div>
                            <div class="nl-progress-bar">
                                <div class="nl-progress-bar-fill" style="width: <?php echo $course->completion_rate; ?>%"></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif;
    }
}
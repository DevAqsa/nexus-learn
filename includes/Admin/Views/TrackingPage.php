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
            
            <div class="nl-detailed-progress">
                <?php $this->render_detailed_progress(); ?>
            </div>
        </div>
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
                COUNT(DISTINCT user_id) as unique_students,
                MAX(score) as highest_score
            FROM $attempts_table
        ");
        
        $total_attempts = $stats ? intval($stats->total_attempts) : 0;
        $average_score = $stats ? round($stats->average_score, 1) : 0;
        $highest_score = $stats ? round($stats->highest_score, 1) : 0;
        $unique_students = $stats ? intval($stats->unique_students) : 0;
        
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

    private function render_detailed_progress() {
        global $wpdb;
        $progress_table = $wpdb->prefix . 'nl_progress';
        
        $results = $wpdb->get_results("
            SELECT 
                p.*,
                u.display_name as student_name,
                c.post_title as course_name,
                l.post_title as lesson_name
            FROM $progress_table p
            JOIN {$wpdb->users} u ON p.user_id = u.ID
            JOIN {$wpdb->posts} c ON p.course_id = c.ID
            JOIN {$wpdb->posts} l ON p.lesson_id = l.ID
            ORDER BY p.last_accessed DESC
            LIMIT 50
        ");
        
        if ($results): ?>
            <h3><?php _e('Recent Activity', 'nexuslearn'); ?></h3>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Course</th>
                        <th>Lesson</th>
                        <th>Status</th>
                        <th>Time Spent</th>
                        <th>Last Accessed</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $row): ?>
                        <tr>
                            <td><?php echo esc_html($row->student_name); ?></td>
                            <td><?php echo esc_html($row->course_name); ?></td>
                            <td><?php echo esc_html($row->lesson_name); ?></td>
                            <td>
                                <span class="nl-status-badge nl-status-<?php echo esc_attr($row->completion_status); ?>">
                                    <?php echo esc_html(ucfirst(str_replace('_', ' ', $row->completion_status))); ?>
                                </span>
                            </td>
                            <td><?php echo round($row->time_spent / 60, 1); ?> mins</td>
                            <td><?php echo esc_html(human_time_diff(strtotime($row->last_accessed)) . ' ago'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif;
    }
}
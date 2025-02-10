<?php
namespace NexusLearn\Frontend\Components;

class AttendanceManager {
    public function __construct() {
        add_action('wp_ajax_nl_get_attendance', [$this, 'get_attendance_data']);
    }

    public function render_attendance_section($user_id) {
        $attendance_data = $this->get_user_attendance($user_id);
        ob_start();
        ?>
        <div class="nl-attendance-section">
            <h2><?php _e('Attendance Overview', 'nexuslearn'); ?></h2>
            <?php if (!empty($attendance_data)): ?>
                <div class="nl-attendance-stats">
                    <div class="nl-stat-item">
                        <span class="nl-stat-value"><?php echo esc_html($attendance_data['present_percentage']); ?>%</span>
                        <span class="nl-stat-label"><?php _e('Present', 'nexuslearn'); ?></span>
                    </div>
                    <div class="nl-stat-item">
                        <span class="nl-stat-value"><?php echo esc_html($attendance_data['absent_count']); ?></span>
                        <span class="nl-stat-label"><?php _e('Absences', 'nexuslearn'); ?></span>
                    </div>
                    <div class="nl-stat-item">
                        <span class="nl-stat-value"><?php echo esc_html($attendance_data['late_count']); ?></span>
                        <span class="nl-stat-label"><?php _e('Late', 'nexuslearn'); ?></span>
                    </div>
                </div>

                <div class="nl-attendance-details">
                    <h3><?php _e('Recent Attendance', 'nexuslearn'); ?></h3>
                    <div class="nl-attendance-list">
                        <?php foreach ($attendance_data['recent'] as $record): ?>
                            <div class="nl-attendance-item">
                                <div class="nl-attendance-date">
                                    <?php echo date_i18n(get_option('date_format'), strtotime($record['date'])); ?>
                                </div>
                                <div class="nl-attendance-status <?php echo esc_attr($record['status']); ?>">
                                    <?php echo esc_html(ucfirst($record['status'])); ?>
                                </div>
                                <div class="nl-attendance-course">
                                    <?php echo esc_html($record['course_title']); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="nl-empty-state">
                    <div class="nl-empty-icon">📅</div>
                    <h3><?php _e('No Attendance Records', 'nexuslearn'); ?></h3>
                    <p><?php _e('Attendance records will appear here once you start attending courses.', 'nexuslearn'); ?></p>
                </div>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    // Changed from private to public
    public function get_user_attendance($user_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'nl_attendance';
        
        // For testing/development, return sample data
        return [
            'present_percentage' => 85,
            'absent_count' => 3,
            'late_count' => 2,
            'recent' => [
                [
                    'date' => date('Y-m-d'),
                    'status' => 'present',
                    'course_title' => 'Introduction to WordPress'
                ],
                [
                    'date' => date('Y-m-d', strtotime('-1 day')),
                    'status' => 'late',
                    'course_title' => 'Advanced PHP Development'
                ],
                [
                    'date' => date('Y-m-d', strtotime('-2 days')),
                    'status' => 'present',
                    'course_title' => 'Web Security Fundamentals'
                ]
            ]
        ];
    }

    public function get_attendance_data() {
        check_ajax_referer('nl_dashboard_nonce', 'nonce');
        $user_id = get_current_user_id();
        $attendance = $this->get_user_attendance($user_id);
        wp_send_json_success($attendance);
    }
}
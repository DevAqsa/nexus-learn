<?php
namespace NexusLearn\Frontend;

if (!defined('ABSPATH')) exit;

class StudentDashboard {
    public function __construct() {
        add_shortcode('nexuslearn_student_dashboard', [$this, 'render_dashboard']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public function enqueue_assets() {
        // Only load on dashboard page
        if (!is_page('dashboard')) return;

        wp_enqueue_style(
            'nl-student-dashboard',
            NEXUSLEARN_PLUGIN_URL . 'assets/css/student-dashboard.css',
            [],
            NEXUSLEARN_VERSION
        );

        wp_enqueue_script(
            'nl-dashboard-scripts',
            NEXUSLEARN_PLUGIN_URL . 'assets/js/dashboard.js',
            ['jquery', 'chartjs'],
            NEXUSLEARN_VERSION,
            true
        );

        // Add Chart.js
        wp_enqueue_script(
            'chartjs',
            'https://cdn.jsdelivr.net/npm/chart.js',
            [],
            '4.4.1',
            true
        );
    }

    public function render_dashboard() {
        if (!is_user_logged_in()) {
            return '<p>' . __('Please log in to view your dashboard.', 'nexuslearn') . '</p>';
        }

        $user_id = get_current_user_id();
        
        // Get dashboard data
        $data = [
            'courses' => $this->get_enrolled_courses($user_id),
            'recent_activities' => $this->get_recent_activities($user_id),
            'upcoming_assignments' => $this->get_upcoming_assignments($user_id),
            'progress_stats' => $this->get_progress_stats($user_id),
            'notifications' => $this->get_notifications($user_id)
        ];

        ob_start();
        include NEXUSLEARN_PLUGIN_DIR . 'templates/frontend/student-dashboard.php';
        return ob_get_clean();
    }

    private function get_enrolled_courses($user_id) {
        global $wpdb;
        
        $courses = $wpdb->get_results($wpdb->prepare(
            "SELECT DISTINCT c.*, 
                    p.completion_status,
                    p.last_accessed
            FROM {$wpdb->posts} c
            JOIN {$wpdb->prefix}nl_progress p ON c.ID = p.course_id
            WHERE p.user_id = %d
            AND c.post_type = 'nl_course'
            AND c.post_status = 'publish'
            ORDER BY p.last_accessed DESC",
            $user_id
        ));

        foreach ($courses as &$course) {
            $course->progress = $this->calculate_course_progress($user_id, $course->ID);
            $course->instructor = get_post_meta($course->ID, '_nl_course_instructor', true);
        }

        return $courses;
    }

    private function get_recent_activities($user_id) {
        global $wpdb;
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}nl_activity_log
            WHERE user_id = %d
            ORDER BY timestamp DESC
            LIMIT 5",
            $user_id
        ));
    }

    private function get_upcoming_assignments($user_id) {
        global $wpdb;
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}nl_assignments
            WHERE user_id = %d
            AND due_date > NOW()
            ORDER BY due_date ASC
            LIMIT 5",
            $user_id
        ));
    }

    private function get_progress_stats($user_id) {
        global $wpdb;
        
        $stats = $wpdb->get_row($wpdb->prepare(
            "SELECT 
                COUNT(DISTINCT course_id) as total_courses,
                COUNT(CASE WHEN completion_status = 'completed' THEN 1 END) as completed_courses,
                AVG(CASE WHEN completion_status = 'completed' THEN 100 
                     ELSE (time_spent / (SELECT MAX(time_spent) FROM {$wpdb->prefix}nl_progress)) * 100 
                END) as average_progress
            FROM {$wpdb->prefix}nl_progress
            WHERE user_id = %d",
            $user_id
        ));

        return [
            'total_courses' => $stats->total_courses ?? 0,
            'completed_courses' => $stats->completed_courses ?? 0,
            'average_progress' => round($stats->average_progress ?? 0, 2)
        ];
    }

    private function get_notifications($user_id) {
        global $wpdb;
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}nl_notifications
            WHERE user_id = %d
            AND is_read = 0
            ORDER BY created_at DESC
            LIMIT 5",
            $user_id
        ));
    }

    private function calculate_course_progress($user_id, $course_id) {
        global $wpdb;
        
        $progress = $wpdb->get_var($wpdb->prepare(
            "SELECT 
                (COUNT(CASE WHEN completion_status = 'completed' THEN 1 END) * 100.0 / COUNT(*)) as progress
            FROM {$wpdb->prefix}nl_progress
            WHERE user_id = %d AND course_id = %d",
            $user_id,
            $course_id
        ));

        return round($progress ?? 0, 2);
    }


    
}
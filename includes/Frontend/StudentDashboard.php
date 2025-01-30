<?php
namespace NexusLearn\Frontend;

if (!defined('ABSPATH')) exit;

class StudentDashboard {
    public function __construct() {
        add_shortcode('nexuslearn_student_dashboard', [$this, 'render_dashboard']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public function enqueue_assets() {
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
            ['jquery'],
            NEXUSLEARN_VERSION,
            true
        );
    }

    public function render_dashboard() {
        if (!is_user_logged_in()) {
            return '<p>Please log in to view your dashboard.</p>';
        }

        $user_id = wp_get_current_user()->ID; // Fixed: Using wp_get_current_user() instead of get_current_user()
        
        // Get dashboard data
        $data = [
            'total_projects' => $this->get_total_projects($user_id),
            'attendance_percentage' => $this->get_attendance($user_id),
            'marks_secured' => $this->get_marks($user_id),
            'leadership_rank' => $this->get_leadership_rank($user_id),
            'continue_watching' => $this->get_continue_watching($user_id),
            'assignments' => $this->get_assignments($user_id),
            'calendar_html' => $this->get_calendar_html($user_id),
        ];

        // Include the template
        ob_start();
        extract($data);
        include NEXUSLEARN_PLUGIN_DIR . 'templates/student-dashboard.php';
        return ob_get_clean();
    }

    private function get_total_projects($user_id) {
        // Implement your logic to get total projects
        return 20; // Example value
    }

    private function get_attendance($user_id) {
        // Implement your logic to calculate attendance
        return 70; // Example value
    }

    private function get_marks($user_id) {
        // Implement your logic to get marks
        return 600; // Example value
    }

    private function get_leadership_rank($user_id) {
        // Implement your logic to calculate rank
        return 3; // Example value
    }

    private function get_continue_watching($user_id) {
        // Implement your logic to get in-progress courses
        return [
            [
                'title' => "Beginner's Guide to Front-end Development",
                'thumbnail' => NEXUSLEARN_PLUGIN_URL . 'assets/images/course1.jpg',
                'instructor' => 'John Doe',
                'progress' => 65
            ],
            // Add more courses...
        ];
    }

    private function get_assignments($user_id) {
        // Implement your logic to get assignments
        return [
            [
                'title' => 'CSS Typography Test',
                'due_date' => '12/20/23',
                'status' => 'completed'
            ],
            // Add more assignments...
        ];
    }

    private function get_calendar_html($user_id) {
        // Implement calendar generation logic
        return '<div class="calendar">Calendar content here...</div>';
    }
}
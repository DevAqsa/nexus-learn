<?php
namespace NexusLearn\Analytics;

class QuizAnalytics {
    public function __construct() {
        add_action('admin_menu', [$this, 'add_analytics_menu']);
    }

    public function add_analytics_menu() {
        add_submenu_page(
            'edit.php?post_type=nl_course',
            __('Quiz Analytics', 'nexuslearn'),
            __('Quiz Analytics', 'nexuslearn'),
            'manage_options',
            'nl-quiz-analytics',
            [$this, 'render_analytics_page']
        );
    }

    public function render_analytics_page() {
        // Include the view file
        require_once NEXUSLEARN_PLUGIN_DIR . 'includes/Analytics/Views/analytics-dashboard.php';
    }

    public function get_quiz_statistics($quiz_id = null, $date_range = '30') {
        global $wpdb;
        
        $where_clause = $quiz_id ? $wpdb->prepare("WHERE quiz_id = %d", $quiz_id) : "";
        $date_limit = date('Y-m-d', strtotime("-{$date_range} days"));
        
        return [
            'total_attempts' => $this->get_total_attempts($quiz_id),
            'average_score' => $this->get_average_score($quiz_id),
            'completion_rate' => $this->get_completion_rate($quiz_id),
            'question_analysis' => $this->get_question_analysis($quiz_id),
            'time_statistics' => $this->get_time_statistics($quiz_id),
            'score_distribution' => $this->get_score_distribution($quiz_id)
        ];
    }

    private function get_total_attempts($quiz_id = null) {
        global $wpdb;
        $where = $quiz_id ? $wpdb->prepare("WHERE quiz_id = %d", $quiz_id) : "";
        return $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}nl_quiz_attempts {$where}");
    }

    private function get_average_score($quiz_id = null) {
        global $wpdb;
        $where = $quiz_id ? $wpdb->prepare("WHERE quiz_id = %d", $quiz_id) : "";
        return $wpdb->get_var("SELECT AVG(score) FROM {$wpdb->prefix}nl_quiz_attempts {$where}");
    }

    private function get_completion_rate($quiz_id = null) {
        global $wpdb;
        $where = $quiz_id ? $wpdb->prepare("WHERE quiz_id = %d", $quiz_id) : "";
        $total = $this->get_total_attempts($quiz_id);
        $completed = $wpdb->get_var(
            "SELECT COUNT(*) FROM {$wpdb->prefix}nl_quiz_attempts 
            {$where} AND status = 'completed'"
        );
        return $total > 0 ? ($completed / $total) * 100 : 0;
    }

    private function get_question_analysis($quiz_id = null) {
        global $wpdb;
        $where = $quiz_id ? $wpdb->prepare("WHERE qa.quiz_id = %d", $quiz_id) : "";
        
        $results = $wpdb->get_results(
            "SELECT 
                q.id,
                q.question_text,
                COUNT(qa.id) as total_attempts,
                SUM(CASE WHEN qa.is_correct = 1 THEN 1 ELSE 0 END) as correct_answers
            FROM {$wpdb->prefix}nl_quiz_questions q
            LEFT JOIN {$wpdb->prefix}nl_quiz_answers qa ON q.id = qa.question_id
            {$where}
            GROUP BY q.id"
        );

        return $results ?: [];
    }

    private function get_time_statistics($quiz_id = null) {
        global $wpdb;
        $where = $quiz_id ? $wpdb->prepare("WHERE quiz_id = %d", $quiz_id) : "";
        
        $stats = $wpdb->get_row(
            "SELECT 
                AVG(time_taken) as average_time,
                MIN(time_taken) as min_time,
                MAX(time_taken) as max_time
            FROM {$wpdb->prefix}nl_quiz_attempts
            {$where}"
        );
    
        // Return default object if no stats found
        if (!$stats) {
            return (object)[
                'average_time' => 0,
                'min_time' => 0,
                'max_time' => 0
            ];
        }
    
        return $stats;
    }

    private function get_score_distribution($quiz_id = null) {
        global $wpdb;
        $where = $quiz_id ? $wpdb->prepare("WHERE quiz_id = %d", $quiz_id) : "";
        
        $results = $wpdb->get_results(
            "SELECT 
                FLOOR(score/10)*10 as score_range,
                COUNT(*) as count
            FROM {$wpdb->prefix}nl_quiz_attempts
            {$where}
            GROUP BY FLOOR(score/10)
            ORDER BY score_range"
        );

        return $results ?: [];
    }
}
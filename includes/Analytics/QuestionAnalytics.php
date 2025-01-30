<?php
// File: includes/Analytics/QuestionAnalytics.php
namespace NexusLearn\Analytics;

class QuestionAnalytics {
    private $wpdb;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    public function get_question_performance($quiz_id = null) {
        $where_clause = $quiz_id ? $this->wpdb->prepare("WHERE qa.quiz_id = %d", $quiz_id) : "";
        
        $query = "
            SELECT 
                q.id,
                q.question_text,
                COUNT(DISTINCT qa.attempt_id) as total_attempts,
                SUM(CASE WHEN qa.is_correct = 1 THEN 1 ELSE 0 END) as correct_answers,
                (SUM(CASE WHEN qa.is_correct = 1 THEN 1 ELSE 0 END) * 100.0 / COUNT(DISTINCT qa.attempt_id)) as success_rate,
                AVG(qa.time_taken) as avg_time_taken
            FROM {$this->wpdb->prefix}nl_quiz_questions q
            LEFT JOIN {$this->wpdb->prefix}nl_quiz_answers qa ON q.id = qa.question_id
            {$where_clause}
            GROUP BY q.id, q.question_text
            ORDER BY success_rate DESC";

        return $this->wpdb->get_results($query);
    }

    public function get_question_analysis_by_type($quiz_id = null) {
        $where_clause = $quiz_id ? $this->wpdb->prepare("WHERE qa.quiz_id = %d", $quiz_id) : "";
        
        $query = "
            SELECT 
                q.question_type,
                COUNT(DISTINCT q.id) as total_questions,
                AVG(CASE WHEN qa.is_correct = 1 THEN 1 ELSE 0 END) * 100 as avg_success_rate
            FROM {$this->wpdb->prefix}nl_quiz_questions q
            LEFT JOIN {$this->wpdb->prefix}nl_quiz_answers qa ON q.id = qa.question_id
            {$where_clause}
            GROUP BY q.question_type
            ORDER BY avg_success_rate DESC";

        return $this->wpdb->get_results($query);
    }

    public function get_time_distribution($quiz_id = null) {
        $where_clause = $quiz_id ? $this->wpdb->prepare("WHERE quiz_id = %d", $quiz_id) : "";
        
        $query = "
            SELECT 
                FLOOR(time_taken/30) * 30 as time_range_start,
                COUNT(*) as count
            FROM {$this->wpdb->prefix}nl_quiz_answers
            {$where_clause}
            GROUP BY FLOOR(time_taken/30)
            ORDER BY time_range_start";

        return $this->wpdb->get_results($query);
    }
}
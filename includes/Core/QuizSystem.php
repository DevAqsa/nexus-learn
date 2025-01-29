<?php
namespace NexusLearn\Core;

class QuizSystem {
    public function __construct() {
        add_action('init', [$this, 'register_quiz_post_type']);
        register_activation_hook(NEXUSLEARN_PLUGIN_DIR . 'nexuslearn.php', [$this, 'create_quiz_tables']);
    }

    public function register_quiz_post_type() {
        register_post_type('nl_quiz', [
            'labels' => [
                'name' => __('Quizzes', 'nexuslearn'),
                'singular_name' => __('Quiz', 'nexuslearn'),
                'add_new' => __('Add New Quiz', 'nexuslearn'),
                'add_new_item' => __('Add New Quiz', 'nexuslearn'),
                'edit_item' => __('Edit Quiz', 'nexuslearn'),
                'view_item' => __('View Quiz', 'nexuslearn'),
            ],
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-clipboard',
            'supports' => ['title', 'editor'],
            'show_in_menu' => 'edit.php?post_type=nl_course',
        ]);
    }

    public function create_quiz_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $sql = [];

        // Questions table
        $sql[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}nl_quiz_questions (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            quiz_id bigint(20) NOT NULL,
            question_type varchar(20) NOT NULL,
            question_text text NOT NULL,
            question_options longtext,
            correct_answer text,
            points int DEFAULT 1,
            order_index int DEFAULT 0,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY quiz_id (quiz_id)
        ) $charset_collate;";

        // Attempts table
        $sql[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}nl_quiz_attempts (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            quiz_id bigint(20) NOT NULL,
            user_id bigint(20) NOT NULL,
            start_time datetime DEFAULT CURRENT_TIMESTAMP,
            end_time datetime,
            score float DEFAULT 0,
            max_score float DEFAULT 0,
            status varchar(20) DEFAULT 'in_progress',
            PRIMARY KEY (id),
            KEY quiz_user (quiz_id,user_id)
        ) $charset_collate;";

        // Answers table
        $sql[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}nl_quiz_answers (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            attempt_id bigint(20) NOT NULL,
            question_id bigint(20) NOT NULL,
            answer_text text,
            is_correct tinyint(1) DEFAULT 0,
            points_earned float DEFAULT 0,
            PRIMARY KEY (id),
            KEY attempt_id (attempt_id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        foreach ($sql as $query) {
            dbDelta($query);
        }
    }
}
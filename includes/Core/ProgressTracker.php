<?php
namespace NexusLearn\Core;

class ProgressTracker {
    public function __construct() {
        add_action('init', [$this, 'init']);
        register_activation_hook(NEXUSLEARN_PLUGIN_DIR . 'nexuslearn.php', [$this, 'create_tables']);
    }

    public function init() {
        if (get_option('nl_progress_tables_version') !== NEXUSLEARN_VERSION) {
            $this->create_tables();
        }
    }

    public function create_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $sql = [];

        $sql[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}nl_progress (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            course_id bigint(20) NOT NULL,
            lesson_id bigint(20) NOT NULL,
            completion_status varchar(20) DEFAULT 'incomplete',
            time_spent int DEFAULT 0,
            last_accessed datetime DEFAULT CURRENT_TIMESTAMP,
            completed_at datetime DEFAULT NULL,
            PRIMARY KEY  (id),
            KEY user_course (user_id,course_id)
        ) $charset_collate;";

        $sql[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}nl_quiz_attempts (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            quiz_id bigint(20) NOT NULL,
            score float DEFAULT 0,
            time_spent int DEFAULT 0,
            status varchar(20) DEFAULT 'incomplete',
            attempt_date datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY user_quiz (user_id,quiz_id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        foreach ($sql as $query) {
            dbDelta($query);
        }

        update_option('nl_progress_tables_version', NEXUSLEARN_VERSION);
    }
}
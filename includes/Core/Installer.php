<?php
namespace NexusLearn\Core;

class Installer {
    public static function activate() {
        self::create_tables();
    }

    private static function create_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();

        $schema = [
            // Activity Log table
            "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}nl_activity_log (
                id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                user_id BIGINT(20) UNSIGNED NOT NULL,
                course_id BIGINT(20) UNSIGNED DEFAULT NULL,
                type VARCHAR(50) NOT NULL,
                description TEXT NOT NULL,
                timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                KEY user_id (user_id),
                KEY course_id (course_id)
            ) $charset_collate;",

            // Progress table (if not already created)
            "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}nl_progress (
                id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                user_id BIGINT(20) UNSIGNED NOT NULL,
                course_id BIGINT(20) UNSIGNED NOT NULL,
                lesson_id BIGINT(20) UNSIGNED NOT NULL,
                status VARCHAR(20) DEFAULT 'incomplete',
                completion_date DATETIME DEFAULT NULL,
                last_accessed DATETIME DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                KEY user_id (user_id),
                KEY course_id (course_id),
                KEY lesson_id (lesson_id)
            ) $charset_collate;"
        ];

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        foreach ($schema as $query) {
            dbDelta($query);
        }
    }
}
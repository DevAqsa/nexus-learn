<?php
namespace NexusLearn\Core;

class Activator {
    public static function activate() {
        self::create_tables();
    }

    private static function create_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}nl_activity_log (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            course_id bigint(20) NOT NULL,
            activity_type varchar(50) NOT NULL,
            description text NOT NULL,
            timestamp datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY user_id (user_id),
            KEY course_id (course_id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}

public static function create_tables() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    // Certificates table
    $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}nexuslearn_certificates (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        user_id bigint(20) NOT NULL,
        course_id bigint(20) NOT NULL,
        title varchar(255) NOT NULL,
        completion_date datetime DEFAULT CURRENT_TIMESTAMP,
        certificate_url varchar(255),
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

global $wpdb;
$charset_collate = $wpdb->get_charset_collate();

$sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}nl_contact_log (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id BIGINT(20) UNSIGNED NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    status VARCHAR(20) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) $charset_collate;";

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($sql);

class Activator {
    public static function activate() {
        global $wpdb;
        
        $sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}nexuslearn_todo_items` (
            `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `user_id` bigint(20) unsigned NOT NULL,
            `course_id` bigint(20) unsigned NOT NULL,
            `title` varchar(255) NOT NULL,
            `description` text,
            `type` varchar(20) NOT NULL,
            `due_date` datetime NOT NULL,
            `status` varchar(20) DEFAULT 'pending',
            `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
            `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `user_id` (`user_id`),
            KEY `course_id` (`course_id`),
            KEY `due_date` (`due_date`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
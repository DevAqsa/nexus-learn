public static function create_dashboard_tables() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    // Activity Log table
    $sql[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}nl_activity_log (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        user_id bigint(20) NOT NULL,
        activity_type varchar(50) NOT NULL,
        description text NOT NULL,
        timestamp datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY user_id (user_id)
    ) $charset_collate;";

    // Notifications table
    $sql[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}nl_notifications (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        user_id bigint(20) NOT NULL,
        title varchar(255) NOT NULL,
        message text NOT NULL,
        type varchar(50) DEFAULT 'info',
        is_read tinyint(1) DEFAULT 0,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY user_id (user_id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    foreach ($sql as $query) {
        dbDelta($query);
    }
}
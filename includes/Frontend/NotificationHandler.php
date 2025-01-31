<?php
namespace NexusLearn\Frontend;

class NotificationHandler {
    public function __construct() {
        add_action('wp_ajax_nl_mark_notification_read', [$this, 'mark_notification_read']);
    }

    public function mark_notification_read() {
        check_ajax_referer('nl_dashboard_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error('Not logged in');
        }

        $notification_id = intval($_POST['notification_id']);
        global $wpdb;
        
        $result = $wpdb->update(
            $wpdb->prefix . 'nl_notifications',
            ['is_read' => 1],
            ['id' => $notification_id, 'user_id' => get_current_user_id()],
            ['%d'],
            ['%d', '%d']
        );

        if ($result) {
            wp_send_json_success();
        } else {
            wp_send_json_error('Update failed');
        }
    }

    public function add_notification($user_id, $title, $message, $type = 'info') {
        global $wpdb;
        
        return $wpdb->insert(
            $wpdb->prefix . 'nl_notifications',
            [
                'user_id' => $user_id,
                'title' => $title,
                'message' => $message,
                'type' => $type
            ],
            ['%d', '%s', '%s', '%s']
        );
    }
}
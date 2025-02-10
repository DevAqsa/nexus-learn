<?php
namespace NexusLearn\Frontend\Components;

class NotesManager {
    public function __construct() {
        add_action('wp_ajax_nl_save_note', [$this, 'save_note']);
        add_action('wp_ajax_nl_get_notes', [$this, 'get_notes']);
        add_action('wp_ajax_nl_get_course_lessons', [$this, 'get_course_lessons']);
    }

    public function save_note() {
        check_ajax_referer('nl_dashboard_nonce', 'nonce');
        
        if (empty($_POST['title']) || empty($_POST['content'])) {
            wp_send_json_error(['message' => 'Title and content are required']);
            return;
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'nexuslearn_notes';

        // Create the table if it doesn't exist
        $this->create_notes_table();

        $data = [
            'user_id' => get_current_user_id(),
            'title' => sanitize_text_field($_POST['title']),
            'content' => wp_kses_post($_POST['content']),
            'course_id' => !empty($_POST['course_id']) ? intval($_POST['course_id']) : null,
            'lesson_id' => !empty($_POST['lesson_id']) ? intval($_POST['lesson_id']) : null,
            'created_at' => current_time('mysql')
        ];

        $result = $wpdb->insert($table_name, $data);

        if ($result === false) {
            wp_send_json_error([
                'message' => 'Database error: ' . $wpdb->last_error,
                'sql' => $wpdb->last_query
            ]);
            return;
        }

        wp_send_json_success([
            'message' => 'Note saved successfully',
            'note_id' => $wpdb->insert_id
        ]);
    }

    private function create_notes_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'nexuslearn_notes';
        
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $charset_collate = $wpdb->get_charset_collate();
            
            $sql = "CREATE TABLE $table_name (
                id bigint(20) NOT NULL AUTO_INCREMENT,
                user_id bigint(20) NOT NULL,
                title varchar(255) NOT NULL,
                content longtext NOT NULL,
                course_id bigint(20) DEFAULT NULL,
                lesson_id bigint(20) DEFAULT NULL,
                created_at datetime DEFAULT CURRENT_TIMESTAMP,
                updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY  (id),
                KEY user_id (user_id),
                KEY course_id (course_id),
                KEY lesson_id (lesson_id)
            ) $charset_collate;";
            
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    }

    public function get_notes() {
        check_ajax_referer('nl_dashboard_nonce', 'nonce');
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'nexuslearn_notes';
        $user_id = get_current_user_id();
        
        // Create table if it doesn't exist
        $this->create_notes_table();
        
        $query = $wpdb->prepare(
            "SELECT n.*, c.post_title as course_title 
            FROM {$table_name} n 
            LEFT JOIN {$wpdb->posts} c ON n.course_id = c.ID 
            WHERE n.user_id = %d 
            ORDER BY n.created_at DESC",
            $user_id
        );
        
        $notes = $wpdb->get_results($query);
        wp_send_json_success($notes);
    }

    public function get_course_lessons() {
        check_ajax_referer('nl_dashboard_nonce', 'nonce');
        
        $course_id = isset($_POST['course_id']) ? intval($_POST['course_id']) : 0;
        if (!$course_id) {
            wp_send_json_error(['message' => 'Invalid course ID']);
            return;
        }
        
        $lessons = get_posts([
            'post_type' => 'nl_lesson',
            'post_parent' => $course_id,
            'posts_per_page' => -1,
            'orderby' => 'menu_order',
            'order' => 'ASC'
        ]);
        
        $formatted_lessons = array_map(function($lesson) {
            return [
                'id' => $lesson->ID,
                'title' => $lesson->post_title
            ];
        }, $lessons);
        
        wp_send_json_success($formatted_lessons);
    }


    
}
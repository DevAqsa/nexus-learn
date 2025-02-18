<?php
namespace NexusLearn\API;

class CommentsController {
    public function __construct() {
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes() {
        register_rest_route('nexuslearn/v1', '/lessons/(?P<id>\d+)/comments', [
            [
                'methods' => 'GET',
                'callback' => [$this, 'get_comments'],
                'permission_callback' => 'is_user_logged_in'
            ],
            [
                'methods' => 'POST',
                'callback' => [$this, 'add_comment'],
                'permission_callback' => 'is_user_logged_in'
            ]
        ]);
    }

    public function get_comments($request) {
        $lesson_id = $request['id'];
        
        global $wpdb;
        $comments = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}nl_comments 
            WHERE lesson_id = %d 
            ORDER BY created_at DESC",
            $lesson_id
        ));
        
        return rest_ensure_response($comments);
    }

    public function add_comment($request) {
        $lesson_id = $request['id'];
        $user_id = get_current_user_id();
        $content = sanitize_text_field($request->get_param('content'));
        
        global $wpdb;
        $result = $wpdb->insert(
            $wpdb->prefix . 'nl_comments',
            [
                'lesson_id' => $lesson_id,
                'user_id' => $user_id,
                'content' => $content,
                'created_at' => current_time('mysql')
            ],
            ['%d', '%d', '%s', '%s']
        );
        
        if ($result === false) {
            return new WP_Error('comment_error', 'Error saving comment');
        }
        
        return rest_ensure_response([
            'success' => true,
            'comment_id' => $wpdb->insert_id
        ]);
    }
}
<?php
namespace NexusLearn\Admin;

class QuizList {
    public function __construct() {
        add_filter('manage_nl_quiz_posts_columns', [$this, 'add_custom_columns']);
        add_action('manage_nl_quiz_posts_custom_column', [$this, 'render_custom_columns'], 10, 2);
        add_filter('manage_edit-nl_quiz_sortable_columns', [$this, 'make_custom_columns_sortable']);
    }

    public function add_custom_columns($columns) {
        $new_columns = [];
        foreach ($columns as $key => $value) {
            if ($key === 'date') {
                $new_columns['questions'] = __('Questions', 'nexuslearn');
                $new_columns['time_limit'] = __('Time Limit', 'nexuslearn');
                $new_columns['passing_score'] = __('Passing Score', 'nexuslearn');
            }
            $new_columns[$key] = $value;
        }
        return $new_columns;
    }

    public function render_custom_columns($column, $post_id) {
        global $wpdb;
        
        switch ($column) {
            case 'questions':
                $count = $wpdb->get_var($wpdb->prepare(
                    "SELECT COUNT(*) FROM {$wpdb->prefix}nl_quiz_questions WHERE quiz_id = %d",
                    $post_id
                ));
                echo $count;
                break;

            case 'time_limit':
                $settings = get_post_meta($post_id, '_quiz_settings', true);
                echo !empty($settings['time_limit']) ? $settings['time_limit'] . ' min' : '—';
                break;

            case 'passing_score':
                $settings = get_post_meta($post_id, '_quiz_settings', true);
                echo !empty($settings['passing_score']) ? $settings['passing_score'] . '%' : '70%';
                break;
        }
    }

    public function make_custom_columns_sortable($columns) {
        $columns['questions'] = 'questions';
        $columns['time_limit'] = 'time_limit';
        $columns['passing_score'] = 'passing_score';
        return $columns;
    }
}
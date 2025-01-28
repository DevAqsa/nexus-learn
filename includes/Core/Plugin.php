<?php

namespace NexusLearn\Core;

class Plugin {
    public function __construct() {
        add_action('init', [$this, 'register_post_types']);
        add_action('admin_menu', [$this, 'add_admin_menu']);
    }

    public function register_post_types() {
        register_post_type('nl_course', [
            'labels' => [
                'name' => __('Courses', 'nexuslearn'),
                'singular_name' => __('Course', 'nexuslearn')
            ],
            'public' => true,
            'has_archive' => true,
            'supports' => ['title', 'editor', 'thumbnail'],
            'menu_icon' => 'dashicons-welcome-learn-more'
        ]);
    }

    public function add_admin_menu() {
        add_menu_page(
            __('NexusLearn', 'nexuslearn'),
            __('NexusLearn', 'nexuslearn'),
            'manage_options',
            'nexuslearn',
            [$this, 'render_admin_page'],
            'dashicons-welcome-learn-more'
        );
    }

    public function render_admin_page() {
        require_once NEXUSLEARN_PLUGIN_DIR . 'templates/admin/dashboard.php';
    }
}
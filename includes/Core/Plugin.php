<?php

namespace NexusLearn\Core;

class Plugin {
    public function __construct() {
        // Initialize taxonomies
        new Taxonomies();
        
        // Initialize admin menus
        if (is_admin()) {
            new \NexusLearn\Admin\MenuManager();
        }

        add_action('init', [$this, 'register_post_types']);
    }

    public function register_post_types() {
        // Register Course Post Type
        register_post_type('nl_course', [
            'labels' => [
                'name' => __('Courses', 'nexuslearn'),
                'singular_name' => __('Course', 'nexuslearn'),
                'menu_name' => __('Courses', 'nexuslearn'),
                'add_new' => __('Add New Course', 'nexuslearn'),
                'add_new_item' => __('Add New Course', 'nexuslearn'),
                'edit_item' => __('Edit Course', 'nexuslearn'),
            ],
            'public' => true,
            'has_archive' => true,
            'supports' => ['title', 'editor', 'thumbnail'],
            'menu_icon' => 'dashicons-welcome-learn-more',
            'show_in_menu' => true,
        ]);

        // Register Lesson Post Type
        register_post_type('nl_lesson', [
            'labels' => [
                'name' => __('Lessons', 'nexuslearn'),
                'singular_name' => __('Lesson', 'nexuslearn'),
            ],
            'public' => true,
            'has_archive' => true,
            'supports' => ['title', 'editor'],
            'show_in_menu' => 'edit.php?post_type=nl_course',
        ]);
    }
}
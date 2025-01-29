<?php

namespace NexusLearn\Core;

class PostTypes {
    public function __construct() {
        add_action('init', [$this, 'register_post_types']);
    }

    public function register_post_types() {
        // Register Course Post Type
        register_post_type('nl_course', [
            'labels' => [
                'name' => __('Courses', 'nexuslearn'),
                'singular_name' => __('Course', 'nexuslearn'),
                'add_new' => __('Add New', 'nexuslearn'),
                'add_new_item' => __('Add New Course', 'nexuslearn'),
                'edit_item' => __('Edit Course', 'nexuslearn'),
                'view_item' => __('View Course', 'nexuslearn'),
            ],
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-welcome-learn-more',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
            'show_in_menu' => true,
            'menu_position' => 5,
        ]);

        // Register Lesson Post Type
        register_post_type('nl_lesson', [
            'labels' => [
                'name' => __('Lessons', 'nexuslearn'),
                'singular_name' => __('Lesson', 'nexuslearn'),
                'add_new' => __('Add New', 'nexuslearn'),
                'add_new_item' => __('Add New Lesson', 'nexuslearn'),
                'edit_item' => __('Edit Lesson', 'nexuslearn'),
                'view_item' => __('View Lesson', 'nexuslearn'),
            ],
            'public' => true,
            'has_archive' => true,
            'supports' => ['title', 'editor', 'thumbnail'],
            'show_in_menu' => 'edit.php?post_type=nl_course',
        ]);
    }
}
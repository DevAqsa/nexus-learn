<?php

namespace NexusLearn\Core;

class Taxonomies {
    public function __construct() {
        add_action('init', [$this, 'register_taxonomies']);
    }

    public function register_taxonomies() {
        // Register Course Category
        register_taxonomy('course_category', 'nl_course', [
            'labels' => [
                'name' => __('Categories', 'nexuslearn'),
                'singular_name' => __('Category', 'nexuslearn'),
            ],
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'course-category'],
        ]);

        // Register Course Difficulty
        register_taxonomy('course_difficulty', 'nl_course', [
            'labels' => [
                'name' => __('Difficulties', 'nexuslearn'),
                'singular_name' => __('Difficulty', 'nexuslearn'),
            ],
            'hierarchical' => false,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'course-difficulty'],
        ]);

        // Register Course Tags
        register_taxonomy('course_tag', 'nl_course', [
            'labels' => [
                'name' => __('Tags', 'nexuslearn'),
                'singular_name' => __('Tag', 'nexuslearn'),
            ],
            'hierarchical' => false,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'course-tag'],
        ]);

        // Register Course Tracks
        register_taxonomy('course_track', 'nl_course', [
            'labels' => [
                'name' => __('Tracks', 'nexuslearn'),
                'singular_name' => __('Track', 'nexuslearn'),
            ],
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'course-track'],
        ]);
    }
}
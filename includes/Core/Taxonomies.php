<?php

namespace NexusLearn\Core;

class Taxonomies {
    public function __construct() {
        add_action('init', [$this, 'register_taxonomies']);
    }

    public function register_taxonomies() {
        // Register Categories Taxonomy
        register_taxonomy('course_category', 'nl_course', [
            'labels' => [
                'name' => __('Categories', 'nexuslearn'),
                'singular_name' => __('Category', 'nexuslearn'),
                'search_items' => __('Search Categories', 'nexuslearn'),
                'all_items' => __('All Categories', 'nexuslearn'),
                'edit_item' => __('Edit Category', 'nexuslearn'),
                'update_item' => __('Update Category', 'nexuslearn'),
                'add_new_item' => __('Add New Category', 'nexuslearn'),
                'new_item_name' => __('New Category Name', 'nexuslearn'),
                'menu_name' => __('Categories', 'nexuslearn'),
            ],
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'course-category'],
        ]);

        // Register Tags Taxonomy
        register_taxonomy('course_tag', 'nl_course', [
            'labels' => [
                'name' => __('Tags', 'nexuslearn'),
                'singular_name' => __('Tag', 'nexuslearn'),
                'search_items' => __('Search Tags', 'nexuslearn'),
                'all_items' => __('All Tags', 'nexuslearn'),
                'edit_item' => __('Edit Tag', 'nexuslearn'),
                'update_item' => __('Update Tag', 'nexuslearn'),
                'add_new_item' => __('Add New Tag', 'nexuslearn'),
                'new_item_name' => __('New Tag Name', 'nexuslearn'),
                'menu_name' => __('Tags', 'nexuslearn'),
            ],
            'hierarchical' => false,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'course-tag'],
        ]);

        // Register Categories Taxonomy for tracks
        register_taxonomy('track_category', 'nl_track', [
            'labels' => [
                'name' => __('Categories', 'nexuslearn'),
                'singular_name' => __('Category', 'nexuslearn'),
                'search_items' => __('Search Categories', 'nexuslearn'),
                'all_items' => __('All Categories', 'nexuslearn'),
                'edit_item' => __('Edit Category', 'nexuslearn'),
                'update_item' => __('Update Category', 'nexuslearn'),
                'add_new_item' => __('Add New Category', 'nexuslearn'),
                'new_item_name' => __('New Category Name', 'nexuslearn'),
                'menu_name' => __('Categories', 'nexuslearn'),
            ],
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'track-category'],
        ]);


        // Register Categories Taxonomy for difficulties
        register_taxonomy('difficulty_category', 'nl_difficulty', [
            'labels' => [
                'name' => __('Categories', 'nexuslearn'),
                'singular_name' => __('Category', 'nexuslearn'),
                'search_items' => __('Search Categories', 'nexuslearn'),
                'all_items' => __('All Categories', 'nexuslearn'),
                'edit_item' => __('Edit Category', 'nexuslearn'),
                'update_item' => __('Update Category', 'nexuslearn'),
                'add_new_item' => __('Add New Category', 'nexuslearn'),
                'new_item_name' => __('New Category Name', 'nexuslearn'),
                'menu_name' => __('Categories', 'nexuslearn'),
            ],
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'difficulty-category'],
        ]);

        



    }
}
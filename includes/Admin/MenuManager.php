<?php

namespace NexusLearn\Admin;

class MenuManager {
    public function __construct() {
        add_action('admin_menu', [$this, 'register_submenus']);
    }

    public function register_submenus() {
        // Main Courses menu (already registered as post type)
        // add_submenu_page(
        //     'edit.php?post_type=nl_course',
        //     __('Add New Course', 'nexuslearn'),
        //     __('Add New Course', 'nexuslearn'),
        //     'manage_options',
        //     'post-new.php?post_type=nl_course'
        // );

        // Categories
        // add_submenu_page(
        //     'edit.php?post_type=nl_course',
        //     __('Categories', 'nexuslearn'),
        //     __('Categories', 'nexuslearn'),
        //     'manage_options',
        //     'edit-tags.php?taxonomy=course_category&post_type=nl_course'
        // );

        // Tags
        // add_submenu_page(
        //     'edit.php?post_type=nl_course',
        //     __('Tags', 'nexuslearn'),
        //     __('Tags', 'nexuslearn'),
        //     'manage_options',
        //     'edit-tags.php?taxonomy=course_tag&post_type=nl_course'
        // );

        // Lessons
        // add_submenu_page(
        //     'edit.php?post_type=nl_course',
        //     __('Lessons', 'nexuslearn'),
        //     __('Lessons', 'nexuslearn'),
        //     'manage_options',
        //     'edit.php?post_type=nl_lesson'
        // );

        // Tracks
        // add_submenu_page(
        //     'edit.php?post_type=nl_course',
        //     __('Tracks', 'nexuslearn'),
        //     __('Tracks', 'nexuslearn'),
        //     'manage_options',
        //     'edit.php?post_type=nl_track'
        // );

       
add_submenu_page(
    'edit.php?post_type=nl_course',
    __('Progress Tracking', 'nexuslearn'),
    __('Progress Tracking', 'nexuslearn'),
    'manage_options',
    'nl-progress-tracking',
    [new \NexusLearn\Admin\Views\TrackingPage(), 'render']
);

        //difficulties
        add_submenu_page(
            'edit.php?post_type=nl_course',
            __('Difficulties', 'nexuslearn'),
            __('Difficulties', 'nexuslearn'),
            'manage_options',
            'edit-tags.php?taxonomy=course_difficulty&post_type=nl_course'
        );
    }
}
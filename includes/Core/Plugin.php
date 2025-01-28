<?php
namespace NexusLearn\Core;

use NexusLearn\Admin\Settings;
use NexusLearn\Admin\GeneralSettings;
use NexusLearn\Admin\EmailSettings;
use NexusLearn\Admin\CourseSettings;
use NexusLearn\Admin\QuizSettings;
use NexusLearn\Admin\CertificateSettings;

class Plugin {
    private $settings;

    public function __construct() {
        add_action('init', [$this, 'register_post_types']);
        add_action('admin_menu', [$this, 'add_admin_menu']);
        
        if (is_admin()) {
            $this->init_admin();
        }
    }

    private function init_admin() {
        // Initialize all settings
        $this->settings = new Settings();
        new GeneralSettings();
        new EmailSettings();
        new CourseSettings();
        new QuizSettings();
        new CertificateSettings();
    }

    public function add_admin_menu() {
        add_menu_page(
            __('NexusLearn', 'nexuslearn'),
            __('NexusLearn', 'nexuslearn'),
            'manage_options',
            'nexuslearn',
            [$this, 'render_admin_dashboard'],
            'dashicons-welcome-learn-more',
            5
        );

        add_submenu_page(
            'nexuslearn',
            __('Settings', 'nexuslearn'),
            __('Settings', 'nexuslearn'),
            'manage_options',
            'nexuslearn-settings',
            [$this->settings, 'render_page']
        );
    }

    public function render_admin_dashboard() {
        require_once NEXUSLEARN_PLUGIN_DIR . 'templates/admin/dashboard.php';
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
            'menu_icon' => 'dashicons-welcome-learn-more',
            'show_in_rest' => true
        ]);
    }
}
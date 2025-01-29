<?php
/**
 * Plugin Name: NexusLearn LMS
 * Description: Advanced Learning Management System for course creation, progress tracking, and quizzes
 * Version: 1.0.0
 * Author: Aqsa Mumtaz
 * Text Domain: nexuslearn
 */

defined('ABSPATH') || exit;

// Plugin Constants
define('NEXUSLEARN_VERSION', '1.0.0');
define('NEXUSLEARN_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('NEXUSLEARN_PLUGIN_URL', plugin_dir_url(__FILE__));



require_once NEXUSLEARN_PLUGIN_DIR . 'includes/Core/Plugin.php';
require_once NEXUSLEARN_PLUGIN_DIR . 'includes/Admin/CourseManager.php';
require_once NEXUSLEARN_PLUGIN_DIR . 'includes/Core/Taxonomies.php';
require_once NEXUSLEARN_PLUGIN_DIR . 'includes/Admin/MenuManager.php';
require_once NEXUSLEARN_PLUGIN_DIR . 'includes/Admin/Settings.php';
require_once NEXUSLEARN_PLUGIN_DIR . 'includes/Admin/GeneralSettings.php';
require_once NEXUSLEARN_PLUGIN_DIR . 'includes/Core/PostTypes.php';



// Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'NexusLearn\\';
    $base_dir = NEXUSLEARN_PLUGIN_DIR . 'includes/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Initialize Plugin
function nexuslearn_init() {
    // Initialize core classes
    new NexusLearn\Core\Plugin();
    new NexusLearn\Core\PostTypes();
    new NexusLearn\Core\Taxonomies();
    new NexusLearn\Admin\MenuManager();
    
}
add_action('plugins_loaded', 'nexuslearn_init');


// // Activation Hook
// register_activation_hook(__FILE__, function() {
//     require_once NEXUSLEARN_PLUGIN_DIR . 'includes/Core/Activator.php';
//     NexusLearn\Core\Activator::activate();
// });

// // Deactivation Hook
// register_deactivation_hook(__FILE__, function() {
//     require_once NEXUSLEARN_PLUGIN_DIR . 'includes/Core/Deactivator.php';
//     NexusLearn\Core\Deactivator::deactivate();
// });
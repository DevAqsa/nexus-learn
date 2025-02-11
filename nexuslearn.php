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
require_once NEXUSLEARN_PLUGIN_DIR . 'includes/Core/ProgressTracker.php';
require_once NEXUSLEARN_PLUGIN_DIR . 'includes/Admin/Views/TrackingPage.php';
require_once NEXUSLEARN_PLUGIN_DIR . 'includes/Core/QuizSystem.php';
require_once NEXUSLEARN_PLUGIN_DIR . 'includes/Admin/QuizManager.php';
require_once NEXUSLEARN_PLUGIN_DIR . 'includes/Frontend/QuizDisplay.php';
require_once NEXUSLEARN_PLUGIN_DIR . 'includes/Admin/QuizList.php';
require_once NEXUSLEARN_PLUGIN_DIR . 'includes/Analytics/QuizAnalytics.php';
require_once NEXUSLEARN_PLUGIN_DIR . 'includes/Admin/CourseTemplateHandler.php';
require_once NEXUSLEARN_PLUGIN_DIR . 'includes/Core/SecurityHandler.php';
require_once NEXUSLEARN_PLUGIN_DIR . 'includes/functions.php';
require_once NEXUSLEARN_PLUGIN_DIR . 'includes/Frontend/Components/StudentSettings.php';
require_once NEXUSLEARN_PLUGIN_DIR . 'includes/Frontend/Components/NotesManager.php';






function nexuslearn_enqueue_admin_scripts($hook) {

    if (strpos($hook, 'nl-progress-tracking') !== false) {
        wp_enqueue_style(
            'nl-progress-tracking',
            NEXUSLEARN_PLUGIN_URL . 'assets/css/progress-tracking.css',
            [],
            NEXUSLEARN_VERSION
        );
    }

    wp_enqueue_style(
        'nl-quiz-analytics',
        NEXUSLEARN_PLUGIN_URL . 'assets/css/quiz-analytics.css',
        [],
        NEXUSLEARN_VERSION
    );

    wp_enqueue_script(
        'chartjs',
        'https://cdn.jsdelivr.net/npm/chart.js',
        [],
        '4.4.1',
        true
    );

    wp_enqueue_script(
        'nl-quiz-analytics',
        NEXUSLEARN_PLUGIN_URL . 'assets/js/quiz-analytics.js',
        ['jquery', 'chartjs'],
        NEXUSLEARN_VERSION,
        true
    );

    if (strpos($hook, 'nl-progress-tracking') !== false) {
        wp_enqueue_style(
            'nl-progress-tracking',
            NEXUSLEARN_PLUGIN_URL . 'assets/css/progress-tracking.css',
            [],
            NEXUSLEARN_VERSION
        );
        
        // Add Chart.js
        wp_enqueue_script(
            'chartjs',
            'https://cdn.jsdelivr.net/npm/chart.js',
            [],
            '4.4.1',
            true
        );
        
        // Add custom scripts if needed
        wp_enqueue_script(
            'nl-analytics',
            NEXUSLEARN_PLUGIN_URL . 'assets/js/analytics.js',
            ['jquery', 'chartjs'],
            NEXUSLEARN_VERSION,
            true
        );
    }
}
add_action('admin_enqueue_scripts', 'nexuslearn_enqueue_admin_scripts');


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
    new NexusLearn\Core\ProgressTracker();
    new NexusLearn\Core\QuizSystem();
    new NexusLearn\Admin\QuizManager();
    new NexusLearn\Frontend\QuizDisplay();
    new NexusLearn\Analytics\QuizAnalytics();
    new NexusLearn\Admin\CourseTemplateHandler();
    new NexusLearn\Frontend\StudentDashboard();
    new NexusLearn\Frontend\Components\NotesManager();
    
    
    
    
    
}
add_action('plugins_loaded', 'nexuslearn_init');

function register_student_dashboard_page() {
    if (!get_page_by_path('student-dashboard')) {
        wp_insert_post([
            'post_title' => 'Student Dashboard',
            'post_content' => '[nexuslearn_student_dashboard]',
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_name' => 'student-dashboard'
        ]);
    }
}
register_activation_hook(__FILE__, 'register_student_dashboard_page');

register_activation_hook(__FILE__, function() {
    // Add the database creation code here
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'nexuslearn_notes';

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        user_id bigint(20) NOT NULL,
        title varchar(255) NOT NULL,
        content longtext NOT NULL,
        course_id bigint(20) DEFAULT NULL,
        lesson_id bigint(20) DEFAULT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY  (id),
        KEY user_id (user_id),
        KEY course_id (course_id),
        KEY lesson_id (lesson_id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
});

function nl_handle_contact_form() {
    check_ajax_referer('nl_dashboard_nonce', 'nonce');
    
    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => __('You must be logged in to submit a message.', 'nexuslearn')]);
    }

    $subject = sanitize_text_field($_POST['subject']);
    $message = sanitize_textarea_field($_POST['message']);
    $user = wp_get_current_user();

    // Prepare email content
    $to = get_option('admin_email');
    $email_subject = sprintf('[NexusLearn Contact] %s - from %s', $subject, $user->display_name);
    $email_message = sprintf(
        "A new contact form submission has been received:\n\n" .
        "From: %s (%s)\n" .
        "Subject: %s\n\n" .
        "Message:\n%s",
        $user->display_name,
        $user->user_email,
        $subject,
        $message
    );

    // Send email
    $sent = wp_mail($to, $email_subject, $email_message);

    // Log the contact attempt
    global $wpdb;
    $table = $wpdb->prefix . 'nl_contact_log';
    
    $wpdb->insert(
        $table,
        [
            'user_id' => get_current_user_id(),
            'subject' => $subject,
            'message' => $message,
            'status' => $sent ? 'sent' : 'failed',
            'created_at' => current_time('mysql')
        ],
        ['%d', '%s', '%s', '%s', '%s']
    );

    if ($sent) {
        wp_send_json_success(['message' => __('Your message has been sent successfully.', 'nexuslearn')]);
    } else {
        wp_send_json_error(['message' => __('Failed to send message. Please try again.', 'nexuslearn')]);
    }
}
add_action('wp_ajax_nl_submit_contact_form', 'nl_handle_contact_form');


add_shortcode('nexuslearn_gradebook', function() {
    $gradebook = new NexusLearn\Frontend\Components\GradeBook();
    return $gradebook->render_gradebook();
});
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
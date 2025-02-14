<?php
// Add this to functions.php or your plugin file

add_action('wp_ajax_nl_load_course_content', 'nl_load_course_content_handler');
add_action('wp_ajax_nopriv_nl_load_course_content', 'nl_load_course_content_handler');

function nl_load_course_content_handler() {
    check_ajax_referer('wp_rest', 'nonce');
    
    $content_type = isset($_GET['content']) ? sanitize_key($_GET['content']) : 'lessons';
    $course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;
    
    if (!$course_id) {
        wp_die('Invalid course ID');
    }
    
    // Define the template path
    $template_path = NEXUSLEARN_PLUGIN_DIR . 'templates/frontend/dashboard/course-content/';
    
    // Load the appropriate template based on content type
    switch ($content_type) {
        case 'info':
            include $template_path . 'course-info-content.php';
            break;
        case 'faqs':
            include $template_path . 'course-faqs-content.php';
            break;
        case 'glossary':
            include $template_path . 'course-glossary-content.php';
            break;
        case 'books':
            include $template_path . 'course-books-content.php';
            break;
        case 'lessons':
        default:
            include $template_path . 'lessons-content.php';
            break;
    }
    
    wp_die();
}
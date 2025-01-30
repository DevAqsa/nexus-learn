<?php
namespace NexusLearn\Core;

class SecurityHandler {
    private static $instance = null;
    private $nonce_actions = [];

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Data Sanitization Methods
    public function sanitize_text($data) {
        if (is_array($data)) {
            return array_map([$this, 'sanitize_text'], $data);
        }
        return sanitize_text_field($data);
    }

    public function sanitize_html($content) {
        return wp_kses_post($content);
    }

    public function sanitize_email($email) {
        return sanitize_email($email);
    }

    public function sanitize_url($url) {
        return esc_url_raw($url);
    }

    public function sanitize_filename($filename) {
        return sanitize_file_name($filename);
    }

    // XSS Protection Methods
    public function escape_html($data) {
        if (is_array($data)) {
            return array_map([$this, 'escape_html'], $data);
        }
        return esc_html($data);
    }

    public function escape_attr($data) {
        if (is_array($data)) {
            return array_map([$this, 'escape_attr'], $data);
        }
        return esc_attr($data);
    }

    public function escape_js($data) {
        if (is_array($data)) {
            return array_map([$this, 'escape_js'], $data);
        }
        return esc_js($data);
    }

    // CSRF Protection Methods
    public function create_nonce($action) {
        $this->nonce_actions[] = $action;
        return wp_create_nonce($action);
    }

    public function verify_nonce($nonce, $action) {
        return wp_verify_nonce($nonce, $action);
    }

    public function add_nonce_field($action) {
        wp_nonce_field($action, $action . '_nonce');
    }

    // Capability Checks
    public function check_capabilities($capability, $object_id = null) {
        if (!current_user_can($capability, $object_id)) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'nexuslearn'));
        }
        return true;
    }

    // Validate and sanitize course data
    public function sanitize_course_data($data) {
        return [
            'title' => $this->sanitize_text($data['title']),
            'description' => $this->sanitize_html($data['description']),
            'duration' => absint($data['duration']),
            'level' => $this->sanitize_text($data['level']),
            'prerequisites' => array_map('absint', (array) $data['prerequisites']),
            'featured_image' => $this->sanitize_url($data['featured_image'])
        ];
    }

    // Validate and sanitize quiz data
    public function sanitize_quiz_data($data) {
        return [
            'title' => $this->sanitize_text($data['title']),
            'description' => $this->sanitize_html($data['description']),
            'time_limit' => absint($data['time_limit']),
            'passing_score' => min(100, max(0, absint($data['passing_score']))),
            'questions' => array_map([$this, 'sanitize_question'], (array) $data['questions'])
        ];
    }

    private function sanitize_question($question) {
        return [
            'text' => $this->sanitize_html($question['text']),
            'type' => $this->sanitize_text($question['type']),
            'options' => array_map([$this, 'sanitize_text'], (array) $question['options']),
            'correct_answer' => $this->sanitize_text($question['correct_answer']),
            'points' => absint($question['points'])
        ];
    }

    // Input validation
    public function validate_required_fields($data, $required_fields) {
        $missing_fields = [];
        foreach ($required_fields as $field) {
            if (empty($data[$field])) {
                $missing_fields[] = $field;
            }
        }
        return empty($missing_fields) ? true : $missing_fields;
    }

    // Security headers
    public function set_security_headers() {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline';");
    }

    // File upload security
    public function validate_file_upload($file, $allowed_types = ['jpg', 'jpeg', 'png', 'pdf']) {
        if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
            return new \WP_Error('no_file', __('No file uploaded', 'nexuslearn'));
        }

        // Check file type
        $file_type = wp_check_filetype($file['name']);
        if (!in_array(strtolower($file_type['ext']), $allowed_types)) {
            return new \WP_Error('invalid_type', __('Invalid file type', 'nexuslearn'));
        }

        // Check file size (default: 5MB)
        $max_size = apply_filters('nexuslearn_max_upload_size', 5 * 1024 * 1024);
        if ($file['size'] > $max_size) {
            return new \WP_Error('file_too_large', __('File is too large', 'nexuslearn'));
        }

        return true;
    }

    // Database query protection
    public function prepare_query($query, $args) {
        global $wpdb;
        if (!empty($args)) {
            return $wpdb->prepare($query, $args);
        }
        return $query;
    }

    // API request validation
    public function validate_api_request() {
        if (!check_ajax_referer('wp_rest', false, false)) {
            return new \WP_Error(
                'invalid_nonce',
                __('Invalid security token', 'nexuslearn'),
                ['status' => 403]
            );
        }
        return true;
    }
}
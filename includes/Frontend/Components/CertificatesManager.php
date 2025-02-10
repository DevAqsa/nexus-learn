<?php
namespace NexusLearn\Frontend\Components;

class CertificatesManager {
    public function __construct() {
        add_action('wp_ajax_nl_download_certificate', [$this, 'handle_certificate_download']);
        add_action('wp_ajax_nl_download_all_certificates', [$this, 'handle_bulk_download']);
    }

    public function get_user_certificates($user_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'nexuslearn_certificates';
        
        // For testing purposes, return empty array if table doesn't exist
        if ($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
            return [];
        }
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$table} WHERE user_id = %d ORDER BY completion_date DESC",
            $user_id
        ), ARRAY_A);
    }

    public function handle_certificate_download() {
        check_ajax_referer('nl_dashboard_nonce', 'nonce');
        
        if (!isset($_POST['certificate_id'])) {
            wp_send_json_error(['message' => __('Invalid certificate ID', 'nexuslearn')]);
        }
        
        $certificate_id = intval($_POST['certificate_id']);
        
        // TODO: Implement actual certificate generation
        wp_send_json_success(['download_url' => '#']);
    }

    public function handle_bulk_download() {
        check_ajax_referer('nl_dashboard_nonce', 'nonce');
        
        $user_id = get_current_user_id();
        $certificates = $this->get_user_certificates($user_id);
        
        if (empty($certificates)) {
            wp_send_json_error(['message' => __('No certificates found', 'nexuslearn')]);
        }
        
        // TODO: Implement actual bulk certificate generation
        wp_send_json_success(['download_url' => '#']);
    }
}
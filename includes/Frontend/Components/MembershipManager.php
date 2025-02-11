<?php
namespace NexusLearn\Frontend\Components;

class MembershipManager {
    public function __construct() {
        add_action('wp_ajax_nl_upgrade_membership', [$this, 'handle_membership_upgrade']);
        add_action('wp_ajax_nl_cancel_membership', [$this, 'handle_membership_cancellation']);
    }

    /**
     * Get user's current membership details
     */
    public function get_membership_details($user_id) {
        return [
            'status' => get_user_meta($user_id, 'nl_membership_status', true) ?: 'free',
            'plan' => get_user_meta($user_id, 'nl_current_plan', true) ?: 'Free',
            'expiry' => get_user_meta($user_id, 'nl_membership_expiry', true),
            'history' => get_user_meta($user_id, 'nl_membership_history', true) ?: []
        ];
    }

    /**
     * Get all available membership plans
     */
    public function get_membership_plans() {
        return [
            'free' => [
                'name' => 'Free Plan',
                'price' => '0',
                'features' => [
                    'Access to free courses',
                    'Basic progress tracking',
                    'Limited quiz attempts'
                ]
            ],
            'basic' => [
                'name' => 'Basic Plan',
                'price' => '9.99',
                'features' => [
                    'Access to all basic courses',
                    'Unlimited quiz attempts',
                    'Course certificates',
                    'Priority support'
                ]
            ],
            'premium' => [
                'name' => 'Premium Plan',
                'price' => '19.99',
                'features' => [
                    'Access to all courses including premium',
                    'Exclusive webinars',
                    'Downloadable resources',
                    '1-on-1 mentoring sessions',
                    'Advanced analytics'
                ]
            ]
        ];
    }

    /**
     * Handle membership upgrade request
     */
    public function handle_membership_upgrade() {
        check_ajax_referer('nl_membership_nonce', 'nonce');
        
        if (!isset($_POST['plan'])) {
            wp_send_json_error(['message' => __('Invalid plan selected', 'nexuslearn')]);
        }

        $user_id = get_current_user_id();
        $new_plan = sanitize_text_field($_POST['plan']);
        $plans = $this->get_membership_plans();

        if (!isset($plans[$new_plan])) {
            wp_send_json_error(['message' => __('Invalid plan selected', 'nexuslearn')]);
        }

        // Update user membership
        $this->update_user_membership($user_id, $new_plan);

        wp_send_json_success([
            'message' => __('Membership upgraded successfully', 'nexuslearn'),
            'redirect' => add_query_arg('view', 'membership', get_permalink())
        ]);
    }

    /**
     * Handle membership cancellation
     */
    public function handle_membership_cancellation() {
        check_ajax_referer('nl_membership_nonce', 'nonce');
        
        $user_id = get_current_user_id();
        $current_status = get_user_meta($user_id, 'nl_membership_status', true);

        if ($current_status === 'free') {
            wp_send_json_error(['message' => __('No active paid membership to cancel', 'nexuslearn')]);
        }

        // Update membership history
        $this->add_to_membership_history($user_id, [
            'plan' => get_user_meta($user_id, 'nl_current_plan', true),
            'start_date' => get_user_meta($user_id, 'nl_membership_start', true),
            'end_date' => current_time('mysql'),
            'status' => 'cancelled'
        ]);

        // Reset to free plan
        $this->update_user_membership($user_id, 'free');

        wp_send_json_success([
            'message' => __('Membership cancelled successfully', 'nexuslearn'),
            'redirect' => add_query_arg('view', 'membership', get_permalink())
        ]);
    }

    /**
     * Update user's membership
     */
    private function update_user_membership($user_id, $plan) {
        $plans = $this->get_membership_plans();
        
        // Add current plan to history before updating
        $current_plan = get_user_meta($user_id, 'nl_current_plan', true);
        if ($current_plan && $current_plan !== 'Free') {
            $this->add_to_membership_history($user_id, [
                'plan' => $current_plan,
                'start_date' => get_user_meta($user_id, 'nl_membership_start', true),
                'end_date' => current_time('mysql'),
                'status' => 'expired'
            ]);
        }

        // Update membership details
        update_user_meta($user_id, 'nl_membership_status', $plan);
        update_user_meta($user_id, 'nl_current_plan', $plans[$plan]['name']);
        update_user_meta($user_id, 'nl_membership_start', current_time('mysql'));
        
        // Set expiry date (30 days from now for paid plans)
        if ($plan !== 'free') {
            $expiry = date('Y-m-d H:i:s', strtotime('+30 days'));
            update_user_meta($user_id, 'nl_membership_expiry', $expiry);
        } else {
            delete_user_meta($user_id, 'nl_membership_expiry');
        }
    }

    /**
     * Add entry to membership history
     */
    private function add_to_membership_history($user_id, $entry) {
        $history = get_user_meta($user_id, 'nl_membership_history', true) ?: [];
        array_unshift($history, $entry); // Add new entry to the beginning
        update_user_meta($user_id, 'nl_membership_history', array_slice($history, 0, 10)); // Keep last 10 entries
    }

    /**
     * Check if user has access to specific content
     */
    public function has_content_access($user_id, $content_type) {
        $membership_status = get_user_meta($user_id, 'nl_membership_status', true) ?: 'free';
        
        switch ($content_type) {
            case 'premium_courses':
                return $membership_status === 'premium';
            case 'basic_courses':
                return in_array($membership_status, ['basic', 'premium']);
            case 'certificates':
                return in_array($membership_status, ['basic', 'premium']);
            case 'mentoring':
                return $membership_status === 'premium';
            default:
                return true; // Free content accessible to all
        }
    }
}
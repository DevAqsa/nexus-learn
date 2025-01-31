<?php

namespace NexusLearn\Frontend\Components;

class CertificatesManager {
    public function __construct() {
        add_action('wp_ajax_nl_download_certificate', [$this, 'download_certificate']);
    }

    public function render_certificates_section($user_id) {
        $certificates = $this->get_user_certificates($user_id);
        ob_start();
        ?>
        <div class="nl-certificates-section">
            <h2><?php _e('Certificates & Achievements', 'nexuslearn'); ?></h2>
            <?php if (!empty($certificates)): ?>
                <div class="nl-certificates-grid">
                    <?php foreach ($certificates as $cert): ?>
                        <div class="nl-certificate-card">
                            <div class="nl-certificate-icon">🏆</div>
                            <h3><?php echo esc_html($cert['title']); ?></h3>
                            <p><?php echo esc_html($cert['completion_date']); ?></p>
                            <button class="nl-download-cert" 
                                    data-cert-id="<?php echo esc_attr($cert['id']); ?>">
                                <?php _e('Download', 'nexuslearn'); ?>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="nl-empty-state"><?php _e('No certificates earned yet.', 'nexuslearn'); ?></p>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    private function get_user_certificates($user_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'nexuslearn_certificates';
        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$table} WHERE user_id = %d",
                $user_id
            ),
            ARRAY_A
        );
    }

    public function download_certificate() {
        // Certificate generation and download logic
        wp_send_json_success(['message' => 'Certificate downloaded']);
    }
}



<?php
if (!defined('ABSPATH')) exit;

// Ensure certificates manager is available
if (!isset($certificates_manager)) {
    return;
}

$user_id = get_current_user_id();
$certificates = $certificates_manager->get_user_certificates($user_id);
?>

<div class="nl-content-section">
    <!-- Header Section -->
    <!-- <div class="nl-section-header">
        <h1 class="nl-page-title"><?php _e('Certificates', 'nexuslearn'); ?></h1>
        <p class="nl-subtitle"><?php _e('View and manage your earned certificates', 'nexuslearn'); ?></p>
    </div> -->

    <!-- Stats Overview -->
    <div class="nl-stats-grid">
        <div class="nl-stat-card">
            <span class="nl-stat-icon award">🏆</span>
            <div class="nl-stat-value"><?php echo count($certificates); ?></div>
            <div class="nl-stat-label"><?php _e('Total Certificates', 'nexuslearn'); ?></div>
        </div> 
    </div>

    <!-- Certificates List -->
    <div class="nl-certificates-container">
        <div class="nl-section-header">
            <h2><?php _e('Your Certificates', 'nexuslearn'); ?></h2>
            <div class="nl-header-actions">
                <button class="nl-button nl-button-secondary" id="nl-download-all">
                    <i class="dashicons dashicons-download"></i>
                    <?php _e('Download All', 'nexuslearn'); ?>
                </button>
            </div>
        </div>

        <?php if (!empty($certificates)): ?>
            <div class="nl-certificates-list">
                <?php foreach ($certificates as $cert): ?>
                    <div class="nl-certificate-card">
                        <div class="nl-certificate-content">
                            <div class="nl-certificate-icon">
                                <i class="dashicons dashicons-awards"></i>
                            </div>
                            <div class="nl-certificate-details">
                                <h3><?php echo esc_html($cert['course_title']); ?></h3>
                                <div class="nl-certificate-meta">
                                    <span class="nl-completion-date">
                                        <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($cert['completion_date']))); ?>
                                    </span>
                                    <span class="nl-grade">
                                        <?php echo esc_html($cert['grade']); ?>%
                                    </span>
                                    <span class="nl-verification-status <?php echo esc_attr(strtolower($cert['status'])); ?>">
                                        <?php echo esc_html($cert['status']); ?>
                                    </span>
                                </div>
                            </div>
                            <div class="nl-certificate-actions">
                                <button class="nl-button nl-button-icon" data-cert-id="<?php echo esc_attr($cert['id']); ?>" onclick="downloadCertificate(this)">
                                    <i class="dashicons dashicons-download"></i>
                                    <?php _e('Download', 'nexuslearn'); ?>
                                </button>
                                <button class="nl-button nl-button-icon" data-cert-id="<?php echo esc_attr($cert['id']); ?>" onclick="shareCertificate(this)">
                                    <i class="dashicons dashicons-share"></i>
                                    <?php _e('Share', 'nexuslearn'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="nl-empty-state">
                <div class="nl-empty-icon">
                    <i class="dashicons dashicons-awards"></i>
                </div>
                <h3><?php _e('No Certificates Yet', 'nexuslearn'); ?></h3>
                <p><?php _e('Complete courses to earn certificates', 'nexuslearn'); ?></p>
                <a href="<?php echo esc_url(get_post_type_archive_link('nl_course')); ?>" class="nl-button nl-button-primary">
                    <?php _e('Browse Courses', 'nexuslearn'); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php

$user_id = get_current_user_id();
$certificates = $certificates_manager->get_user_certificates($user_id);
?>

<div class="nl-certificates-section nl-content-section">
    <div class="nl-section-header">
        <h2><?php _e('My Certificates', 'nexuslearn'); ?></h2>
        <div class="nl-section-actions">
            <button class="nl-button nl-button-secondary" id="nl-download-all-certs">
                <?php _e('Download All', 'nexuslearn'); ?>
            </button>
        </div>
    </div>

    <?php if (!empty($certificates)): ?>
        <div class="nl-certificates-grid">
            <?php foreach ($certificates as $cert): ?>
                <div class="nl-certificate-card" data-cert-id="<?php echo esc_attr($cert['id']); ?>">
                    <div class="nl-certificate-header">
                        <div class="nl-certificate-icon">
                            <i class="dashicons dashicons-awards"></i>
                        </div>
                        <div class="nl-certificate-status <?php echo esc_attr($cert['status']); ?>">
                            <?php echo esc_html($cert['status']); ?>
                        </div>
                    </div>
                    
                    <div class="nl-certificate-content">
                        <h3 class="nl-certificate-title">
                            <?php echo esc_html($cert['course_title']); ?>
                        </h3>
                        <div class="nl-certificate-meta">
                            <span class="nl-certificate-date">
                                <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($cert['completion_date']))); ?>
                            </span>
                            <span class="nl-certificate-grade">
                                <?php echo esc_html($cert['grade']); ?>%
                            </span>
                        </div>
                    </div>

                    <div class="nl-certificate-actions">
                        <button class="nl-download-cert nl-button nl-button-primary" 
                                data-cert-id="<?php echo esc_attr($cert['id']); ?>">
                            <?php _e('Download', 'nexuslearn'); ?>
                        </button>
                        <button class="nl-share-cert nl-button nl-button-secondary" 
                                data-cert-id="<?php echo esc_attr($cert['id']); ?>">
                            <?php _e('Share', 'nexuslearn'); ?>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="nl-empty-state">
            <div class="nl-empty-state-icon">🎓</div>
            <h3><?php _e('No Certificates Yet', 'nexuslearn'); ?></h3>
            <p><?php _e('Complete courses to earn certificates', 'nexuslearn'); ?></p>
            <a href="<?php echo esc_url(get_post_type_archive_link('nl_course')); ?>" 
               class="nl-button nl-button-primary">
                <?php _e('Browse Courses', 'nexuslearn'); ?>
            </a>
        </div>
    <?php endif; ?>
</div>
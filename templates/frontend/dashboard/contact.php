<?php
if (!defined('ABSPATH')) exit;

$support_email = 'support@nexuslearn.com';
$technical_email = 'technical@nexuslearn.com';
$phone_support = '+1 (555) 123-4567';
$emergency_support = '+1 (555) 987-6543';
?>

<div class="nl-contact-section nl-content-section">
    <!-- Header Section -->
    <div class="nl-section-header">
        <h2><?php _e('Contact Us', 'nexuslearn'); ?></h2>
        <p class="nl-subtitle"><?php _e('Get in touch with our support team', 'nexuslearn'); ?></p>
    </div>

    <!-- Contact Cards Grid -->
    <div class="nl-contact-grid">
        <!-- General Support -->
        <div class="nl-contact-card">
            <div class="nl-contact-icon">
                <i class="dashicons dashicons-businessman"></i>
            </div>
            <h3><?php _e('General Support', 'nexuslearn'); ?></h3>
            <p><?php _e('For general inquiries and course-related support', 'nexuslearn'); ?></p>
            <div class="nl-contact-info">
                <div class="nl-info-item">
                    <i class="dashicons dashicons-email"></i>
                    <a href="mailto:<?php echo esc_attr($support_email); ?>"><?php echo esc_html($support_email); ?></a>
                </div>
                <div class="nl-info-item">
                    <i class="dashicons dashicons-phone"></i>
                    <a href="tel:<?php echo esc_attr($phone_support); ?>"><?php echo esc_html($phone_support); ?></a>
                </div>
            </div>
        </div>

        <!-- Technical Support -->
        <div class="nl-contact-card">
            <div class="nl-contact-icon">
                <i class="dashicons dashicons-desktop"></i>
            </div>
            <h3><?php _e('Technical Support', 'nexuslearn'); ?></h3>
            <p><?php _e('For platform-related issues and technical assistance', 'nexuslearn'); ?></p>
            <div class="nl-contact-info">
                <div class="nl-info-item">
                    <i class="dashicons dashicons-email"></i>
                    <a href="mailto:<?php echo esc_attr($technical_email); ?>"><?php echo esc_html($technical_email); ?></a>
                </div>
                <div class="nl-info-item">
                    <i class="dashicons dashicons-phone"></i>
                    <a href="tel:<?php echo esc_attr($emergency_support); ?>"><?php echo esc_html($emergency_support); ?></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Form -->
    <div class="nl-contact-form-container">
        <h3><?php _e('Send us a Message', 'nexuslearn'); ?></h3>
        <form id="nl-contact-form" class="nl-contact-form">
            <div class="nl-form-row">
                <div class="nl-form-group">
                    <label for="subject"><?php _e('Subject', 'nexuslearn'); ?></label>
                    <select id="subject" name="subject" required>
                        <option value=""><?php _e('Select a subject', 'nexuslearn'); ?></option>
                        <option value="course_support"><?php _e('Course Support', 'nexuslearn'); ?></option>
                        <option value="technical_issue"><?php _e('Technical Issue', 'nexuslearn'); ?></option>
                        <option value="billing"><?php _e('Billing Question', 'nexuslearn'); ?></option>
                        <option value="other"><?php _e('Other', 'nexuslearn'); ?></option>
                    </select>
                </div>
            </div>

            <div class="nl-form-row">
                <div class="nl-form-group">
                    <label for="message"><?php _e('Message', 'nexuslearn'); ?></label>
                    <textarea id="message" name="message" rows="5" required></textarea>
                </div>
            </div>

            <div class="nl-form-actions">
                <button type="submit" class="nl-button nl-button-primary">
                    <?php _e('Send Message', 'nexuslearn'); ?>
                </button>
            </div>
        </form>
    </div>

    <!-- Support Hours -->
    <div class="nl-support-hours">
        <h3><?php _e('Support Hours', 'nexuslearn'); ?></h3>
        <div class="nl-hours-grid">
            <div class="nl-hours-item">
                <span class="nl-day"><?php _e('Monday - Friday:', 'nexuslearn'); ?></span>
                <span class="nl-time">9:00 AM - 6:00 PM EST</span>
            </div>
            <div class="nl-hours-item">
                <span class="nl-day"><?php _e('Saturday:', 'nexuslearn'); ?></span>
                <span class="nl-time">10:00 AM - 2:00 PM EST</span>
            </div>
            <div class="nl-hours-item">
                <span class="nl-day"><?php _e('Sunday:', 'nexuslearn'); ?></span>
                <span class="nl-time"><?php _e('Closed', 'nexuslearn'); ?></span>
            </div>
        </div>
        <p class="nl-emergency-note">
            <?php _e('* Emergency technical support is available 24/7', 'nexuslearn'); ?>
        </p>
    </div>
</div>

<style>
.nl-contact-section {
    padding: 2rem;
}

.nl-contact-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.nl-contact-card {
    background: white;
    padding: 2rem;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.nl-contact-icon {
    font-size: 2rem;
    color: #6366f1;
    margin-bottom: 1rem;
}

.nl-contact-info {
    margin-top: 1rem;
}

.nl-info-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.nl-info-item a {
    color: #4b5563;
    text-decoration: none;
}

.nl-info-item a:hover {
    color: #6366f1;
}

.nl-contact-form-container {
    background: white;
    padding: 2rem;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
}

.nl-form-row {
    margin-bottom: 1.5rem;
}

.nl-form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: #4b5563;
    font-weight: 500;
}

.nl-form-group select,
.nl-form-group textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.375rem;
    background-color: #f9fafb;
}

.nl-form-group textarea {
    resize: vertical;
}

.nl-support-hours {
    background: white;
    padding: 2rem;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.nl-hours-grid {
    display: grid;
    gap: 1rem;
    margin: 1rem 0;
}

.nl-hours-item {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    border-bottom: 1px solid #e5e7eb;
}

.nl-emergency-note {
    color: #dc2626;
    font-size: 0.875rem;
    margin-top: 1rem;
}

@media (max-width: 768px) {
    .nl-contact-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    $('#nl-contact-form').on('submit', function(e) {
        e.preventDefault();
        
        // Add AJAX submission logic here
        const formData = {
            subject: $('#subject').val(),
            message: $('#message').val(),
            action: 'nl_submit_contact_form',
            nonce: nlDashboard.nonce
        };

        $.ajax({
            url: nlDashboard.ajaxUrl,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    alert('Message sent successfully!');
                    $('#nl-contact-form')[0].reset();
                } else {
                    alert('Failed to send message. Please try again.');
                }
            },
            error: function() {
                alert('An error occurred. Please try again later.');
            }
        });
    });
});
</script>
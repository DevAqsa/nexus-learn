(function($) {
    'use strict';

    // Certificate Downloads
    function initCertificateDownloads() {
        $('.nl-download-cert').on('click', function(e) {
            e.preventDefault();
            const certId = $(this).data('cert-id');
            
            $.ajax({
                url: nexuslearn_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'nl_download_certificate',
                    cert_id: certId,
                    nonce: nexuslearn_ajax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Handle successful download
                        window.location.href = response.data.download_url;
                    } else {
                        alert('Error downloading certificate');
                    }
                },
                error: function() {
                    alert('Error downloading certificate');
                }
            });
        });
    }

    // Profile Management
    function initProfileManager() {
        $('#nl-profile-form').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('action', 'nl_update_profile');
            formData.append('nonce', nexuslearn_ajax.nonce);

            $.ajax({
                url: nexuslearn_ajax.ajax_url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        showNotification('Profile updated successfully', 'success');
                    } else {
                        showNotification('Error updating profile', 'error');
                    }
                },
                error: function() {
                    showNotification('Error updating profile', 'error');
                }
            });
        });
    }

    // Progress Tracking Animations
    function initProgressAnimations() {
        $('.nl-progress-fill').each(function() {
            const progressBar = $(this);
            const targetWidth = progressBar.data('progress') + '%';
            
            // Animate on scroll into view
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        progressBar.css('width', targetWidth);
                        observer.unobserve(entry.target);
                    }
                });
            });
            
            observer.observe(progressBar[0]);
        });
    }

    // Notification System
    function showNotification(message, type = 'success') {
        const notification = $('<div>', {
            class: `nl-notification nl-notification-${type}`,
            text: message
        }).appendTo('body');

        setTimeout(() => {
            notification.addClass('nl-notification-show');
        }, 100);

        setTimeout(() => {
            notification.removeClass('nl-notification-show');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Progress Data Updates
    function updateProgressData() {
        $.ajax({
            url: nexuslearn_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'nl_get_progress_data',
                nonce: nexuslearn_ajax.nonce
            },
            success: function(response) {
                if (response.success && response.data) {
                    updateProgressUI(response.data);
                }
            }
        });
    }

    function updateProgressUI(progressData) {
        progressData.forEach(course => {
            const progressItem = $(`.nl-progress-item[data-course-id="${course.id}"]`);
            if (progressItem.length) {
                progressItem.find('.nl-progress-percentage').text(course.progress + '%');
                progressItem.find('.nl-progress-fill').css('width', course.progress + '%');
                progressItem.find('.nl-progress-details').text(
                    `${course.completed_lessons} / ${course.total_lessons} lessons completed`
                );
            }
        });
    }

    // Achievement Notifications
    function checkNewAchievements() {
        $.ajax({
            url: nexuslearn_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'nl_check_achievements',
                nonce: nexuslearn_ajax.nonce
            },
            success: function(response) {
                if (response.success && response.data.achievements) {
                    response.data.achievements.forEach(achievement => {
                        showAchievementNotification(achievement);
                    });
                }
            }
        });
    }

    function showAchievementNotification(achievement) {
        const notification = $(`
            <div class="nl-achievement-notification">
                <div class="nl-achievement-icon">🏆</div>
                <div class="nl-achievement-content">
                    <h4>${achievement.title}</h4>
                    <p>${achievement.description}</p>
                </div>
            </div>
        `).appendTo('body');

        setTimeout(() => {
            notification.addClass('nl-achievement-show');
        }, 100);

        setTimeout(() => {
            notification.removeClass('nl-achievement-show');
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }

    // Initialize all components
    $(document).ready(function() {
        initCertificateDownloads();
        initProfileManager();
        initProgressAnimations();

        // Set up periodic updates
        setInterval(updateProgressData, 60000); // Update progress every minute
        setInterval(checkNewAchievements, 300000); // Check achievements every 5 minutes
    });

})(jQuery);
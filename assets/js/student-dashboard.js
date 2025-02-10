jQuery(document).ready(function($) {
    // Handle menu item active states
    function updateActiveMenuItem() {
        const currentView = new URLSearchParams(window.location.search).get('view') || 'overview';
        $('.nl-menu-item').removeClass('active');
        $(`.nl-menu-item[data-view="${currentView}"]`).addClass('active');
    }
    
    // Handle mobile menu toggle
    $('.nl-mobile-menu-toggle').on('click', function() {
        $('.nl-sidebar').toggleClass('nl-sidebar-open');
    });
    
    // Close mobile menu when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.nl-sidebar, .nl-mobile-menu-toggle').length) {
            $('.nl-sidebar').removeClass('nl-sidebar-open');
        }
    });
    
    // Handle course card interactions
    $('.nl-course-card').on('mouseenter', function() {
        $(this).addClass('nl-card-hover');
    }).on('mouseleave', function() {
        $(this).removeClass('nl-card-hover');
    });
    
    // Handle progress updates
    function updateProgress(courseId, progress) {
        $(`[data-course-id="${courseId}"] .nl-progress-fill`).css('width', `${progress}%`);
        $(`[data-course-id="${courseId}"] .nl-progress-text`).text(`${progress}% Complete`);
    }
    
    // Handle certificate downloads
    $('.nl-download-cert').on('click', function(e) {
        e.preventDefault();
        const certId = $(this).data('cert-id');
        
        $.ajax({
            url: nlDashboard.ajaxUrl,
            type: 'POST',
            data: {
                action: 'nl_download_certificate',
                nonce: nlDashboard.nonce,
                cert_id: certId
            },
            success: function(response) {
                if (response.success) {
                    window.location.href = response.data.download_url;
                } else {
                    alert(response.data.message || 'Error downloading certificate');
                }
            },
            error: function() {
                alert('Error downloading certificate');
            }
        });
    });
    
    // Handle profile updates
    $('#nl-profile-form').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const submitBtn = form.find('[type="submit"]');
        
        submitBtn.prop('disabled', true);
        
        $.ajax({
            url: nlDashboard.ajaxUrl,
            type: 'POST',
            data: {
                action: 'nl_update_profile',
                nonce: nlDashboard.nonce,
                formData: form.serialize()
            },
            success: function(response) {
                if (response.success) {
                    $('.nl-profile-message')
                        .removeClass('nl-error')
                        .addClass('nl-success')
                        .text(nlDashboard.i18n.successText)
                        .fadeIn();
                } else {
                    $('.nl-profile-message')
                        .removeClass('nl-success')
                        .addClass('nl-error')
                        .text(response.data.message)
                        .fadeIn();
                }
            },
            error: function() {
                $('.nl-profile-message')
                    .removeClass('nl-success')
                    .addClass('nl-error')
                    .text(nlDashboard.i18n.errorText)
                    .fadeIn();
            },
            complete: function() {
                submitBtn.prop('disabled', false);
                setTimeout(function() {
                    $('.nl-profile-message').fadeOut();
                }, 3000);
            }
        });
    });
    
    // Initialize tooltips
    $('[data-tooltip]').each(function() {
        $(this).tooltip({
            title: $(this).data('tooltip'),
            placement: 'top'
        });
    });
    
    // Initialize on page load
    updateActiveMenuItem();
    
    // Handle browser back/forward
    $(window).on('popstate', function() {
        updateActiveMenuItem();
    });
});








function downloadCertificate(button) {
    const certId = button.dataset.certId;
    
    jQuery.ajax({
        url: nlDashboard.ajaxUrl,
        type: 'POST',
        data: {
            action: 'nl_download_certificate',
            nonce: nlDashboard.nonce,
            certificate_id: certId
        },
        success: function(response) {
            if (response.success) {
                // Handle successful download
                window.location.href = response.data.download_url;
            } else {
                // Handle error
                alert(response.data.message || 'Error downloading certificate');
            }
        }
    });
}

function shareCertificate(button) {
    const certId = button.dataset.certId;
    // Implement sharing functionality
    // This could open a modal with sharing options
}

// Handle "Download All" functionality
jQuery('#nl-download-all').on('click', function() {
    jQuery.ajax({
        url: nlDashboard.ajaxUrl,
        type: 'POST',
        data: {
            action: 'nl_download_all_certificates',
            nonce: nlDashboard.nonce
        },
        success: function(response) {
            if (response.success) {
                window.location.href = response.data.download_url;
            } else {
                alert(response.data.message || 'Error downloading certificates');
            }
        }
    });
});





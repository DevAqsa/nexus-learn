jQuery(document).ready(function($) {
    // Course filtering
    $('#courseFilter').on('change', function() {
        const status = $(this).val();
        
        if (status === 'all') {
            $('.nl-course-card').show();
        } else {
            $('.nl-course-card').hide();
            $(`.nl-course-card[data-status="${status}"]`).show();
        }
    });

    // Course menu handling
    let activeMenu = null;

    $('.nl-course-menu').on('click', function(e) {
        e.stopPropagation();
        const button = $(this);
        const courseId = button.closest('.nl-course-card').data('course-id');
        
        // Close any open menu
        if (activeMenu) {
            activeMenu.popup.hide();
        }
        
        // Position and show menu
        const popup = $('.nl-course-menu-popup');
        const buttonPos = button.offset();
        
        popup.css({
            top: buttonPos.top + button.outerHeight() + 5,
            left: buttonPos.left - popup.outerWidth() + button.outerWidth()
        }).show();
        
        activeMenu = {
            button: button,
            popup: popup,
            courseId: courseId
        };
    });

    // Close menu when clicking outside
    $(document).on('click', function() {
        if (activeMenu) {
            activeMenu.popup.hide();
            activeMenu = null;
        }
    });

    // Handle menu options
    $('.nl-menu-option').on('click', function(e) {
        e.preventDefault();
        if (!activeMenu) return;

        const action = $(this).attr('class').split(' ')[1];
        const courseId = activeMenu.courseId;

        switch (action) {
            case 'nl-view-details':
                window.location.href = `?view=course-details&id=${courseId}`;
                break;
                
            case 'nl-download-certificate':
                downloadCertificate(courseId);
                break;
                
            case 'nl-unenroll':
                confirmUnenroll(courseId);
                break;
        }
    });

    // Helper functions
    function downloadCertificate(courseId) {
        $.ajax({
            url: nlDashboard.ajaxUrl,
            type: 'POST',
            data: {
                action: 'nl_download_certificate',
                nonce: nlDashboard.nonce,
                course_id: courseId
            },
            success: function(response) {
                if (response.success) {
                    window.location.href = response.data.url;
                } else {
                    alert(response.data.message || 'Error downloading certificate');
                }
            }
        });
    }

    function confirmUnenroll(courseId) {
        if (confirm(nlDashboard.i18n.confirmUnenroll)) {
            $.ajax({
                url: nlDashboard.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'nl_unenroll_course',
                    nonce: nlDashboard.nonce,
                    course_id: courseId
                },
                success: function(response) {
                    if (response.success) {
                        $(`.nl-course-card[data-course-id="${courseId}"]`).fadeOut(300, function() {
                            $(this).remove();
                            if ($('.nl-course-card').length === 0) {
                                showEmptyState();
                            }
                        });
                    } else {
                        alert(response.data.message || 'Error unenrolling from course');
                    }
                }
            });
        }
    }

    function showEmptyState() {
        $('.nl-courses-grid').html(`
            <div class="nl-empty-state">
                <div class="nl-empty-icon">
                    <i class="dashicons dashicons-welcome-learn-more"></i>
                </div>
                <h3>${nlDashboard.i18n.noCoursesFound}</h3>
                <p>${nlDashboard.i18n.noCoursesMessage}</p>
                <a href="${nlDashboard.coursesArchiveUrl}" class="nl-button nl-button-primary">
                    ${nlDashboard.i18n.browseCourses}
                </a>
            </div>
        `);
    }

    // Initialize tooltips
    $('[data-tooltip]').each(function() {
        const $element = $(this);
        $element.on('mouseenter', function() {
            const tooltip = $('<div class="nl-tooltip"></div>')
                .text($element.data('tooltip'))
                .appendTo('body');
            
            const pos = $element.offset();
            tooltip.css({
                top: pos.top - tooltip.outerHeight() - 10,
                left: pos.left + ($element.outerWidth() - tooltip.outerWidth()) / 2
            });
            
            $element.on('mouseleave', function() {
                tooltip.remove();
            });
        });
    });
});
jQuery(document).ready(function($) {

     // Menu item click handler
     $('.nl-menu-item a').on('click', function(e) {
        e.preventDefault();
        $('.nl-menu-item').removeClass('active');
        $(this).parent().addClass('active');
        
        // Get the section ID from href
        const section = $(this).attr('href').substring(1);
        
        // Add your section loading logic here
        // For now, just log the section
        console.log('Loading section:', section);
    });
    // Initialize charts if they exist
    if ($('#courseProgressChart').length) {
        const ctx = document.getElementById('courseProgressChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: dashboardData.progress_labels,
                datasets: [{
                    label: 'Course Progress',
                    data: dashboardData.progress_data,
                    borderColor: '#2271b1',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    }

    // Handle notifications
    $('.nl-notification-item').on('click', function() {
        const notificationId = $(this).data('id');
        $.post(ajaxurl, {
            action: 'nl_mark_notification_read',
            notification_id: notificationId,
            nonce: dashboardData.nonce
        });
    });

    // Course filter functionality
    $('#course-filter').on('change', function() {
        const filter = $(this).val();
        $('.nl-course-card').each(function() {
            const status = $(this).data('status');
            $(this).toggle(filter === 'all' || status === filter);
        });
    });
});
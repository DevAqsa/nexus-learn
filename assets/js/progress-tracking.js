
jQuery(document).ready(function($) {
    let timeSpent = 0;
    let timer = null;

    function startTimeTracking() {
        timer = setInterval(function() {
            timeSpent += 1;
            updateProgress();
        }, 1000);
    }

    function stopTimeTracking() {
        if (timer) {
            clearInterval(timer);
        }
    }

    function updateProgress() {
        const data = {
            action: 'update_lesson_progress',
            nonce: nlProgress.nonce,
            lesson_id: nlProgress.lessonId,
            course_id: nlProgress.courseId,
            time_spent: timeSpent,
            status: 'in_progress'
        };

        $.post(ajaxurl, data, function(response) {
            console.log('Progress updated');
        });
    }

    // Start tracking when page loads
    if (typeof nlProgress !== 'undefined') {
        startTimeTracking();
    }

    // Stop tracking when page unloads
    $(window).on('beforeunload', function() {
        stopTimeTracking();
        updateProgress();
    });
});


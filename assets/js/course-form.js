jQuery(document).ready(function($) {
    // Save form data periodically
    var autosaveTimer;
    $('.nl-course-form').on('change input', 'input, select, textarea', function() {
        clearTimeout(autosaveTimer);
        autosaveTimer = setTimeout(function() {
            $('#save-post').click();
        }, 30000); // Autosave after 30 seconds of inactivity
    });

    // Handle prerequisites selection
    $('#nl_course_prerequisites').select2({
        placeholder: 'Select required courses',
        allowClear: true,
        width: '100%'
    });

    // Handle category checkboxes
    $('.nl-checkboxes').on('change', 'input[type="checkbox"]', function() {
        var checked = $('.nl-checkboxes input[type="checkbox"]:checked').length;
        if (checked === 0) {
            $('.nl-checkboxes').addClass('nl-warning');
        } else {
            $('.nl-checkboxes').removeClass('nl-warning');
        }
    });

    // Initialize datepicker for start date
    $('#nl_course_start_date').datepicker({
        dateFormat: 'yy-mm-dd',
        minDate: 0 // Only allow future dates
    });

    // Price formatting
    $('#nl_course_price').on('change', function() {
        var value = $(this).val();
        if (value) {
            $(this).val(parseFloat(value).toFixed(2));
        }
    });

    // Show confirmation before removing image
    $('#nl_remove_image_button').on('click', function(e) {
        if (!confirm('Are you sure you want to remove the course image?')) {
            e.preventDefault();
            return false;
        }
    });
});
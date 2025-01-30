jQuery(document).ready(function($) {
    // Tab Navigation
    $('.nl-tab-button').on('click', function() {
        const tab = $(this).data('tab');
        $('.nl-tab-button').removeClass('active');
        $(this).addClass('active');
        $('.nl-tab-content').removeClass('active');
        $(`.nl-tab-content[data-tab="${tab}"]`).addClass('active');
    });

    // Media Upload Handler
    $('.nl-upload-button').on('click', function(e) {
        e.preventDefault();

        const button = $(this);
        const imagePreview = button.closest('.nl-media-upload').find('.nl-image-preview');
        const imageInput = button.closest('.nl-media-upload').find('input[type="hidden"]');

        const mediaUploader = wp.media({
            title: 'Select Course Image',
            button: {
                text: 'Use this image'
            },
            multiple: false
        });

        mediaUploader.on('select', function() {
            const attachment = mediaUploader.state().get('selection').first().toJSON();
            imageInput.val(attachment.id);
            imagePreview.html(`<img src="${attachment.url}">`);
        });

        mediaUploader.open();
    });

    // Learning Outcomes Handler
    $('.nl-add-outcome').on('click', function() {
        const container = $('#learning-outcomes-container');
        const outcomeHtml = `
            <div class="nl-outcome-item">
                <input type="text" name="course_outcomes[]" class="nl-input" 
                       placeholder="Enter a learning outcome">
                <button type="button" class="nl-remove-outcome">×</button>
            </div>
        `;
        container.append(outcomeHtml);
    });

    $(document).on('click', '.nl-remove-outcome', function() {
        $(this).closest('.nl-outcome-item').remove();
    });

    // Curriculum Section Handler
    let sectionCount = 1;
    $('.nl-add-section').on('click', function() {
        const container = $('#nl-sections-container');
        const sectionHtml = `
            <div class="nl-section">
                <div class="nl-section-header">
                    <input type="text" name="sections[${sectionCount}][title]" class="nl-input" 
                           placeholder="Section Title">
                    <button type="button" class="nl-remove-section">×</button>
                </div>
                <div class="nl-lessons-container">
                    <div class="nl-lesson">
                        <input type="text" name="sections[${sectionCount}][lessons][]" class="nl-input" 
                               placeholder="Lesson Title">
                        <button type="button" class="nl-remove-lesson">×</button>
                    </div>
                </div>
                <button type="button" class="button nl-add-lesson">
                    <span class="dashicons dashicons-plus-alt2"></span>
                    Add Lesson
                </button>
            </div>
        `;
        container.append(sectionHtml);
        sectionCount++;
    });

    $(document).on('click', '.nl-remove-section', function() {
        $(this).closest('.nl-section').remove();
    });

    // Lesson Handler
    $(document).on('click', '.nl-add-lesson', function() {
        const container = $(this).siblings('.nl-lessons-container');
        const sectionIndex = $(this).closest('.nl-section').index();
        const lessonHtml = `
            <div class="nl-lesson">
                <input type="text" name="sections[${sectionIndex}][lessons][]" class="nl-input" 
                       placeholder="Lesson Title">
                <button type="button" class="nl-remove-lesson">×</button>
            </div>
        `;
        container.append(lessonHtml);
    });

    $(document).on('click', '.nl-remove-lesson', function() {
        $(this).closest('.nl-lesson').remove();
    });

    // Pricing Type Handler
    $('#course_pricing_type').on('change', function() {
        const value = $(this).val();
        if (value === 'free') {
            $('.price-fields, .subscription-fields').hide();
        } else if (value === 'one-time') {
            $('.price-fields').show();
            $('.subscription-fields').hide();
        } else if (value === 'subscription') {
            $('.price-fields, .subscription-fields').show();
        }
    });

    // Form Validation
    $('#nl-course-form').on('submit', function(e) {
        const requiredFields = ['course_title', 'course_description'];
        let isValid = true;

        requiredFields.forEach(field => {
            const value = $(`#${field}`).val();
            if (!value || value.trim() === '') {
                isValid = false;
                $(`#${field}`).addClass('nl-error');
            } else {
                $(`#${field}`).removeClass('nl-error');
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields');
            
            // Switch to the tab with the first error
            const firstError = $('.nl-error').first();
            const tabContent = firstError.closest('.nl-tab-content');
            const tabId = tabContent.data('tab');
            $(`.nl-tab-button[data-tab="${tabId}"]`).click();
        }
    });
});
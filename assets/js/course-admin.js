(function($) {
    'use strict';

    // Initialize Media Uploader
    var mediaUploader;
    
    function initializeContentTypeHandlers() {
        var $contentType = $('#content_type');
        var $uploadField = $('.content-upload-field');
        
        $contentType.on('change', function() {
            var type = $(this).val();
            if (type === 'video' || type === 'audio' || type === 'pdf') {
                $uploadField.show();
            } else {
                $uploadField.hide();
            }
        });
    }

    function initializeFormSubmission() {
        $('#add-course-form').on('submit', function(e) {
            e.preventDefault();
            
            var $form = $(this);
            var $submit = $form.find(':submit');
            
            // Basic validation
            var title = $('#course_title').val();
            if (!title) {
                alert(nlCourseAdmin.i18n.required);
                $('#course_title').focus();
                return;
            }

            // Disable submit button
            $submit.prop('disabled', true);
            
            var formData = new FormData(this);
            formData.append('action', 'nl_add_course');
            formData.append('nonce', nlCourseAdmin.nonce);
            
            $.ajax({
                url: nlCourseAdmin.ajaxurl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        window.location.href = response.data.redirect_url;
                    } else {
                        alert(response.data.message || nlCourseAdmin.i18n.error);
                        $submit.prop('disabled', false);
                    }
                },
                error: function() {
                    alert(nlCourseAdmin.i18n.error);
                    $submit.prop('disabled', false);
                }
            });
        });
    }

    function initializeTagsInput() {
        var $tagsInput = $('#course_tags');
        
        if ($tagsInput.length) {
            $tagsInput.tagEditor({
                delimiter: ',',
                placeholder: nlCourseAdmin.i18n.tagsPlaceholder || 'Enter tags...'
            });
        }
    }

    // Initialize everything when document is ready
    $(document).ready(function() {
        initializeContentTypeHandlers();
        initializeFormSubmission();
        initializeTagsInput();
    });

})(jQuery);
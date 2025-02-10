<?php
if (!defined('ABSPATH')) exit;

$user_id = get_current_user_id();
?>

<div class="nl-notes-section">
    <!-- Notes Header -->
    <div class="nl-section-header">
        <div class="nl-header-actions">
            <button class="nl-button nl-button-primary" id="nl-new-note">
                <i class="dashicons dashicons-plus-alt"></i>
                <?php _e('New Note', 'nexuslearn'); ?>
            </button>
            <div class="nl-search-box">
                <input type="text" id="nl-search-notes" placeholder="<?php _e('Search notes...', 'nexuslearn'); ?>">
                <i class="dashicons dashicons-search"></i>
            </div>
        </div>
    </div>

    <!-- Notes Content -->
    <div class="nl-notes-container">
        <!-- Notes Sidebar -->
        <div class="nl-notes-sidebar">
            <div class="nl-notes-filters">
                <select id="nl-notes-course-filter">
                    <option value=""><?php _e('All Courses', 'nexuslearn'); ?></option>
                    <?php
                    $enrolled_courses = get_user_meta($user_id, 'nl_enrolled_courses', true) ?: [];
                    foreach ($enrolled_courses as $course_id) {
                        $course = get_post($course_id);
                        if ($course) {
                            echo '<option value="' . esc_attr($course_id) . '">' . esc_html($course->post_title) . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="nl-notes-list">
                <!-- Notes will be loaded here via AJAX -->
                <div class="nl-loading">
                    <?php _e('Loading notes...', 'nexuslearn'); ?>
                </div>
            </div>
        </div>

        <!-- Notes Editor -->
        <div class="nl-notes-editor">
            <div class="nl-editor-placeholder">
                <i class="dashicons dashicons-welcome-write-blog"></i>
                <p><?php _e('Select a note to view or create a new one', 'nexuslearn'); ?></p>
            </div>
            <div class="nl-editor-content" style="display: none;">
                <div class="nl-editor-header">
                    <input type="text" id="nl-note-title" placeholder="<?php _e('Note title', 'nexuslearn'); ?>">
                    <div class="nl-editor-meta">
                        <select id="nl-note-course">
                            <option value=""><?php _e('Select Course', 'nexuslearn'); ?></option>
                            <?php
                            foreach ($enrolled_courses as $course_id) {
                                $course = get_post($course_id);
                                if ($course) {
                                    echo '<option value="' . esc_attr($course_id) . '">' . esc_html($course->post_title) . '</option>';
                                }
                            }
                            ?>
                        </select>
                        <select id="nl-note-lesson">
                            <option value=""><?php _e('Select Lesson', 'nexuslearn'); ?></option>
                        </select>
                    </div>
                </div>
                <div class="nl-editor-body">
                    <?php 
                    wp_editor('', 'nl-note-content', array(
                        'media_buttons' => false,
                        'textarea_rows' => 20,
                        'teeny' => true,
                        'quicktags' => true,
                        'tinymce' => array(
                            'toolbar1' => 'bold,italic,underline,bullist,numlist,link,unlink',
                            'toolbar2' => '',
                        )
                    ));
                    ?>
                </div>
                <div class="nl-editor-footer">
                    <button class="nl-button nl-button-secondary" id="nl-cancel-note">
                        <?php _e('Cancel', 'nexuslearn'); ?>
                    </button>
                    <button class="nl-button nl-button-primary" id="nl-save-note">
                        <?php _e('Save Note', 'nexuslearn'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Load notes on page load
    loadNotes();

    

    // Handle new note button
    $('#nl-new-note').on('click', function() {
        showEditor(true);
    });

    // Handle cancel button
    $('#nl-cancel-note').on('click', function() {
        showEditor(false);
    });

    // Handle course selection change
    $('#nl-note-course').on('change', function() {
        loadLessons($(this).val());
    });

    // Handle note saving
    $('#nl-save-note').on('click', function() {
        saveNote();
    });

    function loadNotes() {
        $('.nl-notes-list').html('<div class="nl-loading">Loading notes...</div>');
        
        $.ajax({
            url: nlDashboard.ajaxUrl,
            type: 'POST',
            data: {
                action: 'nl_get_notes',
                nonce: nlDashboard.nonce,
                course_id: $('#nl-notes-course-filter').val()
            },
            success: function(response) {
                if (response.success) {
                    updateNotesList(response.data);
                } else {
                    showNotification('Failed to load notes', 'error');
                }
            },
            error: function() {
                showNotification('Error loading notes', 'error');
                $('.nl-notes-list').html('<div class="nl-empty-state">Error loading notes</div>');
            }
        });
    }

    function showEditor(show) {
        if (show) {
            $('.nl-editor-placeholder').hide();
            $('.nl-editor-content').show();
            resetEditor();
        } else {
            $('.nl-editor-placeholder').show();
            $('.nl-editor-content').hide();
        }
    }

    function resetEditor() {
        $('#nl-note-title').val('');
        $('#nl-note-course').val('');
        $('#nl-note-lesson').val('');
        if (tinymce.get('nl-note-content')) {
            tinymce.get('nl-note-content').setContent('');
        }
    }

    function loadLessons(courseId) {
        if (!courseId) {
            $('#nl-note-lesson').html('<option value="">Select Lesson</option>');
            return;
        }

        $.ajax({
            url: nlDashboard.ajaxUrl,
            type: 'POST',
            data: {
                action: 'nl_get_course_lessons',
                nonce: nlDashboard.nonce,
                course_id: courseId
            },
            success: function(response) {
                if (response.success) {
                    updateLessonDropdown(response.data);
                } else {
                    showNotification('Failed to load lessons', 'error');
                }
            },
            error: function() {
                showNotification('Error loading lessons', 'error');
            }
        });
    }

    function saveNote() {
        const title = $('#nl-note-title').val().trim();
        if (!title) {
            showNotification('Please enter a note title', 'error');
            return;
        }

        let content = '';
        if (tinymce.get('nl-note-content')) {
            content = tinymce.get('nl-note-content').getContent().trim();
        } else {
            content = $('#nl-note-content').val().trim();
        }

        if (!content) {
            showNotification('Please enter note content', 'error');
            return;
        }

        // Disable save button and show loading state
        const $saveButton = $('#nl-save-note');
        $saveButton.prop('disabled', true).text('Saving...');
        
        $.ajax({
            url: nlDashboard.ajaxUrl,
            type: 'POST',
            data: {
                action: 'nl_save_note',
                nonce: nlDashboard.nonce,
                title: title,
                content: content,
                course_id: $('#nl-note-course').val(),
                lesson_id: $('#nl-note-lesson').val()
            },
            success: function(response) {
                if (response.success) {
                    showNotification('Note saved successfully', 'success');
                    loadNotes();
                    showEditor(false);
                } else {
                    showNotification(response.data.message || 'Failed to save note', 'error');
                }
            },
            error: function() {
                showNotification('Error saving note', 'error');
            },
            complete: function() {
                // Re-enable save button
                $saveButton.prop('disabled', false).text('Save Note');
            }
        });
    }

    function updateNotesList(notes) {
        var $list = $('.nl-notes-list');
        $list.empty();

        if (!notes || notes.length === 0) {
            $list.html('<div class="nl-empty-state">No notes found</div>');
            return;
        }

        notes.forEach(function(note) {
            $list.append(
                '<div class="nl-note-item" data-id="' + note.id + '">' +
                    '<h4>' + note.title + '</h4>' +
                    '<div class="nl-note-meta">' +
                        '<span class="nl-note-course">' + (note.course_title || 'No Course') + '</span>' +
                        '<span class="nl-note-date">' + note.created_at + '</span>' +
                    '</div>' +
                '</div>'
            );
        });
    }

    function updateLessonDropdown(lessons) {
        var $select = $('#nl-note-lesson');
        $select.empty().append('<option value="">Select Lesson</option>');
        
        if (lessons && lessons.length > 0) {
            lessons.forEach(function(lesson) {
                $select.append('<option value="' + lesson.id + '">' + lesson.title + '</option>');
            });
        }
    }

    function showNotification(message, type = 'success') {
        // Remove any existing notifications
        $('.nl-popup-notification').remove();
        
        // Create popup notification
        const $popup = $(`
            <div class="nl-popup-notification nl-popup-${type}">
                <div class="nl-popup-content">
                    <div class="nl-popup-icon">
                        ${type === 'success' ? '✓' : '✕'}
                    </div>
                    <div class="nl-popup-message">${message}</div>
                </div>
            </div>
        `).appendTo('body');
        
        // Show with animation
        setTimeout(() => $popup.addClass('show'), 10);
        
        // Remove after delay
        setTimeout(() => {
            $popup.removeClass('show');
            setTimeout(() => $popup.remove(), 300);
        }, 2000);
    }

    // Add notification styles
    if (!$('#nl-notification-styles').length) {
        $('<style id="nl-notification-styles">')
            .text(`
                .nl-popup-notification {
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%) scale(0.8);
                    background: white;
                    padding: 20px;
                    border-radius: 10px;
                    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
                    opacity: 0;
                    transition: all 0.3s ease;
                    z-index: 9999;
                }

                .nl-popup-notification.show {
                    opacity: 1;
                    transform: translate(-50%, -50%) scale(1);
                }

                .nl-popup-content {
                    display: flex;
                    align-items: center;
                    gap: 15px;
                }

                .nl-popup-icon {
                    width: 40px;
                    height: 40px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 24px;
                    color: white;
                }

                .nl-popup-success .nl-popup-icon {
                    background-color: #48bb78;
                }

                .nl-popup-error .nl-popup-icon {
                    background-color: #f56565;
                }

                .nl-popup-message {
                    font-size: 16px;
                    color: #2d3748;
                }
            `)
            .appendTo('head');
    }

    // Update the saveNote function to use the new notification:
    function saveNote() {
        const title = $('#nl-note-title').val().trim();
        if (!title) {
            showNotification('Please enter a note title', 'error');
            return;
        }

        let content = '';
        if (tinymce.get('nl-note-content')) {
            content = tinymce.get('nl-note-content').getContent().trim();
        } else {
            content = $('#nl-note-content').val().trim();
        }

        if (!content) {
            showNotification('Please enter note content', 'error');
            return;
        }

        // Disable save button and show loading state
        const $saveButton = $('#nl-save-note');
        $saveButton.prop('disabled', true).text('Saving...');
        
        $.ajax({
            url: nlDashboard.ajaxUrl,
            type: 'POST',
            data: {
                action: 'nl_save_note',
                nonce: nlDashboard.nonce,
                title: title,
                content: content,
                course_id: $('#nl-note-course').val(),
                lesson_id: $('#nl-note-lesson').val()
            },
            success: function(response) {
                if (response.success) {
                    showNotification('Note saved successfully', 'success');
                    loadNotes();
                    showEditor(false);
                } else {
                    showNotification(response.data.message || 'Failed to save note', 'error');
                }
            },
            error: function() {
                showNotification('Error saving note', 'error');
            },
            complete: function() {
                // Re-enable save button
                $saveButton.prop('disabled', false).text('Save Note');
            }
        });
    }
    // Add notification styles
    if (!$('#nl-notification-styles').length) {
        $('<style id="nl-notification-styles">')
            .text(`
                .nl-notification {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    padding: 12px 24px;
                    border-radius: 6px;
                    color: white;
                    font-size: 14px;
                    z-index: 9999;
                    animation: slideIn 0.3s ease-out;
                }
                .nl-notification-success {
                    background-color: #48bb78;
                }
                .nl-notification-error {
                    background-color: #f56565;
                }
                @keyframes slideIn {
                    from {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
            `)
            .appendTo('head');
    }
});
</script>
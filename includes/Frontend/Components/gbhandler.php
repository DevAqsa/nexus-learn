<?php
// Add these functions to your theme's functions.php or a separate plugin file

// Register AJAX handlers
add_action('wp_ajax_load_semester_data', 'nl_load_semester_data');
add_action('wp_ajax_dismiss_announcement', 'nl_dismiss_announcement');
add_action('wp_ajax_submit_assignment', 'nl_submit_assignment');

function nl_load_semester_data() {
    check_ajax_referer('nexuslearn_nonce', 'nonce');
    
    $semester = sanitize_text_field($_POST['semester']);
    $tab = sanitize_key($_POST['tab']);
    $user_id = get_current_user_id();
    
    $response = array(
        'success' => true,
        'data' => array(
            'html' => '',
            'statistics' => array()
        )
    );
    
    // Get semester data based on tab
    switch ($tab) {
        case 'grades':
            $grades = get_student_grades($user_id, $semester);
            ob_start();
            include(plugin_dir_path(__FILE__) . 'templates/grades-table.php');
            $response['data']['html'] = ob_get_clean();
            $response['data']['statistics'] = calculate_grade_statistics($grades);
            break;
            
        case 'quizzes':
            $quizzes = get_student_quizzes($user_id, $semester);
            ob_start();
            include(plugin_dir_path(__FILE__) . 'templates/quizzes-table.php');
            $response['data']['html'] = ob_get_clean();
            break;
            
        case 'assignments':
            $assignments = get_student_assignments($user_id, $semester);
            ob_start();
            include(plugin_dir_path(__FILE__) . 'templates/assignments-table.php');
            $response['data']['html'] = ob_get_clean();
            break;
    }
    
    wp_send_json($response);
}

function nl_dismiss_announcement() {
    check_ajax_referer('nexuslearn_nonce', 'nonce');
    
    $announcement_id = intval($_POST['announcement_id']);
    $user_id = get_current_user_id();
    
    // Add announcement ID to user's dismissed announcements
    $dismissed = get_user_meta($user_id, 'nl_dismissed_announcements', true);
    if (!is_array($dismissed)) {
        $dismissed = array();
    }
    
    $dismissed[] = $announcement_id;
    update_user_meta($user_id, 'nl_dismissed_announcements', $dismissed);
    
    wp_send_json_success();
}

function nl_submit_assignment() {
    check_ajax_referer('nexuslearn_nonce', 'nonce');
    
    $assignment_id = intval($_POST['assignment_id']);
    $user_id = get_current_user_id();
    
    // Handle file upload
    if (!empty($_FILES['assignment_file'])) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        $uploaded_file = wp_handle_upload($_FILES['assignment_file'], array('test_form' => false));
        
        if ($uploaded_file && !isset($uploaded_file['error'])) {
            // Save assignment submission
            $submission_data = array(
                'assignment_id' => $assignment_id,
                'user_id' => $user_id,
                'file_url' => $uploaded_file['url'],
                'file_path' => $uploaded_file['file'],
                'submission_date' => current_time('mysql')
            );
            
            // Save to database
            save_assignment_submission($submission_data);
            
            wp_send_json_success(array(
                'message' => 'Assignment submitted successfully'
            ));
        } else {
            wp_send_json_error(array(
                'message' => $uploaded_file['error']
            ));
        }
    } else {
        wp_send_json_error(array(
            'message' => 'No file uploaded'
        ));
    }
}

// Helper function to calculate grade statistics
function calculate_grade_statistics($grades) {
    $statistics = array(
        'gradeDistribution' => array(),
        'averages' => array()
    );
    
    // Calculate grade distribution
    foreach ($grades as $grade) {
        $percentage = floatval($grade['percentage']);
        if ($percentage >= 93) $statistics['gradeDistribution']['A']++;
        else if ($percentage >= 90) $statistics['gradeDistribution']['A-']++;
        else if ($percentage >= 87) $statistics['gradeDistribution']['B+']++;
        // Add more grade ranges...
    }
    
    // Calculate averages
    $total_percentage = 0;
    $count = count($grades);
    foreach ($grades as $grade) {
        $total_percentage += floatval($grade['percentage']);
    }
    $statistics['averages']['overall'] = $count > 0 ? $total_percentage / $count : 0;
    
    return $statistics;
}

// Helper function to save assignment submission
function save_assignment_submission($data) {
    global $wpdb;
    $table = $wpdb->prefix . 'nexuslearn_submissions';
    
    return $wpdb->insert($table, $data, array(
        '%d', // assignment_id
        '%d', // user_id
        '%s', // file_url
        '%s', // file_path
        '%s'  // submission_date
    ));
}
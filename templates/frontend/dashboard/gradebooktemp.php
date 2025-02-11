<?php
if (!defined('ABSPATH')) exit;

$current_user_id = get_current_user_id();
$current_semester = "FALL 2024";
$current_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'grades';

// Additional functions for new features
function get_student_quizzes($user_id, $semester) {
    global $wpdb;
    // Placeholder data - replace with actual database query
    return [
        [
            'course_code' => 'MTH501',
            'quiz_name' => 'Quiz 1',
            'due_date' => '2024-03-15',
            'status' => 'completed',
            'score' => '85',
            'total_marks' => '100'
        ],
        [
            'course_code' => 'CS504',
            'quiz_name' => 'Quiz 2',
            'due_date' => '2024-03-20',
            'status' => 'pending',
            'score' => '-',
            'total_marks' => '50'
        ]
    ];
}

function get_student_assignments($user_id, $semester) {
    global $wpdb;
    // Placeholder data - replace with actual database query
    return [
        [
            'course_code' => 'MTH501',
            'assignment_name' => 'Assignment 1',
            'due_date' => '2024-03-25',
            'status' => 'submitted',
            'score' => '92',
            'total_marks' => '100',
            'feedback' => 'Excellent work on the proofs.'
        ],
        [
            'course_code' => 'CS504',
            'assignment_name' => 'Programming Project',
            'due_date' => '2024-04-01',
            'status' => 'in_progress',
            'score' => '-',
            'total_marks' => '150',
            'feedback' => ''
        ]
    ];
}

function get_announcements() {
    // Placeholder data - replace with actual database query
    return [
        [
            'title' => 'AI CourseSchedule Released',
            'date' => '2025-02-28',
            'content' => 'The schedule has been published. Please check your student portal for details.',
            'priority' => 'high'
        ],
        [
            'title' => 'Assignment Deadline Extension',
            'date' => '2025-02-25',
            'content' => 'Due to the upcoming holiday, all assignments due next week have been extended by 48 hours.',
            'priority' => 'medium'
        ]
    ];
}

// $grades = get_student_grades($current_user_id, $current_semester);
$quizzes = get_student_quizzes($current_user_id, $current_semester);
$assignments = get_student_assignments($current_user_id, $current_semester);
$announcements = get_announcements();
?>

<div class="nl-gradebook-wrapper">
    <!-- Enhanced Navigation -->
    <div class="nl-gradebook-nav">
        <a href="?page=gradebook&tab=grades" 
           class="nl-nav-item <?php echo $current_tab === 'grades' ? 'active' : ''; ?>">
            <i class="dashicons dashicons-book"></i>
            <?php _e('Student Grade Book', 'nexuslearn'); ?>
        </a>
        <a href="?page=gradebook&tab=quizzes" 
           class="nl-nav-item <?php echo $current_tab === 'quizzes' ? 'active' : ''; ?>">
            <i class="dashicons dashicons-editor-help"></i>
            <?php _e('Quizzes', 'nexuslearn'); ?>
        </a>
        <a href="?page=gradebook&tab=assignments" 
           class="nl-nav-item <?php echo $current_tab === 'assignments' ? 'active' : ''; ?>">
            <i class="dashicons dashicons-portfolio"></i>
            <?php _e('Assignments', 'nexuslearn'); ?>
        </a>
        <a href="?page=gradebook&tab=grading" 
           class="nl-nav-item <?php echo $current_tab === 'grading' ? 'active' : ''; ?>">
            <i class="dashicons dashicons-microphone"></i>
            <?php _e('Imp Announcements', 'nexuslearn'); ?>
        </a>
       
        <a href="?page=gradebook&tab=grading" 
           class="nl-nav-item <?php echo $current_tab === 'grading' ? 'active' : ''; ?>">
            <i class="dashicons dashicons-chart-bar"></i>
            <?php _e('Grading Scheme', 'nexuslearn'); ?>
        </a>
        
    </div>

    <!-- Announcements Section -->
    <div class="nl-announcements">
        <h3><?php _e('Important Announcements', 'nexuslearn'); ?></h3>
        <div class="nl-announcements-list">
            <?php foreach ($announcements as $announcement): ?>
                <div class="nl-announcement-item priority-<?php echo esc_attr($announcement['priority']); ?>">
                    <div class="nl-announcement-header">
                        <h4><?php echo esc_html($announcement['title']); ?></h4>
                        <span class="nl-announcement-date">
                            <?php echo esc_html($announcement['date']); ?>
                        </span>
                    </div>
                    <div class="nl-announcement-content">
                        <?php echo wp_kses_post($announcement['content']); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="nl-gradebook-content">
        <?php if ($current_tab === 'quizzes'): ?>
            <div class="nl-quizzes-section">
                <h2><?php _e('Course Quizzes', 'nexuslearn'); ?></h2>
                <table class="nl-quizzes-table">
                    <thead>
                        <tr>
                            <th><?php _e('Course', 'nexuslearn'); ?></th>
                            <th><?php _e('Quiz', 'nexuslearn'); ?></th>
                            <th><?php _e('Due Date', 'nexuslearn'); ?></th>
                            <th><?php _e('Status', 'nexuslearn'); ?></th>
                            <th><?php _e('Score', 'nexuslearn'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($quizzes as $quiz): ?>
                            <tr>
                                <td><?php echo esc_html($quiz['course_code']); ?></td>
                                <td><?php echo esc_html($quiz['quiz_name']); ?></td>
                                <td><?php echo esc_html($quiz['due_date']); ?></td>
                                <td>
                                    <span class="nl-status-badge <?php echo esc_attr($quiz['status']); ?>">
                                        <?php echo esc_html(ucfirst($quiz['status'])); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php 
                                    echo $quiz['score'] !== '-' 
                                        ? esc_html($quiz['score'] . '/' . $quiz['total_marks']) 
                                        : '-'; 
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php elseif ($current_tab === 'assignments'): ?>
            <div class="nl-assignments-section">
                <h2><?php _e('Course Assignments', 'nexuslearn'); ?></h2>
                <table class="nl-assignments-table">
                    <thead>
                        <tr>
                            <th><?php _e('Course', 'nexuslearn'); ?></th>
                            <th><?php _e('Assignment', 'nexuslearn'); ?></th>
                            <th><?php _e('Due Date', 'nexuslearn'); ?></th>
                            <th><?php _e('Status', 'nexuslearn'); ?></th>
                            <th><?php _e('Score', 'nexuslearn'); ?></th>
                            <th><?php _e('Feedback', 'nexuslearn'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($assignments as $assignment): ?>
                            <tr>
                                <td><?php echo esc_html($assignment['course_code']); ?></td>
                                <td><?php echo esc_html($assignment['assignment_name']); ?></td>
                                <td><?php echo esc_html($assignment['due_date']); ?></td>
                                <td>
                                    <span class="nl-status-badge <?php echo esc_attr($assignment['status']); ?>">
                                        <?php echo esc_html(ucfirst(str_replace('_', ' ', $assignment['status']))); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php 
                                    echo $assignment['score'] !== '-' 
                                        ? esc_html($assignment['score'] . '/' . $assignment['total_marks']) 
                                        : '-'; 
                                    ?>
                                </td>
                                <td><?php echo esc_html($assignment['feedback']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php elseif ($current_tab === 'grading'): ?>
            <div class="nl-grading-scheme">
                <h2><?php _e('Grading Scheme', 'nexuslearn'); ?></h2>
                <div class="nl-grading-tables">
                    <div class="nl-grade-distribution">
                        <h3><?php _e('Grade Distribution', 'nexuslearn'); ?></h3>
                        <table class="nl-grade-table">
                            <thead>
                                <tr>
                                    <th><?php _e('Percentage Range', 'nexuslearn'); ?></th>
                                    <th><?php _e('Letter Grade', 'nexuslearn'); ?></th>
                                    <th><?php _e('Grade Points', 'nexuslearn'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>93-100</td>
                                    <td>A</td>
                                    <td>4.0</td>
                                </tr>
                                <tr>
                                    <td>90-92</td>
                                    <td>A-</td>
                                    <td>3.7</td>
                                </tr>
                                <tr>
                                    <td>87-89</td>
                                    <td>B+</td>
                                    <td>3.3</td>
                                </tr>
                                <!-- Add more grade ranges as needed -->
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="nl-assessment-weights">
                        <h3><?php _e('Assessment Weights', 'nexuslearn'); ?></h3>
                        <table class="nl-weights-table">
                            <thead>
                                <tr>
                                    <th><?php _e('Assessment Type', 'nexuslearn'); ?></th>
                                    <th><?php _e('Weight (%)', 'nexuslearn'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php _e('Assignments', 'nexuslearn'); ?></td>
                                    <td>30</td>
                                </tr>
                                <tr>
                                    <td><?php _e('Quizzes', 'nexuslearn'); ?></td>
                                    <td>20</td>
                                </tr>
                                <tr>
                                    <td><?php _e('Midterm', 'nexuslearn'); ?></td>
                                    <td>20</td>
                                </tr>
                                <tr>
                                    <td><?php _e('Final Exam', 'nexuslearn'); ?></td>
                                    <td>30</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <!-- Original grades and other content remains the same -->
            <!-- <?php include 'original-grades-content.php'; ?> -->
        <?php endif; ?>
    </div>
</div>


<script>
jQuery(document).ready(function($) {
    // Semester selection handling
    $('#semester-select').on('change', function() {
        const selectedSemester = $(this).val();
        const currentTab = new URLSearchParams(window.location.search).get('tab') || 'grades';
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'load_semester_data',
                semester: selectedSemester,
                tab: currentTab,
                nonce: nexusLearn.nonce
            },
            beforeSend: function() {
                $('.nl-gradebook-content').addClass('loading');
            },
            success: function(response) {
                if (response.success) {
                    $('.nl-gradebook-content').html(response.data.html);
                    updateCharts(response.data.statistics);
                } else {
                    alert(response.data.message || 'Error loading data');
                }
            },
            error: function() {
                alert('Failed to load semester data');
            },
            complete: function() {
                $('.nl-gradebook-content').removeClass('loading');
            }
        });
    });

    // Initialize charts for grade distribution
    function initializeCharts() {
        if ($('#grade-distribution-chart').length) {
            const ctx = document.getElementById('grade-distribution-chart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'C-', 'D', 'F'],
                    datasets: [{
                        label: 'Grade Distribution',
                        data: [10, 15, 20, 25, 30, 25, 20, 15, 10, 5],
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Students'
                            }
                        }
                    }
                }
            });
        }
    }

    // Update charts with new data
    function updateCharts(statistics) {
        if (statistics && statistics.gradeDistribution) {
            const chart = Chart.getChart('grade-distribution-chart');
            if (chart) {
                chart.data.datasets[0].data = statistics.gradeDistribution;
                chart.update();
            }
        }
    }

    // Handle announcement dismissal
    $('.nl-announcement-item .dismiss-btn').on('click', function(e) {
        e.preventDefault();
        const announcementId = $(this).data('id');
        const $announcement = $(this).closest('.nl-announcement-item');

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'dismiss_announcement',
                announcement_id: announcementId,
                nonce: nexusLearn.nonce
            },
            success: function(response) {
                if (response.success) {
                    $announcement.slideUp();
                }
            }
        });
    });

    // Initialize tooltips
    $('[data-tooltip]').tooltip();

    // Handle assignment submission
    $('.submit-assignment-btn').on('click', function(e) {
        e.preventDefault();
        const assignmentId = $(this).data('assignment-id');
        const $modal = $('#assignment-submission-modal');
        
        // Open submission modal
        $modal.modal('show');
        
        // Handle file upload
        $('#assignment-upload-form').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('action', 'submit_assignment');
            formData.append('assignment_id', assignmentId);
            formData.append('nonce', nexusLearn.nonce);

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        $modal.modal('hide');
                        window.location.reload();
                    } else {
                        alert(response.data.message || 'Error submitting assignment');
                    }
                }
            });
        });
    });

    // CGPA Calculator functionality
    const $cgpaCalculator = $('.nl-cgpa-calculator');
    if ($cgpaCalculator.length) {
        let courseRows = 1;

        // Add new course row
        $('#add-course-btn').on('click', function() {
            courseRows++;
            const newRow = `
                <div class="course-row" data-row="${courseRows}">
                    <input type="text" class="course-code" placeholder="Course Code">
                    <input type="number" class="credit-hours" min="1" max="6" placeholder="Credit Hours">
                    <select class="grade-select">
                        <option value="4.0">A</option>
                        <option value="3.7">A-</option>
                        <option value="3.3">B+</option>
                        <option value="3.0">B</option>
                        <option value="2.7">B-</option>
                        <option value="2.3">C+</option>
                        <option value="2.0">C</option>
                        <option value="1.7">C-</option>
                        <option value="1.0">D</option>
                        <option value="0.0">F</option>
                    </select>
                    <button type="button" class="remove-course-btn">Remove</button>
                </div>
            `;
            $('#course-list').append(newRow);
        });

        // Remove course row
        $(document).on('click', '.remove-course-btn', function() {
            $(this).closest('.course-row').remove();
            calculateCGPA();
        });

        // Calculate CGPA
        function calculateCGPA() {
            let totalPoints = 0;
            let totalCredits = 0;

            $('.course-row').each(function() {
                const creditHours = parseFloat($(this).find('.credit-hours').val()) || 0;
                const gradePoints = parseFloat($(this).find('.grade-select').val()) || 0;
                
                totalPoints += creditHours * gradePoints;
                totalCredits += creditHours;
            });

            const cgpa = totalCredits > 0 ? (totalPoints / totalCredits).toFixed(2) : '0.00';
            $('#cgpa-result').text(cgpa);
        }

        // Trigger CGPA calculation on input change
        $(document).on('change', '.credit-hours, .grade-select', calculateCGPA);
    }

    // Initialize on page load
    initializeCharts();
});
</script>
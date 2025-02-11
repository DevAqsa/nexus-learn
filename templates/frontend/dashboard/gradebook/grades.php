<?php
if (!defined('ABSPATH')) exit;

$current_user_id = get_current_user_id();
$current_semester = isset($_GET['semester']) ? sanitize_text_field($_GET['semester']) : 'FALL 2024';

// Dummy data for demonstration - Replace with actual database queries
$grades = [
    [
        'course_code' => 'CS501',
        'course_name' => 'Advanced Programming',
        'midterm' => 85,
        'assignments' => 90,
        'quizzes' => 88,
        'final' => 87,
        'total' => 87.5,
        'grade' => 'A'
    ],
    [
        'course_code' => 'MTH401',
        'course_name' => 'Applied Mathematics',
        'midterm' => 82,
        'assignments' => 88,
        'quizzes' => 85,
        'final' => 84,
        'total' => 84.75,
        'grade' => 'B+'
    ]
];

// Calculate overall GPA
$total_points = 0;
$course_count = count($grades);
foreach ($grades as $grade) {
    switch ($grade['grade']) {
        case 'A': $total_points += 4.0; break;
        case 'A-': $total_points += 3.7; break;
        case 'B+': $total_points += 3.3; break;
        case 'B': $total_points += 3.0; break;
        case 'B-': $total_points += 2.7; break;
        // Add more grade cases as needed
    }
}
$gpa = $course_count > 0 ? round($total_points / $course_count, 2) : 0;
?>

<div class="nl-grades-section">
    <!-- Semester Selection -->
    <div class="nl-semester-selector">
        <label for="semester-select"><?php _e('Select Semester:', 'nexuslearn'); ?></label>
        <select id="semester-select" class="nl-select">
            <option value="FALL 2024" <?php selected($current_semester, 'FALL 2024'); ?>>
                Fall 2024
            </option>
            <option value="SPRING 2024" <?php selected($current_semester, 'SPRING 2024'); ?>>
                Spring 2024
            </option>
            <option value="SUMMER 2024" <?php selected($current_semester, 'SUMMER 2024'); ?>>
                Summer 2024
            </option>
        </select>
    </div>

    <!-- GPA Overview -->
    <div class="nl-gpa-overview">
        <div class="nl-gpa-card">
            <div class="nl-gpa-value"><?php echo number_format($gpa, 2); ?></div>
            <div class="nl-gpa-label"><?php _e('Current GPA', 'nexuslearn'); ?></div>
        </div>
    </div>

    <!-- Grades Table -->
    <div class="nl-grades-table-container">
        <table class="nl-grades-table">
            <thead>
                <tr>
                    <th><?php _e('Course Code', 'nexuslearn'); ?></th>
                    <th><?php _e('Course Name', 'nexuslearn'); ?></th>
                    <th><?php _e('Midterm', 'nexuslearn'); ?></th>
                    <th><?php _e('Assignments', 'nexuslearn'); ?></th>
                    <th><?php _e('Quizzes', 'nexuslearn'); ?></th>
                    <th><?php _e('Final', 'nexuslearn'); ?></th>
                    <th><?php _e('Total', 'nexuslearn'); ?></th>
                    <th><?php _e('Grade', 'nexuslearn'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($grades)): ?>
                    <?php foreach ($grades as $grade): ?>
                        <tr>
                            <td><?php echo esc_html($grade['course_code']); ?></td>
                            <td><?php echo esc_html($grade['course_name']); ?></td>
                            <td><?php echo esc_html($grade['midterm']); ?>%</td>
                            <td><?php echo esc_html($grade['assignments']); ?>%</td>
                            <td><?php echo esc_html($grade['quizzes']); ?>%</td>
                            <td><?php echo esc_html($grade['final']); ?>%</td>
                            <td><?php echo esc_html($grade['total']); ?>%</td>
                            <td>
                                <span class="nl-grade-badge grade-<?php echo strtolower(str_replace('+', '-plus', $grade['grade'])); ?>">
                                    <?php echo esc_html($grade['grade']); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="nl-empty-state">
                            <div class="nl-empty-icon">📊</div>
                            <h3><?php _e('No Grades Available', 'nexuslearn'); ?></h3>
                            <p><?php _e('Grades for this semester will appear here once they are posted.', 'nexuslearn'); ?></p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Grade Distribution Chart -->
    <div class="nl-grade-distribution">
        <h3><?php _e('Grade Distribution', 'nexuslearn'); ?></h3>
        <canvas id="grade-distribution-chart"></canvas>
    </div>

    <!-- Additional Information -->
    <div class="nl-grades-info">
        <h3><?php _e('Grade Scale Information', 'nexuslearn'); ?></h3>
        <div class="nl-grade-scale-grid">
            <div class="nl-grade-scale-item">
                <span class="nl-grade-label">A (93-100)</span>
                <span class="nl-grade-points">4.0</span>
            </div>
            <div class="nl-grade-scale-item">
                <span class="nl-grade-label">A- (90-92)</span>
                <span class="nl-grade-points">3.7</span>
            </div>
            <div class="nl-grade-scale-item">
                <span class="nl-grade-label">B+ (87-89)</span>
                <span class="nl-grade-points">3.3</span>
            </div>
            <div class="nl-grade-scale-item">
                <span class="nl-grade-label">B (83-86)</span>
                <span class="nl-grade-points">3.0</span>
            </div>
            <!-- Add more grade scales as needed -->
        </div>
    </div>
</div>

<style>
/* Add specific styles for grades page */
.nl-grades-section {
    padding: 20px;
}

.nl-semester-selector {
    margin-bottom: 20px;
}

.nl-gpa-overview {
    background: #f8fafc;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    text-align: center;
}

.nl-gpa-value {
    font-size: 2.5rem;
    font-weight: bold;
    color: #1e40af;
}

.nl-gpa-label {
    color: #64748b;
    font-size: 0.875rem;
}

.nl-grades-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin-bottom: 20px;
}

.nl-grades-table th,
.nl-grades-table td {
    padding: 12px;
    border-bottom: 1px solid #e2e8f0;
}

.nl-grades-table th {
    background: #f8fafc;
    font-weight: 600;
    text-align: left;
}

.nl-grade-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: 600;
    font-size: 0.875rem;
}

.nl-grade-badge.grade-a {
    background: #dcfce7;
    color: #166534;
}

.nl-grade-badge.grade-b-plus {
    background: #dbeafe;
    color: #1e40af;
}

.nl-grade-distribution {
    margin-top: 40px;
    padding: 20px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.nl-grade-scale-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 16px;
    margin-top: 16px;
}

.nl-grade-scale-item {
    display: flex;
    justify-content: space-between;
    padding: 8px;
    background: #f8fafc;
    border-radius: 4px;
}

.nl-grade-points {
    font-weight: 600;
    color: #1e40af;
}
</style>

<script>
jQuery(document).ready(function($) {
    // Handle semester selection change
    $('#semester-select').on('change', function() {
        // Add AJAX call to load semester grades
    });
});
</script>
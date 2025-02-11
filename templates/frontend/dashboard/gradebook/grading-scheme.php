<?php
if (!defined('ABSPATH')) exit;

// Sample grading data - Replace with actual database data
$grade_scheme = [
    ['min' => 93, 'max' => 100, 'grade' => 'A', 'points' => 4.0],
    ['min' => 90, 'max' => 92, 'grade' => 'A-', 'points' => 3.7],
    ['min' => 87, 'max' => 89, 'grade' => 'B+', 'points' => 3.3],
    ['min' => 83, 'max' => 86, 'grade' => 'B', 'points' => 3.0],
    ['min' => 80, 'max' => 82, 'grade' => 'B-', 'points' => 2.7],
    ['min' => 77, 'max' => 79, 'grade' => 'C+', 'points' => 2.3],
    ['min' => 73, 'max' => 76, 'grade' => 'C', 'points' => 2.0],
    ['min' => 70, 'max' => 72, 'grade' => 'C-', 'points' => 1.7],
    ['min' => 67, 'max' => 69, 'grade' => 'D+', 'points' => 1.3],
    ['min' => 63, 'max' => 66, 'grade' => 'D', 'points' => 1.0],
    ['min' => 60, 'max' => 62, 'grade' => 'D-', 'points' => 0.7],
    ['min' => 0, 'max' => 59, 'grade' => 'F', 'points' => 0.0],
];

$assessment_weights = [
    ['component' => 'Assignments', 'weight' => 30],
    ['component' => 'Quizzes', 'weight' => 20],
    ['component' => 'Accessment', 'weight' => 20],
    ['component' => 'Final Accessment', 'weight' => 30]
];
?>

<div class="nl-grading-scheme-section">
    <h2><?php _e('Course Grading Scheme', 'nexuslearn'); ?></h2>
    
    <!-- Grade Distribution -->
    <div class="nl-grade-tables">
        <div class="nl-grade-table-container">
            <h3><?php _e('Grade Scale', 'nexuslearn'); ?></h3>
            <table class="nl-grade-table">
                <thead>
                    <tr>
                        <th><?php _e('Range', 'nexuslearn'); ?></th>
                        <th><?php _e('Letter Grade', 'nexuslearn'); ?></th>
                        <th><?php _e('Grade Points', 'nexuslearn'); ?></th>
                        <th><?php _e('Performance', 'nexuslearn'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($grade_scheme as $grade): ?>
                        <tr class="grade-row grade-<?php echo strtolower($grade['grade']); ?>">
                            <td><?php echo $grade['min'] . '-' . $grade['max']; ?>%</td>
                            <td><?php echo esc_html($grade['grade']); ?></td>
                            <td><?php echo number_format($grade['points'], 1); ?></td>
                            <td>
                                <?php
                                if ($grade['points'] >= 4.0) echo 'Excellent';
                                else if ($grade['points'] >= 3.0) echo 'Good';
                                else if ($grade['points'] >= 2.0) echo 'Satisfactory';
                                else if ($grade['points'] >= 1.0) echo 'Poor';
                                else echo 'Failing';
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Assessment Components -->
        <div class="nl-assessment-container">
            <h3><?php _e('Assessment Components', 'nexuslearn'); ?></h3>
            <div class="nl-assessment-chart">
                <?php foreach ($assessment_weights as $assessment): ?>
                    <div class="nl-assessment-item">
                        <div class="nl-assessment-label">
                            <?php echo esc_html($assessment['component']); ?>
                        </div>
                        <div class="nl-assessment-bar">
                            <div class="nl-bar-fill" style="width: <?php echo esc_attr($assessment['weight']); ?>%">
                                <?php echo esc_html($assessment['weight']); ?>%
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Additional Information -->
    <div class="nl-grading-info">
        <h3><?php _e('Important Notes', 'nexuslearn'); ?></h3>
        <div class="nl-info-cards">
            <div class="nl-info-card">
                <div class="nl-info-icon">ℹ️</div>
                <h4><?php _e('Passing Grade', 'nexuslearn'); ?></h4>
                <p><?php _e('A minimum grade of D (60%) is required to pass the course.', 'nexuslearn'); ?></p>
            </div>
            <div class="nl-info-card">
                <div class="nl-info-icon">⚠️</div>
                <h4><?php _e('Grade Appeals', 'nexuslearn'); ?></h4>
                <p><?php _e('Grade appeals must be submitted within 5 days of grade posting.', 'nexuslearn'); ?></p>
            </div>
            <div class="nl-info-card">
                <div class="nl-info-icon">📝</div>
                <h4><?php _e('Assignments', 'nexuslearn'); ?></h4>
                <p><?php _e('Late assignments will be penalized 10% per day up to 3 days.', 'nexuslearn'); ?></p>
            </div>
        </div>
    </div>
</div>

<style>
.nl-grading-scheme-section {
    padding: 20px;
    background: #f8fafc;
}

.nl-grade-tables {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin: 20px 0;
}

.nl-grade-table-container,
.nl-assessment-container {
    background: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.nl-grade-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

.nl-grade-table th,
.nl-grade-table td {
    padding: 12px;
    border-bottom: 1px solid #e5e7eb;
    text-align: left;
}

.nl-grade-table th {
    background: #f8fafc;
    font-weight: 600;
}

.grade-row.grade-a {
    background: #f0fdf4;
}

.grade-row.grade-b {
    background: #f0f9ff;
}

.grade-row.grade-c {
    background: #fefce8;
}

.grade-row.grade-d {
    background: #fff7ed;
}

.grade-row.grade-f {
    background: #fef2f2;
}

.nl-assessment-chart {
    margin-top: 20px;
}

.nl-assessment-item {
    margin-bottom: 15px;
}

.nl-assessment-label {
    margin-bottom: 5px;
    font-weight: 500;
}

.nl-assessment-bar {
    background: #e5e7eb;
    border-radius: 9999px;
    overflow: hidden;
}

.nl-bar-fill {
    background: #6366f1;
    color: white;
    padding: 8px;
    text-align: right;
    font-size: 0.875rem;
    transition: width 0.3s ease;
}

.nl-grading-info {
    margin-top: 30px;
}

.nl-info-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.nl-info-card {
    background: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: transform 0.2s ease;
}

.nl-info-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.nl-info-icon {
    font-size: 24px;
    margin-bottom: 12px;
}

.nl-info-card h4 {
    color: #1f2937;
    font-size: 1.1rem;
    font-weight: 600;
    margin: 0 0 10px 0;
}

.nl-info-card p {
    color: #4b5563;
    font-size: 0.95rem;
    margin: 0;
    line-height: 1.5;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .nl-grade-tables {
        grid-template-columns: 1fr;
    }
    
    .nl-info-cards {
        grid-template-columns: 1fr;
    }
}

/* Print Styles */
@media print {
    .nl-grading-scheme-section {
        background: none;
        padding: 0;
    }

    .nl-grade-table-container,
    .nl-assessment-container,
    .nl-info-card {
        box-shadow: none;
        border: 1px solid #e5e7eb;
    }

    .nl-bar-fill {
        print-color-adjust: exact;
        -webkit-print-color-adjust: exact;
    }
}

/* Accessibility Improvements */
.nl-assessment-bar {
    position: relative;
}

.nl-bar-fill {
    min-width: 2em;
}

/* High Contrast Mode Support */
@media (forced-colors: active) {
    .nl-bar-fill {
        border: 1px solid CanvasText;
    }
    
    .nl-grade-table th,
    .nl-grade-table td {
        border: 1px solid CanvasText;
    }
}
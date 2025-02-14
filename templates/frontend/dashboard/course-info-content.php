<?php
if (!defined('ABSPATH')) exit;

// Dummy course data - replace with actual data later
$course_info = [
    'code' => 'CS301',
    'title' => 'Data Structures',
    'category' => 'Computer Science/Information Technology',
    'credit_hours' => '3 Credit Hours',
    'section_incharge' => [
        'name' => 'Muhammad Bilal',
        'email' => 'bilal.saleem@vu.edu.pk',
        'phone' => '+92 (42) 111 880 880',
        'ext' => 'Ext.4826'
    ],
    'synopsis' => 'Data Structures is a core course in a typical undergraduate Computer Science Curriculum. The topics covered in the course are among the most fundamental material in all of computer science. The course prepares the students for (and is a prerequisite for) the more advanced material students will encounter in later courses. The course will cover well-known data structures such as dynamic arrays, linked lists, stacks, queues, tree, heap, disjoint sets and table. Three goals will be accomplished: (1) Implement these structures in C++ (2) Determine which structures are appropriate in various situations (3) Confidently learn new structures beyond what\'s presented in this class',
    'learning_outcomes' => [
        'Understand Abstract Data Types such as Lists, Queues etc.',
        'Understand and program Stack operations (Push, Pop, isEmpty)',
        'Understand and implement Queue Operations (Insert, Remove) using Linked Lists',
        'Describe binary Trees',
        'Know about height balanced trees and applications of trees'
    ]
];
?>

<div class="nl-course-info-content">
    <!-- Course Information -->
    <div class="nl-info-section">
        <h2>Course</h2>
        <div class="nl-info-item">
            <span class="nl-course-code"><?php echo esc_html($course_info['code']); ?></span>
            <span class="nl-course-title"><?php echo esc_html($course_info['title']); ?></span>
        </div>
    </div>

    <!-- Category Information -->
    <div class="nl-info-section">
        <h2>Category</h2>
        <div class="nl-info-item">
            <p><?php echo esc_html($course_info['category']); ?></p>
            <p><?php echo esc_html($course_info['credit_hours']); ?></p>
        </div>
    </div>

    <!-- Section Incharge -->
    <div class="nl-info-section">
        <h2>Section Incharge</h2>
        <div class="nl-info-item">
            <p class="nl-instructor-name"><?php echo esc_html($course_info['section_incharge']['name']); ?></p>
            <p class="nl-instructor-email"><?php echo esc_html($course_info['section_incharge']['email']); ?></p>
            <p class="nl-instructor-phone">
                <?php echo esc_html($course_info['section_incharge']['phone']); ?>
                <span class="nl-ext"><?php echo esc_html($course_info['section_incharge']['ext']); ?></span>
            </p>
        </div>
    </div>

    <!-- Course Synopsis -->
    <div class="nl-info-section nl-full-width">
        <h2>Course Synopsis</h2>
        <div class="nl-info-item">
            <p><?php echo esc_html($course_info['synopsis']); ?></p>
        </div>
    </div>

    <!-- Learning Outcomes -->
    <div class="nl-info-section nl-full-width">
        <h2>Learning Outcomes</h2>
        <div class="nl-info-item">
            <p>At the end of the course, you should be able to:</p>
            <ul class="nl-outcomes-list">
                <?php foreach ($course_info['learning_outcomes'] as $outcome): ?>
                    <li><?php echo esc_html($outcome); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>

<style>
.nl-course-info-content {
    padding: 2rem;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

.nl-info-section {
    background: white;
    border-radius: 8px;
    padding: 1.5rem;
}

.nl-info-section.nl-full-width {
    grid-column: 1 / -1;
}

.nl-info-section h2 {
    font-size: 1.1rem;
    color: #1a1a1a;
    margin: 0 0 1rem 0;
    font-weight: 500;
}

.nl-info-item {
    color: #4b5563;
}

.nl-course-code {
    color: #7c3aed;
    font-weight: 500;
    margin-right: 0.5rem;
}

.nl-instructor-name {
    font-weight: 500;
    margin: 0 0 0.5rem 0;
}

.nl-instructor-email {
    color: #7c3aed;
    margin: 0 0 0.5rem 0;
}

.nl-instructor-phone {
    margin: 0;
}

.nl-ext {
    color: #6b7280;
    margin-left: 0.25rem;
}

.nl-outcomes-list {
    margin: 1rem 0;
    padding-left: 1.5rem;
}

.nl-outcomes-list li {
    margin-bottom: 0.75rem;
    line-height: 1.5;
}

/* Responsive Design */
@media (max-width: 768px) {
    .nl-course-info-content {
        grid-template-columns: 1fr;
    }
}
</style>
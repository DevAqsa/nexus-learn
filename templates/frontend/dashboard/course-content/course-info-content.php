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
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 1rem;
    background-color: #f8f9fa;
}

.nl-info-section {
    background: white;
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #e5e7eb;
}

.nl-info-section:first-child {
    border-top: 1px solid #e5e7eb;
}

.nl-info-section h2 {
    font-size: 1.25rem;
    color: #111827;
    margin: 0 0 1rem 0;
    font-weight: 500;
}

.nl-info-item {
    color: #4b5563;
    line-height: 1.6;
}

.nl-course-code {
    color: #111827;
    font-weight: 600;
    font-size: 1.1rem;
    display: block;
    margin-bottom: 0.25rem;
}

.nl-course-title {
    color: #4b5563;
    font-size: 1rem;
}

.nl-instructor-name {
    font-weight: 500;
    color: #111827;
    margin: 0 0 0.5rem 0;
}

.nl-instructor-email {
    color: #6366f1;
    margin: 0 0 0.5rem 0;
    text-decoration: none;
}

.nl-instructor-email:hover {
    text-decoration: underline;
}

.nl-instructor-phone {
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.nl-ext {
    color: #6b7280;
}

.nl-outcomes-list {
    margin: 0.5rem 0;
    padding-left: 1.25rem;
    list-style-type: disc;
}

.nl-outcomes-list li {
    margin-bottom: 0.75rem;
    line-height: 1.6;
    color: #4b5563;
}

/* Header styles */
.course-header {
    background: linear-gradient(135deg, #7c3aed, #6366f1);
    padding: 1rem 2rem;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.course-header h1 {
    font-size: 1.5rem;
    font-weight: 500;
    margin: 0;
}

.back-button {
    color: white;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .nl-info-section {
        padding: 1rem;
    }
    
    .course-header {
        padding: 1rem;
    }
    
    .nl-instructor-phone {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }
    
    .nl-ext {
        margin-left: 0;
    }
}
</style>
<?php
if (!defined('ABSPATH')) exit;

// For demonstration/testing, let's create dummy course data
$dummy_course = (object)[
    'ID' => 237,
    'post_title' => 'Data Structures and Algorithms',
    'post_type' => 'nl_course',
    'post_author' => 1
];

// Dummy lessons data
$dummy_lessons = [
    (object)[
        'ID' => 1,
        'post_title' => 'Introduction',
        'comment_count' => 156,
        'duration' => 'N/A'
    ],
    (object)[
        'ID' => 2,
        'post_title' => 'List Implementation',
        'comment_count' => 33,
        'duration' => '00:00:01'
    ],
    (object)[
        'ID' => 3,
        'post_title' => 'Linked List',
        'comment_count' => 17,
        'duration' => 'N/A'
    ],
    (object)[
        'ID' => 4,
        'post_title' => 'Linked List and Its Types',
        'comment_count' => 60,
        'duration' => 'N/A'
    ],
    (object)[
        'ID' => 5,
        'post_title' => 'ADT and Stack',
        'comment_count' => 38,
        'duration' => 'N/A'
    ],
    (object)[
        'ID' => 6,
        'post_title' => 'Uses of Stack',
        'comment_count' => 58,
        'duration' => 'N/A'
    ],
    (object)[
        'ID' => 7,
        'post_title' => 'Infix and Postfix Expressions',
        'comment_count' => 63,
        'duration' => 'N/A'
    ],
    (object)[
        'ID' => 8,
        'post_title' => 'Implementation of Stack',
        'comment_count' => 45,
        'duration' => 'N/A'
    ]
];

// Dummy instructor data
$dummy_instructor = (object)[
    'ID' => 1,
    'display_name' => 'Dr. Sohail Aslam',
    'designation' => 'Ph.D Computer Science',
    'institution' => 'University of Illinois at Urbana-Champaign',
    'avatar_url' => 'https://via.placeholder.com/200' // Placeholder avatar
];

$course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;
$user_id = get_current_user_id();

// Use dummy data instead of database query
$course = $dummy_course;
$lessons = $dummy_lessons;
?>

<div class="nl-course-content-wrapper">
    <!-- Header -->
    <div class="nl-course-header">
        <h1 class="nl-header-title">
            Index / Lesson
            <a href="<?php echo esc_url(remove_query_arg(['view', 'course_id'])); ?>" class="nl-back-button">
                ⬅ Back
            </a>
        </h1>
    </div>

    <!-- Course Content -->
    <div class="nl-course-content">
        <!-- Instructor Info -->
        <div class="nl-instructor-sidebar">
            <div class="nl-instructor-profile">
                <div class="nl-instructor-avatar">
                    <img src="<?php echo esc_url($dummy_instructor->avatar_url); ?>" 
                         alt="<?php echo esc_attr($dummy_instructor->display_name); ?>">
                </div>
                <h2><?php echo esc_html($dummy_instructor->display_name); ?></h2>
                <p class="nl-instructor-designation">
                    <?php echo esc_html($dummy_instructor->designation); ?>
                </p>
                <p class="nl-instructor-institution">
                    <?php echo esc_html($dummy_instructor->institution); ?>
                </p>
            </div>

            <nav class="nl-course-nav">
                <a href="#" class="nl-nav-item active">
                    <span class="nl-nav-icon">📋</span>
                    Index / Lesson
                </a>
                <a href="#" class="nl-nav-item">
                    <span class="nl-nav-icon">ℹ️</span>
                    Course Information
                </a>
                <a href="#" class="nl-nav-item">
                    <span class="nl-nav-icon">❓</span>
                    FAQs
                </a>
                <a href="#" class="nl-nav-item">
                    <span class="nl-nav-icon">📚</span>
                    Glossary
                </a>
                <a href="#" class="nl-nav-item">
                    <span class="nl-nav-icon">📖</span>
                    Books
                </a>
            </nav>
        </div>

        <!-- Lessons List -->
        <div class="nl-lessons-content">
            <?php foreach ($lessons as $index => $lesson): ?>
                <div class="nl-lesson-item">
                    <div class="nl-lesson-header">
                        <span class="nl-lesson-number"><?php echo $index + 1; ?></span>
                        <span class="nl-lesson-title">
                            <?php echo esc_html($lesson->post_title); ?>
                        </span>
                    </div>
                    
                    <div class="nl-lesson-meta">
                        <div class="nl-lesson-icons">
                            <span class="nl-icon">📝</span>
                            <span class="nl-icon">🎥</span>
                        </div>
                        
                        <div class="nl-lesson-status">
                            <span class="nl-comments">
                                💬 Closed <span class="nl-count"><?php echo $lesson->comment_count; ?></span>
                            </span>
                            <span class="nl-duration">⏱ <?php echo esc_html($lesson->duration); ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<style>
.nl-course-content-wrapper {
    background: #f8f9fa;
    min-height: 100vh;
}

.nl-course-header {
    background: #7c3aed;
    color: white;
    padding: 1.5rem;
    position: relative;
}

.nl-header-title {
    font-size: 1.5rem;
    margin: 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.nl-back-button {
    color: white;
    text-decoration: none;
    font-size: 1rem;
}

.nl-course-content {
    display: flex;
    gap: 2rem;
    padding: 2rem;
}

.nl-instructor-sidebar {
    width: 300px;
    flex-shrink: 0;
}

.nl-instructor-profile {
    background: white;
    border-radius: 8px;
    padding: 1.5rem;
    text-align: center;
    margin-bottom: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.nl-instructor-avatar img {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    margin-bottom: 1rem;
}

.nl-instructor-designation,
.nl-instructor-institution {
    color: #6b7280;
    margin: 0.5rem 0;
}

.nl-course-nav {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.nl-nav-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    color: #4b5563;
    text-decoration: none;
    border-bottom: 1px solid #e5e7eb;
    transition: background-color 0.2s;
}

.nl-nav-item.active {
    background: #f3f4f6;
    font-weight: 500;
}

.nl-nav-icon {
    margin-right: 0.75rem;
}

.nl-lessons-content {
    flex: 1;
}

.nl-lesson-item {
    background: white;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.nl-lesson-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 0.5rem;
}

.nl-lesson-number {
    color: #6b7280;
}

.nl-lesson-title {
    color: #7c3aed;
    font-weight: 500;
}

.nl-lesson-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: #6b7280;
    font-size: 0.875rem;
}

.nl-lesson-icons {
    display: flex;
    gap: 0.5rem;
}

.nl-lesson-status {
    display: flex;
    gap: 1rem;
}

.nl-comments {
    color: #ef4444;
}

.nl-count {
    background: #fecaca;
    padding: 0.125rem 0.375rem;
    border-radius: 999px;
    font-size: 0.75rem;
}

@media (max-width: 768px) {
    .nl-course-content {
        flex-direction: column;
    }

    .nl-instructor-sidebar {
        width: 100%;
    }
}
</style>
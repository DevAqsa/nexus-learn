<?php
if (!defined('ABSPATH')) exit;

// For demonstration/testing, let's create dummy course data
$dummy_course = (object)[
    'ID' => 237,
    'post_title' => 'Data Structures and Algorithms',
    'post_type' => 'nl_course',
    'post_author' => 1
];

// Dummy lessons data with corrected syntax
$dummy_lessons = [
    (object)[
        'ID' => 1,
        'post_title' => 'Introduction',
        'comment_count' => 156,
        'duration' => 'N/A',
        'video_url' => 'https://www.youtube.com/embed/VTLCoHnyACE',
        'has_slides' => true,
        'has_resources' => true
    ],
    (object)[
        'ID' => 2,
        'post_title' => 'List Implementation',
        'comment_count' => 33,
        'duration' => '00:00:01',
        'video_url' => 'https://www.youtube.com/embed/VTLCoHnyACE',
        'has_slides' => true,
        'has_resources' => false
    ],
    // ... other lessons similarly structured
];

// Dummy instructor data
$dummy_instructor = (object)[
    'ID' => 1,
    'display_name' => 'Dr. Sohail Aslam',
    'designation' => 'Ph.D Computer Science',
    'institution' => 'University of Illinois at Urbana-Champaign',
    'avatar_url' => 'https://via.placeholder.com/200'
];

$course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;
$user_id = get_current_user_id();
$current_page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : 'dashboard';

// Use dummy data instead of database query
$course = $dummy_course;
$lessons = $dummy_lessons;
?>

<div class="nl-course-container">
    <!-- Course Header -->
    <header class="nl-course-header">
        <div class="nl-header-content">
            <h1>Index / Lesson</h1>
            <a href="<?php echo esc_url(remove_query_arg(['view', 'course_id'])); ?>" class="nl-back-btn">
                <span class="dashicons dashicons-arrow-left-alt"></span>
                Back
            </a>
        </div>
    </header>

    <div class="nl-course-layout">
        <!-- Sidebar -->
        <aside class="nl-course-sidebar">
            <!-- Instructor Profile -->
            <div class="nl-instructor-card">
                <div class="nl-instructor-avatar">
                    <img src="<?php echo esc_url($dummy_instructor->avatar_url); ?>" 
                         alt="<?php echo esc_attr($dummy_instructor->display_name); ?>">
                </div>
                <div class="nl-instructor-info">
                    <h3><?php echo esc_html($dummy_instructor->display_name); ?></h3>
                    <p class="nl-instructor-title"><?php echo esc_html($dummy_instructor->designation); ?></p>
                    <p class="nl-instructor-institution"><?php echo esc_html($dummy_instructor->institution); ?></p>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="nl-course-nav">
                <a href="#lessons" class="nl-nav-item active">
                    <span class="nl-nav-icon">📋</span>
                    <span>Index / Lesson</span>
                </a>
                <a href="#info" class="nl-nav-item">
                    <span class="nl-nav-icon">ℹ️</span>
                    <span>Course Information</span>
                </a>
                <a href="#faqs" class="nl-nav-item">
                    <span class="nl-nav-icon">❓</span>
                    <span>FAQs</span>
                </a>
                <a href="#glossary" class="nl-nav-item">
                    <span class="nl-nav-icon">📚</span>
                    <span>Glossary</span>
                </a>
                <a href="#books" class="nl-nav-item">
                    <span class="nl-nav-icon">📖</span>
                    <span>Books</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="nl-course-main">
            <!-- Lessons List -->
            <div class="nl-lessons-list">
                <?php foreach ($lessons as $index => $lesson): ?>
                    <div class="nl-lesson-card">
                        <div class="nl-lesson-header">
                            <div class="nl-lesson-number"><?php echo $index + 1; ?></div>
                            <h3 class="nl-lesson-title"><?php echo esc_html($lesson->post_title); ?></h3>
                        </div>
                        
                        <div class="nl-lesson-content">
                            <div class="nl-lesson-actions">
                                <?php if ($lesson->has_resources): ?>
                                    <span class="nl-action-icon" title="Resources Available">
                                        <span class="dashicons dashicons-media-document"></span>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if ($lesson->has_slides): ?>
                                    <span class="nl-action-icon" title="Slides Available">
                                        <span class="dashicons dashicons-slides"></span>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if (!empty($lesson->video_url)): ?>
                                    <button class="nl-video-btn" 
                                            onclick="showVideo('<?php echo esc_attr($lesson->video_url); ?>', '<?php echo esc_attr($lesson->post_title); ?>')"
                                            title="Watch Video">
                                        <span class="dashicons dashicons-video-alt3"></span>
                                    </button>
                                <?php endif; ?>
                            </div>

                            <div class="nl-lesson-meta">
                                <span class="nl-comments">
                                    <span class="dashicons dashicons-admin-comments"></span>
                                    Comments <span class="nl-count"><?php echo $lesson->comment_count; ?></span>
                                </span>
                                <span class="nl-duration">
                                    <span class="dashicons dashicons-clock"></span>
                                    <?php echo esc_html($lesson->duration); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>
</div>

<!-- Video Modal -->
<div id="nl-video-modal" class="nl-modal">
    <div class="nl-modal-overlay"></div>
    <div class="nl-modal-container">
        <div class="nl-modal-header">
            <h3 id="nl-video-title"></h3>
            <button class="nl-modal-close" onclick="closeVideo()">
                <span class="dashicons dashicons-no-alt"></span>
            </button>
        </div>
        <div class="nl-modal-body">
            <div class="nl-video-wrapper">
                <iframe id="nl-video-frame" 
                        width="100%" 
                        height="100%" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                </iframe>
            </div>
        </div>
    </div>
</div>

<style>
/* Main Container */
.nl-course-container {
    background-color: #f8f9fa;
    min-height: 100vh;
}

/* Header */
.nl-course-header {
    background: #7c3aed;
    padding: 1.5rem 2rem;
    color: white;
}

.nl-header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1400px;
    margin: 0 auto;
}

.nl-header-content h1 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 500;
}

.nl-back-btn {
    display: flex;
    align-items: center;
    color: white;
    text-decoration: none;
    font-size: 1rem;
    gap: 0.5rem;
}

/* Layout */
.nl-course-layout {
    display: flex;
    gap: 2rem;
    max-width: 1400px;
    margin: 2rem auto;
    padding: 0 2rem;
}

/* Sidebar */
.nl-course-sidebar {
    width: 300px;
    flex-shrink: 0;
}

.nl-instructor-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    margin-bottom: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.nl-instructor-avatar img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    margin-bottom: 1rem;
}

.nl-instructor-info h3 {
    margin: 0 0 0.5rem 0;
    color: #1a1a1a;
}

.nl-instructor-title,
.nl-instructor-institution {
    color: #666;
    margin: 0.25rem 0;
    font-size: 0.9rem;
}

/* Navigation */
.nl-course-nav {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.nl-nav-item {
    display: flex;
    align-items: center;
    padding: 1rem 1.5rem;
    color: #4b5563;
    text-decoration: none;
    border-bottom: 1px solid #e5e7eb;
    transition: all 0.2s ease;
    gap: 0.75rem;
}

.nl-nav-item:hover {
    background: #f3f4f6;
}

.nl-nav-item.active {
    background: #7c3aed;
    color: white;
}

/* Lessons List */
.nl-course-main {
    flex: 1;
}

.nl-lesson-card {
    background: white;
    border-radius: 12px;
    padding: 1.25rem;
    margin-bottom: 1rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.nl-lesson-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.nl-lesson-number {
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f3f4f6;
    border-radius: 50%;
    color: #4b5563;
    font-weight: 500;
}

.nl-lesson-title {
    margin: 0;
    color: #1a1a1a;
    font-size: 1.1rem;
    font-weight: 500;
}

.nl-lesson-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.nl-lesson-actions {
    display: flex;
    gap: 1rem;
}

.nl-action-icon,
.nl-video-btn {
    padding: 0.5rem;
    border-radius: 6px;
    color: #4b5563;
    transition: all 0.2s ease;
    cursor: pointer;
    border: none;
    background: none;
}

.nl-action-icon:hover,
.nl-video-btn:hover {
    background: #f3f4f6;
    color: #7c3aed;
}

.nl-lesson-meta {
    display: flex;
    gap: 1.5rem;
    color: #666;
    font-size: 0.9rem;
}

.nl-comments,
.nl-duration {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.nl-count {
    background: #fee2e2;
    color: #991b1b;
    padding: 0.125rem 0.375rem;
    border-radius: 999px;
    font-size: 0.75rem;
}

/* Modal Styles */
.nl-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1000;
}

.nl-modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.75);
    backdrop-filter: blur(4px);
}

.nl-modal-container {
    position: relative;
    width: 90%;
    max-width: 1200px;
    max-height: 90vh;
    margin: 2.5vh auto;
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    animation: modalSlideIn 0.3s ease-out;
}

.nl-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}

.nl-modal-header h3 {
    margin: 0;
    font-size: 1.25rem;
    color: #1a1a1a;
}

.nl-modal-close {
    background: none;
    border: none;
    padding: 0.5rem;
    cursor: pointer;
    color: #666;
    border-radius: 6px;
    transition: all 0.2s;
}

.nl-modal-close:hover {
    background: #f3f4f6;
    color: #ef4444;
}

.nl-modal-body {
    padding: 1.5rem;
    background: #000;
}

.nl-video-wrapper {
    position: relative;
    padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
    height: 0;
    overflow: hidden;
}

.nl-video-wrapper iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

/* Animation */
@keyframes modalSlideIn {
    from {
        transform: translateY(-10px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

/* Responsive Design */
@media (max-width: 1024px) {
    .nl-course-layout {
        padding: 0 1rem;
    }
}

@media (max-width: 768px) {
    .nl-course-layout {
        flex-direction: column;
    }

    .nl-course-sidebar {
        width: 100%;
    }

    .nl-lesson-content {
        flex-direction: column;
        gap: 1rem;
    }

    .nl-lesson-actions {
        justify-content: flex-start;
    }

    .nl-lesson-meta {
        justify-content: flex-start;
    }

    .nl-modal-container {
        width: 95%;
        margin: 2.5vh auto;
    }
}

/* Utility Classes */
.dashicons {
    width: 20px;
    height: 20px;
    font-size: 20px;
    line-height: 1;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle navigation item clicks
    const navItems = document.querySelectorAll('.nl-nav-item');
    navItems.forEach(item => {
        item.addEventListener('click', function(e) {
            // Remove active class from all items
            navItems.forEach(nav => nav.classList.remove('active'));
            // Add active class to clicked item
            this.classList.add('active');
        });
    });
});

// Video Modal Functions
function showVideo(videoUrl, title) {
    const modal = document.getElementById('nl-video-modal');
    const videoFrame = document.getElementById('nl-video-frame');
    const videoTitle = document.getElementById('nl-video-title');
    
    // Format YouTube URL if needed
    if (videoUrl.includes('watch?v=')) {
        videoUrl = videoUrl.replace('watch?v=', 'embed/');
        // Remove additional parameters
        videoUrl = videoUrl.split('&')[0];
    }
    
    // Set video title
    videoTitle.textContent = title;
    
    // Set video source
    videoFrame.src = videoUrl;
    
    // Show modal with fade effect
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden'; // Prevent background scrolling
    
    // Handle click outside
    modal.addEventListener('click', function(event) {
        if (event.target === modal || event.target.classList.contains('nl-modal-overlay')) {
            closeVideo();
        }
    });
}

function closeVideo() {
    const modal = document.getElementById('nl-video-modal');
    const videoFrame = document.getElementById('nl-video-frame');
    
    // Add fade-out animation
    modal.style.opacity = '0';
    
    // Clean up
    setTimeout(() => {
        modal.style.display = 'none';
        modal.style.opacity = '1';
        videoFrame.src = '';
        document.body.style.overflow = ''; // Restore scrolling
    }, 200);
}

// Close modal on escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeVideo();
    }
});

// Handle lesson card hover effects
document.querySelectorAll('.nl-lesson-card').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-2px)';
        this.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
    });

    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
        this.style.boxShadow = '0 1px 3px rgba(0, 0, 0, 0.1)';
    });
});
</script>
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
        'video_url' => 'https://www.youtube.com/watch?v=VTLCoHnyACE&list=PLfqMhTWNBTe137I_EPQd34TsgV6IO55pt&index=3&t=93s'
    ],
    (object)[
        'ID' => 2,
        'post_title' => 'List Implementation',
        'comment_count' => 33,
        'duration' => '00:00:01',
        'video_url' => 'https://www.youtube.com/watch?v=VTLCoHnyACE&list=PLfqMhTWNBTe137I_EPQd34TsgV6IO55pt&index=3&t=93s'
    ],
    (object)[
        'ID' => 3,
        'post_title' => 'Linked List',
        'comment_count' => 17,
        'duration' => 'N/A',
        'video_url' => 'https://www.youtube.com/watch?v=VTLCoHnyACE&list=PLfqMhTWNBTe137I_EPQd34TsgV6IO55pt&index=3&t=93s'
    ],
    (object)[
        'ID' => 4,
        'post_title' => 'Linked List and Its Types',
        'comment_count' => 60,
        'duration' => 'N/A',
        'video_url' => 'https://www.youtube.com/watch?v=VTLCoHnyACE&list=PLfqMhTWNBTe137I_EPQd34TsgV6IO55pt&index=3&t=93s'
    ],
    (object)[
        'ID' => 5,
        'post_title' => 'ADT and Stack',
        'comment_count' => 38,
        'duration' => 'N/A',
        'video_url' => 'https://www.youtube.com/watch?v=VTLCoHnyACE&list=PLfqMhTWNBTe137I_EPQd34TsgV6IO55pt&index=3&t=93s'
    ],
    (object)[
        'ID' => 6,
        'post_title' => 'Uses of Stack',
        'comment_count' => 58,
        'duration' => 'N/A',
        'video_url' => 'https://www.youtube.com/watch?v=VTLCoHnyACE&list=PLfqMhTWNBTe137I_EPQd34TsgV6IO55pt&index=3&t=93s'
    ],
    (object)[
        'ID' => 7,
        'post_title' => 'Infix and Postfix Expressions',
        'comment_count' => 63,
        'duration' => 'N/A',
        'video_url' => 'https://www.youtube.com/watch?v=VTLCoHnyACE&list=PLfqMhTWNBTe137I_EPQd34TsgV6IO55pt&index=3&t=93s'
    ],
    (object)[
        'ID' => 8,
        'post_title' => 'Implementation of Stack',
        'comment_count' => 45,
        'duration' => 'N/A',
        'video_url' => 'https://www.youtube.com/watch?v=VTLCoHnyACE&list=PLfqMhTWNBTe137I_EPQd34TsgV6IO55pt&index=3&t=93s'
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
    <?php if (!empty($lesson->video_url)): ?>
        <span class="nl-icon video" 
              onclick="showVideo('<?php echo esc_attr($lesson->video_url); ?>', '<?php echo esc_attr($lesson->post_title); ?>')"
              title="Watch Video">
            🎥
        </span>
    <?php endif; ?>

    <!-- Video Modal -->
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
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
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
    color: #1e293b;
}

.nl-modal-close {
    background: none;
    border: none;
    padding: 0.5rem;
    cursor: pointer;
    color: #64748b;
    border-radius: 6px;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.nl-modal-close:hover {
    background: #f1f5f9;
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

/* Make video icon clickable */
.nl-icon.video {
    cursor: pointer;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    transition: all 0.2s;
}

.nl-icon.video:hover {
    background: #f3f4f6;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .nl-modal-container {
        width: 95%;
        margin: 2.5vh auto;
    }

    .nl-modal-header h3 {
        font-size: 1.125rem;
    }
}
</style>

<script>
// Replace the existing video JavaScript with:
function showVideo(videoUrl, title) {
    const modal = document.getElementById('nl-video-modal');
    const videoFrame = document.getElementById('nl-video-frame');
    const videoTitle = document.getElementById('nl-video-title');
    
    // Format YouTube URL if needed
    if (videoUrl.includes('watch?v=')) {
        videoUrl = videoUrl.replace('watch?v=', 'embed/');
        videoUrl = videoUrl.split('&')[0]; // Remove additional parameters
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
</script>

<?php
// Update the video icon span in your lessons loop to:
?>
<span class="nl-icon video" 
      onclick="showVideo('<?php echo esc_attr($lesson->video_url); ?>', '<?php echo esc_attr($lesson->post_title); ?>')"
      title="Watch Video">
    <span class="dashicons dashicons-video-alt3"></span>
</span>                
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
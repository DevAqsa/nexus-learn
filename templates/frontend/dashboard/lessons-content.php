<?php
if (!defined('ABSPATH')) exit;

// For demonstration/testing, using dummy lessons data 
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
    (object)[
        'ID' => 3,
        'post_title' => 'Linked List',
        'comment_count' => 17,
        'duration' => 'N/A',
        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
    ],
    (object)[
        'ID' => 4,
        'post_title' => 'Linked List and Its Types',
        'comment_count' => 60,
        'duration' => 'N/A',
        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
    ],
    (object)[
        'ID' => 5,
        'post_title' => 'ADT and Stack',
        'comment_count' => 38,
        'duration' => 'N/A',
        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
    ],
    (object)[
        'ID' => 6,
        'post_title' => 'Uses of Stack',
        'comment_count' => 58,
        'duration' => 'N/A',
        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
    ],
    (object)[
        'ID' => 7,
        'post_title' => 'Infix and Postfix Expressions',
        'comment_count' => 63,
        'duration' => 'N/A',
        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
    ],
    (object)[
        'ID' => 8,
        'post_title' => 'Implementation of Stack',
        'comment_count' => 45,
        'duration' => 'N/A',
        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
    ]
];

$lessons = $dummy_lessons; // Replace with actual data when available
?>

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

<!-- Video Modal -->
<div id="nl-video-modal" class="nl-modal" style="display: none;">
    <div class="nl-modal-content">
        <span class="nl-modal-close">&times;</span>
        <div class="nl-video-container">
            <iframe id="nl-video-frame" 
                    width="100%" 
                    height="100%" 
                    frameborder="0" 
                    allowfullscreen>
            </iframe>
        </div>
    </div>
</div>

<style>
.nl-lessons-content {
    padding: 20px;
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

.nl-icon.video {
    cursor: pointer;
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

/* Modal Styles */
.nl-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.8);
    z-index: 1000;
}

.nl-modal-content {
    position: relative;
    width: 90%;
    max-width: 800px;
    margin: 40px auto;
    background: #000;
    border-radius: 8px;
    overflow: hidden;
}

.nl-modal-close {
    position: absolute;
    right: 10px;
    top: 10px;
    color: white;
    font-size: 24px;
    cursor: pointer;
    z-index: 1001;
}

.nl-video-container {
    position: relative;
    padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
    height: 0;
}

.nl-video-container iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
</style>

<script>
function showVideo(videoUrl, title) {
    const modal = document.getElementById('nl-video-modal');
    const videoFrame = document.getElementById('nl-video-frame');
    
    // Set video source
    videoFrame.src = videoUrl;
    
    // Show modal
    modal.style.display = 'flex';
    
    // Close modal when clicking outside
    modal.onclick = function(event) {
        if (event.target === modal) {
            closeVideo();
        }
    };
}

function closeVideo() {
    const modal = document.getElementById('nl-video-modal');
    const videoFrame = document.getElementById('nl-video-frame');
    
    // Clear video source
    videoFrame.src = '';
    
    // Hide modal
    modal.style.display = 'none';
}

// Close modal when clicking the close button
document.querySelector('.nl-modal-close').onclick = closeVideo;

// Close modal on escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeVideo();
    }
});
</script>
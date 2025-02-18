<?php
if (!defined('ABSPATH')) exit;

// Dummy lessons data with slides
$lessons = [
    [
        'id' => 1,
        'title' => 'Introduction to Data Structures',
        'comments' => 156,
        'duration' => 'N/A',
        'video_url' => 'https://www.youtube.com/embed/VTLCoHnyACE',
        'slides_url' => NEXUSLEARN_PLUGIN_URL . 'assets/slides/introduction.pdf',
        'has_slides' => true,
        'has_resources' => true
    ],
    [
        'id' => 2,
        'title' => 'Arrays and Linked Lists',
        'comments' => 89,
        'duration' => '45:00',
        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
        'slides_url' => NEXUSLEARN_PLUGIN_URL . 'assets/slides/arrays-linkedlists.pdf',
        'has_slides' => true,
        'has_resources' => true
    ],
    [
        'id' => 3,
        'title' => 'Stacks and Queues',
        'comments' => 124,
        'duration' => '50:00',
        'video_url' => 'https://www.youtube.com/embed/VTLCoHnyACE',
        'slides_url' => NEXUSLEARN_PLUGIN_URL . 'assets/slides/stacks-queues.pdf',
        'has_slides' => true,
        'has_resources' => false
    ],
    [
        'id' => 4,
        'title' => 'Binary Trees and Binary Search Trees',
        'comments' => 167,
        'duration' => '55:00',
        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
        'slides_url' => '/uploads/slides/binary-trees.pdf',
        'has_slides' => true,
        'has_resources' => true
    ],
    [
        'id' => 5,
        'title' => 'AVL Trees and Balancing',
        'comments' => 98,
        'duration' => '48:00',
        'video_url' => 'https://www.youtube.com/embed/VTLCoHnyACE',
        'slides_url' => '/uploads/slides/avl-trees.pdf',
        'has_slides' => true,
        'has_resources' => true
    ],
    [
        'id' => 6,
        'title' => 'Hash Tables and Hashing Functions',
        'comments' => 145,
        'duration' => '52:00',
        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
        'slides_url' => '/uploads/slides/hash-tables.pdf',
        'has_slides' => true,
        'has_resources' => false
    ],
    [
        'id' => 7,
        'title' => 'Heaps and Priority Queues',
        'comments' => 112,
        'duration' => '47:00',
        'video_url' => 'https://www.youtube.com/embed/VTLCoHnyACE',
        'slides_url' => '/uploads/slides/heaps-priority-queues.pdf',
        'has_slides' => true,
        'has_resources' => true
    ],
    [
        'id' => 8,
        'title' => 'Graphs and Graph Algorithms',
        'comments' => 178,
        'duration' => '58:00',
        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
        'slides_url' => '/uploads/slides/graphs.pdf',
        'has_slides' => true,
        'has_resources' => true
    ],
    [
        'id' => 9,
        'title' => 'Sorting Algorithms',
        'comments' => 156,
        'duration' => '54:00',
        'video_url' => 'https://www.youtube.com/embed/VTLCoHnyACE',
        'slides_url' => '/uploads/slides/sorting.pdf',
        'has_slides' => true,
        'has_resources' => false
    ],
    [
        'id' => 10,
        'title' => 'Advanced Data Structures',
        'comments' => 134,
        'duration' => '51:00',
        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
        'slides_url' => '/uploads/slides/advanced-ds.pdf',
        'has_slides' => true,
        'has_resources' => true
    ]
];
?>

<div class="nl-lessons-list">
    <?php foreach ($lessons as $index => $lesson): ?>
        <div class="nl-lesson-card">
            <div class="nl-lesson-header">
                <div class="nl-lesson-number"><?php echo $index + 1; ?></div>
                <h3 class="nl-lesson-title"><?php echo esc_html($lesson['title']); ?></h3>
            </div>
            
            <div class="nl-lesson-content">
                <div class="nl-lesson-actions">
                    <?php if ($lesson['has_slides']): ?>
                        <button class="nl-button nl-slides-btn" 
                                onclick="viewSlides('<?php echo esc_attr($lesson['slides_url']); ?>')" 
                                title="View Slides">
                            <span class="dashicons dashicons-media-document"></span>
                        </button>
                    <?php endif; ?>

                    <?php if (!empty($lesson['video_url'])): ?>
                    <button class="nl-button nl-video-btn" 
                    onclick="showVideo('<?php echo esc_attr($lesson['video_url']); ?>')" 
                    title="Watch Video">
                    <span class="dashicons dashicons-video-alt3"></span>
                    </button>
                       <?php endif; ?>

                       <button class="nl-button nl-reading-btn" 
        onclick="showReading(<?php echo $lesson['id']; ?>, '<?php echo esc_attr($lesson['title']); ?>')" 
        title="Read Content">
    <span class="dashicons dashicons-book-alt"></span>
</button>
                </div>
                

                <div class="nl-lesson-meta">
                <span class="nl-comments" onclick="showComments(<?php echo $lesson['id']; ?>, '<?php echo esc_attr($lesson['title']); ?>')">
    <span class="dashicons dashicons-admin-comments"></span>
    Comments <span class="nl-count"><?php echo $lesson['comments']; ?></span>
</span>
                    <span class="nl-duration">
                        <span class="dashicons dashicons-clock"></span>
                        <?php echo esc_html($lesson['duration']); ?>
                    </span>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Comments Modal -->
<div id="nl-comments-modal" class="nl-modal">
    <div class="nl-modal-content">
        <div class="nl-modal-header">
            <h3 id="nl-comments-title">Comments</h3>
            <span class="nl-modal-close" onclick="closeComments()">&times;</span>
        </div>
        <div class="nl-comments-container">
            <div class="nl-comments-list" id="comments-list">
                <!-- Comments will be loaded here -->
            </div>
            
            <!-- Comment Form -->
            <div class="nl-comment-form">
                <textarea id="new-comment" placeholder="Write your comment..."></textarea>
                <button onclick="submitComment()" class="nl-button nl-button-primary">Post Comment</button>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Slides Modal -->
<div id="nl-slides-modal" class="nl-modal">
    <div class="nl-modal-content">
        <div class="nl-modal-header">
            <h3 id="nl-slides-title"></h3>
            <button class="nl-modal-close" onclick="closeSlides()">×</button>
        </div>
        <div class="nl-slides-container">
            <div class="nl-slides-nav">
                <button class="nl-nav-btn prev" onclick="previousSlide()">❮</button>
                <span id="slide-counter">1 / 10</span>
                <button class="nl-nav-btn next" onclick="nextSlide()">❯</button>
            </div>
            <div class="nl-slide-content" id="slide-content">
                <!-- Slides will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Video Modal -->
<div id="nl-video-modal" class="nl-modal">
    <div class="nl-modal-content">
        <div class="nl-modal-header">
            <h3>Lecture Video</h3>
            <span class="nl-modal-close" onclick="closeVideo()">&times;</span>
        </div>
        <div class="nl-video-container">
            <iframe id="nl-video-frame" width="100%" height="100%" frameborder="0" allowfullscreen></iframe>
        </div>
    </div>
</div>

<!-- Reading Modal -->
<div id="nl-reading-modal" class="nl-modal">
    <div class="nl-modal-content">
        <div class="nl-modal-header">
            <h3 id="nl-reading-title"></h3>
            <span class="nl-modal-close" onclick="closeReading()">&times;</span>
        </div>
        <div class="nl-reading-container">
            <div id="reading-content" class="nl-reading-content">
                <!-- Reading content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<style>
.nl-lesson-card {
    background: white;
    border-radius: 8px;
    padding: 1.25rem;
    margin-bottom: 1rem;
    border: 1px solid #e5e7eb;
    transition: all 0.2s ease;
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

.nl-button {
    padding: 0.5rem;
    border-radius: 6px;
    color: #4b5563;
    border: none;
    background: none;
    cursor: pointer;
    transition: all 0.2s ease;
}

.nl-button:hover {
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

.nl-modal-content {
    position: relative;
    width: 90%;
    max-width: 1200px;
    height: 80vh;
    margin: 5vh auto;
    background: white;
    border-radius: 8px;
    overflow: hidden;
}

.nl-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background: #f8fafc;
    border-bottom: 1px solid #e5e7eb;
}

.nl-modal-header h3 {
    margin: 0;
    font-size: 1.25rem;
    color: #1a1a1a;
}

.nl-modal-close {
    font-size: 1.5rem;
    color: #666;
    cursor: pointer;
    padding: 0.5rem;
}

.nl-video-container,
.nl-slides-viewer {
    height: calc(100% - 60px);
    padding: 1rem;
}

.nl-video-container iframe,
.nl-slides-viewer iframe {
    width: 100%;
    height: 100%;
    border: none;
}

.nl-slides-controls {
    padding: 1rem;
    background: #f8fafc;
    border-top: 1px solid #e5e7eb;
    display: flex;
    justify-content: flex-end;
}

.nl-slides-viewer {
    height: calc(100% - 110px); /* Adjusted for controls */
}

.nl-button.nl-slides-btn {
    background-color: #f3f4f6;
    padding: 0.5rem 1rem;
}

.nl-button.nl-slides-btn:hover {
    background-color: #e5e7eb;
}

.nl-slides-viewer {
    height: calc(100% - 120px);
    background: #f8f9fa;
    padding: 20px;
    border-radius: 4px;
    margin: 10px;
}

.nl-slides-controls {
    padding: 10px 20px;
    border-top: 1px solid #e5e7eb;
    text-align: right;
}

.nl-button {
    padding: 8px 16px;
    background: #7c3aed;
    color: white;
    border-radius: 4px;
    text-decoration: none;
    display: inline-block;
}

.nl-button:hover {
    background: #6d28d9;
}

.nl-modal-content {
    background: white;
    width: 90%;
    max-width: 1000px;
    margin: 2% auto;
    border-radius: 8px;
    position: relative;
    height: 90vh;
}

.nl-modal-header {
    padding: 15px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.nl-slides-container {
    height: calc(100% - 60px);
    display: flex;
    flex-direction: column;
}

.nl-slides-nav {
    padding: 10px;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 20px;
}

.nl-nav-btn {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    padding: 5px 15px;
    border-radius: 4px;
}

.nl-nav-btn:hover {
    background: #f0f0f0;
}

.nl-slide-content {
    flex: 1;
    padding: 40px;
    overflow-y: auto;
}

.nl-slide-content h2 {
    font-size: 32px;
    margin-bottom: 30px;
    color: #333;
}

.nl-slide-content ul {
    font-size: 24px;
    line-height: 1.6;
    list-style-type: none;
    padding: 0;
}

.nl-slide-content ul li {
    margin-bottom: 15px;
    padding-left: 25px;
    position: relative;
}

.nl-slide-content ul li:before {
    content: "•";
    position: absolute;
    left: 0;
    color: #7c3aed;
}

.nl-slide-content ul ul {
    margin-top: 10px;
    margin-left: 20px;
    font-size: 20px;
}

#slide-counter {
    font-size: 18px;
    color: #666;
}

.nl-modal-close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    padding: 5px 10px;
    color: #666;
}

.nl-modal-close:hover {
    color: #333;
}

/* Add to your existing styles */
.nl-button.nl-reading-btn {
    background-color: #f3f4f6;
    color: #4b5563;
    
}

.nl-button.nl-reading-btn:hover {
    background-color: #7c3aed;
    color: white;
}

.nl-reading-container {
    height: calc(100% - 60px);
    padding: 2rem;
    overflow-y: auto;
}

.nl-reading-content {
    max-width: 800px;
    margin: 0 auto;
    font-size: 1.1rem;
    line-height: 1.6;
    color: #1a1a1a;
}

.nl-reading-section h2 {
    font-size: 1.8rem;
    margin-bottom: 1.5rem;
    color: #111827;
}

.nl-reading-section h3 {
    font-size: 1.5rem;
    margin: 2rem 0 1rem;
    color: #1f2937;
}

.nl-reading-section p {
    margin-bottom: 1.5rem;
}

.nl-code-example {
    background: #f8fafc;
    padding: 1.5rem;
    border-radius: 8px;
    margin: 1.5rem 0;
    overflow-x: auto;
}

.nl-code-example pre {
    margin: 0;
    font-family: monospace;
    font-size: 0.9rem;
    line-height: 1.4;
}

/* Mobile responsiveness */
@media (max-width: 768px) {
    .nl-reading-container {
        padding: 1rem;
    }
    
    .nl-reading-content {
        font-size: 1rem;
    }
}

/* Comments Modal Styles */
.nl-comments-container {
    height: calc(100% - 60px);
    display: flex;
    flex-direction: column;
}

.nl-comments-list {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
}

.nl-comment-item {
    background: #f8fafc;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
}

.nl-comment-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
}

.nl-comment-author {
    font-weight: 500;
    color: #1a1a1a;
}

.nl-comment-date {
    color: #6b7280;
    font-size: 0.875rem;
}

.nl-comment-content {
    color: #4b5563;
    line-height: 1.5;
}

.nl-comment-form {
    padding: 20px;
    border-top: 1px solid #e5e7eb;
}

.nl-comment-form textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    margin-bottom: 10px;
    resize: vertical;
    min-height: 100px;
}

.nl-comment-form button {
    float: right;
}


</style>

<script>

let currentSlide = 1;
let totalSlides = 0;
const slides = {
    'introduction': [
        {
            title: "Introduction to Data Structures",
            content: `
                <h2>Course Overview</h2>
                <ul>
                    <li>What is a Data Structure?</li>
                    <li>Types of Data Structures</li>
                    <li>Course Objectives</li>
                    <li>Grading Criteria</li>
                </ul>
            `
        },
        {
            title: "Basic Concepts",
            content: `
                <h2>Basic Concepts</h2>
                <ul>
                    <li>Data Organization</li>
                    <li>Abstract Data Types</li>
                    <li>Algorithm Complexity</li>
                    <li>Memory Management</li>
                </ul>
            `
        },
        {
            title: "Types of Data Structures",
            content: `
                <h2>Types of Data Structures</h2>
                <ul>
                    <li>Linear Data Structures
                        <ul>
                            <li>Arrays</li>
                            <li>Linked Lists</li>
                            <li>Stacks</li>
                            <li>Queues</li>
                        </ul>
                    </li>
                    <li>Non-Linear Data Structures
                        <ul>
                            <li>Trees</li>
                            <li>Graphs</li>
                        </ul>
                    </li>
                </ul>
            `
        },
        {
            title: "Course Resources",
            content: `
                <h2>Course Resources</h2>
                <ul>
                    <li>Textbook: Data Structures and Algorithms by Cormen et al.</li>
                    <li>Online Resources</li>
                    <li>Assignments and Projects</li>
                </ul>
            `
        }
    ],
    
    'arrays-linkedlists': [
        {
            title: "Arrays",
            content: `
                <h2>Arrays</h2>
                <ul>
                    <li>Definition and Properties</li>
                    <li>Operations on Arrays</li>
                    <li>Applications of Arrays</li>
                </ul>
            `
        },
        {
            title: "Linked Lists",
            content: `
                <h2>Linked Lists</h2>
                <ul>
                    <li>Definition and Properties</li>
                    <li>Types of Linked Lists</li>
                    <li>Operations on Linked Lists</li>
                </ul>
            `
        },
        {
            title: "Array vs. Linked List",
            content: `
                <h2>Array vs. Linked List</h2>
                <ul>
                    <li>Memory Allocation</li>
                    <li>Insertion and Deletion</li>
                    <li>Access Time</li>
                </ul>
            `
        }
    ],
    "stacks-queues": [
        {
            title: "Stacks",
            content: `
                <h2>Stacks</h2>
                <ul>
                    <li>Definition and Properties</li>
                    <li>Operations on Stacks</li>
                    <li>Applications of Stacks</li>
                </ul>
            `
        },
        {
            title: "Queues",
            content: `
                <h2>Queues</h2>
                <ul>
                    <li>Definition and Properties</li>
                    <li>Types of Queues</li>
                    <li>Operations on Queues</li>
                </ul>
            `
        },
        {
            title: "Stacks vs. Queues",
            content: `
                <h2>Stacks vs. Queues</h2>
                <ul>
                    <li>Memory Allocation</li>
                    <li>Insertion and Deletion</li>
                    <li>Access Time</li>
                </ul>
            `
        }
    ],
};

function viewSlides(url) {
    const modal = document.getElementById('nl-slides-modal');
    const slideContent = document.getElementById('slide-content');
    const slideTitle = document.getElementById('nl-slides-title');
    
   
    const lectureName = url.split('/').pop().replace('.pdf', '');
    
    if (slides[lectureName]) {
        currentSlide = 1;
        totalSlides = slides[lectureName].length;
        showSlide(lectureName, currentSlide);
        modal.style.display = 'block';
        updateSlideCounter();
    }
}

function showSlide(lectureName, slideNumber) {
    const slideContent = document.getElementById('slide-content');
    const slideTitle = document.getElementById('nl-slides-title');
    const slide = slides[lectureName][slideNumber - 1];
    
    slideTitle.textContent = slide.title;
    slideContent.innerHTML = slide.content;
}

function nextSlide() {
    if (currentSlide < totalSlides) {
        currentSlide++;
        showSlide('introduction', currentSlide);
        updateSlideCounter();
    }
}

function previousSlide() {
    if (currentSlide > 1) {
        currentSlide--;
        showSlide('introduction', currentSlide);
        updateSlideCounter();
    }
}

function updateSlideCounter() {
    document.getElementById('slide-counter').textContent = `${currentSlide} / ${totalSlides}`;
}

function closeSlides() {
    const modal = document.getElementById('nl-slides-modal');
    modal.style.display = 'none';
}

function showVideo(url) {
    const modal = document.getElementById('nl-video-modal');
    const frame = document.getElementById('nl-video-frame');
    frame.src = url;
    modal.style.display = 'block';
}

function closeVideo() {
    const modal = document.getElementById('nl-video-modal');
    const frame = document.getElementById('nl-video-frame');
    modal.style.display = 'none';
    frame.src = '';
}

// Close modals on escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeVideo();
        closeSlides();
    }
});

// Close modals when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('nl-modal')) {
        closeVideo();
        closeSlides();
    }
}


// Add this to your existing JavaScript
const readingContent = {
    1: {
        title: "List Implementation",
        content: `
            <div class="nl-reading-section">
                <h2>List Operations</h2>
                <p>Today, we will discuss the concept of list operations. You may have a fair idea of <strong>start</strong> operation that sets the current pointer to the first element of the list while the <strong>tail</strong> operation moves the current pointer to the last element of the list. In the previous lecture, we discussed the operation <strong>next</strong> that moves the current pointer one element forward. Similarly, there is the <strong>back</strong> operation which moves the current pointer one element backward.</p>

                <h3>List Implementation</h3>
                <p>Now we will see what the implementation of the list is and how one can create a list in C++. After designing the interface for the list, it is advisable to know how to implement that interface. Suppose we want to create a list of integers. For this purpose, the methods of the list can be implemented with the use of an array inside.</p>

                <div class="nl-code-example">
                    <pre>
A    2    6    8    7    1              current    size
     1    2    3    4    5                 3        5
                    </pre>
                </div>

                <p>In this case, we start the index of the array from 1 just for simplification against the usual practice in which the index of an array starts from zero in C++. It is not necessary to always start the indexing from zero. Sometimes, it is required to start the indexing from 1.</p>
            </div>
        `
    },
    // Add more reading content for other lectures
};

function showReading(lessonId, title) {
    const modal = document.getElementById('nl-reading-modal');
    const contentDiv = document.getElementById('reading-content');
    const titleElement = document.getElementById('nl-reading-title');
    
    if (readingContent[lessonId]) {
        titleElement.textContent = title;
        contentDiv.innerHTML = readingContent[lessonId].content;
        modal.style.display = 'block';
    }
}

function closeReading() {
    const modal = document.getElementById('nl-reading-modal');
    modal.style.display = 'none';
}

// Update your window.onclick handler to include reading modal
window.onclick = function(event) {
    if (event.target.classList.contains('nl-modal')) {
        closeVideo();
        closeSlides();
        closeReading();
    }
}

// Update your escape key handler to include reading modal
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeVideo();
        closeSlides();
        closeReading();
    }
});


// Add to your existing JavaScript
let currentLessonId = null;

function showComments(lessonId, lessonTitle) {
    currentLessonId = lessonId;
    const modal = document.getElementById('nl-comments-modal');
    const titleElement = document.getElementById('nl-comments-title');
    const commentsList = document.getElementById('comments-list');
    
    titleElement.textContent = `Comments - ${lessonTitle}`;
    
    // Load comments for this lesson
    loadComments(lessonId);
    
    modal.style.display = 'block';
}

function loadComments(lessonId) {
    const commentsList = document.getElementById('comments-list');
    
    // Show loading state
    commentsList.innerHTML = '<div class="nl-loading">Loading comments...</div>';
    
    // Make AJAX call to get comments
    fetch(`/wp-json/nexuslearn/v1/lessons/${lessonId}/comments`)
        .then(response => response.json())
        .then(comments => {
            commentsList.innerHTML = '';
            comments.forEach(comment => {
                commentsList.innerHTML += `
                    <div class="nl-comment-item">
                        <div class="nl-comment-header">
                            <span class="nl-comment-author">${comment.author}</span>
                            <span class="nl-comment-date">${comment.date}</span>
                        </div>
                        <div class="nl-comment-content">${comment.content}</div>
                    </div>
                `;
            });
        })
        .catch(error => {
            commentsList.innerHTML = '<div class="nl-error">Error loading comments</div>';
        });
}

function submitComment() {
    const commentText = document.getElementById('new-comment').value.trim();
    if (!commentText) return;
    
    // Make AJAX call to submit comment
    fetch(`/wp-json/nexuslearn/v1/lessons/${currentLessonId}/comments`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': wpApiSettings.nonce
        },
        body: JSON.stringify({
            content: commentText
        })
    })
    .then(response => response.json())
    .then(result => {
        // Clear comment form
        document.getElementById('new-comment').value = '';
        // Reload comments
        loadComments(currentLessonId);
    })
    .catch(error => {
        alert('Error posting comment');
    });
}

function closeComments() {
    const modal = document.getElementById('nl-comments-modal');
    modal.style.display = 'none';
    currentLessonId = null;
}

// Update your window.onclick handler
window.onclick = function(event) {
    if (event.target.classList.contains('nl-modal')) {
        closeVideo();
        closeSlides();
        closeReading();
        closeComments();
    }
}

// Update your escape key handler
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeVideo();
        closeSlides();
        closeReading();
        closeComments();
    }
});
</script>
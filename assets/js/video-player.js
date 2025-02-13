
function showVideo(videoUrl, title) {
    const modal = document.getElementById('nl-video-modal');
    const videoFrame = document.getElementById('nl-video-frame');
    
    // Set video source
    videoFrame.src = videoUrl;
    
    // Show modal
    modal.style.display = 'block';
    
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
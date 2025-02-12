// Content Viewer JavaScript
class ContentViewer {
    constructor(container) {
        this.container = container;
        this.contentId = container.dataset.contentId;
        this.progress = parseFloat(container.dataset.progress);
        this.autoSaveTimeout = null;
        
        this.initializeEventListeners();
        this.initializeKeyboardShortcuts();
    }

    initializeEventListeners() {
        // Navigation buttons
        this.container.querySelector('.prev-section').addEventListener('click', () => this.navigateSection('prev'));
        this.container.querySelector('.next-section').addEventListener('click', () => this.navigateSection('next'));

        // Bookmark button
        this.container.querySelector('.nl-bookmark-btn').addEventListener('click', () => this.toggleBookmark());

        // Chapter navigation
        this.container.querySelectorAll('.nl-chapter-item').forEach(item => {
            item.addEventListener('click', () => this.navigateToChapter(item.dataset.chapterId));
        });

        // Progress tracking
        const contentBody = this.container.querySelector('.nl-content-body');
        contentBody.addEventListener('scroll', () => this.handleScroll(contentBody));
    }

    initializeKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Only handle shortcuts if content viewer is in view
            if (!this.isInViewport()) return;

            // Alt + Left Arrow: Previous Section
            if (e.altKey && e.key === 'ArrowLeft') {
                e.preventDefault();
                this.navigateSection('prev');
            }
            
            // Alt + Right Arrow: Next Section
            if (e.altKey && e.key === 'ArrowRight') {
                e.preventDefault();
                this.navigateSection('next');
            }
            
            // Ctrl + B: Toggle Bookmark
            if (e.ctrlKey && e.key === 'b') {
                e.preventDefault();
                this.toggleBookmark();
            }
        });
    }

    handleScroll(contentBody) {
        // Calculate progress based on scroll position
        const scrollPercentage = (contentBody.scrollTop / (contentBody.scrollHeight - contentBody.clientHeight)) * 100;
        this.updateProgress(Math.min(scrollPercentage, 100));

        // Auto-save progress
        if (this.autoSaveTimeout) clearTimeout(this.autoSaveTimeout);
        this.autoSaveTimeout = setTimeout(() => this.saveProgress(), 1000);
    }

    updateProgress(value) {
        this.progress = value;
        const progressBar = this.container.querySelector('.nl-progress-fill');
        const progressText = this.container.querySelector('.nl-progress-text');
        
        progressBar.style.width = `${value}%`;
        progressText.textContent = `${Math.round(value)}% Complete`;
    }

    saveProgress() {
        jQuery.ajax({
            url: nlDashboard.ajaxUrl,
            type: 'POST',
            data: {
                action: 'nl_save_progress',
                nonce: nlDashboard.nonce,
                content_id: this.contentId,
                progress: this.progress
            },
            success: (response) => {
                if (response.success) {
                    console.log('Progress saved');
                }
            }
        });
    }

    toggleBookmark() {
        const currentChapter = this.getCurrentChapter();
        if (!currentChapter) return;

        jQuery.ajax({
            url: nlDashboard.ajaxUrl,
            type: 'POST',
            data: {
                action: 'nl_toggle_bookmark',
                nonce: nlDashboard.nonce,
                content_id: this.contentId,
                chapter_id: currentChapter.dataset.chapterId
            },
            success: (response) => {
                if (response.success) {
                    currentChapter.classList.toggle('bookmarked');
                    this.updateBookmarkIcon(currentChapter);
                }
            }
        });
    }

    navigateSection(direction) {
        const chapters = Array.from(this.container.querySelectorAll('.nl-chapter-item'));
        const currentIndex = chapters.findIndex(chapter => chapter.classList.contains('active'));
        
        let newIndex = direction === 'prev' ? currentIndex - 1 : currentIndex + 1;
        if (newIndex >= 0 && newIndex < chapters.length) {
            this.navigateToChapter(chapters[newIndex].dataset.chapterId);
        }
    }

    navigateToChapter(chapterId) {
        // Implementation would depend on your content structure
        console.log(`Navigating to chapter ${chapterId}`);
    }

    getCurrentChapter() {
        return this.container.querySelector('.nl-chapter-item.active');
    }

    isInViewport() {
        const rect = this.container.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }

    updateBookmarkIcon(chapterElement) {
        const hasBookmark = chapterElement.classList.contains('bookmarked');
        const bookmarkIcon = chapterElement.querySelector('.dashicons-bookmark');
        
        if (hasBookmark && !bookmarkIcon) {
            const icon = document.createElement('span');
            icon.className = 'dashicons dashicons-bookmark';
            chapterElement.appendChild(icon);
        } else if (!hasBookmark && bookmarkIcon) {
            bookmarkIcon.remove();
        }
    }
}

// Initialize content viewers
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.nl-content-viewer').forEach(container => {
        new ContentViewer(container);
    });
});
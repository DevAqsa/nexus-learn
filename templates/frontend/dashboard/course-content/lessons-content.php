<?php
if (!defined('ABSPATH')) exit;

// Dummy lessons data - replace with actual data
$lessons = [
    [
        'id' => 1,
        'title' => 'Introduction',
        'comments' => 156,
        'duration' => 'N/A',
        'video_url' => 'https://www.youtube.com/embed/VTLCoHnyACE',
        'has_slides' => true,
        'has_resources' => true
    ],
    [
        'id' => 2,
        'title' => 'List Implementation',
        'comments' => 33,
        'duration' => '00:00:01',
        'video_url' => 'https://www.youtube.com/embed/VTLCoHnyACE',
        'has_slides' => true,
        'has_resources' => false
    ],
    [
        'id' => 2,
        'title' => 'List Implementation',
        'comments' => 33,
        'duration' => '00:00:01',
        'video_url' => 'https://www.youtube.com/embed/VTLCoHnyACE',
        'has_slides' => true,
        'has_resources' => false
    ],
    [
        'id' => 2,
        'title' => 'List Implementation',
        'comments' => 33,
        'duration' => '00:00:01',
        'video_url' => 'https://www.youtube.com/embed/VTLCoHnyACE',
        'has_slides' => true,
        'has_resources' => false
    ],
    [
        'id' => 2,
        'title' => 'List Implementation',
        'comments' => 33,
        'duration' => '00:00:01',
        'video_url' => 'https://www.youtube.com/embed/VTLCoHnyACE',
        'has_slides' => true,
        'has_resources' => false
    ],
    [
        'id' => 2,
        'title' => 'List Implementation',
        'comments' => 33,
        'duration' => '00:00:01',
        'video_url' => 'https://www.youtube.com/embed/VTLCoHnyACE',
        'has_slides' => true,
        'has_resources' => false
    ],
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
                    <?php if ($lesson['has_resources']): ?>
                        <span class="nl-action-icon" title="Resources Available">
                            <span class="dashicons dashicons-media-document"></span>
                        </span>
                    <?php endif; ?>
                    
                    <?php if ($lesson['has_slides']): ?>
                        <span class="nl-action-icon" title="Slides Available">
                            <span class="dashicons dashicons-slides"></span>
                        </span>
                    <?php endif; ?>
                    
                    <?php if (!empty($lesson['video_url'])): ?>
                        <button class="nl-video-btn" 
                                onclick="showVideo('<?php echo esc_attr($lesson['video_url']); ?>', '<?php echo esc_attr($lesson['title']); ?>')"
                                title="Watch Video">
                            <span class="dashicons dashicons-video-alt3"></span>
                        </button>
                    <?php endif; ?>
                </div>

                <div class="nl-lesson-meta">
                    <span class="nl-comments">
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

.nl-lesson-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
</style>
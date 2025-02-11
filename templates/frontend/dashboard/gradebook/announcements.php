<?php
if (!defined('ABSPATH')) exit;

$current_user_id = get_current_user_id();
$announcements = [
    [
        'title' => 'AI CourseSchedule Released',
        'date' => '2025-02-28',
        'content' => 'The schedule has been published. Please check your student portal for details.',
        'priority' => 'high',
        'course' => 'All Courses'
    ],
    [
        'title' => 'Assignment Deadline Extension',
        'date' => '2025-02-25',
        'content' => 'Due to the upcoming holiday, all assignments due next week have been extended by 48 hours.',
        'priority' => 'medium',
        'course' => 'CS501'
    ]
];

?>

<div class="nl-announcements-section">
    <!-- Header Section -->
    <div class="nl-section-header">
        <h2><?php _e('Important Announcements', 'nexuslearn'); ?></h2>
        <div class="nl-filter-actions">
            <select class="nl-select" id="priority-filter">
                <option value="all"><?php _e('All Priorities', 'nexuslearn'); ?></option>
                <option value="high"><?php _e('High Priority', 'nexuslearn'); ?></option>
                <option value="medium"><?php _e('Medium Priority', 'nexuslearn'); ?></option>
                <option value="low"><?php _e('Low Priority', 'nexuslearn'); ?></option>
            </select>
        </div>
    </div>

    <!-- Announcements List -->
    <div class="nl-announcements-list">
        <?php if (!empty($announcements)): ?>
            <?php foreach ($announcements as $announcement): ?>
                <div class="nl-announcement-card priority-<?php echo esc_attr($announcement['priority']); ?>">
                    <div class="nl-announcement-header">
                        <div class="nl-announcement-priority">
                            <?php echo $announcement['priority'] === 'high' ? '🔴' : ($announcement['priority'] === 'medium' ? '🟡' : '🟢'); ?>
                        </div>
                        <div class="nl-announcement-date">
                            <?php echo date_i18n(get_option('date_format'), strtotime($announcement['date'])); ?>
                        </div>
                    </div>
                    
                    <div class="nl-announcement-content">
                        <h3><?php echo esc_html($announcement['title']); ?></h3>
                        <div class="nl-announcement-course">
                            <?php echo esc_html($announcement['course']); ?>
                        </div>
                        <div class="nl-announcement-text">
                            <?php echo wp_kses_post($announcement['content']); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="nl-empty-state">
                <div class="nl-empty-icon">📢</div>
                <h3><?php _e('No Announcements', 'nexuslearn'); ?></h3>
                <p><?php _e('There are no announcements at this time.', 'nexuslearn'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.nl-announcements-section {
    padding: 20px;
}

.nl-announcement-card {
    background: white;
    border-radius: 8px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    overflow: hidden;
}

.nl-announcement-card.priority-high {
    border-left: 4px solid #ef4444;
}

.nl-announcement-card.priority-medium {
    border-left: 4px solid #f59e0b;
}

.nl-announcement-card.priority-low {
    border-left: 4px solid #10b981;
}

.nl-announcement-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 20px;
    background: #f8fafc;
}

.nl-announcement-content {
    padding: 20px;
}

.nl-announcement-content h3 {
    margin: 0 0 10px 0;
    color: #1f2937;
}

.nl-announcement-course {
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 10px;
}

.nl-announcement-text {
    color: #4b5563;
    line-height: 1.5;
}

.nl-announcement-date {
    font-size: 0.875rem;
    color: #6b7280;
}

.nl-section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.nl-select {
    padding: 8px 12px;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    background: white;
}

@media (max-width: 768px) {
    .nl-section-header {
        flex-direction: column;
        gap: 10px;
    }
    
    .nl-filter-actions {
        width: 100%;
    }
    
    .nl-select {
        width: 100%;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    $('#priority-filter').on('change', function() {
        const priority = $(this).val();
        if (priority === 'all') {
            $('.nl-announcement-card').show();
        } else {
            $('.nl-announcement-card').hide();
            $(`.nl-announcement-card.priority-${priority}`).show();
        }
    });
});
</script>
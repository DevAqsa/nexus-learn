<?php
if (!defined('ABSPATH')) exit;

if (!isset($assignments_manager)) {
    return;
}

$user_id = get_current_user_id();
$assignments = $assignments_manager->get_user_assignments($user_id);

// Get assignments grouped by status
$pending = array_filter($assignments, function($ass) {
    return $ass['status'] === 'pending';
});
$submitted = array_filter($assignments, function($ass) {
    return $ass['status'] === 'submitted';
});
$graded = array_filter($assignments, function($ass) {
    return $ass['status'] === 'graded';
});
?>

<div class="nl-content-section">
    <!-- Header Section -->
    <!-- <div class="nl-section-header">
        <h1 class="nl-page-title"><?php _e('Assignments', 'nexuslearn'); ?></h1>
        <p class="nl-subtitle"><?php _e('View and manage your course assignments', 'nexuslearn'); ?></p>
    </div> -->

    <!-- Stats Overview -->
    <div class="nl-stats-grid">
        <div class="nl-stat-card">
            <span class="nl-stat-icon pending">📝</span>
            <div class="nl-stat-value"><?php echo count($pending); ?></div>
            <div class="nl-stat-label"><?php _e('Pending', 'nexuslearn'); ?></div>
        </div>
        <div class="nl-stat-card">
            <span class="nl-stat-icon submitted">📤</span>
            <div class="nl-stat-value"><?php echo count($submitted); ?></div>
            <div class="nl-stat-label"><?php _e('Submitted', 'nexuslearn'); ?></div>
        </div>
        <div class="nl-stat-card">
            <span class="nl-stat-icon graded">✓</span>
            <div class="nl-stat-value"><?php echo count($graded); ?></div>
            <div class="nl-stat-label"><?php _e('Graded', 'nexuslearn'); ?></div>
        </div>
    </div>

    <!-- Assignments List -->
    <div class="nl-assignments-container">
        <div class="nl-section-header">
            <h2><?php _e('Your Assignments', 'nexuslearn'); ?></h2>
            <div class="nl-header-actions">
                <select class="nl-filter-dropdown" id="nl-assignment-filter">
                    <option value="all"><?php _e('All Assignments', 'nexuslearn'); ?></option>
                    <option value="pending"><?php _e('Pending', 'nexuslearn'); ?></option>
                    <option value="submitted"><?php _e('Submitted', 'nexuslearn'); ?></option>
                    <option value="graded"><?php _e('Graded', 'nexuslearn'); ?></option>
                </select>
            </div>
        </div>

        <?php if (!empty($assignments)): ?>
            <div class="nl-assignments-list">
                <?php foreach ($assignments as $assignment): ?>
                    <div class="nl-assignment-card" data-status="<?php echo esc_attr($assignment['status']); ?>">
                        <div class="nl-assignment-content">
                            <div class="nl-assignment-icon">
                                <?php echo $assignment['status'] === 'pending' ? '📝' : ($assignment['status'] === 'submitted' ? '📤' : '✓'); ?>
                            </div>
                            <div class="nl-assignment-details">
                                <h3><?php echo esc_html($assignment['title']); ?></h3>
                                <div class="nl-assignment-meta">
                                    <span class="nl-course-name">
                                        <?php echo esc_html($assignment['course_title']); ?>
                                    </span>
                                    <span class="nl-due-date">
                                        <?php printf(
                                            __('Due: %s', 'nexuslearn'),
                                            date_i18n(get_option('date_format'), strtotime($assignment['due_date']))
                                        ); ?>
                                    </span>
                                    <span class="nl-status <?php echo esc_attr($assignment['status']); ?>">
                                        <?php echo esc_html(ucfirst($assignment['status'])); ?>
                                    </span>
                                </div>
                            </div>
                            <div class="nl-assignment-actions">
                                <?php if ($assignment['status'] === 'pending'): ?>
                                    <button class="nl-button nl-button-primary" 
                                            onclick="submitAssignment(<?php echo esc_attr($assignment['id']); ?>)">
                                        <?php _e('Submit', 'nexuslearn'); ?>
                                    </button>
                                <?php elseif ($assignment['status'] === 'graded'): ?>
                                    <button class="nl-button nl-button-secondary" 
                                            onclick="viewFeedback(<?php echo esc_attr($assignment['id']); ?>)">
                                        <?php _e('View Feedback', 'nexuslearn'); ?>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="nl-empty-state">
                <div class="nl-empty-icon">📝</div>
                <h3><?php _e('No Assignments Yet', 'nexuslearn'); ?></h3>
                <p><?php _e('You don\'t have any assignments at the moment.', 'nexuslearn'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>
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
    <div class="nl-section-header">
        <h1 class="nl-page-title"><?php _e('Assignments', 'nexuslearn'); ?></h1>
        <p class="nl-subtitle"><?php _e('View and manage your course assignments', 'nexuslearn'); ?></p>
    </div>

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
                                <?php if (!empty($assignment['description'])): ?>
                                    <div class="nl-assignment-description">
                                        <?php echo wp_kses_post($assignment['description']); ?>
                                    </div>
                                <?php endif; ?>
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
                        
                        <?php if ($assignment['status'] === 'graded' && !empty($assignment['score'])): ?>
                            <div class="nl-assignment-score">
                                <div class="nl-score-value">
                                    <?php echo esc_html($assignment['score']); ?>/<?php echo esc_html($assignment['total_score']); ?>
                                </div>
                                <div class="nl-score-percentage">
                                    <?php echo round(($assignment['score'] / $assignment['total_score']) * 100); ?>%
                                </div>
                            </div>
                        <?php endif; ?>
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

    <!-- Assignment Submission Modal -->
    <div id="nl-submission-modal" class="nl-modal" style="display: none;">
        <div class="nl-modal-content">
            <h3><?php _e('Submit Assignment', 'nexuslearn'); ?></h3>
            <form id="nl-assignment-form" enctype="multipart/form-data">
                <div class="nl-form-group">
                    <label for="assignment-file"><?php _e('Upload File', 'nexuslearn'); ?></label>
                    <input type="file" id="assignment-file" name="assignment_file" required>
                </div>
                <div class="nl-form-group">
                    <label for="submission-notes"><?php _e('Notes (Optional)', 'nexuslearn'); ?></label>
                    <textarea id="submission-notes" name="submission_notes"></textarea>
                </div>
                <div class="nl-form-actions">
                    <button type="button" class="nl-button nl-button-secondary" onclick="closeModal()">
                        <?php _e('Cancel', 'nexuslearn'); ?>
                    </button>
                    <button type="submit" class="nl-button nl-button-primary">
                        <?php _e('Submit', 'nexuslearn'); ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Assignment Section Styles */
.nl-assignments-container {
    background: white;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin-top: 20px;
}

.nl-section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.nl-assignment-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 15px;
    transition: all 0.3s ease;
}

.nl-assignment-card:hover {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.nl-assignment-content {
    display: flex;
    gap: 20px;
}

.nl-assignment-icon {
    font-size: 24px;
    color: #6b7280;
}

.nl-assignment-details {
    flex: 1;
}

.nl-assignment-meta {
    display: flex;
    gap: 15px;
    margin-top: 8px;
    color: #6b7280;
    font-size: 0.875rem;
}

.nl-status {
    padding: 2px 8px;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

.nl-status.pending {
    background: #fef3c7;
    color: #92400e;
}

.nl-status.submitted {
    background: #dbeafe;
    color: #1e40af;
}

.nl-status.graded {
    background: #dcfce7;
    color: #166534;
}

.nl-assignment-actions {
    display: flex;
    gap: 10px;
}

.nl-button {
    padding: 8px 16px;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
    border: none;
    transition: all 0.2s ease;
}

.nl-button-primary {
    background: #6366f1;
    color: white;
}

.nl-button-primary:hover {
    background: #4f46e5;
}

.nl-button-secondary {
    background: #f3f4f6;
    color: #4b5563;
}

.nl-button-secondary:hover {
    background: #e5e7eb;
}

/* Modal Styles */
.nl-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.nl-modal-content {
    background: white;
    padding: 20px;
    border-radius: 8px;
    width: 90%;
    max-width: 500px;
}

.nl-form-group {
    margin-bottom: 15px;
}

.nl-form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
}

.nl-form-group input,
.nl-form-group textarea {
    width: 100%;
    padding: 8px;
    border: 1px solid #e5e7eb;
    border-radius: 4px;
}

.nl-form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 20px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .nl-section-header {
        flex-direction: column;
        gap: 10px;
    }
    
    .nl-assignment-content {
        flex-direction: column;
    }
    
    .nl-assignment-meta {
        flex-wrap: wrap;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Filter assignments
    $('#nl-assignment-filter').on('change', function() {
        const status = $(this).val();
        if (status === 'all') {
            $('.nl-assignment-card').show();
        } else {
            $('.nl-assignment-card').hide();
            $(`.nl-assignment-card[data-status="${status}"]`).show();
        }
    });
});

function submitAssignment(assignmentId) {
    const modal = document.getElementById('nl-submission-modal');
    modal.style.display = 'flex';
}

function closeModal() {
    const modal = document.getElementById('nl-submission-modal');
    modal.style.display = 'none';
}

function viewFeedback(assignmentId) {
    // Add feedback viewing logic
}
</script>
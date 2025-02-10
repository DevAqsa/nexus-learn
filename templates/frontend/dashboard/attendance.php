<?php
if (!defined('ABSPATH')) exit;

if (!isset($attendance_manager)) {
    return;
}

$user_id = get_current_user_id();
$attendance_data = $attendance_manager->get_user_attendance($user_id);
?>

<div class="nl-content-section">
    <!-- Header Section -->
    <!-- <div class="nl-section-header">
        <h1 class="nl-page-title"><?php _e('Attendance Records', 'nexuslearn'); ?></h1>
        <p class="nl-subtitle"><?php _e('View your course attendance history', 'nexuslearn'); ?></p>
    </div> -->

    <!-- Attendance Overview -->
    <div class="nl-stats-grid">
        <div class="nl-stat-card">
            <span class="nl-stat-icon present">✓</span>
            <div class="nl-stat-value"><?php echo esc_html($attendance_data['present_percentage']); ?>%</div>
            <div class="nl-stat-label"><?php _e('Attendance Rate', 'nexuslearn'); ?></div>
        </div>
        <div class="nl-stat-card">
            <span class="nl-stat-icon absent">✗</span>
            <div class="nl-stat-value"><?php echo esc_html($attendance_data['absent_count']); ?></div>
            <div class="nl-stat-label"><?php _e('Absences', 'nexuslearn'); ?></div>
        </div>
        <div class="nl-stat-card">
            <span class="nl-stat-icon late">⏰</span>
            <div class="nl-stat-value"><?php echo esc_html($attendance_data['late_count']); ?></div>
            <div class="nl-stat-label"><?php _e('Late Arrivals', 'nexuslearn'); ?></div>
        </div>
    </div>

    <!-- Attendance History -->
    <div class="nl-attendance-container">
        <div class="nl-section-header">
            <h2><?php _e('Recent Attendance', 'nexuslearn'); ?></h2>
            <div class="nl-header-actions">
                <select class="nl-filter-dropdown" id="nl-attendance-filter">
                    <option value="all"><?php _e('All Records', 'nexuslearn'); ?></option>
                    <option value="present"><?php _e('Present', 'nexuslearn'); ?></option>
                    <option value="absent"><?php _e('Absent', 'nexuslearn'); ?></option>
                    <option value="late"><?php _e('Late', 'nexuslearn'); ?></option>
                </select>
            </div>
        </div>

        <?php if (!empty($attendance_data['recent'])): ?>
            <div class="nl-attendance-list">
                <?php foreach ($attendance_data['recent'] as $record): ?>
                    <div class="nl-attendance-card" data-status="<?php echo esc_attr($record['status']); ?>">
                        <div class="nl-attendance-content">
                            <div class="nl-attendance-icon">
                                <?php 
                                echo $record['status'] === 'present' ? '✓' : 
                                    ($record['status'] === 'absent' ? '✗' : '⏰'); 
                                ?>
                            </div>
                            <div class="nl-attendance-details">
                                <div class="nl-attendance-meta">
                                    <span class="nl-course-name">
                                        <?php echo esc_html($record['course_title']); ?>
                                    </span>
                                    <span class="nl-attendance-date">
                                        <?php echo date_i18n(get_option('date_format'), strtotime($record['date'])); ?>
                                    </span>
                                    <span class="nl-status <?php echo esc_attr($record['status']); ?>">
                                        <?php echo esc_html(ucfirst($record['status'])); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="nl-empty-state">
                <div class="nl-empty-icon">📅</div>
                <h3><?php _e('No Attendance Records', 'nexuslearn'); ?></h3>
                <p><?php _e('Your attendance records will appear here once you start attending courses.', 'nexuslearn'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>
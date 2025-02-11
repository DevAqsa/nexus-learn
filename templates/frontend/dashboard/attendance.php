<?php
if (!defined('ABSPATH')) exit;

$user_id = get_current_user_id();
$current_month = isset($_GET['month']) ? sanitize_text_field($_GET['month']) : date('Y-m');

// Function to get attendance data
function get_user_attendance($user_id, $month) {
    global $wpdb;
    $table = $wpdb->prefix . 'nexuslearn_attendance';
    
    return $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM {$table} 
        WHERE user_id = %d 
        AND DATE_FORMAT(attendance_date, '%%Y-%%m') = %s
        ORDER BY attendance_date DESC",
        $user_id,
        $month
    ), ARRAY_A);
}

$attendance_records = get_user_attendance($user_id, $current_month);
?>

<div class="nl-attendance-section nl-content-section">
    <!-- Header Section -->
    <div class="nl-section-header">
        <h2><?php _e('Attendance Records', 'nexuslearn'); ?></h2>
        <div class="nl-header-actions">
            <select id="nl-month-selector" class="nl-select">
                <?php
                for ($i = 0; $i < 12; $i++) {
                    $month = date('Y-m', strtotime("-$i months"));
                    $selected = ($month === $current_month) ? 'selected' : '';
                    echo sprintf(
                        '<option value="%s" %s>%s</option>',
                        esc_attr($month),
                        $selected,
                        date_i18n('F Y', strtotime($month))
                    );
                }
                ?>
            </select>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="nl-stats-grid">
        <div class="nl-stat-card">
            <span class="nl-stat-icon present">✓</span>
            <div class="nl-stat-value"><?php echo count(array_filter($attendance_records, function($record) {
                return $record['status'] === 'present';
            })); ?></div>
            <div class="nl-stat-label"><?php _e('Present', 'nexuslearn'); ?></div>
        </div>
        <div class="nl-stat-card">
            <span class="nl-stat-icon absent">✗</span>
            <div class="nl-stat-value"><?php echo count(array_filter($attendance_records, function($record) {
                return $record['status'] === 'absent';
            })); ?></div>
            <div class="nl-stat-label"><?php _e('Absent', 'nexuslearn'); ?></div>
        </div>
        <div class="nl-stat-card">
            <span class="nl-stat-icon percentage">%</span>
            <div class="nl-stat-value"><?php 
                $total = count($attendance_records);
                $present = count(array_filter($attendance_records, function($record) {
                    return $record['status'] === 'present';
                }));
                echo $total > 0 ? round(($present / $total) * 100) : 0;
            ?>%</div>
            <div class="nl-stat-label"><?php _e('Attendance Rate', 'nexuslearn'); ?></div>
        </div>
    </div>

    <!-- Calendar View -->
    <div class="nl-calendar-container">
        <table class="nl-calendar">
            <thead>
                <tr>
                    <th><?php _e('Date', 'nexuslearn'); ?></th>
                    <th><?php _e('Status', 'nexuslearn'); ?></th>
                    <th><?php _e('Check-in Time', 'nexuslearn'); ?></th>
                    <th><?php _e('Check-out Time', 'nexuslearn'); ?></th>
                    <th><?php _e('Duration', 'nexuslearn'); ?></th>
                    <th><?php _e('Course', 'nexuslearn'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($attendance_records)): ?>
                    <?php foreach ($attendance_records as $record): ?>
                        <tr class="nl-attendance-row <?php echo esc_attr($record['status']); ?>">
                            <td><?php echo date_i18n(get_option('date_format'), strtotime($record['attendance_date'])); ?></td>
                            <td>
                                <span class="nl-status-badge <?php echo esc_attr($record['status']); ?>">
                                    <?php echo esc_html(ucfirst($record['status'])); ?>
                                </span>
                            </td>
                            <td><?php echo $record['check_in'] ? date_i18n(get_option('time_format'), strtotime($record['check_in'])) : '-'; ?></td>
                            <td><?php echo $record['check_out'] ? date_i18n(get_option('time_format'), strtotime($record['check_out'])) : '-'; ?></td>
                            <td><?php
                                if ($record['check_in'] && $record['check_out']) {
                                    $duration = strtotime($record['check_out']) - strtotime($record['check_in']);
                                    echo sprintf(
                                        __('%d hrs %d mins', 'nexuslearn'),
                                        floor($duration / 3600),
                                        floor(($duration % 3600) / 60)
                                    );
                                } else {
                                    echo '-';
                                }
                            ?></td>
                            <td><?php echo esc_html(get_the_title($record['course_id'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="nl-no-records">
                            <div class="nl-empty-state">
                                <div class="nl-empty-icon">📅</div>
                                <h3><?php _e('No Attendance Records', 'nexuslearn'); ?></h3>
                                <p><?php _e('No attendance records found for this month.', 'nexuslearn'); ?></p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
.nl-attendance-section {
    padding: 2rem;
}

.nl-calendar {
    width: 100%;
    border-collapse: collapse;
    margin-top: 2rem;
    background: white;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.nl-calendar th,
.nl-calendar td {
    padding: 1rem;
    border: 1px solid #eee;
    text-align: left;
}

.nl-calendar th {
    background: #f8f9fa;
    font-weight: 600;
}

.nl-status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 999px;
    font-size: 0.875rem;
    font-weight: 500;
}

.nl-status-badge.present {
    background: #d1fae5;
    color: #065f46;
}

.nl-status-badge.absent {
    background: #fee2e2;
    color: #991b1b;
}

.nl-empty-state {
    text-align: center;
    padding: 3rem;
}

.nl-empty-icon {
    font-size: 2.5rem;
    margin-bottom: 1rem;
}

.nl-stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.nl-stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    text-align: center;
}

.nl-stat-icon {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}

.nl-stat-value {
    font-size: 2rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.nl-stat-label {
    color: #6b7280;
    font-size: 0.875rem;
}

.nl-section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.nl-select {
    padding: 0.5rem 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.375rem;
    background: white;
}
</style>

<script>
jQuery(document).ready(function($) {
    $('#nl-month-selector').on('change', function() {
        window.location.href = '?view=attendance&month=' + $(this).val();
    });
});
</script>
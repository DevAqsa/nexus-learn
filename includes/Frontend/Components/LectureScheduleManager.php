<?php
namespace NexusLearn\Frontend\Components;

class LectureScheduleManager {
    public function __construct() {
        add_action('wp_ajax_nl_get_lecture_schedule', [$this, 'get_lecture_schedule_data']);
    }

    public function render_schedule_section($user_id) {
        $schedule_data = $this->get_user_schedule($user_id);
        ob_start();
        ?>
        <div class="nl-schedule-section nl-content-section">
            <div class="nl-section-header">
                <h2><?php _e('Lecture Schedule', 'nexuslearn'); ?></h2>
                <div class="nl-header-actions">
                    <select id="nl-schedule-week" class="nl-select">
                        <option value="current"><?php _e('This Week', 'nexuslearn'); ?></option>
                        <option value="next"><?php _e('Next Week', 'nexuslearn'); ?></option>
                    </select>
                </div>
            </div>

            <?php if (!empty($schedule_data)): ?>
                <div class="nl-schedule-grid">
                    <?php foreach ($schedule_data as $day => $lectures): ?>
                        <div class="nl-schedule-day">
                            <div class="nl-day-header"><?php echo esc_html($day); ?></div>
                            <?php foreach ($lectures as $lecture): ?>
                                <div class="nl-lecture-card">
                                    <div class="nl-lecture-time">
                                        <?php echo esc_html($lecture['start_time'] . ' - ' . $lecture['end_time']); ?>
                                    </div>
                                    <div class="nl-lecture-details">
                                        <h4><?php echo esc_html($lecture['course_title']); ?></h4>
                                        <p class="nl-lecture-instructor">
                                            <?php echo esc_html($lecture['instructor']); ?>
                                        </p>
                                        <p class="nl-lecture-location">
                                            <?php echo esc_html($lecture['location']); ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="nl-empty-state">
                    <div class="nl-empty-icon">📚</div>
                    <h3><?php _e('No Lectures Scheduled', 'nexuslearn'); ?></h3>
                    <p><?php _e('Your lecture schedule will appear here once you are enrolled in courses.', 'nexuslearn'); ?></p>
                </div>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    private function get_user_schedule($user_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'nl_lecture_schedule';
        
        // For development/testing, return sample data
        return [
            'Monday' => [
                [
                    'course_title' => 'Introduction to WordPress',
                    'start_time' => '09:00 AM',
                    'end_time' => '10:30 AM',
                    'instructor' => 'Dr. John ',
                    'location' => 'Room 101'
                ],
                [
                    'course_title' => 'PHP Development',
                    'start_time' => '11:00 AM',
                    'end_time' => '12:30 PM',
                    'instructor' => 'Dr. John ',
                    'location' => 'Lab 203'
                ]
            ],
            'Wednesday' => [
                [
                    'course_title' => 'Web Security',
                    'start_time' => '02:00 PM',
                    'end_time' => '03:30 PM',
                    'instructor' => 'Dr. John ',
                    'location' => 'Room 105'
                ]
            ],
            'Friday' => [
                [
                    'course_title' => 'Database Management',
                    'start_time' => '10:00 AM',
                    'end_time' => '11:30 AM',
                    'instructor' => 'Dr. John ',
                    'location' => 'Lab 204'
                ]
            ]
        ];
    }

    public function get_lecture_schedule_data() {
        check_ajax_referer('nl_dashboard_nonce', 'nonce');
        $user_id = get_current_user_id();
        $schedule = $this->get_user_schedule($user_id);
        wp_send_json_success($schedule);
    }
}

?>
<style>

.nl-schedule-section {
    margin-top: 2rem;
}

.nl-schedule-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-top: 1.5rem;
}

.nl-schedule-day {
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    padding: 1rem;
}

.nl-day-header {
    font-weight: 600;
    font-size: 1.1rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #eee;
    margin-bottom: 1rem;
}

.nl-lecture-card {
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 0.375rem;
    margin-bottom: 1rem;
}

.nl-lecture-card:last-child {
    margin-bottom: 0;
}

.nl-lecture-time {
    font-weight: 500;
    color: #4a5568;
    margin-bottom: 0.5rem;
}

.nl-lecture-details h4 {
    margin: 0 0 0.5rem 0;
    font-size: 1rem;
}

.nl-lecture-instructor,
.nl-lecture-location {
    font-size: 0.875rem;
    color: #718096;
    margin: 0.25rem 0;
}
</style>
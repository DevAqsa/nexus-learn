<?php
namespace NexusLearn\Frontend\Components;

class GradeBook {
    private $current_tab;
    private $user_id;

    public function __construct() {
        $this->user_id = get_current_user_id();
        $this->current_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'grades';
        
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public function enqueue_assets() {
        wp_enqueue_style(
            'nl-gradebook-styles',
            NEXUSLEARN_PLUGIN_URL . 'assets/css/gradebook.css',
            [],
            NEXUSLEARN_VERSION
        );

        wp_enqueue_script(
            'nl-gradebook-scripts',
            NEXUSLEARN_PLUGIN_URL . 'assets/js/gradebook.js',
            ['jquery'],
            NEXUSLEARN_VERSION,
            true
        );

        wp_localize_script('nl-gradebook-scripts', 'nlGradebook', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('nl_gradebook_nonce')
        ]);
    }

    public function render_gradebook() {
        ob_start();
        ?>
        <div class="nl-gradebook-container">
            <!-- Navigation Tabs -->
            <div class="nl-gradebook-nav">
                <?php $this->render_navigation(); ?>
            </div>

            <!-- Content Area -->
            <div class="nl-gradebook-content">
                <?php
                switch ($this->current_tab) {
                    case 'announcements':
                        $this->render_announcements();
                        break;
                    case 'assignments':
                        $this->render_assignments();
                        break;
                    case 'quizzes':
                        $this->render_quizzes();
                        break;
                    case 'grading-scheme':
                        $this->render_grading_scheme();
                        break;
                    default:
                        $this->render_grades();
                        break;
                }
                ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    private function render_navigation() {
        $tabs = [
            'grades' => [
                'icon' => 'dashicons-book',
                'label' => __('Student Grade Book', 'nexuslearn')
            ],
            'quizzes' => [
                'icon' => 'dashicons-editor-help',
                'label' => __('Quizzes', 'nexuslearn')
            ],
            'assignments' => [
                'icon' => 'dashicons-portfolio',
                'label' => __('Assignments', 'nexuslearn')
            ],
            'announcements' => [
                'icon' => 'dashicons-megaphone',
                'label' => __('Announcements', 'nexuslearn')
            ],
            'grading-scheme' => [
                'icon' => 'dashicons-chart-bar',
                'label' => __('Grading Scheme', 'nexuslearn')
            ]
        ];

        foreach ($tabs as $tab_key => $tab) {
            $active_class = $this->current_tab === $tab_key ? 'active' : '';
            ?>
            <a href="?page=gradebook&tab=<?php echo esc_attr($tab_key); ?>" 
               class="nl-nav-item <?php echo esc_attr($active_class); ?>">
                <i class="dashicons <?php echo esc_attr($tab['icon']); ?>"></i>
                <?php echo esc_html($tab['label']); ?>
            </a>
            <?php
        }
    }

    private function render_announcements() {
        $announcements = $this->get_announcements();
        ?>
        <div class="nl-announcements-section">
            <h2><?php _e('Important Announcements', 'nexuslearn'); ?></h2>
            <?php
            if (!empty($announcements)) {
                foreach ($announcements as $announcement) {
                    ?>
                    <div class="nl-announcement-card priority-<?php echo esc_attr($announcement['priority']); ?>">
                        <div class="nl-announcement-header">
                            <h3><?php echo esc_html($announcement['title']); ?></h3>
                            <span class="nl-date"><?php echo esc_html($announcement['date']); ?></span>
                        </div>
                        <div class="nl-announcement-content">
                            <?php echo wp_kses_post($announcement['content']); ?>
                        </div>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div class="nl-empty-state">
                    <?php _e('No announcements available', 'nexuslearn'); ?>
                </div>
                <?php
            }
            ?>
        </div>
        <?php
    }

    private function render_assignments() {
        $assignments = $this->get_assignments();
        ?>
        <div class="nl-assignments-section">
            <h2><?php _e('Course Assignments', 'nexuslearn'); ?></h2>
            <table class="nl-assignments-table">
                <thead>
                    <tr>
                        <th><?php _e('Course', 'nexuslearn'); ?></th>
                        <th><?php _e('Assignment', 'nexuslearn'); ?></th>
                        <th><?php _e('Due Date', 'nexuslearn'); ?></th>
                        <th><?php _e('Status', 'nexuslearn'); ?></th>
                        <th><?php _e('Score', 'nexuslearn'); ?></th>
                        <th><?php _e('Actions', 'nexuslearn'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($assignments)) {
                        foreach ($assignments as $assignment) {
                            ?>
                            <tr>
                                <td><?php echo esc_html($assignment['course_code']); ?></td>
                                <td><?php echo esc_html($assignment['title']); ?></td>
                                <td><?php echo esc_html($assignment['due_date']); ?></td>
                                <td>
                                    <span class="nl-status-badge <?php echo esc_attr($assignment['status']); ?>">
                                        <?php echo esc_html($assignment['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo esc_html($assignment['score']); ?></td>
                                <td>
                                    <?php if ($assignment['status'] !== 'submitted'): ?>
                                        <button class="nl-button submit-assignment" 
                                                data-assignment-id="<?php echo esc_attr($assignment['id']); ?>">
                                            <?php _e('Submit', 'nexuslearn'); ?>
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="6" class="nl-empty-state">
                                <?php _e('No assignments available', 'nexuslearn'); ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    private function render_grading_scheme() {
        ?>
        <div class="nl-grading-scheme">
            <h2><?php _e('Grading Scheme', 'nexuslearn'); ?></h2>
            
            <div class="nl-scheme-grid">
                <!-- Grade Distribution -->
                <div class="nl-scheme-card">
                    <h3><?php _e('Grade Distribution', 'nexuslearn'); ?></h3>
                    <table class="nl-scheme-table">
                        <thead>
                            <tr>
                                <th><?php _e('Percentage', 'nexuslearn'); ?></th>
                                <th><?php _e('Grade', 'nexuslearn'); ?></th>
                                <th><?php _e('Points', 'nexuslearn'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>93-100</td><td>A</td><td>4.0</td></tr>
                            <tr><td>90-92</td><td>A-</td><td>3.7</td></tr>
                            <tr><td>87-89</td><td>B+</td><td>3.3</td></tr>
                            <tr><td>83-86</td><td>B</td><td>3.0</td></tr>
                            <tr><td>80-82</td><td>B-</td><td>2.7</td></tr>
                            <!-- Add more grade ranges -->
                        </tbody>
                    </table>
                </div>

                <!-- Assessment Weights -->
                <div class="nl-scheme-card">
                    <h3><?php _e('Assessment Weights', 'nexuslearn'); ?></h3>
                    <table class="nl-scheme-table">
                        <thead>
                            <tr>
                                <th><?php _e('Component', 'nexuslearn'); ?></th>
                                <th><?php _e('Weight', 'nexuslearn'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>Assignments</td><td>30%</td></tr>
                            <tr><td>Quizzes</td><td>20%</td></tr>
                            <tr><td>Midterm</td><td>20%</td></tr>
                            <tr><td>Final Exam</td><td>30%</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
    }

    // Helper methods to fetch data
    private function get_announcements() {
        global $wpdb;
        // Replace with actual database query
        return [
            [
                'title' => 'AI Course Schedule Released',
                'date' => '2024-02-28',
                'content' => 'The schedule has been published. Please check your student portal for details.',
                'priority' => 'high'
            ],
            [
                'title' => 'Assignment Deadline Extension',
                'date' => '2024-02-25',
                'content' => 'Due to the upcoming holiday, all assignments due next week have been extended by 48 hours.',
                'priority' => 'medium'
            ]
        ];
    }

    private function get_assignments() {
        global $wpdb;
        // Replace with actual database query
        return [
            [
                'id' => 1,
                'course_code' => 'CS501',
                'title' => 'Programming Assignment 1',
                'due_date' => '2024-03-15',
                'status' => 'pending',
                'score' => '-'
            ],
            [
                'id' => 2,
                'course_code' => 'MTH401',
                'title' => 'Mathematical Proofs',
                'due_date' => '2024-03-20',
                'status' => 'submitted',
                'score' => '85/100'
            ]
        ];
    }
}
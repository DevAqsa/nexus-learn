<?php
namespace NexusLearn\Frontend;

class StudentDashboard {
    private $certificates_manager;
    private $progress_tracker;
    private $profile_manager;
    private $assignments_manager;
    private $notes_manager;
    private $general_settings;
    private $grade_book;

    public function __construct() {
        $this->certificates_manager = new Components\CertificatesManager();
        $this->progress_tracker = new Components\ProgressTracker();
        $this->profile_manager = new Components\ProfileManager();
        $this->assignments_manager = new Components\AssignmentsManager();
        $this->notes_manager = new Components\NotesManager();
        $this->general_settings = new Components\GeneralSettings();
        $this->grade_book = new Components\GradeBook();
        $this->todo_calendar = new Components\TodoCalendar();
        $this->lecture_schedule = new Components\LectureScheduleManager();
        

        add_shortcode('nexuslearn_student_dashboard', [$this, 'render_dashboard']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public function enqueue_assets() {
        if (!has_shortcode(get_post()->post_content, 'nexuslearn_student_dashboard')) {
            return;
        }

        if (isset($_GET['view']) && $_GET['view'] === 'notes') {
            wp_enqueue_style(
                'nl-notes-styles',
                NEXUSLEARN_PLUGIN_URL . 'assets/css/notes.css',
                [],
                NEXUSLEARN_VERSION
            );
        }
        wp_enqueue_style(
            'nl-dashboard-styles',
            NEXUSLEARN_PLUGIN_URL . 'assets/css/student-dashboard.css',
            [],
            NEXUSLEARN_VERSION
        );

        wp_enqueue_script(
            'nl-dashboard-scripts',
            NEXUSLEARN_PLUGIN_URL . 'assets/js/student-dashboard.js',
            ['jquery'],
            NEXUSLEARN_VERSION,
            true
        );

        // Add TinyMCE for notes editor if we're on the notes page
        if (isset($_GET['view']) && $_GET['view'] === 'notes') {
            wp_enqueue_editor();
        }

        wp_localize_script('nl-dashboard-scripts', 'nlDashboard', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('nl_dashboard_nonce')
        ]);
    }

    public function render_dashboard() {
        if (!is_user_logged_in()) {
            return $this->render_login_required();
        }

        ob_start();
        
        // Make class properties available to templates
        $certificates_manager = $this->certificates_manager;
        $progress_tracker = $this->progress_tracker;
        $profile_manager = $this->profile_manager;
        $assignments_manager = $this->assignments_manager;
        $notes_manager = $this->notes_manager;
        $general_settings = $this->general_settings;
        
        // Load the main dashboard template
        include NEXUSLEARN_PLUGIN_DIR . 'templates/frontend/dashboard/main.php';
        
        return ob_get_clean();
    }

    private function render_login_required() {
        ob_start();
        ?>
        <div class="nl-login-required">
            <h2><?php _e('Login Required', 'nexuslearn'); ?></h2>
            <p><?php _e('Please log in to access your dashboard.', 'nexuslearn'); ?></p>
            <a href="<?php echo wp_login_url(get_permalink()); ?>" class="nl-button nl-button-primary">
                <?php _e('Log In', 'nexuslearn'); ?>
            </a>
        </div>
        <?php
        return ob_get_clean();
    }
}
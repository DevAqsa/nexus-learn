<?php
namespace NexusLearn\Frontend;

class StudentDashboard {
    private $certificates_manager;
    private $progress_tracker;
    private $profile_manager;

    public function __construct() {
        $this->certificates_manager = new Components\CertificatesManager();
        $this->progress_tracker = new Components\ProgressTracker();
        $this->profile_manager = new Components\ProfileManager();

        add_shortcode('nexuslearn_student_dashboard', [$this, 'render_dashboard']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public function enqueue_assets() {
        if (!has_shortcode(get_post()->post_content, 'nexuslearn_student_dashboard')) {
            return;
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
        
        // Load the main dashboard template
        include NEXUSLEARN_PLUGIN_DIR . 'templates/frontend/dashboard/main.php';
        
        // Load section templates based on current view
        $view = isset($_GET['view']) ? sanitize_key($_GET['view']) : 'overview';
        
        switch ($view) {
            case 'certificates':
                include NEXUSLEARN_PLUGIN_DIR . 'templates/frontend/dashboard/certificates.php';
                break;
            case 'courses':
                include NEXUSLEARN_PLUGIN_DIR . 'templates/frontend/dashboard/courses.php';
                break;
            case 'progress':
                include NEXUSLEARN_PLUGIN_DIR . 'templates/frontend/dashboard/progress.php';
                break;
            default:
                include NEXUSLEARN_PLUGIN_DIR . 'templates/frontend/dashboard/overview.php';
                break;
        }
        
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
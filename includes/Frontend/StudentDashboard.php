<?php
namespace NexusLearn\Frontend;

use NexusLearn\Frontend\Components\CertificatesManager;
use NexusLearn\Frontend\Components\ProgressTracker;
use NexusLearn\Frontend\Components\ProfileManager;

class StudentDashboard {
    private $certificates_manager;
    private $progress_tracker;
    private $profile_manager;
    

    public function __construct() {
        $this->certificates_manager = new CertificatesManager();
        $this->progress_tracker = new ProgressTracker();
        $this->profile_manager = new ProfileManager();

        add_shortcode('nexuslearn_student_dashboard', [$this, 'render_dashboard']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
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
        
        // Load the main dashboard template
        include NEXUSLEARN_PLUGIN_DIR . 'templates/frontend/dashboard/main.php';
        
        return ob_get_clean();
    }

    private function load_template($template) {
        // Make class properties available to templates
        $certificates_manager = $this->certificates_manager;
        $progress_tracker = $this->progress_tracker;
        $profile_manager = $this->profile_manager;
        
        include NEXUSLEARN_PLUGIN_DIR . 'templates/frontend/dashboard/' . $template . '.php';
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
}
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
        if (!is_user_logged_in()) {
            return $this->render_login_required();
        }

        ob_start();
        include NEXUSLEARN_PLUGIN_DIR . 'templates/frontend/gradebook/main.php';
        return ob_get_clean();
    }

    private function render_login_required() {
        ob_start();
        ?>
        <div class="nl-login-required">
            <h2><?php _e('Login Required', 'nexuslearn'); ?></h2>
            <p><?php _e('Please log in to access your gradebook.', 'nexuslearn'); ?></p>
            <a href="<?php echo wp_login_url(get_permalink()); ?>" class="nl-button nl-button-primary">
                <?php _e('Log In', 'nexuslearn'); ?>
            </a>
        </div>
        <?php
        return ob_get_clean();
    }
}
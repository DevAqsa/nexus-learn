<?php
namespace NexusLearn\Admin;

class Settings {
    private $general_settings;
    private $email_settings;
    private $course_settings;
    private $quiz_settings;
    private $certificate_settings;
    private $current_tab;

    public function __construct() {
        add_action('admin_init', [$this, 'init_settings']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
        
        // Initialize all settings classes
        $this->general_settings = new GeneralSettings();
        $this->email_settings = new EmailSettings();
        $this->course_settings = new CourseSettings();
        $this->quiz_settings = new QuizSettings();
        $this->certificate_settings = new CertificateSettings();
        
        // Get current tab
        $this->current_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'general';
    }

    public function init_settings() {
        // Each settings class handles its own registration
    }

    public function enqueue_scripts($hook) {
        if ($hook !== 'toplevel_page_nexuslearn-settings') {
            return;
        }

        wp_enqueue_style(
            'nexuslearn-admin',
            NEXUSLEARN_PLUGIN_URL . 'assets/css/admin.css',
            [],
            NEXUSLEARN_VERSION
        );

        wp_enqueue_script(
            'nexuslearn-admin',
            NEXUSLEARN_PLUGIN_URL . 'assets/js/admin.js',
            ['jquery'],
            NEXUSLEARN_VERSION,
            true
        );
    }

    public function render_page() {
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            return;
        }

        // Get current tab
        $current_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'general';
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <?php settings_errors(); ?>

            <nav class="nav-tab-wrapper">
                <a href="?page=nexuslearn-settings&tab=general" 
                   class="nav-tab <?php echo $current_tab === 'general' ? 'nav-tab-active' : ''; ?>">
                    <?php _e('General', 'nexuslearn'); ?>
                </a>
                <a href="?page=nexuslearn-settings&tab=email" 
                   class="nav-tab <?php echo $current_tab === 'email' ? 'nav-tab-active' : ''; ?>">
                    <?php _e('Email', 'nexuslearn'); ?>
                </a>
                <a href="?page=nexuslearn-settings&tab=courses" 
                   class="nav-tab <?php echo $current_tab === 'courses' ? 'nav-tab-active' : ''; ?>">
                    <?php _e('Courses', 'nexuslearn'); ?>
                </a>
                <a href="?page=nexuslearn-settings&tab=quizzes" 
                   class="nav-tab <?php echo $current_tab === 'quizzes' ? 'nav-tab-active' : ''; ?>">
                    <?php _e('Quizzes', 'nexuslearn'); ?>
                </a>
                <a href="?page=nexuslearn-settings&tab=certificates" 
                   class="nav-tab <?php echo $current_tab === 'certificates' ? 'nav-tab-active' : ''; ?>">
                    <?php _e('Certificates', 'nexuslearn'); ?>
                </a>
            </nav>

            <form action="options.php" method="post">
                <?php
                // Output security fields
                settings_fields('nexuslearn_options');
                
                // Output setting sections for current tab
                switch ($current_tab) {
                    case 'email':
                        do_settings_sections('nexuslearn-email-settings');
                        break;
                    case 'courses':
                        do_settings_sections('nexuslearn-course-settings');
                        break;
                    case 'quizzes':
                        do_settings_sections('nexuslearn-quiz-settings');
                        break;
                    case 'certificates':
                        do_settings_sections('nexuslearn-certificate-settings');
                        break;
                    default:
                        do_settings_sections('nexuslearn-settings');
                        break;
                }
                
                // Output save settings button
                submit_button(__('Save Changes', 'nexuslearn'));
                ?>
            </form>
        </div>

        <style>
        .nav-tab-wrapper {
            margin-bottom: 20px;
        }
        .form-table th {
            width: 250px;
            padding: 20px 10px;
        }
        .form-table td {
            padding: 15px 10px;
        }
        .description {
            font-style: italic;
            color: #666;
        }
        </style>
        <?php
    }
}
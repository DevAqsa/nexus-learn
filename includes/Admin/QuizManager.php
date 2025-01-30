<?php
namespace NexusLearn\Admin;

class QuizManager {
    public function __construct() {
        add_action('add_meta_boxes', [$this, 'add_quiz_meta_boxes']);
        add_action('save_post_nl_quiz', [$this, 'save_quiz_meta']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_quiz_scripts']);
    }

    public function add_quiz_meta_boxes() {
        add_meta_box(
            'nl_quiz_settings',
            __('Quiz Settings', 'nexuslearn'),
            [$this, 'render_quiz_settings'],
            'nl_quiz',
            'normal',
            'high'
        );

        add_meta_box(
            'nl_quiz_questions',
            __('Quiz Questions', 'nexuslearn'),
            [$this, 'render_quiz_questions'],
            'nl_quiz',
            'normal',
            'high'
        );
    }

    public function render_quiz_settings($post) {
        wp_nonce_field('nl_quiz_settings', 'nl_quiz_settings_nonce');
        $settings = get_post_meta($post->ID, '_quiz_settings', true) ?: [];
        ?>
        <table class="form-table">
            <tr>
                <th><label for="time_limit"><?php _e('Time Limit (minutes)', 'nexuslearn'); ?></label></th>
                <td>
                    <input type="number" id="time_limit" name="quiz_settings[time_limit]" 
                           value="<?php echo esc_attr($settings['time_limit'] ?? ''); ?>" min="0">
                    <p class="description"><?php _e('Leave empty for no time limit', 'nexuslearn'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="passing_score"><?php _e('Passing Score (%)', 'nexuslearn'); ?></label></th>
                <td>
                    <input type="number" id="passing_score" name="quiz_settings[passing_score]" 
                           value="<?php echo esc_attr($settings['passing_score'] ?? '70'); ?>" min="0" max="100">
                </td>
            </tr>
            <tr>
                <th><?php _e('Question Settings', 'nexuslearn'); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="quiz_settings[randomize_questions]" 
                               <?php checked(isset($settings['randomize_questions'])); ?>>
                        <?php _e('Randomize question order', 'nexuslearn'); ?>
                    </label>
                    <br>
                    <label>
                        <input type="checkbox" name="quiz_settings[show_results]" 
                               <?php checked(isset($settings['show_results'])); ?>>
                        <?php _e('Show results immediately after completion', 'nexuslearn'); ?>
                    </label>
                </td>
            </tr>
        </table>
        <?php
    }

    public function render_quiz_questions($post) {
        global $wpdb;
        $questions = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}nl_quiz_questions WHERE quiz_id = %d ORDER BY order_index",
            $post->ID
        ));
        ?>
        <div id="nl-quiz-questions">
            <div class="nl-question-list">
                <?php foreach ($questions as $question): ?>
                    <?php $this->render_question($question); ?>
                <?php endforeach; ?>
            </div>
            
            <button type="button" class="button nl-add-question">
                <?php _e('Add Question', 'nexuslearn'); ?>
            </button>
            
            <div class="nl-question-type-selector" style="display: none;">
                <button type="button" data-type="multiple_choice">Multiple Choice</button>
                <button type="button" data-type="true_false">True/False</button>
                <button type="button" data-type="essay">Essay</button>
                <button type="button" data-type="matching">Matching</button>
                <button type="button" data-type="fill_blanks">Fill in Blanks</button>
            </div>
        </div>

        <script type="text/template" id="nl-question-template">
            <!-- Template for new questions -->
            <div class="nl-question" data-id="{{id}}">
                <div class="nl-question-header">
                    <span class="nl-question-type">{{type}}</span>
                    <button type="button" class="nl-remove-question">&times;</button>
                </div>
                <div class="nl-question-content">
                    <textarea name="questions[{{id}}][text]" placeholder="Question text"></textarea>
                    <div class="nl-question-options"></div>
                    <div class="nl-question-points">
                        <label>Points: <input type="number" name="questions[{{id}}][points]" value="1" min="1"></label>
                    </div>
                </div>
            </div>
        </script>
        <?php
    }

    public function save_quiz_meta($post_id) {
        $security = \NexusLearn\Core\SecurityHandler::getInstance();
    
        if (!isset($_POST['nl_quiz_settings_nonce']) || 
            !$security->verify_nonce($_POST['nl_quiz_settings_nonce'], 'nl_quiz_settings')) {
            return;
        }
    
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
    
        if (!$security->check_capabilities('edit_post', $post_id)) {
            return;
        }
    
        // Save quiz settings
        if (isset($_POST['quiz_settings'])) {
            $sanitized_settings = $security->sanitize_quiz_data($_POST['quiz_settings']);
            update_post_meta($post_id, '_quiz_settings', $sanitized_settings);
        }
    }

    public function enqueue_quiz_scripts($hook) {
        global $post_type;
        
            if ($hook == 'post.php' || $hook == 'post-new.php') {
                if (get_post_type() == 'nl_quiz') {
                    wp_enqueue_script('jquery-ui-sortable');
                    wp_enqueue_style(
                        'nl-quiz-admin',
                        NEXUSLEARN_PLUGIN_URL . 'assets/css/quiz-admin.css',
                        [],
                        NEXUSLEARN_VERSION
                    );
                    wp_enqueue_script(
                        'nl-quiz-admin',
                        NEXUSLEARN_PLUGIN_URL . 'assets/js/quiz-admin.js',
                        ['jquery', 'jquery-ui-sortable'],
                        NEXUSLEARN_VERSION,
                        true
                    );
                }
            }
        
    }
}
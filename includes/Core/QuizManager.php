<?php
namespace NexusLearn\Core;

class QuizManager {
    public function __construct() {
        add_action('init', [$this, 'register_quiz_post_type']);
        add_action('add_meta_boxes', [$this, 'add_quiz_meta_boxes']);
        add_action('save_post_nl_quiz', [$this, 'save_quiz_data'], 10, 2);
    }

    public function register_quiz_post_type() {
        $labels = [
            'name'               => __('Quizzes', 'nexuslearn'),
            'singular_name'      => __('Quiz', 'nexuslearn'),
            'menu_name'          => __('Quizzes', 'nexuslearn'),
            'add_new'           => __('Add New Quiz', 'nexuslearn'),
            'add_new_item'      => __('Add New Quiz', 'nexuslearn'),
            'edit_item'         => __('Edit Quiz', 'nexuslearn'),
            'view_item'         => __('View Quiz', 'nexuslearn'),
            'all_items'         => __('All Quizzes', 'nexuslearn'),
            'search_items'      => __('Search Quizzes', 'nexuslearn'),
            'not_found'         => __('No quizzes found', 'nexuslearn'),
            'not_found_in_trash' => __('No quizzes found in Trash', 'nexuslearn')
        ];

        $args = [
            'labels'              => $labels,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => 'edit.php?post_type=nl_course',
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'menu_icon'           => 'dashicons-welcome-learn-more',
            'hierarchical'        => false,
            'supports'            => ['title', 'editor'],
            'has_archive'         => true,
            'rewrite'             => ['slug' => 'quizzes'],
            'menu_position'       => 5,
            'capability_type'     => 'post'
        ];

        register_post_type('nl_quiz', $args);
    }

    public function add_quiz_meta_boxes() {
        add_meta_box(
            'nl_quiz_settings',
            __('Quiz Settings', 'nexuslearn'),
            [$this, 'render_settings_meta_box'],
            'nl_quiz',
            'normal',
            'high'
        );

        add_meta_box(
            'nl_quiz_questions',
            __('Quiz Questions', 'nexuslearn'),
            [$this, 'render_questions_meta_box'],
            'nl_quiz',
            'normal',
            'high'
        );
    }

    public function render_settings_meta_box($post) {
        wp_nonce_field('nl_quiz_settings', 'nl_quiz_settings_nonce');
        $settings = get_post_meta($post->ID, '_quiz_settings', true) ?: [];
        ?>
        <table class="form-table">
            <tr>
                <th><label for="time_limit"><?php _e('Time Limit (minutes)', 'nexuslearn'); ?></label></th>
                <td>
                    <input type="number" id="time_limit" name="quiz_settings[time_limit]" 
                           value="<?php echo esc_attr($settings['time_limit'] ?? ''); ?>" min="0">
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
                <th><?php _e('Options', 'nexuslearn'); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="quiz_settings[randomize_questions]" value="1"
                               <?php checked(!empty($settings['randomize_questions'])); ?>>
                        <?php _e('Randomize Questions', 'nexuslearn'); ?>
                    </label>
                    <br>
                    <label>
                        <input type="checkbox" name="quiz_settings[show_results]" value="1"
                               <?php checked(!empty($settings['show_results'])); ?>>
                        <?php _e('Show Results Immediately', 'nexuslearn'); ?>
                    </label>
                </td>
            </tr>
        </table>
        <?php
    }

    public function render_questions_meta_box($post) {
        global $wpdb;
        $questions = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}nl_quiz_questions WHERE quiz_id = %d ORDER BY order_index",
            $post->ID
        ));
        ?>
        <div id="nl-quiz-questions">
            <div class="nl-question-list">
                <?php if ($questions): ?>
                    <?php foreach ($questions as $question): ?>
                        <?php $this->render_question($question); ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <button type="button" class="button button-primary nl-add-question">
                <?php _e('Add Question', 'nexuslearn'); ?>
            </button>

            <div class="nl-question-type-selector" style="display: none;">
                <button type="button" data-type="multiple_choice" class="button">Multiple Choice</button>
                <button type="button" data-type="true_false" class="button">True/False</button>
                <button type="button" data-type="essay" class="button">Essay</button>
                <button type="button" data-type="matching" class="button">Matching</button>
                <button type="button" data-type="fill_blanks" class="button">Fill in Blanks</button>
            </div>
        </div>
        <?php
    }

    private function render_question($question = null) {
        $id = $question ? $question->id : 'new_' . uniqid();
        $type = $question ? $question->question_type : '';
        $text = $question ? $question->question_text : '';
        $options = $question ? json_decode($question->question_options, true) : [];
        ?>
        <div class="nl-question" data-id="<?php echo esc_attr($id); ?>">
            <input type="hidden" name="questions[<?php echo $id; ?>][id]" value="<?php echo esc_attr($id); ?>">
            <input type="hidden" name="questions[<?php echo $id; ?>][type]" value="<?php echo esc_attr($type); ?>">
            
            <div class="nl-question-header">
                <span class="nl-question-type"><?php echo esc_html(ucwords(str_replace('_', ' ', $type))); ?></span>
                <button type="button" class="nl-remove-question">&times;</button>
            </div>
            
            <div class="nl-question-content">
                <textarea name="questions[<?php echo $id; ?>][text]" 
                          placeholder="Question text" class="widefat"><?php echo esc_textarea($text); ?></textarea>
                
                <?php $this->render_question_options($id, $type, $options); ?>
                
                <div class="nl-question-points">
                    <label>Points:
                        <input type="number" name="questions[<?php echo $id; ?>][points]" 
                               value="<?php echo esc_attr($question ? $question->points : 1); ?>" min="1">
                    </label>
                </div>
            </div>
        </div>
        <?php
    }

    private function render_question_options($id, $type, $options) {
        switch ($type) {
            case 'multiple_choice':
                ?>
                <div class="nl-options-list">
                    <?php if (!empty($options)): ?>
                        <?php foreach ($options as $key => $option): ?>
                            <div class="nl-option-item">
                                <input type="radio" name="questions[<?php echo $id; ?>][correct]" 
                                       value="<?php echo $key; ?>" 
                                       <?php checked($key == ($options['correct'] ?? '')); ?>>
                                <input type="text" name="questions[<?php echo $id; ?>][options][]" 
                                       value="<?php echo esc_attr($option); ?>" class="widefat">
                                <button type="button" class="nl-remove-option">&times;</button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <button type="button" class="button nl-add-option">Add Option</button>
                <?php
                break;

            case 'true_false':
                ?>
                <select name="questions[<?php echo $id; ?>][correct_answer]">
                    <option value="true" <?php selected($options['correct_answer'] ?? '', 'true'); ?>>True</option>
                    <option value="false" <?php selected($options['correct_answer'] ?? '', 'false'); ?>>False</option>
                </select>
                <?php
                break;

            // Add other question type options here
        }
    }

    public function save_quiz_data($post_id, $post) {
        if (!isset($_POST['nl_quiz_settings_nonce']) || 
            !wp_verify_nonce($_POST['nl_quiz_settings_nonce'], 'nl_quiz_settings')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if ($post->post_type !== 'nl_quiz') {
            return;
        }

        // Save quiz settings
        if (isset($_POST['quiz_settings'])) {
            update_post_meta($post_id, '_quiz_settings', $_POST['quiz_settings']);
        }

        // Save questions
        global $wpdb;
        if (isset($_POST['questions'])) {
            foreach ($_POST['questions'] as $question_data) {
                $data = [
                    'quiz_id' => $post_id,
                    'question_type' => sanitize_text_field($question_data['type']),
                    'question_text' => wp_kses_post($question_data['text']),
                    'points' => intval($question_data['points']),
                    'question_options' => json_encode($this->sanitize_question_options($question_data))
                ];

                if (strpos($question_data['id'], 'new_') === 0) {
                    $wpdb->insert($wpdb->prefix . 'nl_quiz_questions', $data);
                } else {
                    $wpdb->update(
                        $wpdb->prefix . 'nl_quiz_questions',
                        $data,
                        ['id' => $question_data['id']]
                    );
                }
            }
        }
    }

    private function sanitize_question_options($question_data) {
        $options = [];
        
        switch ($question_data['type']) {
            case 'multiple_choice':
                $options = array_map('sanitize_text_field', $question_data['options'] ?? []);
                $options['correct'] = sanitize_text_field($question_data['correct'] ?? '');
                break;

            case 'true_false':
                $options['correct_answer'] = $question_data['correct_answer'] === 'true' ? 'true' : 'false';
                break;
        }

        return $options;
    }
}
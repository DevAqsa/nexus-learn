<?php
namespace NexusLearn\Admin;

class QuizSettings {
    private $option_name = 'nexuslearn_quiz_options';
    private $options;

    public function __construct() {
        add_action('admin_init', [$this, 'init_settings']);
        $this->options = get_option($this->option_name, []);
    }

    public function init_settings() {
        register_setting(
            'nexuslearn_options',
            $this->option_name,
            [$this, 'sanitize_options']
        );

        add_settings_section(
            'nl_quiz_settings',
            __('Quiz Settings', 'nexuslearn'),
            [$this, 'render_section_description'],
            'nexuslearn-quiz-settings'
        );

        $this->add_settings_fields();
    }

    public function add_settings_fields() {
        $fields = [
            'default_passing_score' => [
                'title' => __('Default Passing Score (%)', 'nexuslearn'),
                'type' => 'number',
                'desc' => __('Default passing percentage for quizzes', 'nexuslearn'),
                'min' => 0,
                'max' => 100
            ],
            'time_limit' => [
                'title' => __('Default Time Limit', 'nexuslearn'),
                'type' => 'number',
                'desc' => __('Default time limit in minutes (0 for no limit)', 'nexuslearn'),
                'min' => 0
            ],
            'attempts_allowed' => [
                'title' => __('Default Attempts Allowed', 'nexuslearn'),
                'type' => 'number',
                'desc' => __('Number of attempts allowed (0 for unlimited)', 'nexuslearn'),
                'min' => 0
            ],
            'question_types' => [
                'title' => __('Enabled Question Types', 'nexuslearn'),
                'type' => 'multicheck',
                'options' => [
                    'multiple_choice' => __('Multiple Choice', 'nexuslearn'),
                    'true_false' => __('True/False', 'nexuslearn'),
                    'essay' => __('Essay', 'nexuslearn'),
                    'matching' => __('Matching', 'nexuslearn'),
                    'fill_blank' => __('Fill in the Blanks', 'nexuslearn'),
                    'short_answer' => __('Short Answer', 'nexuslearn')
                ],
                'desc' => __('Select which question types to enable', 'nexuslearn')
            ],
            'quiz_features' => [
                'title' => __('Quiz Features', 'nexuslearn'),
                'type' => 'multicheck',
                'options' => [
                    'randomize_questions' => __('Randomize Questions', 'nexuslearn'),
                    'show_correct_answers' => __('Show Correct Answers', 'nexuslearn'),
                    'instant_feedback' => __('Instant Feedback', 'nexuslearn'),
                    'question_numbering' => __('Question Numbering', 'nexuslearn'),
                    'review_after_submit' => __('Review After Submit', 'nexuslearn')
                ],
                'desc' => __('Select which features to enable for quizzes', 'nexuslearn')
            ],
            'result_display' => [
                'title' => __('Result Display', 'nexuslearn'),
                'type' => 'select',
                'options' => [
                    'percentage' => __('Percentage Only', 'nexuslearn'),
                    'letter_grade' => __('Letter Grade', 'nexuslearn'),
                    'both' => __('Both', 'nexuslearn')
                ],
                'desc' => __('How to display quiz results', 'nexuslearn')
            ],
            'grading_system' => [
                'title' => __('Grading System', 'nexuslearn'),
                'type' => 'textarea',
                'desc' => __('Define grade ranges (e.g., A: 90-100%, B: 80-89%, etc.)', 'nexuslearn')
            ]
        ];

        foreach ($fields as $field_id => $field) {
            add_settings_field(
                $field_id,
                $field['title'],
                [$this, 'render_field'],
                'nexuslearn-quiz-settings',
                'nl_quiz_settings',
                [
                    'id' => $field_id,
                    'type' => $field['type'],
                    'desc' => $field['desc'],
                    'options' => $field['options'] ?? [],
                    'min' => $field['min'] ?? '',
                    'max' => $field['max'] ?? ''
                ]
            );
        }
    }

    public function render_section_description() {
        echo '<p>' . __('Configure quiz-related settings for your learning platform.', 'nexuslearn') . '</p>';
    }

    public function render_field($args) {
        $id = $args['id'];
        $type = $args['type'];
        $value = $this->get_option($id);
        
        switch ($type) {
            case 'number':
                printf(
                    '<input type="number" id="%1$s" name="%2$s[%1$s]" value="%3$s" class="small-text" %4$s %5$s>',
                    esc_attr($id),
                    esc_attr($this->option_name),
                    esc_attr($value),
                    isset($args['min']) ? 'min="' . esc_attr($args['min']) . '"' : '',
                    isset($args['max']) ? 'max="' . esc_attr($args['max']) . '"' : ''
                );
                break;

            case 'select':
                printf(
                    '<select id="%1$s" name="%2$s[%1$s]">',
                    esc_attr($id),
                    esc_attr($this->option_name)
                );
                
                foreach ($args['options'] as $key => $label) {
                    printf(
                        '<option value="%1$s" %2$s>%3$s</option>',
                        esc_attr($key),
                        selected($value, $key, false),
                        esc_html($label)
                    );
                }
                
                echo '</select>';
                break;

            case 'textarea':
                printf(
                    '<textarea id="%1$s" name="%2$s[%1$s]" rows="5" class="large-text">%3$s</textarea>',
                    esc_attr($id),
                    esc_attr($this->option_name),
                    esc_textarea($value)
                );
                break;

            case 'multicheck':
                $saved_values = !empty($value) ? (array) $value : [];
                foreach ($args['options'] as $key => $label) {
                    printf(
                        '<label class="checkbox-label"><input type="checkbox" name="%1$s[%2$s][]" value="%3$s" %4$s> %5$s</label><br>',
                        esc_attr($this->option_name),
                        esc_attr($id),
                        esc_attr($key),
                        checked(in_array($key, $saved_values), true, false),
                        esc_html($label)
                    );
                }
                break;
        }

        if (!empty($args['desc'])) {
            printf('<p class="description">%s</p>', esc_html($args['desc']));
        }
    }

    private function get_option($key) {
        return isset($this->options[$key]) ? $this->options[$key] : '';
    }

    public function sanitize_options($input) {
        $sanitized = [];

        foreach ($input as $key => $value) {
            switch ($key) {
                case 'default_passing_score':
                case 'time_limit':
                case 'attempts_allowed':
                    $sanitized[$key] = absint($value);
                    break;

                case 'result_display':
                    $sanitized[$key] = sanitize_text_field($value);
                    break;

                case 'grading_system':
                    $sanitized[$key] = wp_kses_post($value);
                    break;

                case 'question_types':
                case 'quiz_features':
                    $sanitized[$key] = array_map('sanitize_text_field', (array) $value);
                    break;
            }
        }

        return $sanitized;
    }
}
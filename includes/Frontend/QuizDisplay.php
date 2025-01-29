<?php
namespace NexusLearn\Frontend;

class QuizDisplay {
    public function __construct() {
        add_shortcode('nl_quiz', [$this, 'render_quiz']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_quiz_scripts']);
        add_action('wp_ajax_nl_submit_quiz', [$this, 'handle_quiz_submission']);
    }

    public function render_quiz($atts) {
        $atts = shortcode_atts([
            'id' => 0
        ], $atts);

        if (!$atts['id']) {
            return '';
        }

        $quiz_id = intval($atts['id']);
        $quiz = get_post($quiz_id);
        
        if (!$quiz || $quiz->post_type !== 'nl_quiz') {
            return '';
        }

        $settings = get_post_meta($quiz_id, '_quiz_settings', true);
        $questions = $this->get_quiz_questions($quiz_id);

        ob_start();
        ?>
        <div class="nl-quiz" data-id="<?php echo $quiz_id; ?>">
            <div class="nl-quiz-header">
                <h2><?php echo esc_html($quiz->post_title); ?></h2>
                <?php if (!empty($settings['time_limit'])): ?>
                    <div class="nl-quiz-timer" data-time="<?php echo intval($settings['time_limit']) * 60; ?>">
                        Time remaining: <span class="nl-timer-display">--:--</span>
                    </div>
                <?php endif; ?>
            </div>

            <form class="nl-quiz-form">
                <?php foreach ($questions as $index => $question): ?>
                    <div class="nl-quiz-question" data-id="<?php echo $question->id; ?>">
                        <div class="nl-question-number"><?php echo $index + 1; ?></div>
                        <div class="nl-question-text"><?php echo wp_kses_post($question->question_text); ?></div>
                        
                        <?php $this->render_question_input($question); ?>
                    </div>
                <?php endforeach; ?>

                <div class="nl-quiz-actions">
                    <button type="submit" class="nl-submit-quiz">
                        <?php _e('Submit Quiz', 'nexuslearn'); ?>
                    </button>
                </div>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }

    private function render_question_input($question) {
        $options = json_decode($question->question_options, true);
        
        switch ($question->question_type) {
            case 'multiple_choice':
                foreach ($options as $key => $option) {
                    ?>
                    <label class="nl-option">
                        <input type="radio" name="q[<?php echo $question->id; ?>]" value="<?php echo $key; ?>">
                        <?php echo esc_html($option); ?>
                    </label>
                    <?php
                }
                break;

            case 'true_false':
                ?>
                <label class="nl-option">
                    <input type="radio" name="q[<?php echo $question->id; ?>]" value="true">
                    <?php _e('True', 'nexuslearn'); ?>
                </label>
                <label class="nl-option">
                    <input type="radio" name="q[<?php echo $question->id; ?>]" value="false">
                    <?php _e('False', 'nexuslearn'); ?>
                </label>
                <?php
                break;

            case 'essay':
                ?>
                <textarea name="q[<?php echo $question->id; ?>]" 
                          class="nl-essay-answer" 
                          rows="5" 
                          placeholder="<?php esc_attr_e('Enter your answer here...', 'nexuslearn'); ?>"></textarea>
                <?php
                break;

            case 'matching':
                $left_options = $options['left'] ?? [];
                $right_options = $options['right'] ?? [];
                ?>
                <div class="nl-matching-container">
                    <div class="nl-matching-left">
                        <?php foreach ($left_options as $key => $option): ?>
                            <div class="nl-matching-item" data-key="<?php echo $key; ?>">
                                <?php echo esc_html($option); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="nl-matching-right">
                        <?php foreach ($right_options as $key => $option): ?>
                            <select name="q[<?php echo $question->id; ?>][<?php echo $key; ?>]">
                                <option value=""><?php _e('Select match...', 'nexuslearn'); ?></option>
                                <?php foreach ($left_options as $left_key => $left_option): ?>
                                    <option value="<?php echo $left_key; ?>">
                                        <?php echo esc_html($left_option); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="nl-matching-text"><?php echo esc_html($option); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php
                break;

            case 'fill_blanks':
                $text = $question->question_text;
                $text = preg_replace_callback('/\[blank\]/', function($matches) use ($question) {
                    static $i = 0;
                    return '<input type="text" name="q[' . $question->id . '][' . $i++ . ']" class="nl-blank-input">';
                }, $text);
                echo $text;
                break;
        }
    }

    private function get_quiz_questions($quiz_id) {
        global $wpdb;
        $questions = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}nl_quiz_questions 
            WHERE quiz_id = %d 
            ORDER BY order_index",
            $quiz_id
        ));

        // Randomize if setting is enabled
        $settings = get_post_meta($quiz_id, '_quiz_settings', true);
        if (!empty($settings['randomize_questions'])) {
            shuffle($questions);
        }

        return $questions;
    }

    public function enqueue_quiz_scripts() {
        if (has_shortcode(get_post()->post_content, 'nl_quiz')) {
            wp_enqueue_style('nl-quiz', NEXUSLEARN_PLUGIN_URL . 'assets/css/quiz.css');
            wp_enqueue_script('nl-quiz', NEXUSLEARN_PLUGIN_URL . 'assets/js/quiz.js', ['jquery'], false, true);
            wp_localize_script('nl-quiz', 'nlQuiz', [
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('nl_quiz_submit')
            ]);
        }
    }

    public function handle_quiz_submission() {
        check_ajax_referer('nl_quiz_submit', 'nonce');

        $quiz_id = intval($_POST['quiz_id']);
        $answers = $_POST['answers'];
        $user_id = get_current_user_id();

        // Create attempt record
        global $wpdb;
        $wpdb->insert(
            $wpdb->prefix . 'nl_quiz_attempts',
            [
                'quiz_id' => $quiz_id,
                'user_id' => $user_id,
                'start_time' => $_POST['start_time'],
                'end_time' => current_time('mysql'),
                'status' => 'completed'
            ]
        );
        $attempt_id = $wpdb->insert_id;

        // Process each answer
        $total_points = 0;
        $earned_points = 0;

        foreach ($answers as $question_id => $answer) {
            $question = $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}nl_quiz_questions WHERE id = %d",
                $question_id
            ));

            $is_correct = $this->check_answer($question, $answer);
            $points = $is_correct ? $question->points : 0;
            $total_points += $question->points;
            $earned_points += $points;

            // Save answer
            $wpdb->insert(
                $wpdb->prefix . 'nl_quiz_answers',
                [
                    'attempt_id' => $attempt_id,
                    'question_id' => $question_id,
                    'answer_text' => is_array($answer) ? json_encode($answer) : $answer,
                    'is_correct' => $is_correct,
                    'points_earned' => $points
                ]
            );
        }

        // Update attempt with score
        $score_percentage = ($earned_points / $total_points) * 100;
        $wpdb->update(
            $wpdb->prefix . 'nl_quiz_attempts',
            [
                'score' => $score_percentage,
                'max_score' => $total_points
            ],
            ['id' => $attempt_id]
        );

        // Return results
        wp_send_json_success([
            'score' => round($score_percentage, 2),
            'points_earned' => $earned_points,
            'total_points' => $total_points,
            'redirect_url' => get_permalink(get_post_meta($quiz_id, '_quiz_results_page', true))
        ]);
    }

    private function check_answer($question, $answer) {
        switch ($question->question_type) {
            case 'multiple_choice':
            case 'true_false':
                return $answer === $question->correct_answer;

            case 'essay':
                // Essay questions need manual grading
                return null;

            case 'matching':
                $correct_matches = json_decode($question->correct_answer, true);
                foreach ($answer as $key => $value) {
                    if ($correct_matches[$key] !== $value) {
                        return false;
                    }
                }
                return true;

            case 'fill_blanks':
                $correct_answers = json_decode($question->correct_answer, true);
                foreach ($answer as $key => $value) {
                    if (strtolower(trim($value)) !== strtolower(trim($correct_answers[$key]))) {
                        return false;
                    }
                }
                return true;

            default:
                return false;
        }
    }
}
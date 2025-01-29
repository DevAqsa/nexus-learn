<?php
namespace NexusLearn\Frontend;

class QuizDisplay {
    public function __construct() {
        add_shortcode('nl_quiz', [$this, 'render_quiz']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_quiz_scripts']);
        add_action('wp_ajax_nl_submit_quiz', [$this, 'handle_quiz_submission']);
    }

    public function enqueue_quiz_scripts() {
        global $post;
        
        // Only enqueue if we're on a post/page and it contains our shortcode
        if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'nl_quiz')) {
            wp_enqueue_style('nl-quiz', NEXUSLEARN_PLUGIN_URL . 'assets/css/quiz.css');
            wp_enqueue_script('nl-quiz', NEXUSLEARN_PLUGIN_URL . 'assets/js/quiz.js', ['jquery'], false, true);
            wp_localize_script('nl-quiz', 'nlQuiz', [
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('nl_quiz_submit')
            ]);
        }
    }

    public function render_quiz($atts) {
        $atts = shortcode_atts([
            'id' => 0
        ], $atts);

        if (!$atts['id']) {
            return __('Quiz ID is required.', 'nexuslearn');
        }

        $quiz_id = intval($atts['id']);
        $quiz = get_post($quiz_id);
        
        if (!$quiz || $quiz->post_type !== 'nl_quiz') {
            return __('Invalid quiz ID.', 'nexuslearn');
        }

        // Check if user is logged in
        if (!is_user_logged_in()) {
            return __('Please log in to take this quiz.', 'nexuslearn');
        }

        $settings = get_post_meta($quiz_id, '_quiz_settings', true) ?: [];
        $questions = $this->get_quiz_questions($quiz_id);

        if (empty($questions)) {
            return __('No questions found in this quiz.', 'nexuslearn');
        }

        ob_start();
        ?>
        <div class="nl-quiz" data-id="<?php echo $quiz_id; ?>">
            <div class="nl-quiz-header">
                <h2><?php echo esc_html($quiz->post_title); ?></h2>
                <?php if (!empty($settings['time_limit'])): ?>
                    <div class="nl-quiz-timer" data-time="<?php echo intval($settings['time_limit']) * 60; ?>">
                        <span class="nl-timer-label"><?php _e('Time Remaining:', 'nexuslearn'); ?></span>
                        <span class="nl-timer-display">--:--</span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($quiz->post_content)): ?>
                    <div class="nl-quiz-description">
                        <?php echo wp_kses_post($quiz->post_content); ?>
                    </div>
                <?php endif; ?>
            </div>

            <form class="nl-quiz-form" method="post">
                <?php 
                $question_number = 1;
                foreach ($questions as $question): 
                ?>
                    <div class="nl-quiz-question" data-id="<?php echo $question->id; ?>">
                        <div class="nl-question-number"><?php printf(__('Question %d', 'nexuslearn'), $question_number); ?></div>
                        <div class="nl-question-text"><?php echo wp_kses_post($question->question_text); ?></div>
                        
                        <?php $this->render_question_input($question); ?>
                    </div>
                <?php 
                    $question_number++;
                endforeach; 
                ?>

                <div class="nl-quiz-actions">
                    <?php wp_nonce_field('nl_quiz_submit', 'quiz_nonce'); ?>
                    <button type="submit" class="nl-submit-quiz">
                        <?php _e('Submit Quiz', 'nexuslearn'); ?>
                    </button>
                </div>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }

    private function get_quiz_questions($quiz_id) {
        global $wpdb;
        $questions = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}nl_quiz_questions 
            WHERE quiz_id = %d 
            ORDER BY order_index",
            $quiz_id
        ));

        $settings = get_post_meta($quiz_id, '_quiz_settings', true);
        if (!empty($settings['randomize_questions'])) {
            shuffle($questions);
        }

        return $questions;
    }

    private function render_question_input($question) {
        $options = json_decode($question->question_options, true) ?: [];
        
        switch ($question->question_type) {
            case 'multiple_choice':
                $this->render_multiple_choice($question, $options);
                break;
            case 'true_false':
                $this->render_true_false($question);
                break;
            case 'essay':
                $this->render_essay($question);
                break;
            case 'matching':
                $this->render_matching($question, $options);
                break;
            case 'fill_blanks':
                $this->render_fill_blanks($question);
                break;
        }
    }

    private function render_multiple_choice($question, $options) {
        if (!empty($options)): ?>
            <div class="nl-answer-options">
                <?php foreach ($options as $key => $option): ?>
                    <label class="nl-option">
                        <input type="radio" 
                               name="answers[<?php echo $question->id; ?>]" 
                               value="<?php echo $key; ?>"
                               required>
                        <span class="nl-option-text"><?php echo esc_html($option); ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        <?php endif;
    }

    private function render_true_false($question) {
        ?>
        <div class="nl-answer-options">
            <label class="nl-option">
                <input type="radio" name="answers[<?php echo $question->id; ?>]" value="true" required>
                <span class="nl-option-text"><?php _e('True', 'nexuslearn'); ?></span>
            </label>
            <label class="nl-option">
                <input type="radio" name="answers[<?php echo $question->id; ?>]" value="false" required>
                <span class="nl-option-text"><?php _e('False', 'nexuslearn'); ?></span>
            </label>
        </div>
        <?php
    }

    private function render_essay($question) {
        ?>
        <div class="nl-answer-essay">
            <textarea name="answers[<?php echo $question->id; ?>]" 
                      rows="5" 
                      placeholder="<?php esc_attr_e('Enter your answer here...', 'nexuslearn'); ?>"
                      required></textarea>
        </div>
        <?php
    }

    public function handle_quiz_submission() {
        check_ajax_referer('nl_quiz_submit', 'nonce');

        if (!is_user_logged_in()) {
            wp_send_json_error(['message' => __('You must be logged in to submit a quiz.', 'nexuslearn')]);
        }

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

        // Process answers and calculate score
        $total_points = 0;
        $earned_points = 0;

        foreach ($answers as $question_id => $answer) {
            // Get question details
            $question = $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}nl_quiz_questions WHERE id = %d",
                $question_id
            ));

            if ($question) {
                $total_points += $question->points;
                $is_correct = $this->check_answer($question, $answer);
                
                if ($is_correct) {
                    $earned_points += $question->points;
                }

                // Save answer
                $wpdb->insert(
                    $wpdb->prefix . 'nl_quiz_answers',
                    [
                        'attempt_id' => $attempt_id,
                        'question_id' => $question_id,
                        'answer_text' => is_array($answer) ? json_encode($answer) : $answer,
                        'is_correct' => $is_correct ? 1 : 0,
                        'points_earned' => $is_correct ? $question->points : 0
                    ]
                );
            }
        }

        // Calculate final score
        $score = $total_points > 0 ? ($earned_points / $total_points) * 100 : 0;

        // Update attempt with score
        $wpdb->update(
            $wpdb->prefix . 'nl_quiz_attempts',
            ['score' => $score],
            ['id' => $attempt_id]
        );

        // Return results
        wp_send_json_success([
            'score' => round($score, 2),
            'points_earned' => $earned_points,
            'total_points' => $total_points
        ]);
    }

    private function check_answer($question, $answer) {
        $options = json_decode($question->question_options, true) ?: [];
        
        switch ($question->question_type) {
            case 'multiple_choice':
                return isset($options['correct']) && $answer === $options['correct'];
                
            case 'true_false':
                return $answer === $options['correct_answer'];
                
            case 'essay':
                // Essay questions need manual grading
                return null;
                
            default:
                return false;
        }
    }
}
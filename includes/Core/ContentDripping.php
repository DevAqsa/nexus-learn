<?php
namespace NexusLearn\Core;

class ContentDripping {
    private $db;

    public function __construct() {
        global $wpdb;
        $this->db = $wpdb;
        
        // Add dripping rules meta box
        add_action('add_meta_boxes', [$this, 'add_dripping_meta_box']);
        add_action('save_post_nl_course', [$this, 'save_dripping_rules']);
        
        // Filter content based on dripping rules
        add_filter('the_content', [$this, 'filter_lesson_content'], 20);
    }

    /**
     * Create dripping rules table
     */
    public function create_tables() {
        $charset_collate = $this->db->get_charset_collate();
        
        $sql = "CREATE TABLE IF NOT EXISTS {$this->db->prefix}nexuslearn_dripping_rules (
            rule_id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            lesson_id BIGINT(20) UNSIGNED NOT NULL,
            drip_type VARCHAR(50) NOT NULL,
            drip_value TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (rule_id),
            KEY lesson_id (lesson_id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    /**
     * Add meta box for dripping rules
     */
    public function add_dripping_meta_box() {
        add_meta_box(
            'nl_dripping_rules',
            __('Content Dripping Rules', 'nexuslearn'),
            [$this, 'render_dripping_meta_box'],
            'nl_course'
        );
    }

    /**
     * Render dripping rules meta box
     */
    public function render_dripping_meta_box($post) {
        wp_nonce_field('nl_dripping_rules', 'nl_dripping_nonce');
        $rules = $this->get_dripping_rules($post->ID);
        ?>
        <div class="nl-dripping-rules">
            <p>
                <label><?php _e('Dripping Type:', 'nexuslearn'); ?></label>
                <select name="nl_drip_type">
                    <option value="date" <?php selected($rules['type'] ?? '', 'date'); ?>>
                        <?php _e('Specific Date', 'nexuslearn'); ?>
                    </option>
                    <option value="interval" <?php selected($rules['type'] ?? '', 'interval'); ?>>
                        <?php _e('Days After Enrollment', 'nexuslearn'); ?>
                    </option>
                    <option value="prerequisite" <?php selected($rules['type'] ?? '', 'prerequisite'); ?>>
                        <?php _e('Previous Lesson Completion', 'nexuslearn'); ?>
                    </option>
                </select>
            </p>
            <div class="nl-drip-value-fields">
                <div class="nl-date-field" style="display: none;">
                    <input type="date" name="nl_drip_date" value="<?php echo esc_attr($rules['value'] ?? ''); ?>">
                </div>
                <div class="nl-interval-field" style="display: none;">
                    <input type="number" name="nl_drip_days" value="<?php echo esc_attr($rules['value'] ?? ''); ?>">
                    <?php _e('days', 'nexuslearn'); ?>
                </div>
                <div class="nl-prerequisite-field" style="display: none;">
                    <?php
                    $lessons = get_posts([
                        'post_type' => 'nl_course',
                        'posts_per_page' => -1,
                        'post__not_in' => [$post->ID]
                    ]);
                    if ($lessons): ?>
                        <select name="nl_drip_prerequisite">
                            <?php foreach ($lessons as $lesson): ?>
                                <option value="<?php echo $lesson->ID; ?>" <?php selected($rules['value'] ?? '', $lesson->ID); ?>>
                                    <?php echo esc_html($lesson->post_title); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <script>
        jQuery(document).ready(function($) {
            function showDripField() {
                var type = $('select[name="nl_drip_type"]').val();
                $('.nl-drip-value-fields > div').hide();
                $('.nl-' + type + '-field').show();
            }
            $('select[name="nl_drip_type"]').on('change', showDripField);
            showDripField();
        });
        </script>
        <?php
    }

    /**
     * Save dripping rules
     */
    public function save_dripping_rules($post_id) {
        if (!isset($_POST['nl_dripping_nonce']) || 
            !wp_verify_nonce($_POST['nl_dripping_nonce'], 'nl_dripping_rules')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        $type = sanitize_text_field($_POST['nl_drip_type'] ?? '');
        $value = '';
        
        switch ($type) {
            case 'date':
                $value = sanitize_text_field($_POST['nl_drip_date'] ?? '');
                break;
            case 'interval':
                $value = absint($_POST['nl_drip_days'] ?? 0);
                break;
            case 'prerequisite':
                $value = absint($_POST['nl_drip_prerequisite'] ?? 0);
                break;
        }

        $this->update_dripping_rule($post_id, $type, $value);
    }

    /**
     * Update dripping rule in database
     */
    private function update_dripping_rule($lesson_id, $type, $value) {
        $this->db->replace(
            $this->db->prefix . 'nexuslearn_dripping_rules',
            [
                'lesson_id' => $lesson_id,
                'drip_type' => $type,
                'drip_value' => $value
            ],
            ['%d', '%s', '%s']
        );
    }

    /**
     * Get dripping rules for a lesson
     */
    public function get_dripping_rules($lesson_id) {
        $rule = $this->db->get_row(
            $this->db->prepare(
                "SELECT drip_type, drip_value FROM {$this->db->prefix}nexuslearn_dripping_rules 
                WHERE lesson_id = %d",
                $lesson_id
            ),
            ARRAY_A
        );

        return $rule ? [
            'type' => $rule['drip_type'],
            'value' => $rule['drip_value']
        ] : [];
    }

    /**
     * Check if content should be available
     */
    public function is_content_available($lesson_id, $user_id = null) {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }

        $rules = $this->get_dripping_rules($lesson_id);
        if (empty($rules)) {
            return true;
        }

        switch ($rules['type']) {
            case 'date':
                return strtotime($rules['value']) <= current_time('timestamp');

            case 'interval':
                $enrollment_date = $this->get_enrollment_date($user_id, get_post($lesson_id)->post_parent);
                if (!$enrollment_date) {
                    return false;
                }
                $unlock_date = strtotime("+{$rules['value']} days", strtotime($enrollment_date));
                return current_time('timestamp') >= $unlock_date;

            case 'prerequisite':
                return $this->is_lesson_completed($rules['value'], $user_id);

            default:
                return true;
        }
    }

    /**
     * Filter lesson content based on dripping rules
     */
    public function filter_lesson_content($content) {
        if (!is_singular('nl_course')) {
            return $content;
        }

        $lesson_id = get_the_ID();
        if ($this->is_content_available($lesson_id)) {
            return $content;
        }

        $rules = $this->get_dripping_rules($lesson_id);
        $message = $this->get_locked_message($rules);
        
        return '<div class="nl-locked-content">' . $message . '</div>';
    }

    /**
     * Get appropriate locked message based on rule type
     */
    private function get_locked_message($rules) {
        switch ($rules['type']) {
            case 'date':
                $date = wp_date(get_option('date_format'), strtotime($rules['value']));
                return sprintf(
                    __('This content will be available on %s', 'nexuslearn'),
                    $date
                );

            case 'interval':
                return sprintf(
                    __('This content will be available %d days after enrollment', 'nexuslearn'),
                    $rules['value']
                );

            case 'prerequisite':
                $lesson = get_post($rules['value']);
                return sprintf(
                    __('Complete "%s" to unlock this content', 'nexuslearn'),
                    $lesson->post_title
                );

            default:
                return __('This content is not yet available', 'nexuslearn');
        }
    }

    /**
     * Get user's enrollment date for a course
     */
    private function get_enrollment_date($user_id, $course_id) {
        return $this->db->get_var($this->db->prepare(
            "SELECT created_at FROM {$this->db->prefix}nexuslearn_progress 
            WHERE user_id = %d AND course_id = %d 
            ORDER BY created_at ASC LIMIT 1",
            $user_id,
            $course_id
        ));
    }

    /**
     * Check if a lesson is completed
     */
    private function is_lesson_completed($lesson_id, $user_id) {
        return $this->db->get_var($this->db->prepare(
            "SELECT COUNT(*) FROM {$this->db->prefix}nexuslearn_progress 
            WHERE user_id = %d AND lesson_id = %d AND status = 'completed'",
            $user_id,
            $lesson_id
        )) > 0;
    }
}
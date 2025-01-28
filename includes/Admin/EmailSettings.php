<?php
namespace NexusLearn\Admin;

class EmailSettings {
    private $option_name = 'nexuslearn_email_options';
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
            'nl_email_settings',
            __('Email Settings', 'nexuslearn'),
            [$this, 'render_section_description'],
            'nexuslearn-email-settings'
        );

        $this->add_settings_fields();
    }

    public function add_settings_fields() {
        $fields = [
            'sender_name' => [
                'title' => __('Sender Name', 'nexuslearn'),
                'type' => 'text',
                'desc' => __('Name that appears in the from field', 'nexuslearn')
            ],
            'sender_email' => [
                'title' => __('Sender Email', 'nexuslearn'),
                'type' => 'email',
                'desc' => __('Email address that appears in the from field', 'nexuslearn')
            ],
            'email_template' => [
                'title' => __('Email Template', 'nexuslearn'),
                'type' => 'textarea',
                'desc' => __('Default template for notification emails. Use {content} for email body.', 'nexuslearn')
            ],
            'notification_types' => [
                'title' => __('Enable Notifications', 'nexuslearn'),
                'type' => 'multicheck',
                'options' => [
                    'course_enrollment' => __('Course Enrollment', 'nexuslearn'),
                    'course_completion' => __('Course Completion', 'nexuslearn'),
                    'quiz_completion' => __('Quiz Completion', 'nexuslearn'),
                    'certificate_issued' => __('Certificate Issued', 'nexuslearn')
                ],
                'desc' => __('Select which notifications to send', 'nexuslearn')
            ]
        ];

        foreach ($fields as $field_id => $field) {
            add_settings_field(
                $field_id,
                $field['title'],
                [$this, 'render_field'],
                'nexuslearn-email-settings',
                'nl_email_settings',
                [
                    'id' => $field_id,
                    'type' => $field['type'],
                    'desc' => $field['desc'],
                    'options' => $field['options'] ?? []
                ]
            );
        }
    }

    public function render_section_description() {
        echo '<p>' . __('Configure email notification settings for your learning platform.', 'nexuslearn') . '</p>';
    }

    public function render_field($args) {
        $id = $args['id'];
        $type = $args['type'];
        $value = $this->get_option($id);
        
        switch ($type) {
            case 'text':
            case 'email':
                printf(
                    '<input type="%4$s" id="%1$s" name="%2$s[%1$s]" value="%3$s" class="regular-text">',
                    esc_attr($id),
                    esc_attr($this->option_name),
                    esc_attr($value),
                    esc_attr($type)
                );
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
                        '<label><input type="checkbox" name="%1$s[%2$s][]" value="%3$s" %4$s> %5$s</label><br>',
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
                case 'sender_name':
                    $sanitized[$key] = sanitize_text_field($value);
                    break;
                case 'sender_email':
                    $sanitized[$key] = sanitize_email($value);
                    break;
                case 'email_template':
                    $sanitized[$key] = wp_kses_post($value);
                    break;
                case 'notification_types':
                    $sanitized[$key] = array_map('sanitize_text_field', (array) $value);
                    break;
            }
        }

        return $sanitized;
    }
}
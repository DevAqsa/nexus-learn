<?php
namespace NexusLearn\Admin;

class GeneralSettings {
    private $option_name = 'nexuslearn_general_options';
    private $options;

    public function __construct() {
        add_action('admin_init', [$this, 'init_settings']);
        $this->options = get_option($this->option_name, []);
    }

    public function init_settings() {
        // Register setting
        register_setting(
            'nexuslearn_options',
            $this->option_name,
            [$this, 'sanitize_options']
        );

        // Add settings section
        add_settings_section(
            'nl_general_settings',
            __('General Settings', 'nexuslearn'),
            [$this, 'render_section_description'],
            'nexuslearn-settings'
        );

        // Add settings fields
        $this->add_settings_fields();
    }

    public function add_settings_fields() {
        $fields = [
            'platform_name' => [
                'title' => __('Platform Name', 'nexuslearn'),
                'type' => 'text',
                'desc' => __('The name of your learning platform', 'nexuslearn')
            ],
            'platform_description' => [
                'title' => __('Platform Description', 'nexuslearn'),
                'type' => 'textarea',
                'desc' => __('A brief description of your learning platform', 'nexuslearn')
            ],
            'user_registration' => [
                'title' => __('User Registration', 'nexuslearn'),
                'type' => 'checkbox',
                'desc' => __('Allow new users to register', 'nexuslearn')
            ],
            'currency' => [
                'title' => __('Currency', 'nexuslearn'),
                'type' => 'select',
                'options' => [
                    'USD' => __('US Dollar ($)', 'nexuslearn'),
                    'EUR' => __('Euro (€)', 'nexuslearn'),
                    'GBP' => __('British Pound (£)', 'nexuslearn'),
                    'PKR' => __('Pakistani Rupee (₨)', 'nexuslearn')
                ],
                'desc' => __('Select the currency for course pricing', 'nexuslearn')
            ],
            'date_format' => [
                'title' => __('Date Format', 'nexuslearn'),
                'type' => 'select',
                'options' => [
                    'Y-m-d' => date('Y-m-d'),
                    'm/d/Y' => date('m/d/Y'),
                    'd/m/Y' => date('d/m/Y'),
                    'F j, Y' => date('F j, Y')
                ],
                'desc' => __('Select how dates should be displayed', 'nexuslearn')
            ]
        ];

        foreach ($fields as $field_id => $field) {
            add_settings_field(
                $field_id,
                $field['title'],
                [$this, 'render_field'],
                'nexuslearn-settings',
                'nl_general_settings',
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
        echo '<p>' . __('Configure the general settings for your learning platform.', 'nexuslearn') . '</p>';
    }

    public function render_field($args) {
        $id = $args['id'];
        $type = $args['type'];
        $value = $this->get_option($id);
        
        switch ($type) {
            case 'text':
                printf(
                    '<input type="text" id="%1$s" name="%2$s[%1$s]" value="%3$s" class="regular-text">',
                    esc_attr($id),
                    esc_attr($this->option_name),
                    esc_attr($value)
                );
                break;

            case 'textarea':
                printf(
                    '<textarea id="%1$s" name="%2$s[%1$s]" rows="4" class="large-text">%3$s</textarea>',
                    esc_attr($id),
                    esc_attr($this->option_name),
                    esc_textarea($value)
                );
                break;

            case 'checkbox':
                printf(
                    '<input type="checkbox" id="%1$s" name="%2$s[%1$s]" value="1" %3$s>',
                    esc_attr($id),
                    esc_attr($this->option_name),
                    checked(1, $value, false)
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
                case 'platform_name':
                case 'currency':
                case 'date_format':
                    $sanitized[$key] = sanitize_text_field($value);
                    break;

                case 'platform_description':
                    $sanitized[$key] = wp_kses_post($value);
                    break;

                case 'user_registration':
                    $sanitized[$key] = (bool) $value;
                    break;
            }
        }

        return $sanitized;
    }
}
<?php
namespace NexusLearn\Admin;

class CourseSettings {
    private $option_name = 'nexuslearn_course_options';
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
            'nl_course_settings',
            __('Course Settings', 'nexuslearn'),
            [$this, 'render_section_description'],
            'nexuslearn-course-settings'
        );

        $this->add_settings_fields();
    }

    public function add_settings_fields() {
        $fields = [
            'enable_prerequisites' => [
                'title' => __('Course Prerequisites', 'nexuslearn'),
                'type' => 'checkbox',
                'desc' => __('Enable course prerequisites feature', 'nexuslearn')
            ],
            'enable_drip_content' => [
                'title' => __('Content Dripping', 'nexuslearn'),
                'type' => 'checkbox',
                'desc' => __('Enable content dripping feature', 'nexuslearn')
            ],
            'course_display' => [
                'title' => __('Course Display', 'nexuslearn'),
                'type' => 'select',
                'options' => [
                    'list' => __('List View', 'nexuslearn'),
                    'grid' => __('Grid View', 'nexuslearn'),
                    'masonry' => __('Masonry Grid', 'nexuslearn')
                ],
                'desc' => __('Choose how courses are displayed on archive pages', 'nexuslearn')
            ],
            'courses_per_page' => [
                'title' => __('Courses Per Page', 'nexuslearn'),
                'type' => 'number',
                'desc' => __('Number of courses to display per page', 'nexuslearn'),
                'min' => 1,
                'max' => 100
            ],
            'student_capacity' => [
                'title' => __('Default Student Capacity', 'nexuslearn'),
                'type' => 'number',
                'desc' => __('Default maximum number of students per course (0 for unlimited)', 'nexuslearn'),
                'min' => 0
            ],
            'course_permalink' => [
                'title' => __('Course Permalink Base', 'nexuslearn'),
                'type' => 'text',
                'desc' => __('Base slug for course URLs (e.g., "courses")', 'nexuslearn')
            ],
            'allowed_content_types' => [
                'title' => __('Allowed Content Types', 'nexuslearn'),
                'type' => 'multicheck',
                'options' => [
                    'text' => __('Text', 'nexuslearn'),
                    'video' => __('Video', 'nexuslearn'),
                    'audio' => __('Audio', 'nexuslearn'),
                    'pdf' => __('PDF', 'nexuslearn'),
                    'presentation' => __('Presentation', 'nexuslearn')
                ],
                'desc' => __('Select allowed content types for lessons', 'nexuslearn')
            ],
            'instructor_capabilities' => [
                'title' => __('Instructor Capabilities', 'nexuslearn'),
                'type' => 'multicheck',
                'options' => [
                    'create_courses' => __('Create Courses', 'nexuslearn'),
                    'edit_courses' => __('Edit Own Courses', 'nexuslearn'),
                    'manage_students' => __('Manage Students', 'nexuslearn'),
                    'grade_assignments' => __('Grade Assignments', 'nexuslearn'),
                    'view_reports' => __('View Reports', 'nexuslearn')
                ],
                'desc' => __('Select what instructors can do', 'nexuslearn')
            ]
        ];

        foreach ($fields as $field_id => $field) {
            add_settings_field(
                $field_id,
                $field['title'],
                [$this, 'render_field'],
                'nexuslearn-course-settings',
                'nl_course_settings',
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
        echo '<p>' . __('Configure course-related settings for your learning platform.', 'nexuslearn') . '</p>';
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
                case 'enable_prerequisites':
                case 'enable_drip_content':
                    $sanitized[$key] = (bool) $value;
                    break;

                case 'course_display':
                case 'course_permalink':
                    $sanitized[$key] = sanitize_text_field($value);
                    break;

                case 'courses_per_page':
                case 'student_capacity':
                    $sanitized[$key] = absint($value);
                    break;

                case 'allowed_content_types':
                case 'instructor_capabilities':
                    $sanitized[$key] = array_map('sanitize_text_field', (array) $value);
                    break;
            }
        }

        return $sanitized;
    }
}
<?php
namespace NexusLearn\Admin;

class CertificateSettings {
    private $option_name = 'nexuslearn_certificate_options';
    private $options;

    public function __construct() {
        add_action('admin_init', [$this, 'init_settings']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
        $this->options = get_option($this->option_name, []);
    }

    public function init_settings() {
        register_setting(
            'nexuslearn_options',
            $this->option_name,
            [$this, 'sanitize_options']
        );

        add_settings_section(
            'nl_certificate_settings',
            __('Certificate Settings', 'nexuslearn'),
            [$this, 'render_section_description'],
            'nexuslearn-certificate-settings'
        );

        $this->add_settings_fields();
    }

    public function enqueue_scripts($hook) {
        if ($hook !== 'nexuslearn_page_nexuslearn-settings') {
            return;
        }

        wp_enqueue_media();
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        
        // Add custom JS for color picker and media uploader
        wp_add_inline_script('wp-color-picker', '
            jQuery(document).ready(function($) {
                $(".color-picker").wpColorPicker();
                
                $(".upload-image-button").click(function(e) {
                    e.preventDefault();
                    var button = $(this);
                    var customUploader = wp.media({
                        title: "Choose Image",
                        button: {
                            text: "Use this image"
                        },
                        multiple: false
                    }).on("select", function() {
                        var attachment = customUploader.state().get("selection").first().toJSON();
                        button.siblings(".image-preview").attr("src", attachment.url);
                        button.siblings(".image-url").val(attachment.url);
                    }).open();
                });
            });
        ');
    }

    public function render_section_description() {
        echo '<p>' . __('Configure certificate settings and appearance.', 'nexuslearn') . '</p>';
    }

    public function add_settings_fields() {
        $fields = [
            'enable_certificates' => [
                'title' => __('Enable Certificates', 'nexuslearn'),
                'type' => 'checkbox',
                'desc' => __('Enable automatic certificate generation', 'nexuslearn')
            ],
            'certificate_logo' => [
                'title' => __('Certificate Logo', 'nexuslearn'),
                'type' => 'image',
                'desc' => __('Upload logo to display on certificates', 'nexuslearn')
            ],
            'certificate_title' => [
                'title' => __('Certificate Title', 'nexuslearn'),
                'type' => 'text',
                'desc' => __('Default title for certificates', 'nexuslearn')
            ],
            'certificate_text' => [
                'title' => __('Certificate Text', 'nexuslearn'),
                'type' => 'textarea',
                'desc' => __('Use {student}, {course}, {date} as placeholders', 'nexuslearn')
            ],
            'signature_image' => [
                'title' => __('Signature Image', 'nexuslearn'),
                'type' => 'image',
                'desc' => __('Upload signature image for certificates', 'nexuslearn')
            ],
            'title_font' => [
                'title' => __('Title Font', 'nexuslearn'),
                'type' => 'select',
                'options' => [
                    'Arial' => 'Arial',
                    'Times New Roman' => 'Times New Roman',
                    'Helvetica' => 'Helvetica',
                    'Georgia' => 'Georgia'
                ],
                'desc' => __('Font for certificate title', 'nexuslearn')
            ],
            'title_color' => [
                'title' => __('Title Color', 'nexuslearn'),
                'type' => 'color',
                'desc' => __('Color for certificate title', 'nexuslearn')
            ],
            'border_style' => [
                'title' => __('Border Style', 'nexuslearn'),
                'type' => 'select',
                'options' => [
                    'none' => __('None', 'nexuslearn'),
                    'simple' => __('Simple', 'nexuslearn'),
                    'ornate' => __('Ornate', 'nexuslearn')
                ],
                'desc' => __('Certificate border style', 'nexuslearn')
            ],
            'paper_size' => [
                'title' => __('Paper Size', 'nexuslearn'),
                'type' => 'select',
                'options' => [
                    'A4' => 'A4',
                    'letter' => 'Letter',
                    'legal' => 'Legal'
                ],
                'desc' => __('Certificate paper size', 'nexuslearn')
            ]
        ];

        foreach ($fields as $field_id => $field) {
            add_settings_field(
                $field_id,
                $field['title'],
                [$this, 'render_field'],
                'nexuslearn-certificate-settings',
                'nl_certificate_settings',
                [
                    'id' => $field_id,
                    'type' => $field['type'],
                    'desc' => $field['desc'],
                    'options' => $field['options'] ?? []
                ]
            );
        }
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

            case 'textarea':
                printf(
                    '<textarea id="%1$s" name="%2$s[%1$s]" rows="5" class="large-text">%3$s</textarea>',
                    esc_attr($id),
                    esc_attr($this->option_name),
                    esc_textarea($value)
                );
                break;

            case 'color':
                printf(
                    '<input type="text" id="%1$s" name="%2$s[%1$s]" value="%3$s" class="color-picker">',
                    esc_attr($id),
                    esc_attr($this->option_name),
                    esc_attr($value)
                );
                break;

            case 'image':
                ?>
                <div class="image-upload-field">
                    <input type="hidden" id="<?php echo esc_attr($id); ?>" 
                           name="<?php echo esc_attr($this->option_name) . '[' . esc_attr($id) . ']'; ?>" 
                           value="<?php echo esc_attr($value); ?>" 
                           class="image-url">
                    
                    <img src="<?php echo esc_url($value); ?>" 
                         class="image-preview" 
                         style="max-width: 200px; <?php echo empty($value) ? 'display: none;' : ''; ?>">
                    
                    <button class="button upload-image-button">
                        <?php _e('Upload Image', 'nexuslearn'); ?>
                    </button>
                    
                    <?php if (!empty($value)): ?>
                        <button class="button remove-image-button">
                            <?php _e('Remove Image', 'nexuslearn'); ?>
                        </button>
                    <?php endif; ?>
                </div>
                <?php
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
                case 'enable_certificates':
                    $sanitized[$key] = (bool) $value;
                    break;

                case 'certificate_title':
                case 'title_font':
                case 'title_color':
                case 'border_style':
                case 'paper_size':
                    $sanitized[$key] = sanitize_text_field($value);
                    break;

                case 'certificate_text':
                    $sanitized[$key] = wp_kses_post($value);
                    break;

                case 'certificate_logo':
                case 'signature_image':
                    $sanitized[$key] = esc_url_raw($value);
                    break;
            }
        }

        return $sanitized;
    }
}
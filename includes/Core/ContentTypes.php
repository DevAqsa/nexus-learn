<?php
namespace NexusLearn\Core;

class ContentTypes {
    private $allowed_types = [
        'text' => ['post_content' => true],
        'video' => ['mp4', 'webm', 'ogg'],
        'audio' => ['mp3', 'wav', 'ogg'],
        'pdf' => ['pdf'],
        'embed' => ['url' => true]
    ];

    public function __construct() {
        // Add content type meta box
        add_action('add_meta_boxes', [$this, 'add_content_type_meta_box']);
        add_action('save_post_nl_course', [$this, 'save_content_type_meta']);
        
        // Add media upload support
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
        
        // Filter content display
        add_filter('the_content', [$this, 'filter_content_display'], 20);
        
        // Add shortcode for content embedding
        add_shortcode('nl_content', [$this, 'content_shortcode']);
        
        // Handle file uploads
        add_action('wp_ajax_nl_upload_content', [$this, 'handle_upload']);
    }

    /**
     * Add meta box for content type selection
     */
    public function add_content_type_meta_box() {
        add_meta_box(
            'nl_content_type',
            __('Content Type', 'nexuslearn'),
            [$this, 'render_content_type_meta_box'],
            'nl_course'
        );
    }

    /**
     * Render content type meta box
     */
    public function render_content_type_meta_box($post) {
        wp_nonce_field('nl_content_type', 'nl_content_type_nonce');
        
        $content_type = get_post_meta($post->ID, '_nl_content_type', true);
        $content_url = get_post_meta($post->ID, '_nl_content_url', true);
        $embed_code = get_post_meta($post->ID, '_nl_embed_code', true);
        ?>
        <div class="nl-content-type-selector">
            <p>
                <label><?php _e('Content Type:', 'nexuslearn'); ?></label>
                <select name="nl_content_type" id="nl_content_type">
                    <option value="text" <?php selected($content_type, 'text'); ?>>
                        <?php _e('Text Content', 'nexuslearn'); ?>
                    </option>
                    <option value="video" <?php selected($content_type, 'video'); ?>>
                        <?php _e('Video', 'nexuslearn'); ?>
                    </option>
                    <option value="audio" <?php selected($content_type, 'audio'); ?>>
                        <?php _e('Audio', 'nexuslearn'); ?>
                    </option>
                    <option value="pdf" <?php selected($content_type, 'pdf'); ?>>
                        <?php _e('PDF Document', 'nexuslearn'); ?>
                    </option>
                    <option value="embed" <?php selected($content_type, 'embed'); ?>>
                        <?php _e('Embed External Content', 'nexuslearn'); ?>
                    </option>
                </select>
            </p>

            <div class="nl-content-fields">
                <!-- File Upload Field -->
                <div class="nl-file-upload" style="display: none;">
                    <input type="hidden" name="nl_content_url" id="nl_content_url" 
                           value="<?php echo esc_attr($content_url); ?>">
                    <button type="button" class="button" id="nl_upload_button">
                        <?php _e('Upload File', 'nexuslearn'); ?>
                    </button>
                    <span class="file-name"><?php echo basename($content_url); ?></span>
                </div>

                <!-- Embed Code Field -->
                <div class="nl-embed-code" style="display: none;">
                    <textarea name="nl_embed_code" rows="5" style="width: 100%;"><?php 
                        echo esc_textarea($embed_code); 
                    ?></textarea>
                    <p class="description">
                        <?php _e('Enter embed code or URL for external content', 'nexuslearn'); ?>
                    </p>
                </div>
            </div>
        </div>

        <script>
        jQuery(document).ready(function($) {
            function toggleFields() {
                var type = $('#nl_content_type').val();
                $('.nl-file-upload, .nl-embed-code').hide();
                
                if (type === 'video' || type === 'audio' || type === 'pdf') {
                    $('.nl-file-upload').show();
                } else if (type === 'embed') {
                    $('.nl-embed-code').show();
                }
            }

            $('#nl_content_type').on('change', toggleFields);
            toggleFields();

            // Media upload handling
            $('#nl_upload_button').on('click', function(e) {
                e.preventDefault();
                var uploadButton = $(this);
                var fileInput = $('#nl_content_url');
                var fileName = uploadButton.siblings('.file-name');
                
                var mediaUploader = wp.media({
                    title: '<?php _e('Select File', 'nexuslearn'); ?>',
                    button: {
                        text: '<?php _e('Use this file', 'nexuslearn'); ?>'
                    },
                    multiple: false
                });

                mediaUploader.on('select', function() {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    fileInput.val(attachment.url);
                    fileName.text(attachment.filename);
                });

                mediaUploader.open();
            });
        });
        </script>
        <?php
    }

    /**
     * Save content type metadata
     */
    public function save_content_type_meta($post_id) {
        if (!isset($_POST['nl_content_type_nonce']) || 
            !wp_verify_nonce($_POST['nl_content_type_nonce'], 'nl_content_type')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (isset($_POST['nl_content_type'])) {
            update_post_meta(
                $post_id, 
                '_nl_content_type', 
                sanitize_text_field($_POST['nl_content_type'])
            );
        }

        if (isset($_POST['nl_content_url'])) {
            update_post_meta(
                $post_id, 
                '_nl_content_url', 
                esc_url_raw($_POST['nl_content_url'])
            );
        }

        if (isset($_POST['nl_embed_code'])) {
            update_post_meta(
                $post_id, 
                '_nl_embed_code', 
                wp_kses_post($_POST['nl_embed_code'])
            );
        }
    }

    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook) {
        if ('post.php' !== $hook && 'post-new.php' !== $hook) {
            return;
        }

        wp_enqueue_media();
    }

    /**
     * Filter content display based on content type
     */
    public function filter_content_display($content) {
        if (!is_singular('nl_course')) {
            return $content;
        }

        $post_id = get_the_ID();
        $content_type = get_post_meta($post_id, '_nl_content_type', true);
        
        if (!$content_type || $content_type === 'text') {
            return $content;
        }

        return $this->render_content($post_id, $content_type) . $content;
    }

    /**
     * Render content based on type
     */
    public function render_content($post_id, $content_type) {
        $output = '';
        
        switch ($content_type) {
            case 'video':
                $url = get_post_meta($post_id, '_nl_content_url', true);
                if ($url) {
                    $output = sprintf(
                        '<div class="nl-video-container"><video controls><source src="%s"></video></div>',
                        esc_url($url)
                    );
                }
                break;

            case 'audio':
                $url = get_post_meta($post_id, '_nl_content_url', true);
                if ($url) {
                    $output = sprintf(
                        '<div class="nl-audio-container"><audio controls><source src="%s"></audio></div>',
                        esc_url($url)
                    );
                }
                break;

            case 'pdf':
                $url = get_post_meta($post_id, '_nl_content_url', true);
                if ($url) {
                    $output = sprintf(
                        '<div class="nl-pdf-container"><iframe src="%s" style="width: 100%%; height: 800px;"></iframe></div>',
                        esc_url($url)
                    );
                }
                break;

            case 'embed':
                $embed_code = get_post_meta($post_id, '_nl_embed_code', true);
                if ($embed_code) {
                    $output = '<div class="nl-embed-container">' . $embed_code . '</div>';
                }
                break;
        }

        return $output;
    }

    /**
     * Shortcode handler for content embedding
     */
    public function content_shortcode($atts) {
        $atts = shortcode_atts([
            'id' => get_the_ID(),
            'type' => ''
        ], $atts, 'nl_content');

        if (empty($atts['type'])) {
            $atts['type'] = get_post_meta($atts['id'], '_nl_content_type', true);
        }

        return $this->render_content($atts['id'], $atts['type']);
    }

    /**
     * Handle file uploads
     */
    public function handle_upload() {
        check_ajax_referer('nl_upload_content', 'nonce');

        if (!current_user_can('upload_files')) {
            wp_send_json_error(__('Permission denied', 'nexuslearn'));
        }

        $file = $_FILES['file'] ?? null;
        if (!$file) {
            wp_send_json_error(__('No file provided', 'nexuslearn'));
        }

        $content_type = $_POST['content_type'] ?? '';
        if (!array_key_exists($content_type, $this->allowed_types)) {
            wp_send_json_error(__('Invalid content type', 'nexuslearn'));
        }

        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $attachment_id = media_handle_upload('file', 0);

        if (is_wp_error($attachment_id)) {
            wp_send_json_error($attachment_id->get_error_message());
        }

        wp_send_json_success([
            'url' => wp_get_attachment_url($attachment_id),
            'id' => $attachment_id
        ]);
    }
}
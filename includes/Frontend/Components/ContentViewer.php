<?php
namespace NexusLearn\Frontend\Components;

class ContentViewer {
    private $user_id;
    private $bookmarks;

    public function __construct() {
        $this->user_id = get_current_user_id();
        add_action('wp_ajax_nl_save_progress', [$this, 'handle_save_progress']);
        add_action('wp_ajax_nl_toggle_bookmark', [$this, 'handle_toggle_bookmark']);
    }

    public function render($content_id) {
        // Get content details
        $content = $this->get_content($content_id);
        if (!$content) {
            return '<div class="nl-error">Content not found.</div>';
        }

        // Get user's progress
        $progress = $this->get_user_progress($content_id);
        $bookmarks = $this->get_user_bookmarks($content_id);

        ob_start();
        ?>
        <div class="nl-content-viewer" 
             data-content-id="<?php echo esc_attr($content_id); ?>"
             data-progress="<?php echo esc_attr($progress); ?>">
            
            <!-- Navigation Bar -->
            <div class="nl-content-nav">
                <button class="nl-nav-btn prev-section" title="Previous Section (Alt + Left)">
                    <span class="dashicons dashicons-arrow-left-alt2"></span>
                </button>
                <button class="nl-nav-btn next-section" title="Next Section (Alt + Right)">
                    <span class="dashicons dashicons-arrow-right-alt2"></span>
                </button>
                <div class="nl-progress-indicator">
                    <div class="nl-progress-bar">
                        <div class="nl-progress-fill" style="width: <?php echo esc_attr($progress); ?>%"></div>
                    </div>
                    <span class="nl-progress-text"><?php echo esc_html($progress); ?>% Complete</span>
                </div>
            </div>

            <!-- Content Area -->
            <div class="nl-content-area">
                <!-- Chapter Navigation -->
                <div class="nl-chapter-nav">
                    <h3>Chapters</h3>
                    <?php $this->render_chapter_list($content->chapters, $bookmarks); ?>
                </div>

                <!-- Main Content -->
                <div class="nl-main-content">
                    <div class="nl-content-header">
                        <h2><?php echo esc_html($content->title); ?></h2>
                        <button class="nl-bookmark-btn" title="Bookmark (Ctrl + B)">
                            <span class="dashicons dashicons-bookmark"></span>
                        </button>
                    </div>
                    <div class="nl-content-body">
                        <?php echo wp_kses_post($content->content); ?>
                    </div>
                </div>
            </div>
        </div>

        <?php
        return ob_get_clean();
    }

    private function get_content($content_id) {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}nexuslearn_content WHERE id = %d",
            $content_id
        ));
    }

    private function get_user_progress($content_id) {
        return get_user_meta($this->user_id, "nl_content_{$content_id}_progress", true) ?: 0;
    }

    private function get_user_bookmarks($content_id) {
        return get_user_meta($this->user_id, "nl_content_{$content_id}_bookmarks", true) ?: [];
    }

    private function render_chapter_list($chapters, $bookmarks) {
        echo '<ul class="nl-chapter-list">';
        foreach ($chapters as $chapter) {
            $is_bookmarked = in_array($chapter->id, $bookmarks);
            $bookmark_class = $is_bookmarked ? 'bookmarked' : '';
            
            echo "<li class='nl-chapter-item {$bookmark_class}' data-chapter-id='{$chapter->id}'>";
            echo "<span class='nl-chapter-title'>" . esc_html($chapter->title) . "</span>";
            if ($is_bookmarked) {
                echo "<span class='dashicons dashicons-bookmark'></span>";
            }
            echo "</li>";
        }
        echo '</ul>';
    }

    public function handle_save_progress() {
        check_ajax_referer('nl_content_viewer', 'nonce');
        
        $content_id = intval($_POST['content_id']);
        $progress = floatval($_POST['progress']);
        
        update_user_meta($this->user_id, "nl_content_{$content_id}_progress", $progress);
        wp_send_json_success(['message' => 'Progress saved']);
    }

    public function handle_toggle_bookmark() {
        check_ajax_referer('nl_content_viewer', 'nonce');
        
        $content_id = intval($_POST['content_id']);
        $chapter_id = intval($_POST['chapter_id']);
        
        $bookmarks = $this->get_user_bookmarks($content_id);
        
        if (in_array($chapter_id, $bookmarks)) {
            $bookmarks = array_diff($bookmarks, [$chapter_id]);
            $action = 'removed';
        } else {
            $bookmarks[] = $chapter_id;
            $action = 'added';
        }
        
        update_user_meta($this->user_id, "nl_content_{$content_id}_bookmarks", $bookmarks);
        wp_send_json_success([
            'message' => "Bookmark {$action}",
            'bookmarks' => $bookmarks
        ]);
    }
}
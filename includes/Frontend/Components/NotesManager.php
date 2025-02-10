<?php
namespace NexusLearn\Frontend\Components;

class NotesManager {
    public function __construct() {
        add_action('wp_ajax_nl_save_note', [$this, 'save_note']);
        add_action('wp_ajax_nl_delete_note', [$this, 'delete_note']);
    }

    public function render_notes_section($user_id) {
        $notes = $this->get_user_notes($user_id);
        ob_start();
        ?>
        <div class="nl-notes-section">
            <div class="nl-section-header">
                <h2><?php _e('My Notes', 'nexuslearn'); ?></h2>
                <button class="nl-button nl-button-primary" id="nl-add-note">
                    <?php _e('Add Note', 'nexuslearn'); ?>
                </button>
            </div>

            <div class="nl-notes-container">
                <div class="nl-notes-sidebar">
                    <div class="nl-notes-filter">
                        <input type="text" placeholder="<?php _e('Search notes...', 'nexuslearn'); ?>" 
                               class="nl-search-notes">
                    </div>
                    <div class="nl-notes-list">
                        <?php if (!empty($notes)): ?>
                            <?php foreach ($notes as $note): ?>
                                <div class="nl-note-item" data-note-id="<?php echo esc_attr($note['id']); ?>">
                                    <h4><?php echo esc_html($note['title']); ?></h4>
                                    <span class="nl-note-date">
                                        <?php echo date_i18n(get_option('date_format'), strtotime($note['created_at'])); ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="nl-empty-notes"><?php _e('No notes yet', 'nexuslearn'); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="nl-note-editor">
                    <input type="text" id="nl-note-title" placeholder="<?php _e('Note title', 'nexuslearn'); ?>">
                    <textarea id="nl-note-content" placeholder="<?php _e('Start writing...', 'nexuslearn'); ?>"></textarea>
                    <div class="nl-editor-actions">
                        <button class="nl-button nl-button-secondary" id="nl-cancel-note">
                            <?php _e('Cancel', 'nexuslearn'); ?>
                        </button>
                        <button class="nl-button nl-button-primary" id="nl-save-note">
                            <?php _e('Save Note', 'nexuslearn'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    private function get_user_notes($user_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'nexuslearn_notes';
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$table} WHERE user_id = %d ORDER BY created_at DESC",
            $user_id
        ), ARRAY_A);
    }

    public function save_note() {
        check_ajax_referer('nl_dashboard_nonce', 'nonce');
        
        $user_id = get_current_user_id();
        $title = sanitize_text_field($_POST['title']);
        $content = wp_kses_post($_POST['content']);
        
        global $wpdb;
        $table = $wpdb->prefix . 'nexuslearn_notes';
        
        $result = $wpdb->insert(
            $table,
            [
                'user_id' => $user_id,
                'title' => $title,
                'content' => $content,
                'created_at' => current_time('mysql')
            ],
            ['%d', '%s', '%s', '%s']
        );
        
        if ($result) {
            wp_send_json_success(['message' => __('Note saved successfully', 'nexuslearn')]);
        } else {
            wp_send_json_error(['message' => __('Failed to save note', 'nexuslearn')]);
        }
    }

    public function delete_note() {
        check_ajax_referer('nl_dashboard_nonce', 'nonce');
        
        $note_id = intval($_POST['note_id']);
        $user_id = get_current_user_id();
        
        global $wpdb;
        $table = $wpdb->prefix . 'nexuslearn_notes';
        
        $result = $wpdb->delete(
            $table,
            [
                'id' => $note_id,
                'user_id' => $user_id
            ],
            ['%d', '%d']
        );
        
        if ($result) {
            wp_send_json_success(['message' => __('Note deleted successfully', 'nexuslearn')]);
        } else {
            wp_send_json_error(['message' => __('Failed to delete note', 'nexuslearn')]);
        }
    }
}
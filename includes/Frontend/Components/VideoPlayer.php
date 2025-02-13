<?php
namespace NexusLearn\Frontend\Components;

class VideoPlayer {
    public static function render_modal() {
        ob_start();
        ?>
        <!-- Video Modal HTML here -->
        <?php
        return ob_get_clean();
    }

    public static function enqueue_assets() {
        wp_enqueue_style(
            'nl-video-player',
            NEXUSLEARN_PLUGIN_URL . 'assets/css/video-player.css',
            [],
            NEXUSLEARN_VERSION
        );

        wp_enqueue_script(
            'nl-video-player',
            NEXUSLEARN_PLUGIN_URL . 'assets/js/video-player.js',
            ['jquery'],
            NEXUSLEARN_VERSION,
            true
        );
    }
}
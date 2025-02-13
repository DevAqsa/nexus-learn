<?php
namespace NexusLearn\Frontend\Components;

class VideoHandler {
    private $video_data = [];

    public function __construct() {
        // Sample video data - Replace with your database implementation
        $this->video_data = [
            1 => ['type' => 'youtube', 'video_id' => 'dQw4w9WgXcQ', 'title' => 'Introduction'],
            2 => ['type' => 'youtube', 'video_id' => 'KyqhyNqQwQI', 'title' => 'List Implementation'],
            3 => ['type' => 'youtube', 'video_id' => '7sk4ISPkAW4', 'title' => 'Linked List'],
            4 => ['type' => 'youtube', 'video_id' => 'njTh_OwMljA', 'title' => 'Linked List Types'],
            5 => ['type' => 'youtube', 'video_id' => 'FNeL18KsWPc', 'title' => 'ADT and Stack'],
            6 => ['type' => 'youtube', 'video_id' => 'L8zhNb8ANe8', 'title' => 'Uses of Stack'],
            7 => ['type' => 'youtube', 'video_id' => 'PAceaOSnxQs', 'title' => 'Infix and Postfix'],
            8 => ['type' => 'youtube', 'video_id' => 'RAMqDJpu5Qo', 'title' => 'Stack Implementation']
        ];
    }

    public function get_video_embed($lesson_id) {
        if (!isset($this->video_data[$lesson_id])) {
            return '';
        }

        $video = $this->video_data[$lesson_id];
        
        if ($video['type'] === 'youtube') {
            return sprintf(
                '<div class="nl-video-container">
                    <iframe 
                        src="https://www.youtube.com/embed/%s" 
                        frameborder="0" 
                        allowfullscreen
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
                    </iframe>
                </div>',
                esc_attr($video['video_id'])
            );
        }

        return '';
    }

    public function get_video_data($lesson_id) {
        return $this->video_data[$lesson_id] ?? null;
    }
}
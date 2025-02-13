<?php
namespace NexusLearn\Frontend\Components;

class TodoCalendar {
    private $current_month;
    private $current_year;
    private $current_day;
    private $current_week;
    private $current_view;
    private $user_id;
    private $output = '';

    public function __construct() {
        // Start output buffering at construction
        ob_start();
        
        $this->user_id = get_current_user_id();
        $this->current_month = isset($_GET['month']) ? intval($_GET['month']) : intval(date('m'));
        $this->current_year = isset($_GET['year']) ? intval($_GET['year']) : intval(date('Y'));
        $this->current_day = isset($_GET['day']) ? intval($_GET['day']) : intval(date('d'));
        $this->current_week = isset($_GET['week']) ? intval($_GET['week']) : intval(date('W'));
        $this->current_view = isset($_GET['cal_view']) ? sanitize_text_field($_GET['cal_view']) : 'month';
    }

    public function __destruct() {
        // Clean up any output buffering
        if (ob_get_level()) {
            ob_end_clean();
        }
    }

    public function render() {
        // Clear any existing output
        if (ob_get_level()) {
            ob_clean();
        }

        ob_start();
        
        $this->render_calendar();
        
        $output = ob_get_clean();
        
        // Store the output instead of immediately returning it
        $this->output = $output;
        
        return $this->output;
    }

    private function render_calendar() {
        ?>
        <div class="nl-todo-calendar">
            <!-- Calendar Header -->
            <div class="nl-calendar-header">
                <h2 class="nl-calendar-title">To Do Calendar</h2>
                <div class="nl-calendar-controls">
                    <div class="nl-month-navigation">
                        <?php $this->render_navigation(); ?>
                    </div>
                    <div class="nl-view-controls">
                        <button class="nl-view-btn <?php echo $this->current_view === 'month' ? 'active' : ''; ?>" 
                                data-view="month">Month</button>
                        <button class="nl-view-btn <?php echo $this->current_view === 'week' ? 'active' : ''; ?>" 
                                data-view="week">Week</button>
                        <button class="nl-view-btn <?php echo $this->current_view === 'day' ? 'active' : ''; ?>" 
                                data-view="day">Day</button>
                    </div>
                </div>
            </div>

            <!-- Calendar Grid -->
            <div class="nl-calendar-grid">
                <?php
                switch ($this->current_view) {
                    case 'day':
                        $this->render_day_view();
                        break;
                    case 'week':
                        $this->render_week_view();
                        break;
                    default:
                        $this->render_month_view();
                }
                ?>
            </div>

            <!-- Calendar Legend -->
            <div class="nl-calendar-legend">
                <div class="nl-legend-item">
                    <span class="nl-legend-color assignment"></span>
                    <span class="nl-legend-text">Assignment</span>
                </div>
                <div class="nl-legend-item">
                    <span class="nl-legend-color quiz"></span>
                    <span class="nl-legend-text">Quiz</span>
                </div>
                <div class="nl-legend-item">
                    <span class="nl-legend-color announcement"></span>
                    <span class="nl-legend-text">Announcement</span>
                </div>
            </div>
        </div>

        <?php $this->render_styles(); ?>
        <?php $this->render_scripts(); ?>
        <?php
    }

    // Keep all other methods the same, but move styles and scripts to separate methods
    private function render_styles() {
        ?>
        <style>
        /* Your existing styles here */
        </style>
        <?php
    }

    private function render_scripts() {
        ?>
        <script>
        jQuery(document).ready(function($) {
            /* Your existing JavaScript here */
        });
        </script>
        <?php
    }

    // ... (keep all other existing methods the same)
}
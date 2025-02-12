<?php
namespace NexusLearn\Frontend\Components;

class TodoCalendar {
    private $current_month;
    private $current_year;
    private $current_view;
    private $user_id;

    public function __construct() {
        $this->user_id = get_current_user_id();
        $this->current_month = isset($_GET['month']) ? intval($_GET['month']) : intval(date('m'));
        $this->current_year = isset($_GET['year']) ? intval($_GET['year']) : intval(date('Y'));
        $this->current_view = isset($_GET['cal_view']) ? sanitize_text_field($_GET['cal_view']) : 'month';
    }

    public function render() {
        ob_start();
        ?>
        <div class="nl-todo-calendar">
            <!-- Calendar Header -->
            <div class="nl-calendar-header">
                <h2 class="nl-calendar-title">To Do Calendar</h2>
                <div class="nl-calendar-controls">
                    <div class="nl-month-navigation">
                        <?php $this->render_month_navigation(); ?>
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

            <!-- Calendar Grid -->
            <div class="nl-calendar-grid">
                <?php $this->render_calendar(); ?>
            </div>
        </div>

        <style>
        .nl-todo-calendar {
            background: #ffffff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .nl-calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .nl-calendar-controls {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .nl-month-navigation {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nl-nav-btn {
            padding: 8px;
            border: none;
            background: none;
            cursor: pointer;
            color: #4a5568;
        }

        .nl-view-controls {
            display: flex;
            gap: 5px;
        }

        .nl-view-btn {
            padding: 8px 16px;
            border: 1px solid #e2e8f0;
            background: white;
            border-radius: 4px;
            cursor: pointer;
        }

        .nl-view-btn.active {
            background: #4f46e5;
            color: white;
            border-color: #4f46e5;
        }

        .nl-calendar-legend {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            padding: 10px;
            background: #f8fafc;
            border-radius: 4px;
        }

        .nl-legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nl-legend-color {
            width: 12px;
            height: 12px;
            border-radius: 2px;
        }

        .nl-legend-color.assignment { background: #ef4444; }
        .nl-legend-color.quiz { background: #10b981; }
        .nl-legend-color.announcement { background: #f59e0b; }

        .nl-calendar-grid table {
            width: 100%;
            border-collapse: collapse;
        }

        .nl-calendar-grid th {
            padding: 10px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            font-weight: 600;
        }

        .nl-calendar-grid td {
            border: 1px solid #e2e8f0;
            padding: 10px;
            height: 120px;
            vertical-align: top;
        }

        .nl-calendar-grid td.today {
            background: #fff7ed;
        }

        .nl-calendar-grid td.other-month {
            background: #f8fafc;
            color: #94a3b8;
        }

        .nl-date-number {
            font-weight: 500;
            margin-bottom: 5px;
        }

        .nl-todo-item {
            margin: 2px 0;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.875rem;
            color: white;
            cursor: pointer;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .nl-todo-item.assignment { background: #ef4444; }
        .nl-todo-item.quiz { background: #10b981; }
        .nl-todo-item.announcement { background: #f59e0b; }
        
        </style>

        <script>
        jQuery(document).ready(function($) {
            // View switching
            $('.nl-view-btn').on('click', function() {
                const view = $(this).data('view');
                window.location.href = updateQueryParam('cal_view', view);
            });

            // Month navigation
            $('.nl-nav-btn').on('click', function() {
                const direction = $(this).data('direction');
                let month = <?php echo $this->current_month; ?>;
                let year = <?php echo $this->current_year; ?>;

                if (direction === 'prev') {
                    month--;
                    if (month < 1) {
                        month = 12;
                        year--;
                    }
                } else {
                    month++;
                    if (month > 12) {
                        month = 1;
                        year++;
                    }
                }

                window.location.href = updateQueryParam('month', month) + '&year=' + year;
            });

            function updateQueryParam(key, value) {
                const urlParams = new URLSearchParams(window.location.search);
                urlParams.set(key, value);
                return '?' + urlParams.toString();
            }

            // Todo item click handling
            $('.nl-todo-item').on('click', function() {
                const itemId = $(this).data('id');
                const itemType = $(this).data('type');
                // Handle item click - show details modal, etc.
            });
        });
        </script>
        <?php
        return ob_get_clean();
    }

    private function render_calendar() {
        $first_day = mktime(0, 0, 0, $this->current_month, 1, $this->current_year);
        $total_days = date('t', $first_day);
        $start_day = date('w', $first_day);
        
        $days_array = $this->get_calendar_days($total_days, $start_day);
        $todo_items = $this->get_todo_items();

        // Render calendar header
        echo '<table>';
        echo '<thead><tr>';
        $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        foreach ($days as $day) {
            echo "<th>{$day}</th>";
        }
        echo '</tr></thead><tbody>';

        // Render calendar days
        $current_date = date('Y-m-d');
        foreach ($days_array as $week) {
            echo '<tr>';
            foreach ($week as $day) {
                if ($day['date'] === $current_date) {
                    $class = 'today';
                } elseif ($day['month'] !== $this->current_month) {
                    $class = 'other-month';
                } else {
                    $class = '';
                }

                echo "<td class='{$class}'>";
                echo "<div class='nl-date-number'>{$day['day']}</div>";

                // Display todo items for this day
                if (isset($todo_items[$day['date']])) {
                    foreach ($todo_items[$day['date']] as $item) {
                        echo "<div class='nl-todo-item {$item['type']}' 
                                   data-id='{$item['id']}' 
                                   data-type='{$item['type']}'>";
                        echo esc_html($item['title']);
                        echo "</div>";
                    }
                }
                echo '</td>';
            }
            echo '</tr>';
        }
        echo '</tbody></table>';
    }

    private function get_calendar_days($total_days, $start_day) {
        $days = [];
        $week = [];
        $current_day = 1;

        // Previous month's days
        $prev_month = $this->current_month - 1;
        $prev_year = $this->current_year;
        if ($prev_month < 1) {
            $prev_month = 12;
            $prev_year--;
        }
        $prev_month_days = date('t', mktime(0, 0, 0, $prev_month, 1, $prev_year));

        for ($i = 0; $i < $start_day; $i++) {
            $day = $prev_month_days - ($start_day - $i - 1);
            $week[] = [
                'day' => $day,
                'month' => $prev_month,
                'date' => date('Y-m-d', mktime(0, 0, 0, $prev_month, $day, $prev_year))
            ];
        }

        // Current month's days
        while ($current_day <= $total_days) {
            $week[] = [
                'day' => $current_day,
                'month' => $this->current_month,
                'date' => date('Y-m-d', mktime(0, 0, 0, $this->current_month, $current_day, $this->current_year))
            ];

            if (count($week) === 7) {
                $days[] = $week;
                $week = [];
            }
            $current_day++;
        }

        // Next month's days
        if (!empty($week)) {
            $next_month = $this->current_month + 1;
            $next_year = $this->current_year;
            if ($next_month > 12) {
                $next_month = 1;
                $next_year++;
            }
            $next_day = 1;
            while (count($week) < 7) {
                $week[] = [
                    'day' => $next_day,
                    'month' => $next_month,
                    'date' => date('Y-m-d', mktime(0, 0, 0, $next_month, $next_day, $next_year))
                ];
                $next_day++;
            }
            $days[] = $week;
        }

        return $days;
    }

    private function get_todo_items() {
        global $wpdb;
        $start_date = date('Y-m-d', mktime(0, 0, 0, $this->current_month, 1, $this->current_year));
        $end_date = date('Y-m-t', mktime(0, 0, 0, $this->current_month, 1, $this->current_year));

        // Get assignments
        $assignments = $wpdb->get_results($wpdb->prepare(
            "SELECT id, title, due_date as date, 'assignment' as type 
            FROM {$wpdb->prefix}nexuslearn_assignments 
            WHERE user_id = %d 
            AND due_date BETWEEN %s AND %s",
            $this->user_id, $start_date, $end_date
        ), ARRAY_A);

        // Get quizzes
        $quizzes = $wpdb->get_results($wpdb->prepare(
            "SELECT id, title, due_date as date, 'quiz' as type 
            FROM {$wpdb->prefix}nexuslearn_quizzes 
            WHERE user_id = %d 
            AND due_date BETWEEN %s AND %s",
            $this->user_id, $start_date, $end_date
        ), ARRAY_A);

        // Get announcements
        $announcements = $wpdb->get_results($wpdb->prepare(
            "SELECT id, title, date, 'announcement' as type 
            FROM {$wpdb->prefix}nexuslearn_announcements 
            WHERE DATE(date) BETWEEN %s AND %s",
            $start_date, $end_date
        ), ARRAY_A);

        // Combine and organize by date
        $items = array_merge($assignments, $quizzes, $announcements);
        $organized = [];
        foreach ($items as $item) {
            $date = date('Y-m-d', strtotime($item['date']));
            $organized[$date][] = $item;
        }

        return $organized;
    }

    private function render_month_navigation() {
        ?>
        <button class="nl-nav-btn" data-direction="prev">
            <span class="dashicons dashicons-arrow-left-alt2"></span>
        </button>
        <span class="nl-current-month">
            <?php echo date('F Y', mktime(0, 0, 0, $this->current_month, 1, $this->current_year)); ?>
        </span>
        <button class="nl-nav-btn" data-direction="next">
            <span class="dashicons dashicons-arrow-right-alt2"></span>
        </button>
        <?php
    }
}
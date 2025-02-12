<?php
namespace NexusLearn\Frontend\Components;

class TodoCalendar {
     private $current_month;
    private $current_year;
    private $current_day;
    private $current_week;
    private $current_view;
    private $user_id;

    public function __construct() {
        $this->user_id = get_current_user_id();
        $this->current_month = isset($_GET['month']) ? intval($_GET['month']) : intval(date('m'));
        $this->current_year = isset($_GET['year']) ? intval($_GET['year']) : intval(date('Y'));
        $this->current_day = isset($_GET['day']) ? intval($_GET['day']) : intval(date('d'));
        $this->current_week = isset($_GET['week']) ? intval($_GET['week']) : intval(date('W'));
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
        </div>

        <style>
        /* ... (previous styles remain the same) ... */
        
        /* Add new styles for day and week views */
        .nl-day-view,
        .nl-week-view {
            display: grid;
            gap: 10px;
        }

        .nl-time-slot {
            display: flex;
            padding: 10px;
            border: 1px solid #e2e8f0;
            min-height: 60px;
        }

        .nl-time-label {
            width: 80px;
            font-weight: 500;
        }

        .nl-time-content {
            flex: 1;
            padding-left: 10px;
        }

        .nl-week-grid {
            display: grid;
            grid-template-columns: 80px repeat(7, 1fr);
            gap: 1px;
            background: #e2e8f0;
        }

        .nl-week-header {
            background: #f8fafc;
            padding: 10px;
            text-align: center;
            font-weight: 500;
        }

        .nl-week-time-slot {
            background: white;
            padding: 10px;
            min-height: 60px;
        }
        </style>

        <script>
        jQuery(document).ready(function($) {
            // View switching
            $('.nl-view-btn').on('click', function() {
                const view = $(this).data('view');
                window.location.href = updateQueryString({cal_view: view});
            });

            // Navigation
            $('.nl-nav-btn').on('click', function() {
                const direction = $(this).data('direction');
                const view = '<?php echo $this->current_view; ?>';
                let params = {};
                
                switch(view) {
                    case 'day':
                        let currentDate = new Date(<?php echo $this->current_year; ?>, 
                                                 <?php echo $this->current_month - 1; ?>, 
                                                 <?php echo $this->current_day; ?>);
                        currentDate.setDate(currentDate.getDate() + (direction === 'next' ? 1 : -1));
                        params = {
                            year: currentDate.getFullYear(),
                            month: currentDate.getMonth() + 1,
                            day: currentDate.getDate(),
                            cal_view: 'day'
                        };
                        break;
                    
                    case 'week':
                        // Calculate week navigation
                        let week = <?php echo $this->current_week; ?>;
                        let year = <?php echo $this->current_year; ?>;
                        
                        if (direction === 'next') {
                            week++;
                            if (week > 52) {
                                week = 1;
                                year++;
                            }
                        } else {
                            week--;
                            if (week < 1) {
                                week = 52;
                                year--;
                            }
                        }
                        
                        params = {
                            year: year,
                            week: week,
                            cal_view: 'week'
                        };
                        break;
                    
                    default: // month view
                        let month = <?php echo $this->current_month; ?>;
                        let yearMonth = <?php echo $this->current_year; ?>;
                        
                        if (direction === 'next') {
                            month++;
                            if (month > 12) {
                                month = 1;
                                yearMonth++;
                            }
                        } else {
                            month--;
                            if (month < 1) {
                                month = 12;
                                yearMonth--;
                            }
                        }
                        
                        params = {
                            year: yearMonth,
                            month: month,
                            cal_view: 'month'
                        };
                }
                
                window.location.href = updateQueryString(params);
            });

            // Day cell click handler for month view
            $('.nl-calendar-grid td').on('click', function() {
                const date = $(this).data('date');
                if (date) {
                    window.location.href = updateQueryString({
                        cal_view: 'day',
                        year: date.split('-')[0],
                        month: date.split('-')[1],
                        day: date.split('-')[2]
                    });
                }
            });

            function updateQueryString(params) {
                const urlParams = new URLSearchParams(window.location.search);
                Object.keys(params).forEach(key => {
                    urlParams.set(key, params[key]);
                });
                return '?' + urlParams.toString();
            }
        });
        </script>
        <?php
        return ob_get_clean();
    }

    private function render_navigation() {
        $format = '';
        $current_date = '';
        
        switch ($this->current_view) {
            case 'day':
                $format = 'F j, Y';
                $current_date = date($format, mktime(0, 0, 0, $this->current_month, $this->current_day, $this->current_year));
                break;
            case 'week':
                $week_start = new DateTime();
                $week_start->setISODate($this->current_year, $this->current_week);
                $week_end = clone $week_start;
                $week_end->modify('+6 days');
                $current_date = $week_start->format('M j') . ' - ' . $week_end->format('M j, Y');
                break;
            default:
                $format = 'F Y';
                $current_date = date($format, mktime(0, 0, 0, $this->current_month, 1, $this->current_year));
        }
        
        ?>
        <button class="nl-nav-btn" data-direction="prev">
            <span class="dashicons dashicons-arrow-left-alt2"></span>
        </button>
        <span class="nl-current-month"><?php echo $current_date; ?></span>
        <button class="nl-nav-btn" data-direction="next">
            <span class="dashicons dashicons-arrow-right-alt2"></span>
        </button>
        <?php
    }

    private function render_month_view() {
        // Previous month view code remains the same
        $first_day = mktime(0, 0, 0, $this->current_month, 1, $this->current_year);
        $total_days = date('t', $first_day);
        $start_day = date('w', $first_day);
        
        $days_array = $this->get_calendar_days($total_days, $start_day);
        $todo_items = $this->get_todo_items();

        echo '<table>';
        echo '<thead><tr>';
        $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        foreach ($days as $day) {
            echo "<th>{$day}</th>";
        }
        echo '</tr></thead><tbody>';

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

                echo "<td class='{$class}' data-date='{$day['date']}'>";
                echo "<div class='nl-date-number'>{$day['day']}</div>";

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

    private function render_week_view() {
        // Initialize the week start date
        $week_start = new \DateTime(); // Use global DateTime class
        $week_start->setISODate($this->current_year, $this->current_week);
    
        // Generate an array of dates for the week
        $dates = [];
        for ($i = 0; $i < 7; $i++) {
            $current = clone $week_start;
            $current->modify("+$i days");
            $dates[] = $current->format('Y-m-d');
        }
    
        // Retrieve to-do items for the week
        $start_date = reset($dates); // First day of the week
        $end_date = end($dates);     // Last day of the week
        $todo_items = $this->get_todo_items($start_date, $end_date);
    
        // Start rendering the week view
        echo '<table>';
        
        // Header row: Display days of the week
        echo '<tr><th>Time</th>';
        foreach ($dates as $date) {
            $formatted_date = date('D n/j', strtotime($date));
            $class = ($date === date('Y-m-d')) ? 'today' : '';
            echo "<th class='$class'>$formatted_date</th>";
        }
        echo '</tr>';
    
        // Time slots: Display 24-hour slots
        for ($hour = 0; $hour < 24; $hour++) {
            $time = sprintf('%02d:00', $hour);
            echo '<tr>';
            echo "<td>$time</td>"; // Time column
    
            foreach ($dates as $date) {
                echo '<td>';
                if (isset($todo_items[$date])) {
                    foreach ($todo_items[$date] as $item) {
                        // Only show items for this hour
                        $item_hour = date('H', strtotime($item['date']));
                        if ($item_hour == $hour) {
                            echo '<div>' . esc_html($item['title']) . '</div>';
                        }
                    }
                }
                echo '</td>';
            }
            echo '</tr>';
        }
    
        echo '</table>';
    }
    private function render_day_view() {
        $current_date = date('Y-m-d', mktime(0, 0, 0, $this->current_month, $this->current_day, $this->current_year));
        $todo_items = $this->get_todo_items($current_date, $current_date);
        
        echo '<div class="nl-day-view">';
        
        for ($hour = 0; $hour < 24; $hour++) {
            echo "<div class='nl-time-slot'>";
            echo "<div class='nl-time-label'>" . sprintf('%02d:00', $hour) . "</div>";
            echo "<div class='nl-time-content'>";
            
            if (isset($todo_items[$current_date])) {
                foreach ($todo_items[$current_date] as $item) {
                    // Only show items for this hour
                    $item_hour = date('H', strtotime($item['date']));
                    if ($item_hour == $hour) {
                        echo "<div class='nl-todo-item {$item['type']}'>";
                        echo esc_html($item['title']);
                        echo "</div>";
                    }
                }
            }
            
            echo "</div></div>";
        }
        
        echo '</div>';
    }

    private function get_todo_items($start_date = null, $end_date = null) {
        // Use provided dates or default to current month
        if (!$start_date) {
            $start_date = date('Y-m-d', mktime(0, 0, 0, $this->current_month, 1, $this->current_year));
        }
        if (!$end_date) {
            $end_date = date('Y-m-t', mktime(0, 0, 0, $this->current_month, 1, $this->current_year));
        }

        // Rest of the get_todo_items function remains the same
        global $wpdb;

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
            if (!isset($organized[$date])) {
                $organized[$date] = [];
            }
            // Add time information for day/week views
            $item['time'] = date('H:i', strtotime($item['date']));
            $organized[$date][] = $item;
        }

        // Sort items by time for each date
        foreach ($organized as $date => $items) {
            usort($organized[$date], function($a, $b) {
                return strtotime($a['date']) - strtotime($b['date']);
            });
        }

        return $organized;
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
}
?>


<style>
    /* Calendar Container */
.nl-todo-calendar {
    background: #ffffff;
    border-radius: 8px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

/* Header Styles */
.nl-calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.nl-calendar-title {
    font-size: 24px;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0;
}

.nl-calendar-controls {
    display: flex;
    gap: 16px;
    align-items: center;
}

/* Navigation Controls */
.nl-month-navigation {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-right: 16px;
}

.nl-current-month {
    font-size: 16px;
    font-weight: 500;
    color: #1a1a1a;
}

.nl-nav-btn {
    padding: 8px;
    border: none;
    background: none;
    cursor: pointer;
    color: #666;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    transition: background-color 0.2s;
}

.nl-nav-btn:hover {
    background-color: #f5f5f5;
}

/* View Controls */
.nl-view-controls {
    display: flex;
    gap: 4px;
    background: #f5f5f5;
    padding: 4px;
    border-radius: 6px;
}

.nl-view-btn {
    padding: 6px 12px;
    border: none;
    background: transparent;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    color: #666;
    transition: all 0.2s;
}

.nl-view-btn:hover {
    background: rgba(0,0,0,0.05);
}

.nl-view-btn.active {
    background: #6366f1;
    color: white;
}

/* Legend Styles */
.nl-calendar-legend {
    display: flex;
    gap: 16px;
    margin-bottom: 20px;
    padding: 12px;
    background: #f8fafc;
    border-radius: 6px;
}

.nl-legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #666;
}

.nl-legend-color {
    width: 12px;
    height: 12px;
    border-radius: 3px;
}

.nl-legend-color.assignment { background: #ef4444; }
.nl-legend-color.quiz { background: #10b981; }
.nl-legend-color.announcement { background: #f59e0b; }

/* Calendar Grid */
.nl-calendar-grid table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 1px;
    background: #e2e8f0;
}

.nl-calendar-grid th {
    background: #f8fafc;
    padding: 12px;
    font-weight: 500;
    color: #1a1a1a;
    text-align: center;
    font-size: 14px;
}

.nl-calendar-grid td {
    background: white;
    padding: 8px;
    height: 100px;
    vertical-align: top;
    position: relative;
    transition: background-color 0.2s;
}

.nl-calendar-grid td:hover {
    background: #f8fafc;
    cursor: pointer;
}

.nl-calendar-grid td.today {
    background: #fff7ed;
}

.nl-calendar-grid td.other-month {
    background: #f8fafc;
}

.nl-calendar-grid td.other-month .nl-date-number {
    color: #94a3b8;
}

.nl-date-number {
    font-size: 14px;
    font-weight: 500;
    color: #1a1a1a;
    margin-bottom: 8px;
}

/* Todo Items */
.nl-todo-item {
    margin: 2px 0;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    color: white;
    cursor: pointer;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    transition: transform 0.2s;
}

.nl-todo-item:hover {
    transform: translateX(2px);
}

.nl-todo-item.assignment { background: #ef4444; }
.nl-todo-item.quiz { background: #10b981; }
.nl-todo-item.announcement { background: #f59e0b; }

/* Day and Week Views */
.nl-day-view,
.nl-week-view {
    background: white;
}

.nl-time-slot {
    display: flex;
    padding: 12px;
    border-bottom: 1px solid #e2e8f0;
    min-height: 60px;
}

.nl-time-label {
    width: 80px;
    font-weight: 500;
    color: #64748b;
    font-size: 14px;
}

.nl-time-content {
    flex: 1;
    padding-left: 16px;
}

/* Week View Specific */
.nl-week-grid {
    display: grid;
    grid-template-columns: 80px repeat(7, 1fr);
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    overflow: hidden;
}

.nl-week-header {
    background: #f8fafc;
    padding: 12px;
    text-align: center;
    font-weight: 500;
    font-size: 14px;
    border-bottom: 1px solid #e2e8f0;
}

.nl-week-time-slot {
    padding: 12px;
    min-height: 60px;
    border-bottom: 1px solid #e2e8f0;
    border-right: 1px solid #e2e8f0;
}
</style>
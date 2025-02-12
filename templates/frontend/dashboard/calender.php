<?php
// File: templates/frontend/dashboard/calendar.php
if (!defined('ABSPATH')) exit;

// Initialize the calendar component if needed
if (!isset($this->todo_calendar)) {
    $this->todo_calendar = new NexusLearn\Frontend\Components\TodoCalendar();
}

// Render the calendar interface
?>
<div class="nl-calendar-view">
    <?php echo $this->todo_calendar->render(); ?>
</div>

<style>
.nl-calendar-view {
    padding: 20px;
    background: #ffffff;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}
</style>
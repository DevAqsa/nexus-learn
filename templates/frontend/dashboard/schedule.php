<?php
if (!defined('ABSPATH')) exit;

echo $this->lecture_schedule->render_schedule_section(get_current_user_id());
?>
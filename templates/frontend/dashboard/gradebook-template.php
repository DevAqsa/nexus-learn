<?php
if (!defined('ABSPATH')) exit;

$current_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'grades';
?>

<div class="nl-gradebook-wrapper">
    <!-- Navigation Menu -->
    <div class="nl-gradebook-nav">
        <a href="?view=gradebook&tab=grades" 
           class="nl-nav-item <?php echo $current_tab === 'grades' ? 'active' : ''; ?>">
            <i class="dashicons dashicons-book"></i>
            <?php _e('Student Grade Book', 'nexuslearn'); ?>
        </a>
        <a href="?view=gradebook&tab=quizzes" 
           class="nl-nav-item <?php echo $current_tab === 'quizzes' ? 'active' : ''; ?>">
            <i class="dashicons dashicons-editor-help"></i>
            <?php _e('Quizzes', 'nexuslearn'); ?>
        </a>
        <a href="?view=gradebook&tab=assignments" 
           class="nl-nav-item <?php echo $current_tab === 'assignments' ? 'active' : ''; ?>">
            <i class="dashicons dashicons-portfolio"></i>
            <?php _e('Assignments', 'nexuslearn'); ?>
        </a>
        <a href="?view=gradebook&tab=announcements" 
           class="nl-nav-item <?php echo $current_tab === 'announcements' ? 'active' : ''; ?>">
            <i class="dashicons dashicons-megaphone"></i>
            <?php _e('Imp Announcements', 'nexuslearn'); ?>
        </a>
        <a href="?view=gradebook&tab=grading-scheme" 
           class="nl-nav-item <?php echo $current_tab === 'grading-scheme' ? 'active' : ''; ?>">
            <i class="dashicons dashicons-chart-bar"></i>
            <?php _e('Grading Scheme', 'nexuslearn'); ?>
        </a>
    </div>

    <!-- Content Area -->
    <div class="nl-gradebook-content">
        <?php
        switch ($current_tab) {
            case 'grades':
                include NEXUSLEARN_PLUGIN_DIR . 'templates/frontend/dashboard/gradebook/grades.php';
                break;
            case 'quizzes':
                include NEXUSLEARN_PLUGIN_DIR . 'templates/frontend/dashboard/gradebook/quizzes.php';
                break;
            case 'assignments':
                include NEXUSLEARN_PLUGIN_DIR . 'templates/frontend/dashboard/gradebook/assignments.php';
                break;
            case 'announcements':
                include NEXUSLEARN_PLUGIN_DIR . 'templates/frontend/dashboard/gradebook/announcements.php';
                break;
            case 'grading-scheme':
                include NEXUSLEARN_PLUGIN_DIR . 'templates/frontend/dashboard/gradebook/grading-scheme.php';
                break;
        }
        ?>
    </div>
</div>
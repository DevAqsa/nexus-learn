<?php if (!defined('ABSPATH')) exit; ?>

<div class="nl-dashboard-wrapper">
    <!-- Sidebar -->
    <div class="nl-sidebar">
        <div class="nl-logo">
            <h2>NexusLearn</h2>
        </div>

        <div class="nl-menu">
            <h3 class="nl-menu-title"><?php _e('MAIN MENU', 'nexuslearn'); ?></h3>
            <ul class="nl-menu-items">
                <li class="nl-menu-item active">
                    <a href="#dashboard">
                        <span class="dashicons dashicons-dashboard"></span>
                        <?php _e('Dashboard', 'nexuslearn'); ?>
                    </a>
                </li>
                <li class="nl-menu-item">
                    <a href="#assignments">
                        <span class="dashicons dashicons-clipboard"></span>
                        <?php _e('Assignments', 'nexuslearn'); ?>
                    </a>
                </li>
                <li class="nl-menu-item">
                    <a href="#syllabus">
                        <span class="dashicons dashicons-book"></span>
                        <?php _e('Syllabus', 'nexuslearn'); ?>
                    </a>
                </li>
                <li class="nl-menu-item">
                    <a href="#chats">
                        <span class="dashicons dashicons-format-chat"></span>
                        <?php _e('Chats', 'nexuslearn'); ?>
                    </a>
                </li>
                <li class="nl-menu-item">
                    <a href="#attendance">
                        <span class="dashicons dashicons-calendar-alt"></span>
                        <?php _e('Attendance', 'nexuslearn'); ?>
                    </a>
                </li>
                <li class="nl-menu-item">
                    <a href="#settings">
                        <span class="dashicons dashicons-admin-generic"></span>
                        <?php _e('Settings', 'nexuslearn'); ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="nl-main-content">
    <div class="nl-user-profile">
        <h2>My Dashboard</h2>
        <div class="nl-user-info">
            <?php 
            $current_user = wp_get_current_user();
            echo get_avatar($current_user->ID, 40);
            ?>
            <span class="nl-user-email"><?php echo esc_html($current_user->user_email); ?></span>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="nl-stats-container">
        <div class="nl-stat-card">
            <div class="nl-stat-card-header">
                <span class="nl-stat-icon nl-enrolled-icon">
                    <span class="dashicons dashicons-book-alt"></span>
                </span>
                <h4 class="nl-stat-title">Enrolled Courses</h4>
            </div>
            <p class="nl-stat-value">0</p>
        </div>

        <div class="nl-stat-card">
            <div class="nl-stat-card-header">
                <span class="nl-stat-icon nl-completed-icon">
                    <span class="dashicons dashicons-yes-alt"></span>
                </span>
                <h4 class="nl-stat-title">Completed Courses</h4>
            </div>
            <p class="nl-stat-value">0</p>
        </div>

        <div class="nl-stat-card">
            <div class="nl-stat-card-header">
                <span class="nl-stat-icon nl-progress-icon">
                    <span class="dashicons dashicons-chart-line"></span>
                </span>
                <h4 class="nl-stat-title">Average Progress</h4>
            </div>
            <p class="nl-stat-value">0%</p>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="nl-content-grid">
        <!-- Courses Section -->
        <div class="nl-my-courses">
            <h3>My Courses</h3>
            <div class="nl-no-courses">
                <p>No courses found.</p>
            </div>
        </div>

        <!-- Sidebar Content -->
        <div class="nl-sidebar-content">
            <div class="nl-upcoming-assignments">
                <h3>Upcoming Assignments</h3>
                <div class="nl-no-assignments">
                    <p>No upcoming assignments</p>
                </div>
            </div>

            <div class="nl-recent-activities">
                <h3>Recent Activities</h3>
                <div class="nl-no-activities">
                    <p>No recent activities</p>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
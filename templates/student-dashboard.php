<?php
if (!defined('ABSPATH')) exit;

$current_user = wp_get_current_user();
?>

<div class="nl-dashboard-wrapper">
    <!-- Left Sidebar -->
    <div class="nl-sidebar">
        <div class="nl-logo">
            <img src="<?php echo NEXUSLEARN_PLUGIN_URL; ?>assets/images/logo.png" alt="NexusLearn">
        </div>
        <div class="nl-menu">
            <h3>Main Menu</h3>
            <ul>
                <li class="active"><a href="#"><span class="dashicons dashicons-dashboard"></span> Dashboard</a></li>
                <li><a href="#"><span class="dashicons dashicons-clipboard"></span> Assignments</a></li>
                <li><a href="#"><span class="dashicons dashicons-book"></span> Syllabus</a></li>
                <li><a href="#"><span class="dashicons dashicons-groups"></span> Chats</a></li>
                <li><a href="#"><span class="dashicons dashicons-calendar-alt"></span> Attendance</a></li>
                <li><a href="#"><span class="dashicons dashicons-admin-generic"></span> Settings</a></li>
            </ul>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="nl-main-content">
        <!-- Top Header -->
        <div class="nl-header">
            <div class="nl-search">
                <input type="text" placeholder="Search">
            </div>
            <div class="nl-user-menu">
                <span class="nl-notifications">
                    <span class="dashicons dashicons-bell"></span>
                </span>
                <div class="nl-user-profile">
                    <?php echo get_avatar($current_user->ID, 32); ?>
                    <span><?php echo esc_html($current_user->display_name); ?></span>
                </div>
            </div>
        </div>

        <!-- Dashboard Content -->
        <div class="nl-dashboard-content">
            <!-- Stats Overview -->
            <div class="nl-stats-container">
                <div class="nl-stat-card">
                    <div class="nl-stat-icon">
                        <span class="dashicons dashicons-portfolio"></span>
                    </div>
                    <div class="nl-stat-info">
                        <h4>Total Projects</h4>
                        <p class="nl-stat-value"><?php echo esc_html($total_projects); ?>/50</p>
                    </div>
                </div>

                <div class="nl-stat-card">
                    <div class="nl-stat-icon">
                        <span class="dashicons dashicons-calendar-alt"></span>
                    </div>
                    <div class="nl-stat-info">
                        <h4>Attendance</h4>
                        <p class="nl-stat-value"><?php echo esc_html($attendance_percentage); ?>%</p>
                    </div>
                </div>

                <div class="nl-stat-card">
                    <div class="nl-stat-icon">
                        <span class="dashicons dashicons-chart-bar"></span>
                    </div>
                    <div class="nl-stat-info">
                        <h4>Marks Secured</h4>
                        <p class="nl-stat-value"><?php echo esc_html($marks_secured); ?>/600</p>
                    </div>
                </div>

                <div class="nl-stat-card">
                    <div class="nl-stat-icon">
                        <span class="dashicons dashicons-groups"></span>
                    </div>
                    <div class="nl-stat-info">
                        <h4>Leadership</h4>
                        <p class="nl-stat-value"><?php echo esc_html($leadership_rank); ?></p>
                    </div>
                </div>
            </div>

            <!-- Continue Watching Section -->
            <div class="nl-section">
                <h2>Continue Watching</h2>
                <div class="nl-course-grid">
                    <?php foreach ($continue_watching as $course): ?>
                    <div class="nl-course-card">
                        <div class="nl-course-thumbnail">
                            <img src="<?php echo esc_url($course['thumbnail']); ?>" alt="<?php echo esc_attr($course['title']); ?>">
                        </div>
                        <div class="nl-course-info">
                            <h3><?php echo esc_html($course['title']); ?></h3>
                            <div class="nl-instructor">
                                <?php echo get_avatar($course['instructor_id'], 24); ?>
                                <span><?php echo esc_html($course['instructor']); ?></span>
                            </div>
                            <div class="nl-progress">
                                <div class="nl-progress-bar">
                                    <div class="nl-progress-fill" style="width: <?php echo esc_attr($course['progress']); ?>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Bottom Grid -->
            <div class="nl-bottom-grid">
                <!-- Assignments Section -->
                <div class="nl-section nl-assignments">
                    <div class="nl-section-header">
                        <h2>My Assignments</h2>
                        <a href="#" class="nl-view-all">View All</a>
                    </div>
                    <div class="nl-assignment-list">
                        <?php foreach ($assignments as $assignment): ?>
                        <div class="nl-assignment-item">
                            <div class="nl-assignment-info">
                                <h4><?php echo esc_html($assignment['title']); ?></h4>
                                <p>Due: <?php echo esc_html($assignment['due_date']); ?></p>
                            </div>
                            <span class="nl-status <?php echo esc_attr($assignment['status']); ?>">
                                <?php echo esc_html($assignment['status']); ?>
                            </span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Schedule Section -->
                <div class="nl-section nl-schedule">
                    <div class="nl-section-header">
                        <h2>Schedule</h2>
                        <div class="nl-calendar-nav">
                            <button class="nl-prev-month"><span class="dashicons dashicons-arrow-left-alt2"></span></button>
                            <button class="nl-next-month"><span class="dashicons dashicons-arrow-right-alt2"></span></button>
                        </div>
                    </div>
                    <div class="nl-calendar">
                        <?php echo wp_kses_post($calendar_html); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
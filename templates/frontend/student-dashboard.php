<?php
$user_id = get_current_user_id();
$current_page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : 'dashboard';
$user_email = wp_get_current_user()->user_email;
?>
<div class="nl-dashboard-container">
    <!-- Sidebar -->
    <div class="nl-sidebar">
        <div class="nl-menu-title">MAIN MENU</div>
        <ul class="nl-menu">
            <li>
                <a href="?page=dashboard" class="nl-menu-item <?php echo $current_page === 'dashboard' ? 'active' : ''; ?>">
                    <i class="dashicons dashicons-dashboard"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="?page=assignments" class="nl-menu-item">
                    <i class="dashicons dashicons-clipboard"></i>
                    Assignments
                </a>
            </li>
            <li>
                <a href="?page=syllabus" class="nl-menu-item">
                    <i class="dashicons dashicons-book-alt"></i>
                    Syllabus
                </a>
            </li>
            <li>
                <a href="?page=chats" class="nl-menu-item">
                    <i class="dashicons dashicons-format-chat"></i>
                    Chats
                </a>
            </li>
            <li>
                <a href="?page=attendance" class="nl-menu-item">
                    <i class="dashicons dashicons-calendar-alt"></i>
                    Attendance
                </a>
            </li>
            <li>
                <a href="?page=settings" class="nl-menu-item">
                    <i class="dashicons dashicons-admin-generic"></i>
                    Settings
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="nl-main-content">
        <!-- Header -->
        <div class="nl-header">
            <h1>My Dashboard</h1>
            <div class="nl-user-profile">
                <span class="nl-user-email"><?php echo esc_html($user_email); ?></span>
                <button class="nl-dropdown-toggle">
                    <i class="dashicons dashicons-arrow-down-alt2"></i>
                </button>
            </div>

            <?php 
        // Display Progress Tracking
        echo $this->progress_tracker->render_progress_section(get_current_user_id());
        
        // Display Certificates
        echo $this->certificates_manager->render_certificates_section(get_current_user_id());
        ?>
        </div>

        <!-- Stats Grid -->
        <div class="nl-stats-grid">
            <div class="nl-stat-card">
                <span class="nl-stat-icon book">📚</span>
                <div class="nl-stat-value">0</div>
                <div class="nl-stat-label">Enrolled Courses</div>
            </div>
            <div class="nl-stat-card">
                <span class="nl-stat-icon check">✓</span>
                <div class="nl-stat-value">0</div>
                <div class="nl-stat-label">Completed Courses</div>
            </div>
            <div class="nl-stat-card">
                <span class="nl-stat-icon chart">📈</span>
                <div class="nl-stat-value">0%</div>
                <div class="nl-stat-label">Average Progress</div>
            </div>
        </div>

        <!-- Content Layout -->
        <div class="nl-content-layout">
            <!-- Main Section -->
            <div class="nl-content-main">
                <div class="nl-content-section">
                    <h2>Recent Courses</h2>
                    <div class="nl-empty-state">No courses found</div>
                </div>
            </div>

            <!-- Sidebar Section -->
            <div class="nl-content-sidebar">

            <?php 
        // Display Profile Management
        echo $this->profile_manager->render_profile_section(get_current_user_id());
        ?>
                <div class="nl-content-section">
                    <h2>Upcoming Assignments</h2>
                    <div class="nl-empty-state">No upcoming assignments</div>
                </div>

                <div class="nl-content-section">
                    <h2>Recent Activities</h2>
                    <div class="nl-empty-state">No recent activities</div>
                </div>
            </div>
        </div>
    </div>
</div>
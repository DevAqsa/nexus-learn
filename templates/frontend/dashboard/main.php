<?php
$user_id = get_current_user_id();
$current_view = isset($_GET['view']) ? sanitize_key($_GET['view']) : 'overview';
$user_info = get_userdata($user_id);
?>
<div class="nl-dashboard-container">
    <!-- Sidebar -->
    <div class="nl-sidebar">
        <div class="nl-menu-title">MAIN MENU</div>
        <ul class="nl-nav-menu">
            <li class="nl-nav-item">
                <a href="?view=overview" class="<?php echo $current_view === 'overview' ? 'active' : ''; ?>">
                    <i class="dashicons dashicons-dashboard"></i>
                    <?php _e('Overview', 'nexuslearn'); ?>
                </a>
            </li>
            <li class="nl-nav-item">
                <a href="?view=courses" class="<?php echo $current_view === 'courses' ? 'active' : ''; ?>">
                    <i class="dashicons dashicons-welcome-learn-more"></i>
                    <?php _e('My Courses', 'nexuslearn'); ?>
                </a>
            </li>
            <li class="nl-nav-item">
                <a href="?view=progress" class="<?php echo $current_view === 'progress' ? 'active' : ''; ?>">
                    <i class="dashicons dashicons-chart-bar"></i>
                    <?php _e('Progress', 'nexuslearn'); ?>
                </a>
            </li>
            <li class="nl-nav-item">
                <a href="?view=certificates" class="<?php echo $current_view === 'certificates' ? 'active' : ''; ?>">
                    <i class="dashicons dashicons-awards"></i>
                    <?php _e('Certificates', 'nexuslearn'); ?>
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="nl-main-content">
        <!-- Header -->
        <div class="nl-header">
            <h1>
                <?php
                switch ($current_view) {
                    case 'courses':
                        _e('My Courses', 'nexuslearn');
                        break;
                    case 'progress':
                        _e('Learning Progress', 'nexuslearn');
                        break;
                    case 'certificates':
                        _e('Certificates', 'nexuslearn');
                        break;
                    default:
                        _e('My Dashboard', 'nexuslearn');
                        break;
                }
                ?>
            </h1>
            <div class="nl-user-profile">
                <span class="nl-user-email"><?php echo esc_html($user_info->user_email); ?></span>
                <div class="nl-user-menu">
                    <button class="nl-dropdown-toggle">
                        <i class="dashicons dashicons-admin-users"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Dynamic Content -->
        <?php
        switch ($current_view) {
            case 'overview':
                include NEXUSLEARN_PLUGIN_DIR . 'templates/frontend/dashboard/overview.php';
                break;
            case 'courses':
                include NEXUSLEARN_PLUGIN_DIR . 'templates/frontend/dashboard/courses.php';
                break;
            case 'progress':
                include NEXUSLEARN_PLUGIN_DIR . 'templates/frontend/dashboard/progress.php';
                break;
            case 'certificates':
                include NEXUSLEARN_PLUGIN_DIR . 'templates/frontend/dashboard/certificates.php';
                break;
        }
        ?>
    </div>
</div>
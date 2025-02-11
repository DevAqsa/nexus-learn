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
            <li class="nl-nav-item">
                <a href="?view=assignments" class="<?php echo $current_view === 'assignments' ? 'active' : ''; ?>">
                    <i class="dashicons dashicons-clipboard"></i>
                    <?php _e('Assignments', 'nexuslearn'); ?>
                </a>
            </li>
            <li class="nl-nav-item">
                <a href="?view=quiz" class="<?php echo $current_view === 'quiz' ? 'active' : ''; ?>">
                    <i class="dashicons dashicons-welcome-write-blog"></i>
                    <?php _e('Quiz', 'nexuslearn'); ?>
                </a>
            </li>
            <li class="nl-nav-item">
    <a href="?view=gradebook" class="<?php echo $current_view === 'gradebook' ? 'active' : ''; ?>">
        <i class="dashicons dashicons-book"></i>
        <?php _e('Grade Book', 'nexuslearn'); ?>
    </a>
</li>
            <li class="nl-nav-item">
                <a href="?view=attendance" class="<?php echo $current_view === 'attendance' ? 'active' : ''; ?>">
                    <i class="dashicons dashicons-calendar-alt"></i>
                    <?php _e('Attendance', 'nexuslearn'); ?>
                </a>
            </li>

            <li class="nl-nav-item">
                <a href="?view=membership" class="<?php echo $current_view === 'membership' ? 'active' : ''; ?>">
                    <i class="dashicons dashicons-money-alt"></i>
                    <?php _e('Membership', 'nexuslearn'); ?>
                </a>
            </li>
            <li class="nl-nav-item">
                <a href="?view=notes" class="<?php echo $current_view === 'notes' ? 'active' : ''; ?>">
                    <i class="dashicons dashicons-welcome-write-blog"></i>
                    <?php _e('My Notes', 'nexuslearn'); ?>
                </a>
            </li>
            <li class="nl-nav-item">
                <a href="?view=settings" class="<?php echo $current_view === 'settings' ? 'active' : ''; ?>">
                    <i class="dashicons dashicons-admin-generic"></i>
                    <?php _e('Settings', 'nexuslearn'); ?>
                </a>
            </li>
        </ul>

        <li class="nl-nav-item">
    <a href="?view=contact" class="<?php echo $current_view === 'contact' ? 'active' : ''; ?>">
        <i class="dashicons dashicons-phone"></i>
        <?php _e('Contact Us', 'nexuslearn'); ?>
    </a>
</li>
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
                    case 'assignments':
                        _e('Assignments', 'nexuslearn');
                        break;
                        case 'quiz':
                            _e('Quiz', 'nexuslearn');
                            break;
                        case 'gradebook':
                            _e('GradeBook', 'nexuslearn');
                            break;
                    case 'attendance':
                        _e('Attendance', 'nexuslearn');
                        break;
                        case 'membership':
                            _e('Membership', 'nexuslearn');
                            break;
                    case 'notes':
                        _e('My Notes', 'nexuslearn');
                        break;
                    case 'settings':
                        _e('Settings', 'nexuslearn');
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
                    <div class="nl-dropdown-menu">
                        <a href="?view=settings">
                            <i class="dashicons dashicons-admin-generic"></i>
                            <?php _e('Settings', 'nexuslearn'); ?>
                        </a>
                        <a href="<?php echo wp_logout_url(home_url()); ?>">
                            <i class="dashicons dashicons-logout"></i>
                            <?php _e('Logout', 'nexuslearn'); ?>
                        </a>
                    </div>
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
            case 'assignments':
                include NEXUSLEARN_PLUGIN_DIR . 'templates/frontend/dashboard/assignments.php';
                break;
                case 'quiz':
                    include NEXUSLEARN_PLUGIN_DIR . 'templates/frontend/dashboard/quiz.php';
                    break;
                case 'gradebook':
                    include NEXUSLEARN_PLUGIN_DIR . 'templates/frontend/dashboard/gradebook-template.php';
                    break;
            case 'attendance':
                include NEXUSLEARN_PLUGIN_DIR . 'templates/frontend/dashboard/attendance.php';
                break;
                case 'membership':
                    include NEXUSLEARN_PLUGIN_DIR . 'templates/frontend/dashboard/membership.php';
                    break;
            case 'notes':
                include NEXUSLEARN_PLUGIN_DIR . 'templates/frontend/dashboard/notes.php';
                break;
            case 'settings':
                include NEXUSLEARN_PLUGIN_DIR . 'templates/frontend/dashboard/settings.php';
                break;
                case 'contact':
                    include NEXUSLEARN_PLUGIN_DIR . 'templates/frontend/dashboard/contact.php';
                    break;
        }
        ?>
    </div>
</div>
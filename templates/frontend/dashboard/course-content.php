<?php
if (!defined('ABSPATH')) exit;

$course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;
$user_id = get_current_user_id();
$active_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'lessons';

// Dummy instructor data - replace with actual data later
$instructor = [
    'name' => 'Dr. Sohail Aslam',
    'designation' => 'Ph.D Computer Science',
    'institution' => 'University of Illinois at Urbana-Champaign',
];
?>

<div class="nl-course-container">
    <!-- Course Header -->
    <header class="nl-course-header">
        <div class="nl-header-content">
            <h1>Data structure Algorithm</h1>
            <a href="<?php echo esc_url(remove_query_arg(['view', 'course_id'])); ?>" class="nl-back-btn">
                <span class="dashicons dashicons-arrow-left-alt"></span>
                Back
            </a>
        </div>
    </header>

    <div class="nl-course-layout">
        <!-- Sidebar -->
        <aside class="nl-course-sidebar">
            <!-- Instructor Profile -->
            <div class="nl-instructor-card">
                <div class="nl-instructor-info">
                    <h3><?php echo esc_html($instructor['name']); ?></h3>
                    <p class="nl-instructor-title"><?php echo esc_html($instructor['designation']); ?></p>
                    <p class="nl-instructor-institution"><?php echo esc_html($instructor['institution']); ?></p>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="nl-course-nav">
                <a href="?view=course-content&course_id=<?php echo $course_id; ?>&tab=lessons" 
                   class="nl-nav-item <?php echo $active_tab === 'lessons' ? 'active' : ''; ?>">
                    <span class="nl-nav-icon">📋</span>
                    <span>Index / Lesson</span>
                </a>
                <a href="?view=course-content&course_id=<?php echo $course_id; ?>&tab=info" 
                   class="nl-nav-item <?php echo $active_tab === 'info' ? 'active' : ''; ?>">
                    <span class="nl-nav-icon">ℹ️</span>
                    <span>Course Information</span>
                </a>
                <a href="?view=course-content&course_id=<?php echo $course_id; ?>&tab=labs" 
       class="nl-nav-item <?php echo $active_tab === 'labs' ? 'active' : ''; ?>">
        <span class="nl-nav-icon">🧪</span>
        <span>Labs / Practicals</span>
                <a href="?view=course-content&course_id=<?php echo $course_id; ?>&tab=faqs" 
                   class="nl-nav-item <?php echo $active_tab === 'faqs' ? 'active' : ''; ?>">
                    <span class="nl-nav-icon">❓</span>
                    <span>FAQs</span>
                </a>
                <a href="?view=course-content&course_id=<?php echo $course_id; ?>&tab=glossary" 
                   class="nl-nav-item <?php echo $active_tab === 'glossary' ? 'active' : ''; ?>">
                    <span class="nl-nav-icon">📚</span>
                    <span>Glossary</span>
                </a>
                <a href="?view=course-content&course_id=<?php echo $course_id; ?>&tab=books" 
                   class="nl-nav-item <?php echo $active_tab === 'books' ? 'active' : ''; ?>">
                    <span class="nl-nav-icon">📖</span>
                    <span>Books</span>
                </a>

                <a href="?view=course-content&course_id=<?php echo $course_id; ?>&tab=downloadfiles" 
   class="nl-nav-item <?php echo $active_tab === 'downloadfiles' ? 'active' : ''; ?>">
    <span class="nl-nav-icon">📂</span>
    <span>Download Files</span>
</a>
<a href="?view=course-content&course_id=<?php echo $course_id; ?>&tab=internetlinks" 
   class="nl-nav-item <?php echo $active_tab === 'internetlinks' ? 'active' : ''; ?>">
    <span class="nl-nav-icon">🔗</span>
    <span>Internet Links</span>
</a>
<a href="?view=course-content&course_id=<?php echo $course_id; ?>&tab=assessment" 
   class="nl-nav-item <?php echo $active_tab === 'assessment' ? 'active' : ''; ?>">
    <span class="nl-nav-icon">📊</span>
    <span>Assessment Scheme</span>
</a>

            </nav>
        </aside>

        <!-- Main Content -->
        <main class="nl-course-main">
            <?php
            // Load the appropriate content based on the active tab
            $template_path = NEXUSLEARN_PLUGIN_DIR . 'templates/frontend/dashboard/course-content/';
            
            switch ($active_tab) {
                case 'info':
                    include $template_path . 'course-info-content.php';
                    break;
                    case 'labs':
                        include $template_path . 'course-labs-content.php';
                        break;
                case 'faqs':
                    include $template_path . 'course-faqs-content.php';
                    break;
                case 'glossary':
                    include $template_path . 'course-glossary-content.php';
                    break;
                case 'books':
                    include $template_path . 'course-books-content.php';
                    break;
                    case 'downloadfiles':
                        include $template_path . 'downloadfiles.php';
                        break;
                        case 'internetlinks':
                            include $template_path . 'internetlinks.php';
                            break;
                            case 'assessment':
                                include $template_path . 'assessment-scheme.php';
                                break;
                case 'lessons':
                default:
                    include $template_path . 'lessons-content.php';
                    break;
            }
            ?>
        </main>
    </div>
</div>

<style>
/* Main Container */
.nl-course-container {
    background-color: #f8f9fa;
    min-height: 100vh;
}

/* Header */
.nl-course-header {
    background: #7c3aed;
    padding: 1.5rem 2rem;
    color: white;
}

.nl-header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1400px;
    margin: 0 auto;
}

.nl-header-content h1 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 500;
}

.nl-back-btn {
    display: flex;
    align-items: center;
    color: white;
    text-decoration: none;
    font-size: 1rem;
    gap: 0.5rem;
}

/* Layout */
.nl-course-layout {
    display: flex;
    gap: 2rem;
    max-width: 1400px;
    margin: 2rem auto;
    padding: 0 2rem;
}

/* Sidebar */
.nl-course-sidebar {
    width: 300px;
    flex-shrink: 0;
}

.nl-instructor-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    margin-bottom: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.nl-instructor-info h3 {
    margin: 0 0 0.5rem 0;
    color: #1a1a1a;
}

.nl-instructor-title,
.nl-instructor-institution {
    color: #666;
    margin: 0.25rem 0;
    font-size: 0.9rem;
}

/* Navigation */
.nl-course-nav {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.nl-nav-item {
    display: flex;
    align-items: center;
    padding: 1rem 1.5rem;
    color: #4b5563;
    text-decoration: none;
    border-bottom: 1px solid #e5e7eb;
    transition: all 0.2s ease;
    gap: 0.75rem;
}

.nl-nav-item:hover {
    background: #f3f4f6;
}

.nl-nav-item.active {
    background: #7c3aed;
    color: white;
}

/* Main Content Area */
.nl-course-main {
    flex: 1;
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

/* Responsive Design */
@media (max-width: 1024px) {
    .nl-course-layout {
        padding: 0 1rem;
    }
}

@media (max-width: 768px) {
    .nl-course-layout {
        flex-direction: column;
    }

    .nl-course-sidebar {
        width: 100%;
    }
}
</style>
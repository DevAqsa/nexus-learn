<?php
if (!defined('ABSPATH')) exit;

// Dummy course files (Replace with dynamic data retrieval from LMS plugin)
$course_files = [
    [
        'title' => 'DEv C++ Installation Guide',
        'size' => '248 KB',
        'updated' => '3/12/2009 9:49:46 AM',
        'file_url' => 'path/to/dev-cpp-installation-guide.pdf'
    ],
    [
        'title' => 'Making a Project in DevC++',
        'size' => '390 KB',
        'updated' => '10/25/2023 10:38:27 AM',
        'file_url' => 'path/to/making-project-devcpp.pdf'
    ],
    [
        'title' => 'PowerPoint Slides of CS301',
        'size' => '1396.44 KB',
        'updated' => '1/4/2012 4:13:47 PM',
        'file_url' => 'path/to/cs301-powerpoint.pdf'
    ],
    [
        'title' => 'Dev-C++ Setup',
        'size' => '9107.88 KB',
        'updated' => '4/13/2011 9:19:42 AM',
        'file_url' => 'path/to/dev-cpp-setup.pdf'
    ]
];

// Internet links data
$internet_links = [
    [
        'url' => 'http://courses.cs.vt.edu/csonline/DataStructures/Lessons/',
        'description' => 'Nice tutorials with animation of various data structures.'
    ],
    [
        'url' => 'http://www.cplusplus.com/doc/tutorial/',
        'description' => 'These tutorials explain the C++ language from its basics up to the newest features of ANSI-C++, including basic concepts such as arrays or classes and advanced concepts such as polymorphism or templates.'
    ],
    [
        'url' => 'http://www.cs.colorado.edu/~main/supplements/lectures.html',
        'description' => 'Data Structures and Other Objects Using C++'
    ],
    [
        'url' => 'http://www.engr.mun.ca/~theo/Courses/ds/CPP-REV.HTM',
        'description' => 'This is an excellent and strongly recommended review of the topics of C++ that are essential for this course.'
    ],
    [
        'url' => 'http://www.nist.gov/dads/',
        'description' => 'Dictionary of algorithms and data structures'
    ],
    [
        'url' => 'http://www.cs.sunysb.edu/~skiena/214/lectures/',
        'description' => 'Lecture Notes : Data Structures'
    ]
];
?>

<div class="student-dashboard">
    <div class="content-tabs">
        <button class="tab-btn active" data-tab="files">Course Files</button>
        <button class="tab-btn" data-tab="links">Internet Links</button>
    </div>

    <div class="search-container">
        <input type="text" id="searchInput" class="search-input" placeholder="Search for...">
        <button class="search-btn">🔍</button>
    </div>

    <!-- Course Files Section -->
    <div class="content-section active" id="files-section">
        <ul id="fileList">
            <?php foreach ($course_files as $file): ?>
                <li class="file-item">
                    <div class="file-details">
                        <strong><?php echo esc_html($file['title']); ?></strong>
                        <p>File Size: <?php echo esc_html($file['size']); ?></p>
                        <p>Last Updated: <?php echo esc_html($file['updated']); ?></p>
                    </div>
                    <a href="<?php echo esc_url($file['file_url']); ?>" download class="download-btn">⬇</a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- Internet Links Section -->
    <div class="content-section" id="links-section">
        <ul id="linksList">
            <?php foreach ($internet_links as $link): ?>
                <li class="link-item">
                    <div class="link-details">
                        <a href="<?php echo esc_url($link['url']); ?>" target="_blank" class="link-url">
                            <?php echo esc_html($link['url']); ?>
                        </a>
                        <p class="link-description"><?php echo esc_html($link['description']); ?></p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<style>
.student-dashboard {
    font-family: Arial, sans-serif;
    padding: 20px;
    max-width: 1000px;
    margin: 0 auto;
}

.content-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

.tab-btn {
    padding: 10px 20px;
    border: none;
    background: #f0f0f0;
    cursor: pointer;
    border-radius: 5px;
    font-size: 16px;
    transition: all 0.3s ease;
}

.tab-btn.active {
    background: #7c3aed;
    color: white;
}

.search-container {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.search-input {
    padding: 10px 15px;
    width: 100%;
    border: 1px solid #d3d3d3;
    border-radius: 5px 0 0 5px;
    font-size: 16px;
    outline: none;
}

.search-btn {
    padding: 10px 15px;
    background: #7c3aed;
    color: white;
    border: none;
    border-radius: 0 5px 5px 0;
    cursor: pointer;
    font-size: 16px;
}

.content-section {
    display: none;
}

.content-section.active {
    display: block;
}

.file-item, .link-item {
    display: flex;
    align-items: center;
    padding: 15px;
    border-bottom: 1px solid #eee;
    transition: background-color 0.3s ease;
}

.file-item:hover, .link-item:hover {
    background-color: #f8f8f8;
}

.file-details, .link-details {
    flex: 1;
}

.file-details strong {
    display: block;
    font-size: 16px;
    margin-bottom: 5px;
    color: #333;
}

.file-details p, .link-description {
    font-size: 14px;
    color: #666;
    margin: 5px 0;
}

.download-btn {
    text-decoration: none;
    color: #7c3aed;
    font-size: 20px;
    margin-left: 15px;
}

.link-url {
    display: block;
    color: #7c3aed;
    text-decoration: none;
    font-size: 16px;
    margin-bottom: 5px;
}

.link-url:hover {
    text-decoration: underline;
}

@media (max-width: 768px) {
    .content-tabs {
        flex-direction: column;
    }
    
    .search-container {
        flex-direction: column;
    }
    
    .search-input, .search-btn {
        width: 100%;
        border-radius: 5px;
        margin-bottom: 10px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching functionality
    const tabBtns = document.querySelectorAll('.tab-btn');
    const contentSections = document.querySelectorAll('.content-section');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const tabName = btn.dataset.tab;
            
            // Update active states
            tabBtns.forEach(b => b.classList.remove('active'));
            contentSections.forEach(s => s.classList.remove('active'));
            
            btn.classList.add('active');
            document.getElementById(`${tabName}-section`).classList.add('active');
        });
    });

    // Search functionality
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const activeSection = document.querySelector('.content-section.active');
        const items = activeSection.querySelectorAll('.file-item, .link-item');

        items.forEach(item => {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(searchTerm) ? 'flex' : 'none';
        });
    });
});
</script>
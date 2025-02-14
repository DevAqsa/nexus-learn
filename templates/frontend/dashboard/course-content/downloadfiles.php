<?php
// Ensure WordPress environment is loaded
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
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
    ],
    [
        'title' => 'AVL-Rotation',
        'size' => '18.88 KB',
        'updated' => '12/12/2011 4:09:38 PM',
        'file_url' => 'path/to/avl-rotation.pdf'
    ],
    [
        'title' => 'Lecture Handouts',
        'size' => 'File Size Unknown',
        'updated' => 'Date Unknown',
        'file_url' => 'path/to/lecture-handouts.pdf'
    ]
];
?>
<div class="student-dashboard">
    <div class="header">
        <h2>Download Files</h2>
       
    </div>
    <div class="search-container">
    <input type="text" id="searchInput" class="search-input" placeholder="Search for...">
    <button class="search-btn">🔍</button>
</div>
    <ul id="fileList">
        <?php foreach ($course_files as $file): ?>
            <li class="file-item">
                <img src="path/to/icon.png" alt="File Icon" class="file-icon">
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
<script>
function filterFiles() {
    var input = document.getElementById("searchFiles");
    var filter = input.value.toLowerCase();
    var ul = document.getElementById("fileList");
    var li = ul.getElementsByClassName("file-item");
    
    for (var i = 0; i < li.length; i++) {
        var title = li[i].getElementsByClassName("file-details")[0].getElementsByTagName("strong")[0].innerText.toLowerCase();
        if (title.indexOf(filter) > -1) {
            li[i].style.display = "";
        } else {
            li[i].style.display = "none";
        }
    }
}
</script>
<style>
.student-dashboard {
    font-family: Arial, sans-serif;
    padding: 20px;
  
    max-width: 800px;
    margin: 0 auto;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
   
    color: black;
    padding: 10px 20px;
    border-radius: 5px;
}

.search-container {
    display: flex;
    align-items: center;
    margin-top: 20px;
}

.search-input {
    padding: 10px 15px;
    width: 100%;
    max-width: 600px;
    border: 1px solid #d3d3d3;
    border-radius: 5px 0 0 5px;
    font-size: 16px;
    outline: none;
}

.search-btn {
    padding: 10px 15px;
   
    color: white;
    border: none;
    border-radius: 0 5px 5px 0;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

.search-btn:hover {
    background-color: #6a5acd;
}

#searchFiles {
    padding: 8px;
    width: 100%;
    margin-right: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}



.file-item {
    display: flex;
    align-items: center;
    padding: 15px;
    border-bottom: 1px dashed #ddd;
}

.file-icon {
    width: 30px;
    height: 30px;
    margin-right: 15px;
}

.file-details {
    flex: 1;
}

.file-details strong {
    display: block;
    font-size: 16px;
    margin-bottom: 5px;
}

.file-details p {
    font-size: 14px;
    color: #666;
}

.download-btn {
    text-decoration: none;
    color: #6a5acd;
    font-size: 20px;
    margin-left: 15px;
}
</style>
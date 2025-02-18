<?php
if (!defined('ABSPATH')) exit;

// Dummy lab data - replace with actual data from your database
$labs = [
    [
        'id' => 1,
        'title' => 'Lab 1: Introduction to Dev C++',
        'due_date' => '2024-03-20',
        'status' => 'completed',
        'description' => 'Setting up the development environment and first C++ program',
        'objectives' => [
            'Install Dev C++ IDE',
            'Create your first C++ program',
            'Understanding basic program structure'
        ],
        'resources' => [
            ['name' => 'Lab Manual', 'type' => 'pdf', 'size' => '1.2 MB'],
            ['name' => 'Code Examples', 'type' => 'zip', 'size' => '450 KB']
        ],
        'submission_type' => 'code',
        'max_attempts' => 3,
        'current_attempt' => 2
    ],
    [
        'id' => 2,
        'title' => 'Lab 2: Array Implementation',
        'due_date' => '2024-03-27',
        'status' => 'pending',
        'description' => 'Working with arrays and implementing basic operations',
        'objectives' => [
            'Create and initialize arrays',
            'Implement array operations',
            'Practice array traversal'
        ],
        'resources' => [
            ['name' => 'Lab Guidelines', 'type' => 'pdf', 'size' => '850 KB'],
            ['name' => 'Starter Code', 'type' => 'cpp', 'size' => '25 KB']
        ],
        'submission_type' => 'code',
        'max_attempts' => 3,
        'current_attempt' => 0
    ]
];
?>

<div class="nl-labs-container">
    <!-- Header -->
    <div class="nl-labs-header">
        <h2>Lab Work / Practicals</h2>
        <div class="nl-labs-filters">
            <select class="nl-select" id="statusFilter">
                <option value="all">All Status</option>
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
                <option value="late">Late</option>
            </select>
        </div>
    </div>

    <!-- Labs List -->
    <div class="nl-labs-list">
        <?php foreach ($labs as $lab): ?>
            <div class="nl-lab-card" data-status="<?php echo esc_attr($lab['status']); ?>">
                <div class="nl-lab-header">
                    <h3><?php echo esc_html($lab['title']); ?></h3>
                    <span class="nl-status-badge <?php echo esc_attr($lab['status']); ?>">
                        <?php echo ucfirst(esc_html($lab['status'])); ?>
                    </span>
                </div>

                <div class="nl-lab-content">
                    <div class="nl-lab-description">
                        <p><?php echo esc_html($lab['description']); ?></p>
                    </div>

                    <div class="nl-lab-objectives">
                        <h4>Objectives</h4>
                        <ul>
                            <?php foreach ($lab['objectives'] as $objective): ?>
                                <li><?php echo esc_html($objective); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div class="nl-lab-resources">
                        <h4>Resources</h4>
                        <div class="nl-resources-grid">
                            <?php foreach ($lab['resources'] as $resource): ?>
                                <div class="nl-resource-item">
                                    <span class="nl-resource-icon">
                                        <?php echo $resource['type'] === 'pdf' ? '📄' : '📦'; ?>
                                    </span>
                                    <div class="nl-resource-info">
                                        <span class="nl-resource-name">
                                            <?php echo esc_html($resource['name']); ?>
                                        </span>
                                        <span class="nl-resource-meta">
                                            <?php echo esc_html($resource['type']); ?> • 
                                            <?php echo esc_html($resource['size']); ?>
                                        </span>
                                    </div>
                                    <button class="nl-download-btn">⬇️</button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="nl-lab-meta">
                        <div class="nl-meta-item">
                            <span class="nl-meta-label">Due Date</span>
                            <span class="nl-meta-value">
                                <?php echo date('M j, Y', strtotime($lab['due_date'])); ?>
                            </span>
                        </div>
                        <div class="nl-meta-item">
                            <span class="nl-meta-label">Attempts</span>
                            <span class="nl-meta-value">
                                <?php echo $lab['current_attempt']; ?>/<?php echo $lab['max_attempts']; ?>
                            </span>
                        </div>
                    </div>

                    <div class="nl-lab-actions">
                        <?php if ($lab['status'] !== 'completed'): ?>
                            <button class="nl-button nl-button-primary">
                                Start Lab
                            </button>
                        <?php else: ?>
                            <button class="nl-button nl-button-secondary">
                                View Submission
                            </button>
                        <?php endif; ?>
                        <button class="nl-button nl-button-outline">
                            View Instructions
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div id="instructionsModal" class="nl-modal">
    <div class="nl-modal-content">
        <div class="nl-modal-header">
            <h3 id="instructionsTitle">Lab Instructions</h3>
            <button class="nl-modal-close">&times;</button>
        </div>
        <div class="nl-modal-body">
            <div id="lab1Instructions" class="nl-instructions-content lab-instructions">
                <h4>Overview</h4>
                <p>This lab will guide you through setting up your development environment and creating your first C++ program.</p>
                
                <h4>Step-by-Step Instructions</h4>
                <ol>
                    <li>
                        <h5>Download and Install Dev C++</h5>
                        <p>Visit the official website and follow the installation guide for your operating system.</p>
                    </li>
                    <li>
                        <h5>Create a New Project</h5>
                        <p>Open Dev C++ and create a new source file.</p>
                    </li>
                    <li>
                        <h5>Write Your First Program</h5>
                        <p>Follow the code examples provided in the resources section.</p>
                    </li>
                </ol>

                <h4>Submission Requirements</h4>
                <ul>
                    <li>Source code files (.cpp)</li>
                    <li>Screenshot of program output</li>
                    <li>Brief explanation of your implementation</li>
                </ul>
            </div>

            <div id="lab2Instructions" class="nl-instructions-content lab-instructions">
                <h4>Overview</h4>
                <p>In this lab, you will learn about arrays in C++ and implement various array operations.</p>
                
                <h4>Step-by-Step Instructions</h4>
                <ol>
                    <li>
                        <h5>Array Creation and Initialization</h5>
                        <p>Learn different ways to create and initialize arrays in C++.</p>
                        <pre><code>int numbers[5] = {1, 2, 3, 4, 5};
int dynamicArray[size];</code></pre>
                    </li>
                    <li>
                        <h5>Implement Basic Operations</h5>
                        <p>Create functions for the following operations:</p>
                        <ul>
                            <li>Insert element at specific position</li>
                            <li>Delete element from specific position</li>
                            <li>Search for an element</li>
                            <li>Update element at specific position</li>
                        </ul>
                    </li>
                    <li>
                        <h5>Array Traversal</h5>
                        <p>Implement different ways to traverse arrays:</p>
                        <ul>
                            <li>Forward traversal</li>
                            <li>Backward traversal</li>
                            <li>Using different loop types</li>
                        </ul>
                    </li>
                </ol>

                <h4>Submission Requirements</h4>
                <ul>
                    <li>Source code implementing all required operations</li>
                    <li>Test cases demonstrating each operation</li>
                    <li>Documentation explaining your implementation</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div id="submissionModal" class="nl-modal">
    <div class="nl-modal-content">
        <div class="nl-modal-header">
            <h3>Lab Submission</h3>
            <button class="nl-modal-close">&times;</button>
        </div>
        <div class="nl-modal-body">
            <div class="nl-submission-content">
                <div class="nl-submission-status">
                    <div class="nl-status-header">
                        <h4>Submission Status</h4>
                        <span class="nl-status-badge completed">Completed</span>
                    </div>
                    <div class="nl-submission-meta">
                        <div class="nl-meta-item">
                            <span class="nl-meta-label">Submitted On</span>
                            <span class="nl-meta-value">Mar 15, 2024</span>
                        </div>
                        <div class="nl-meta-item">
                            <span class="nl-meta-label">Attempt</span>
                            <span class="nl-meta-value">2/3</span>
                        </div>
                        <div class="nl-meta-item">
                            <span class="nl-meta-label">Grade</span>
                            <span class="nl-meta-value">95/100</span>
                        </div>
                    </div>
                </div>
                
                <div class="nl-submission-files">
                    <h4>Submitted Files</h4>
                    <div class="nl-resources-grid">
                        <div class="nl-resource-item">
                            <span class="nl-resource-icon">📄</span>
                            <div class="nl-resource-info">
                                <span class="nl-resource-name">main.cpp</span>
                                <span class="nl-resource-meta">cpp • 25 KB</span>
                            </div>
                            <button class="nl-download-btn">⬇️</button>
                        </div>
                        <div class="nl-resource-item">
                            <span class="nl-resource-icon">📄</span>
                            <div class="nl-resource-info">
                                <span class="nl-resource-name">output.png</span>
                                <span class="nl-resource-meta">png • 150 KB</span>
                            </div>
                            <button class="nl-download-btn">⬇️</button>
                        </div>
                    </div>
                </div>

                <div class="nl-feedback">
                    <h4>Instructor Feedback</h4>
                    <p>Excellent work! Your implementation shows good understanding of basic C++ concepts. Code is well-structured and properly commented. Consider using more meaningful variable names in future submissions.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add a new modal for the Start Lab functionality -->
<div id="startLabModal" class="nl-modal">
    <div class="nl-modal-content">
        <div class="nl-modal-header">
            <h3 id="startLabTitle">Start Lab</h3>
            <button class="nl-modal-close">&times;</button>
        </div>
        <div class="nl-modal-body">
            <div class="nl-start-lab-content">
                <div class="nl-lab-timer">
                    <h4>Time Remaining</h4>
                    <div class="nl-timer">02:00:00</div>
                </div>
                
                <div class="nl-submission-form">
                    <h4>Submit Your Work</h4>
                    <div class="nl-form-group">
                        <label for="codeFile">Source Code File (.cpp)</label>
                        <input type="file" id="codeFile" accept=".cpp" class="nl-file-input">
                    </div>
                    
                    <div class="nl-form-group">
                        <label for="documentationFile">Documentation</label>
                        <input type="file" id="documentationFile" accept=".pdf,.doc,.docx" class="nl-file-input">
                    </div>
                    
                    <div class="nl-form-group">
                        <label for="comments">Additional Comments</label>
                        <textarea id="comments" class="nl-textarea" rows="4"></textarea>
                    </div>
                    
                    <button class="nl-button nl-button-primary nl-submit-btn">
                        Submit Lab
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

<style>

.nl-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1000;
}

.nl-modal-content {
    position: relative;
    background-color: white;
    margin: 50px auto;
    padding: 0;
    width: 90%;
    max-width: 800px;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    max-height: 80vh;
    overflow-y: auto;
}

.nl-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid #e5e7eb;
}

.nl-modal-header h3 {
    margin: 0;
    font-size: 20px;
    color: #1a1a1a;
}

.nl-modal-close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #6b7280;
}

.nl-modal-body {
    padding: 24px;
}

.nl-instructions-content,
.nl-submission-content {
    color: #4b5563;
}

.nl-instructions-content h4,
.nl-submission-content h4 {
    color: #1a1a1a;
    margin: 24px 0 16px;
}

.nl-instructions-content h4:first-child,
.nl-submission-content h4:first-child {
    margin-top: 0;
}

.nl-instructions-content ol,
.nl-instructions-content ul {
    padding-left: 24px;
    margin-bottom: 20px;
}

.nl-instructions-content li {
    margin-bottom: 16px;
}

.nl-instructions-content h5 {
    margin: 0 0 8px;
    color: #1a1a1a;
}

.nl-submission-status {
    background: #f8fafc;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 24px;
}

.nl-status-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.nl-status-header h4 {
    margin: 0;
}

.nl-feedback {
    background: #f8fafc;
    border-radius: 8px;
    padding: 20px;
    margin-top: 24px;
}

@media (max-width: 768px) {
    .nl-modal-content {
        margin: 20px;
        width: auto;
    }
    
    .nl-status-header {
        flex-direction: column;
        gap: 12px;
        align-items: flex-start;
    }
}
.nl-labs-container {
    padding: 20px;
}

.lab-instructions {
    display: none;
}

.nl-lab-timer {
    background: #f8fafc;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 24px;
    text-align: center;
}

.nl-timer {
    font-size: 32px;
    font-weight: bold;
    color: #7c3aed;
    margin-top: 8px;
}

.nl-form-group {
    margin-bottom: 20px;
}

.nl-form-group label {
    display: block;
    margin-bottom: 8px;
    color: #1a1a1a;
    font-weight: 500;
}

.nl-file-input {
    width: 100%;
    padding: 8px;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
}

.nl-textarea {
    width: 100%;
    padding: 8px;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    resize: vertical;
}

.nl-submit-btn {
    width: 100%;
    margin-top: 16px;
}

pre {
    background: #f8fafc;
    padding: 12px;
    border-radius: 6px;
    overflow-x: auto;
}

code {
    font-family: monospace;
}

.nl-labs-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.nl-labs-header h2 {
    margin: 0;
    font-size: 24px;
    color: #1a1a1a;
}

.nl-select {
    padding: 8px 16px;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    background: white;
}

.nl-lab-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 20px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.nl-lab-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.nl-lab-header h3 {
    margin: 0;
    font-size: 18px;
    color: #1a1a1a;
}

.nl-status-badge {
    padding: 4px 12px;
    border-radius: 999px;
    font-size: 14px;
    font-weight: 500;
}

.nl-status-badge.completed {
    background: #dcfce7;
    color: #166534;
}

.nl-status-badge.pending {
    background: #fff7ed;
    color: #9a3412;
}

.nl-status-badge.late {
    background: #fee2e2;
    color: #991b1b;
}

.nl-lab-description {
    color: #4b5563;
    margin-bottom: 20px;
}

.nl-lab-objectives {
    margin-bottom: 20px;
}

.nl-lab-objectives h4,
.nl-lab-resources h4 {
    font-size: 16px;
    color: #1a1a1a;
    margin: 0 0 12px 0;
}

.nl-lab-objectives ul {
    margin: 0;
    padding-left: 20px;
}

.nl-lab-objectives li {
    color: #4b5563;
    margin-bottom: 8px;
}

.nl-resources-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 12px;
    margin-bottom: 20px;
}

.nl-resource-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: #f8fafc;
    border-radius: 8px;
}

.nl-resource-info {
    flex: 1;
}

.nl-resource-name {
    display: block;
    font-weight: 500;
    color: #1a1a1a;
}

.nl-resource-meta {
    font-size: 14px;
    color: #6b7280;
}

.nl-download-btn {
    background: none;
    border: none;
    cursor: pointer;
    padding: 4px;
}

.nl-lab-meta {
    display: flex;
    gap: 24px;
    margin-bottom: 20px;
    padding: 16px;
    background: #f8fafc;
    border-radius: 8px;
}

.nl-meta-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.nl-meta-label {
    font-size: 14px;
    color: #6b7280;
}

.nl-meta-value {
    font-weight: 500;
    color: #1a1a1a;
}

.nl-lab-actions {
    display: flex;
    gap: 12px;
}

.nl-button {
    padding: 8px 16px;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.nl-button-primary {
    background: #7c3aed;
    color: white;
    border: none;
}

.nl-button-primary:hover {
    background: #6d28d9;
}

.nl-button-secondary {
    background: #f3f4f6;
    color: #1f2937;
    border: none;
}

.nl-button-secondary:hover {
    background: #e5e7eb;
}

.nl-button-outline {
    background: white;
    color: #7c3aed;
    border: 1px solid #7c3aed;
}

.nl-button-outline:hover {
    background: #f5f3ff;
}

@media (max-width: 768px) {
    .nl-labs-header {
        flex-direction: column;
        gap: 16px;
    }
    
    .nl-lab-actions {
        flex-direction: column;
    }
    
    .nl-button {
        width: 100%;
    }
    
    .nl-resources-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.getElementById('statusFilter').addEventListener('change', function() {
    const status = this.value;
    const labCards = document.querySelectorAll('.nl-lab-card');
    
    labCards.forEach(card => {
        if (status === 'all' || card.dataset.status === status) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});

// Add hover effects to lab cards
document.querySelectorAll('.nl-lab-card').forEach(card => {
    card.addEventListener('mouseenter', () => {
        card.style.transform = 'translateY(-2px)';
        card.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
    });

    card.addEventListener('mouseleave', () => {
        card.style.transform = 'translateY(0)';
        card.style.boxShadow = '0 1px 3px rgba(0, 0, 0, 0.1)';
    });
});

// Get modal elements
const instructionsModal = document.getElementById('instructionsModal');
const submissionModal = document.getElementById('submissionModal');

// Get all View Instructions buttons
document.querySelectorAll('.nl-button-outline').forEach(button => {
    button.addEventListener('click', () => {
        instructionsModal.style.display = 'block';
    });
});

// Get all View Submission buttons
document.querySelectorAll('.nl-button-secondary').forEach(button => {
    button.addEventListener('click', () => {
        submissionModal.style.display = 'block';
    });
});

// Close modals when clicking the close button
document.querySelectorAll('.nl-modal-close').forEach(button => {
    button.addEventListener('click', () => {
        instructionsModal.style.display = 'none';
        submissionModal.style.display = 'none';
    });
});

// Close modals when clicking outside
window.addEventListener('click', (event) => {
    if (event.target === instructionsModal) {
        instructionsModal.style.display = 'none';
    }
    if (event.target === submissionModal) {
        submissionModal.style.display = 'none';
    }
});

// Get additional modal elements
const startLabModal = document.getElementById('startLabModal');
const lab1Instructions = document.getElementById('lab1Instructions');
const lab2Instructions = document.getElementById('lab2Instructions');

// Update instruction modal handling
document.querySelectorAll('.nl-button-outline').forEach(button => {
    button.addEventListener('click', (e) => {
        const labCard = e.target.closest('.nl-lab-card');
        const labTitle = labCard.querySelector('h3').textContent;
        
        // Update modal title
        document.getElementById('instructionsTitle').textContent = labTitle + ' Instructions';
        
        // Show appropriate instructions
        if (labTitle.includes('Lab 1')) {
            lab1Instructions.style.display = 'block';
            lab2Instructions.style.display = 'none';
        } else if (labTitle.includes('Lab 2')) {
            lab1Instructions.style.display = 'none';
            lab2Instructions.style.display = 'block';
        }
        
        instructionsModal.style.display = 'block';
    });
});

// Handle Start Lab button clicks
document.querySelectorAll('.nl-button-primary').forEach(button => {
    button.addEventListener('click', (e) => {
        if (e.target.textContent.trim() === 'Start Lab') {
            const labCard = e.target.closest('.nl-lab-card');
            const labTitle = labCard.querySelector('h3').textContent;
            document.getElementById('startLabTitle').textContent = labTitle;
            startLabModal.style.display = 'block';
            startTimer();
        }
    });
});

// Close start lab modal
document.querySelectorAll('.nl-modal-close').forEach(button => {
    button.addEventListener('click', () => {
        startLabModal.style.display = 'none';
    });
});

// Timer functionality
function startTimer() {
    let time = 7200; // 2 hours in seconds
    const timerDisplay = document.querySelector('.nl-timer');
    
    const timer = setInterval(() => {
        time--;
        const hours = Math.floor(time / 3600);
        const minutes = Math.floor((time % 3600) / 60);
        const seconds = time % 60;
        
        timerDisplay.textContent = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        
        if (time <= 0) {
            clearInterval(timer);
            alert('Time is up! Please submit your work.');
        }
    }, 1000);
}

// Submit lab functionality
document.querySelector('.nl-submit-btn').addEventListener('click', () => {
    const codeFile = document.getElementById('codeFile').files[0];
    const documentationFile = document.getElementById('documentationFile').files[0];
    const comments = document.getElementById('comments').value;
    
    if (!codeFile) {
        alert('Please upload your source code file.');
        return;
    }
    
    // Here you would typically send the files to your server
    alert('Lab submitted successfully!');
    startLabModal.style.display = 'none';
});

// ------------------------------

// Add this helper function at the start of your JavaScript code
function formatDate(date) {
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    return `${months[date.getMonth()]} ${date.getDate()}, ${date.getFullYear()}`;
}

// Update the submission button click handler
document.querySelector('.nl-submit-btn').addEventListener('click', () => {
    const codeFile = document.getElementById('codeFile').files[0];
    const documentationFile = document.getElementById('documentationFile').files[0];
    const comments = document.getElementById('comments').value;
    
    if (!codeFile) {
        alert('Please upload your source code file.');
        return;
    }
    
    // Get the current lab card
    const currentLabTitle = document.getElementById('startLabTitle').textContent;
    const labCard = Array.from(document.querySelectorAll('.nl-lab-card')).find(card => 
        card.querySelector('h3').textContent === currentLabTitle
    );
    
    if (labCard) {
        // Get the due date from the lab card
        const dueDateText = labCard.querySelector('.nl-meta-value').textContent;
        const dueDate = new Date(dueDateText);
        
        // Calculate submission date (2 days before due date)
        const submissionDate = new Date(dueDate);
        submissionDate.setDate(dueDate.getDate() - 2);
        
        // Update the status badge
        const statusBadge = labCard.querySelector('.nl-status-badge');
        statusBadge.textContent = 'Completed';
        statusBadge.className = 'nl-status-badge completed';
        
        // Update the data-status attribute for filtering
        labCard.dataset.status = 'completed';
        
        // Update the button
        const startButton = labCard.querySelector('.nl-button-primary');
        startButton.textContent = 'View Submission';
        startButton.className = 'nl-button nl-button-secondary';
        
        // Update attempts display
        const attemptsDisplay = labCard.querySelector('.nl-meta-value');
        const [current, max] = attemptsDisplay.textContent.split('/');
        attemptsDisplay.textContent = `${parseInt(current) + 1}/${max}`;
        
        // Store the submission date for the modal
        labCard.dataset.submissionDate = formatDate(submissionDate);
    }
    
    // Show success message and close modal
    alert('Lab submitted successfully!');
    startLabModal.style.display = 'none';
    
    // Update submission modal content
    updateSubmissionModal(labCard);
});

// Add this function to update the submission modal
function updateSubmissionModal(labCard) {
    const submissionDateDisplay = document.querySelector('#submissionModal .nl-meta-value');
    if (submissionDateDisplay) {
        submissionDateDisplay.textContent = labCard.dataset.submissionDate;
    }
}

// Update the view submission button click handler
document.addEventListener('click', (e) => {
    if (e.target.classList.contains('nl-button-secondary') && 
        e.target.textContent.trim() === 'View Submission') {
        const submissionModal = document.getElementById('submissionModal');
        const labCard = e.target.closest('.nl-lab-card');
        
        // Update submission date in modal
        const submissionDateDisplay = submissionModal.querySelector('.nl-meta-value');
        if (submissionDateDisplay) {
            submissionDateDisplay.textContent = labCard.dataset.submissionDate;
        }
        
        // Update modal title
        const labTitle = labCard.querySelector('h3').textContent;
        const submissionTitle = submissionModal.querySelector('.nl-modal-header h3');
        submissionTitle.textContent = `${labTitle} - Submission`;
        
        submissionModal.style.display = 'block';
    }
});

</script>
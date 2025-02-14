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
</div>

<style>
.nl-labs-container {
    padding: 20px;
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
</script>
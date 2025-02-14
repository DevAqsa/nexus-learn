<?php
if (!defined('ABSPATH')) exit;

$assessment_data = [
    [
        'name' => 'Assignments',
        'percentage' => 10,
        'color' => '#FF6B6B'
    ],
    [
        'name' => 'Discussion',
        'percentage' => 5,
        'color' => '#FFD93D'
    ],
    [
        'name' => 'FinalTerm',
        'percentage' => 60,
        'color' => '#FF9F43'
    ],
    [
        'name' => 'MidTerm',
        'percentage' => 20,
        'color' => '#4FC3F7'
    ],
    [
        'name' => 'Quizzes',
        'percentage' => 5,
        'color' => '#4DD0E1'
    ]
];
?>

<div class="nl-assessment-wrapper">
    <div class="nl-assessment-header">
        <h2>Assessment Scheme</h2>
    </div>

    <div class="nl-assessment-content">
        <!-- Assessment Grid -->
        <div class="nl-assessment-grid">
            <?php foreach ($assessment_data as $item): ?>
                <div class="nl-assessment-item">
                    <div class="nl-assessment-info">
                        <div class="nl-assessment-color" style="background-color: <?php echo $item['color']; ?>"></div>
                        <span class="nl-assessment-name"><?php echo esc_html($item['name']); ?></span>
                    </div>
                    <div class="nl-assessment-percentage">
                        <span class="nl-percentage-value"><?php echo esc_html($item['percentage']); ?>%</span>
                        <div class="nl-percentage-bar">
                            <div class="nl-percentage-fill" style="width: <?php echo esc_attr($item['percentage']); ?>%; background-color: <?php echo $item['color']; ?>"></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Total Distribution Visualization -->
        <div class="nl-distribution-visual">
            <div class="nl-chart-container">
                <?php
                $offset = 0;
                foreach ($assessment_data as $item):
                    $percentage = $item['percentage'];
                    $dasharray = $percentage * 3.14; // Circumference is 314 (100 * 3.14)
                    $dashoffset = 314 - $dasharray;
                ?>
                    <div class="nl-chart-ring" style="--rotation: <?php echo $offset; ?>deg;">
                        <svg viewBox="0 0 100 100">
                            <circle 
                                class="nl-chart-circle"
                                cx="50" 
                                cy="50" 
                                r="45"
                                stroke="<?php echo $item['color']; ?>"
                                stroke-width="10"
                                stroke-dasharray="<?php echo $dasharray; ?> 314"
                                transform="rotate(-90) translate(-100 0)"
                            />
                        </svg>
                    </div>
                <?php 
                    $offset += ($percentage / 100) * 360;
                endforeach; 
                ?>
                <div class="nl-chart-center">Total</div>
            </div>
        </div>
    </div>
</div>

<style>
.nl-assessment-wrapper {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    margin: 20px;
}

.nl-assessment-header {
    margin-bottom: 30px;
}

.nl-assessment-header h2 {
    font-size: 24px;
    color: #1a1a1a;
    margin: 0;
    font-weight: 500;
}

.nl-assessment-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    align-items: start;
}

.nl-assessment-grid {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.nl-assessment-item {
    display: flex;
    flex-direction: column;
    gap: 8px;
    padding: 15px;
    background: #f8fafc;
    border-radius: 8px;
    transition: transform 0.2s ease;
}

.nl-assessment-item:hover {
    transform: translateX(5px);
}

.nl-assessment-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.nl-assessment-color {
    width: 12px;
    height: 12px;
    border-radius: 3px;
}

.nl-assessment-name {
    font-size: 16px;
    color: #1a1a1a;
    font-weight: 500;
}

.nl-assessment-percentage {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.nl-percentage-value {
    font-size: 24px;
    font-weight: 600;
    color: #1a1a1a;
}

.nl-percentage-bar {
    height: 6px;
    background: #e2e8f0;
    border-radius: 3px;
    overflow: hidden;
}

.nl-percentage-fill {
    height: 100%;
    border-radius: 3px;
    transition: width 1s ease-out;
}

.nl-distribution-visual {
    display: flex;
    justify-content: center;
    align-items: center;
}

.nl-chart-container {
    position: relative;
    width: 300px;
    height: 300px;
}

.nl-chart-ring {
    position: absolute;
    width: 100%;
    height: 100%;
    transform: rotate(var(--rotation));
}

.nl-chart-circle {
    fill: none;
    transition: stroke-dasharray 1s ease-out;
}

.nl-chart-center {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 20px;
    font-weight: 500;
    color: #1a1a1a;
}

@media (max-width: 1024px) {
    .nl-assessment-content {
        grid-template-columns: 1fr;
    }

    .nl-chart-container {
        width: 250px;
        height: 250px;
    }
}

@media (max-width: 768px) {
    .nl-assessment-wrapper {
        padding: 20px;
        margin: 10px;
    }

    .nl-assessment-item {
        padding: 12px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add animation for percentage bars
    setTimeout(() => {
        document.querySelectorAll('.nl-percentage-fill').forEach(fill => {
            fill.style.width = fill.getAttribute('style').match(/width: (.*?);/)[1];
        });
    }, 100);

    // Hover effects for assessment items
    document.querySelectorAll('.nl-assessment-item').forEach(item => {
        item.addEventListener('mouseenter', () => {
            const circle = document.querySelector(`.nl-chart-circle[stroke="${item.querySelector('.nl-assessment-color').style.backgroundColor}"]`);
            if (circle) {
                circle.style.opacity = '0.8';
                circle.style.strokeWidth = '12';
            }
        });

        item.addEventListener('mouseleave', () => {
            const circle = document.querySelector(`.nl-chart-circle[stroke="${item.querySelector('.nl-assessment-color').style.backgroundColor}"]`);
            if (circle) {
                circle.style.opacity = '1';
                circle.style.strokeWidth = '10';
            }
        });
    });
});
</script>
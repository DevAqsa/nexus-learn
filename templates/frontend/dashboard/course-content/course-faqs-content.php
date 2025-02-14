<?php
if (!defined('ABSPATH')) exit;

// Dummy FAQ data - replace with actual data from your database
$faqs = [
    [
        'question' => 'Define Abstract Base Class?',
        'answer' => 'Your answer content here...'
    ],
    [
        'question' => 'What is Instance?',
        'answer' => 'A class is a definition of a set of data and member functions. When space for the data is actually allocated, we say that a member of the class has been instantiated. The instantiation is called an instance of the class. Each instance has its own set of data (there is also a mechanism in C++ to define data that is only allocated once per class, and shared amongst all instances of the class).'
    ],
    [
        'question' => 'Define Concrete Class?',
        'answer' => 'Your answer content here...'
    ],
    [
        'question' => 'Name the Properties of a Binary Tree?',
        'answer' => 'Your answer content here...'
    ],
    [
        'question' => 'Define Binary Search Trees (BST) with example?',
        'answer' => 'Your answer content here...'
    ],
    [
        'question' => 'Define Heap?',
        'answer' => 'Your answer content here...'
    ],
    [
        'question' => 'Define Binary Trees?',
        'answer' => 'Your answer content here...'
    ],
    [
        'question' => 'How can we differentiate among database, data communication and data structure?',
        'answer' => 'Your answer content here...'
    ],
    [
        'question' => 'Describe 32bit programming and 16 bit programming and difference between them? Is there 64 bit programming available?',
        'answer' => 'Your answer content here...'
    ]
];
?>

<div class="nl-faq-container">
    <!-- FAQ Header -->
    <div class="nl-faq-header">
        <h1>FAQs</h1>
        <div class="nl-search-container">
            <input type="text" id="faqSearch" class="nl-search-input" placeholder="Search for..." />
            <button class="nl-search-button">
                <span class="dashicons dashicons-search"></span>
            </button>
        </div>
    </div>

    <!-- FAQ List -->
    <div class="nl-faq-list">
        <?php foreach ($faqs as $index => $faq): ?>
            <div class="nl-faq-item">
                <button class="nl-faq-question" onclick="toggleFaq(<?php echo $index; ?>)">
                    <span class="nl-question-icon">
                        <span class="dashicons dashicons-editor-help"></span>
                    </span>
                    <?php echo esc_html($faq['question']); ?>
                    <span class="nl-expand-icon">
                        <span class="dashicons dashicons-plus"></span>
                    </span>
                </button>
                <div class="nl-faq-answer" id="faq-answer-<?php echo $index; ?>">
                    <?php echo wp_kses_post($faq['answer']); ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
.nl-faq-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.nl-faq-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 20px;
}

.nl-faq-header h1 {
    font-size: 24px;
    margin: 0;
    color: #333;
}

.nl-search-container {
    display: flex;
    align-items: center;
    gap: 10px;
    max-width: 400px;
    width: 100%;
}

.nl-search-input {
    flex: 1;
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
}

.nl-search-button {
    background: #7c3aed;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.nl-faq-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.nl-faq-item {
    border: 1px solid #eee;
    border-radius: 8px;
    overflow: hidden;
    background: white;
}

.nl-faq-question {
    width: 100%;
    display: flex;
    align-items: center;
    padding: 20px;
    background: none;
    border: none;
    cursor: pointer;
    text-align: left;
    font-size: 16px;
    color: #333;
    gap: 15px;
}

.nl-question-icon {
    color: #7c3aed;
    display: flex;
    align-items: center;
}

.nl-expand-icon {
    margin-left: auto;
    color: #7c3aed;
    display: flex;
    align-items: center;
}

.nl-faq-answer {
    display: none;
    padding: 0 20px 20px 60px;
    color: #333;
    line-height: 1.6;
    font-size: 16px;

}

.nl-faq-item.active .nl-faq-answer {
    display: block;
}

.nl-faq-item.active .nl-expand-icon .dashicons-plus::before {
    content: "\f460"; /* WordPress dashicons minus icon */
}

@media (max-width: 768px) {
    .nl-faq-header {
        flex-direction: column;
        align-items: stretch;
    }
    
    .nl-search-container {
        max-width: 100%;
    }
}
</style>

<script>
function toggleFaq(index) {
    const faqItem = document.getElementById(`faq-answer-${index}`).parentElement;
    const isActive = faqItem.classList.contains('active');
    
    // Close all FAQs
    document.querySelectorAll('.nl-faq-item').forEach(item => {
        item.classList.remove('active');
    });
    
    // Open clicked FAQ if it wasn't already open
    if (!isActive) {
        faqItem.classList.add('active');
    }
}

// Search functionality
document.getElementById('faqSearch').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const faqItems = document.querySelectorAll('.nl-faq-item');
    
    faqItems.forEach(item => {
        const question = item.querySelector('.nl-faq-question').textContent.toLowerCase();
        if (question.includes(searchTerm)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
});
</script>
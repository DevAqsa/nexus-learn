<?php
if (!defined('ABSPATH')) exit;

// Dummy glossary data - replace with actual data from your database
$glossary_terms = [
    [
        'term' => 'Abstract Data Type',
        'definition' => 'A set of data values and associated operations that are precisely specified independent of any particular implementation. Also known as ADT'
    ],
    [
        'term' => 'Algorithm',
        'definition' => 'A computable set of steps to achieve a desired result.'
    ],
    [
        'term' => 'Alias',
        'definition' => 'An alternative name for the same object. A "nickname".'
    ],
    [
        'term' => 'Ancestor',
        'definition' => 'A parent of a node in a tree, the parent of the parent, etc.'
    ]
];

// Get all letters for navigation
$letters = range('A', 'Z');
?>

<div class="nl-glossary-container">
    <!-- Header -->
    <div class="nl-glossary-header">
        <h1>Glossary</h1>
        
    </div>

    <!-- Search Bar -->
    <div class="nl-search-wrapper">
        <input type="text" id="glossarySearch" class="nl-search-input" placeholder="Search for..." />
        <button class="nl-search-button">
            <span class="dashicons dashicons-search"></span>
        </button>
    </div>

    <!-- Alphabet Navigation -->
    <div class="nl-alphabet-nav">
        <?php foreach ($letters as $letter): ?>
            <a href="#<?php echo $letter; ?>" class="nl-letter-link"><?php echo $letter; ?></a>
        <?php endforeach; ?>
        <a href="#" class="nl-letter-link">All</a>
    </div>

    <!-- Glossary Content -->
    <div class="nl-glossary-content">
        <?php foreach ($glossary_terms as $term): ?>
            <div class="nl-term-item">
                <div class="nl-term-icon">t</div>
                <div class="nl-term-content">
                    <h3><?php echo esc_html($term['term']); ?></h3>
                    <p><?php echo esc_html($term['definition']); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
.nl-glossary-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

.nl-glossary-header {
    
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.nl-glossary-header h1 {
    color: black;
    margin: 0;
    font-size: 24px;
    font-weight: 500;
}

.nl-back-button {
    color: white;
    text-decoration: none;
    font-size: 16px;
}

.nl-search-wrapper {
    padding: 0 20px;
    margin-bottom: 20px;
    display: flex;
    gap: 10px;
}

.nl-search-input {
    flex: 1;
    padding: 12px 20px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    font-size: 16px;
}

.nl-search-button {
    background: #7c3aed;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 6px;
    cursor: pointer;
}

.nl-alphabet-nav {
    padding: 10px 20px;
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 20px;
}

.nl-letter-link {
    color: #7c3aed;
    text-decoration: none;
    font-size: 16px;
}

.nl-letter-link:hover {
    text-decoration: underline;
}

.nl-glossary-content {
    padding: 0 20px;
}

.nl-term-item {
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
    align-items: flex-start;
}

.nl-term-icon {
    width: 40px;
    height: 40px;
    background-color: #fff3e0;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: #333;
    flex-shrink: 0;
}

.nl-term-content {
    flex: 1;
}

.nl-term-content h3 {
    margin: 0 0 10px 0;
    font-size: 18px;
    color: #333;
    font-weight: 500;
}

.nl-term-content p {
    margin: 0;
    color: #4a5568;
    line-height: 1.6;
}

@media (max-width: 768px) {
    .nl-search-wrapper {
        flex-direction: column;
    }
    
    .nl-search-button {
        width: 100%;
    }
    
    .nl-alphabet-nav {
        justify-content: center;
    }
}
</style>

<script>
document.getElementById('glossarySearch').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const termItems = document.querySelectorAll('.nl-term-item');
    
    termItems.forEach(item => {
        const term = item.querySelector('h3').textContent.toLowerCase();
        const definition = item.querySelector('p').textContent.toLowerCase();
        
        if (term.includes(searchTerm) || definition.includes(searchTerm)) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
});

document.querySelectorAll('.nl-letter-link').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const letter = this.textContent;
        
        const termItems = document.querySelectorAll('.nl-term-item');
        termItems.forEach(item => {
            if (letter === 'All') {
                item.style.display = 'flex';
            } else {
                const termText = item.querySelector('h3').textContent;
                if (termText.charAt(0).toUpperCase() === letter) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            }
        });
    });
});
</script>
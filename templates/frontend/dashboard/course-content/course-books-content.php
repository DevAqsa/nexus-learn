<?php
if (!defined('ABSPATH')) exit;

// Dummy book data - replace with actual data from your database
$books = [
    [
        'title' => 'Data Structures and Algorithm Analysis in C++',
        'citation' => 'Weiss (1998)',
        'author' => 'Mark Allen Weiss',
        'edition' => '2nd',
        'publisher' => 'Addison Wesley'
    ],
    // Add more books as needed
];
?>

<div class="nl-books-container">
    <!-- Header -->
    <div class="nl-books-header">
        <h1>Books</h1>
       
    </div>

    <!-- Search Bar -->
    <div class="nl-search-wrapper">
        <input type="text" id="bookSearch" class="nl-search-input" placeholder="Search for..." />
        <button class="nl-search-button">
            <span class="dashicons dashicons-search"></span>
        </button>
    </div>

    <!-- Books List -->
    <div class="nl-books-list">
        <?php foreach ($books as $book): ?>
            <div class="nl-book-item">
                <h2 class="nl-book-title"><?php echo esc_html($book['title']); ?></h2>
                <div class="nl-book-details">
                    <div class="nl-detail-row">
                        <span class="nl-detail-label">Citation:</span>
                        <span class="nl-detail-value"><?php echo esc_html($book['citation']); ?></span>
                    </div>
                    <div class="nl-detail-row">
                        <span class="nl-detail-label">Author:</span>
                        <span class="nl-detail-value"><?php echo esc_html($book['author']); ?></span>
                    </div>
                    <div class="nl-detail-row">
                        <span class="nl-detail-label">Edition:</span>
                        <span class="nl-detail-value"><?php echo esc_html($book['edition']); ?></span>
                    </div>
                    <div class="nl-detail-row">
                        <span class="nl-detail-label">Publisher:</span>
                        <span class="nl-detail-value"><?php echo esc_html($book['publisher']); ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
.nl-books-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

.nl-books-header {
   
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    position: relative;
}

.nl-books-header h1 {
    color: Black;
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
    margin-bottom: 30px;
    display: flex;
    gap: 10px;
}

.nl-search-input {
    flex: 1;
    padding: 12px 20px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    font-size: 16px;
    max-width: 100%;
}

.nl-search-button {
    background: #7c3aed;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 6px;
    cursor: pointer;
}

.nl-books-list {
    padding: 0 20px;
}

.nl-book-item {
    background: white;
    border-radius: 8px;
    padding: 24px;
    margin-bottom: 20px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.nl-book-title {
    font-size: 20px;
    color: #1a202c;
    margin: 0 0 20px 0;
    font-weight: 500;
}

.nl-book-details {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.nl-detail-row {
    display: flex;
    gap: 20px;
}

.nl-detail-label {
    min-width: 100px;
    color: #4a5568;
    font-weight: 500;
}

.nl-detail-value {
    color: #1a202c;
}

@media (max-width: 768px) {
    .nl-search-wrapper {
        flex-direction: column;
    }
    
    .nl-search-button {
        width: 100%;
    }
    
    .nl-detail-row {
        flex-direction: column;
        gap: 4px;
    }
    
    .nl-detail-label {
        min-width: auto;
    }
}
</style>

<script>
document.getElementById('bookSearch').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const bookItems = document.querySelectorAll('.nl-book-item');
    
    bookItems.forEach(item => {
        const title = item.querySelector('.nl-book-title').textContent.toLowerCase();
        const details = item.querySelector('.nl-book-details').textContent.toLowerCase();
        
        if (title.includes(searchTerm) || details.includes(searchTerm)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
});
</script>
jQuery(document).ready(function($) {
    // Initialize Charts
    const scoreCtx = document.getElementById('scoreDistribution').getContext('2d');
    const questionCtx = document.getElementById('questionAnalysis').getContext('2d');
    
    // Score Distribution Chart
    new Chart(scoreCtx, {
        type: 'bar',
        data: {
            labels: ['0-10%', '11-20%', '21-30%', '31-40%', '41-50%', '51-60%', '61-70%', '71-80%', '81-90%', '91-100%'],
            datasets: [{
                label: 'Number of Students',
                data: window.scoreDistribution || [],
                backgroundColor: '#2271b1'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Score Distribution'
                }
            }
        }
    });
    
    // Question Analysis Chart
    new Chart(questionCtx, {
        type: 'horizontalBar',
        data: {
            labels: window.questionLabels || [],
            datasets: [{
                label: 'Correct Answer Rate (%)',
                data: window.questionData || [],
                backgroundColor: '#2271b1'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Question Analysis'
                }
            }
        }
    });
    
    // Handle filter changes
    $('#quiz-selector, #date-range').on('change', function() {
        const quizId = $('#quiz-selector').val();
        const dateRange = $('#date-range').val();
        window.location.href = `?page=nl-quiz-analytics&quiz_id=${quizId}&range=${dateRange}`;
    });
});
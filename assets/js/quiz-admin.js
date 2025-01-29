jQuery(document).ready(function($) {
    const questionList = $('.nl-question-list');
    const addQuestionBtn = $('.nl-add-question');
    const typeSelector = $('.nl-question-type-selector');
    let questionCounter = $('.nl-question').length;

    // Show question type selector when Add Question button is clicked
    addQuestionBtn.on('click', function() {
        typeSelector.slideDown();
        typeSelector.css('display', 'flex');
    });

    // Handle question type selection
    typeSelector.find('button').on('click', function() {
        const type = $(this).data('type');
        addNewQuestion(type);
        typeSelector.slideUp();
    });

    // Function to add new question
    function addNewQuestion(type) {
        questionCounter++;
        const questionId = 'new_' + questionCounter;
        
        let template = `
            <div class="nl-question" data-id="${questionId}">
                <div class="nl-question-header">
                    <span class="nl-question-type">${formatQuestionType(type)}</span>
                    <button type="button" class="nl-remove-question">&times;</button>
                </div>
                <div class="nl-question-content">
                    <input type="hidden" name="questions[${questionId}][type]" value="${type}">
                    <div class="nl-question-text">
                        <label>Question Text:</label>
                        <textarea name="questions[${questionId}][text]" class="widefat" rows="3"></textarea>
                    </div>
                    ${getQuestionTypeFields(type, questionId)}
                    <div class="nl-question-points">
                        <label>Points:
                            <input type="number" name="questions[${questionId}][points]" value="1" min="1">
                        </label>
                    </div>
                </div>
            </div>
        `;

        questionList.append(template);
        initializeQuestionEvents(questionId);
    }

    // Format question type for display
    function formatQuestionType(type) {
        return type.split('_').map(word => 
            word.charAt(0).toUpperCase() + word.slice(1)
        ).join(' ');
    }

    // Get fields based on question type
    function getQuestionTypeFields(type, questionId) {
        switch(type) {
            case 'multiple_choice':
                return `
                    <div class="nl-question-options">
                        <label>Options:</label>
                        <div class="nl-options-list">
                            <div class="nl-option-item">
                                <input type="radio" name="questions[${questionId}][correct]" value="0">
                                <input type="text" name="questions[${questionId}][options][]" class="widefat" placeholder="Option 1">
                                <button type="button" class="nl-remove-option">&times;</button>
                            </div>
                        </div>
                        <button type="button" class="button nl-add-option">Add Option</button>
                    </div>
                `;

            case 'true_false':
                return `
                    <div class="nl-question-options">
                        <label>Correct Answer:</label>
                        <select name="questions[${questionId}][correct_answer]">
                            <option value="true">True</option>
                            <option value="false">False</option>
                        </select>
                    </div>
                `;

            case 'essay':
                return `
                    <div class="nl-question-options">
                        <label>Model Answer (Optional):</label>
                        <textarea name="questions[${questionId}][model_answer]" class="widefat" rows="3"></textarea>
                    </div>
                `;

            case 'matching':
                return `
                    <div class="nl-matching-pairs">
                        <div class="nl-matching-pair">
                            <input type="text" name="questions[${questionId}][left][]" placeholder="Left item">
                            <span class="nl-matching-separator">→</span>
                            <input type="text" name="questions[${questionId}][right][]" placeholder="Right item">
                            <button type="button" class="nl-remove-pair">&times;</button>
                        </div>
                        <button type="button" class="button nl-add-pair">Add Matching Pair</button>
                    </div>
                `;

            case 'fill_blanks':
                return `
                    <div class="nl-question-options">
                        <p class="description">Use [blank] to indicate where blanks should appear.</p>
                        <label>Correct Answers:</label>
                        <div class="nl-blanks-list">
                            <div class="nl-blank-answer">
                                <input type="text" name="questions[${questionId}][answers][]" class="widefat" placeholder="Answer">
                                <button type="button" class="nl-remove-blank">&times;</button>
                            </div>
                        </div>
                        <button type="button" class="button nl-add-blank">Add Answer</button>
                    </div>
                `;
        }
    }

    // Initialize events for new questions
    function initializeQuestionEvents(questionId) {
        const question = $(`.nl-question[data-id="${questionId}"]`);

        // Remove question
        question.find('.nl-remove-question').on('click', function() {
            if (confirm('Are you sure you want to remove this question?')) {
                $(this).closest('.nl-question').remove();
            }
        });

        // Add multiple choice option
        question.find('.nl-add-option').on('click', function() {
            const optionsList = $(this).siblings('.nl-options-list');
            const optionCount = optionsList.children().length;
            const optionTemplate = `
                <div class="nl-option-item">
                    <input type="radio" name="questions[${questionId}][correct]" value="${optionCount}">
                    <input type="text" name="questions[${questionId}][options][]" class="widefat" placeholder="Option ${optionCount + 1}">
                    <button type="button" class="nl-remove-option">&times;</button>
                </div>
            `;
            optionsList.append(optionTemplate);
        });

        // Remove option
        question.on('click', '.nl-remove-option', function() {
            $(this).closest('.nl-option-item').remove();
        });

        // Add matching pair
        question.find('.nl-add-pair').on('click', function() {
            const template = `
                <div class="nl-matching-pair">
                    <input type="text" name="questions[${questionId}][left][]" placeholder="Left item">
                    <span class="nl-matching-separator">→</span>
                    <input type="text" name="questions[${questionId}][right][]" placeholder="Right item">
                    <button type="button" class="nl-remove-pair">&times;</button>
                </div>
            `;
            $(this).before(template);
        });

        // Remove matching pair
        question.on('click', '.nl-remove-pair', function() {
            $(this).closest('.nl-matching-pair').remove();
        });

        // Add blank answer
        question.find('.nl-add-blank').on('click', function() {
            const template = `
                <div class="nl-blank-answer">
                    <input type="text" name="questions[${questionId}][answers][]" class="widefat" placeholder="Answer">
                    <button type="button" class="nl-remove-blank">&times;</button>
                </div>
            `;
            question.find('.nl-blanks-list').append(template);
        });

        // Remove blank answer
        question.on('click', '.nl-remove-blank', function() {
            $(this).closest('.nl-blank-answer').remove();
        });
    }

    // Make questions sortable
    questionList.sortable({
        handle: '.nl-question-header',
        update: function() {
            questionList.find('.nl-question').each(function(index) {
                $(this).find('input[name*="[order]"]').val(index);
            });
        }
    });
});
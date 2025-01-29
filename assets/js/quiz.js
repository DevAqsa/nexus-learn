jQuery(document).ready(function($) {
    class QuizManager {
        constructor(quizElement) {
            this.quiz = $(quizElement);
            this.quizId = this.quiz.data('id');
            this.timer = this.quiz.find('.nl-quiz-timer');
            this.form = this.quiz.find('.nl-quiz-form');
            this.questions = this.quiz.find('.nl-quiz-question');
            this.currentQuestion = 0;
            this.answers = {};
            this.startTime = new Date().toISOString();

            this.initializeTimer();
            this.initializeEvents();
            this.setupNavigation();
        }

        initializeTimer() {
            if (this.timer.length) {
                this.timeLeft = parseInt(this.timer.data('time'));
                this.startTimer();
            }
        }

        startTimer() {
            this.timerInterval = setInterval(() => {
                this.timeLeft--;
                if (this.timeLeft <= 0) {
                    clearInterval(this.timerInterval);
                    this.submitQuiz();
                } else {
                    this.updateTimerDisplay();
                }
            }, 1000);
        }

        updateTimerDisplay() {
            const minutes = Math.floor(this.timeLeft / 60);
            const seconds = this.timeLeft % 60;
            const display = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            this.timer.find('.nl-timer-display').text(display);

            if (this.timeLeft < 60) {
                this.timer.addClass('nl-timer-warning');
            }
        }

        initializeEvents() {
            this.form.on('submit', (e) => {
                e.preventDefault();
                this.submitQuiz();
            });

            // Save answers as they're entered
            this.form.on('change', 'input, select, textarea', (e) => {
                const input = $(e.target);
                const questionId = input.closest('.nl-quiz-question').data('id');
                
                if (input.is(':radio')) {
                    this.answers[questionId] = input.val();
                } else if (input.is('select')) {
                    this.answers[questionId] = this.answers[questionId] || {};
                    this.answers[questionId][input.attr('name').match(/\[(\d+)\]$/)[1]] = input.val();
                } else {
                    this.answers[questionId] = input.val();
                }
            });
        }

        setupNavigation() {
            this.questions.hide().first().show();
            
            const nav = $('<div class="nl-question-nav"></div>');
            const prevBtn = $('<button type="button" class="nl-nav-btn nl-prev">Previous</button>');
            const nextBtn = $('<button type="button" class="nl-nav-btn nl-next">Next</button>');
            
            nav.append(prevBtn).append(nextBtn);
            this.form.append(nav);

            const progressBar = $('<div class="nl-progress-bar"><div class="nl-progress-fill"></div></div>');
            this.quiz.find('.nl-quiz-header').append(progressBar);

            prevBtn.on('click', () => this.navigateQuestions('prev'));
            nextBtn.on('click', () => this.navigateQuestions('next'));

            this.updateNavigation();
        }

        navigateQuestions(direction) {
            this.questions.eq(this.currentQuestion).hide();
            
            if (direction === 'next') {
                this.currentQuestion = Math.min(this.currentQuestion + 1, this.questions.length - 1);
            } else {
                this.currentQuestion = Math.max(this.currentQuestion - 1, 0);
            }

            this.questions.eq(this.currentQuestion).show();
            this.updateNavigation();
        }

        updateNavigation() {
            const progress = ((this.currentQuestion + 1) / this.questions.length) * 100;
            this.quiz.find('.nl-progress-fill').css('width', `${progress}%`);

            const prev = this.quiz.find('.nl-prev');
            const next = this.quiz.find('.nl-next');

            prev.prop('disabled', this.currentQuestion === 0);
            next.prop('disabled', this.currentQuestion === this.questions.length - 1);
        }

        submitQuiz() {
            if (this.isSubmitting) return;
            this.isSubmitting = true;

            this.quiz.addClass('nl-loading');
            clearInterval(this.timerInterval);

            $.ajax({
                url: nlQuiz.ajaxurl,
                method: 'POST',
                data: {
                    action: 'nl_submit_quiz',
                    nonce: nlQuiz.nonce,
                    quiz_id: this.quizId,
                    answers: this.answers,
                    start_time: this.startTime,
                    time_spent: this.timer.length ? 
                        (parseInt(this.timer.data('time')) - this.timeLeft) : null
                },
                success: (response) => {
                    if (response.success) {
                        if (response.data.redirect_url) {
                            window.location.href = response.data.redirect_url;
                        } else {
                            this.showResults(response.data);
                        }
                    }
                },
                error: () => {
                    alert('Error submitting quiz. Please try again.');
                    this.isSubmitting = false;
                    this.quiz.removeClass('nl-loading');
                }
            });
        }

        showResults(results) {
            this.quiz.removeClass('nl-loading');
            this.form.hide();

            const resultsHtml = `
                <div class="nl-quiz-results">
                    <h3>Quiz Results</h3>
                    <div class="nl-score">${results.score}%</div>
                    <div class="nl-points">
                        Points: ${results.points_earned}/${results.total_points}
                    </div>
                    <div class="nl-pass-fail ${results.score >= 70 ? 'nl-pass' : 'nl-fail'}">
                        ${results.score >= 70 ? 'Passed' : 'Failed'}
                    </div>
                </div>
            `;

            this.quiz.append(resultsHtml);
        }
    }

    // Initialize all quizzes on the page
    $('.nl-quiz').each(function() {
        new QuizManager(this);
    });
});
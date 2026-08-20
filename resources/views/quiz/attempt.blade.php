<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mengerjakan Quiz - {{ $attempt->quiz->judul }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <!-- Header -->
    <header class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('img/logo.svg') }}" alt="SDN Susukan 08 Pagi" class="h-14 w-auto">
                    <div>
                        <h1 class="text-xl font-bold">{{ $attempt->quiz->judul }}</h1>
                        <p class="text-blue-100 text-sm">Pilihan Ganda</p>
                    </div>
                </div>
                
                <!-- Timer -->
                <div class="flex items-center space-x-6">
                    <div class="bg-red-600 bg-opacity-20 border-2 border-red-400 px-6 py-3 rounded-xl">
                        <p class="text-red-100 text-xs uppercase tracking-wide mb-1">Sisa Waktu</p>
                        <p class="text-4xl font-bold text-red-300" id="timer">{{ gmdate('i:s', $remainingTime) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Sidebar - Question List -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-6 sticky top-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Daftar Soal</h3>
                    <div class="space-y-2">
                        @foreach($questions as $index => $question)
                        <button 
                            type="button"
                            class="question-nav-btn w-full p-3 rounded-lg font-semibold transition text-center text-sm
                                {{ $index === 0 ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                            data-question-index="{{ $index }}"
                        >
                            <span class="question-nav-number">Soal {{ $index + 1 }}</span>
                            <span class="question-nav-status ml-2">
                                @if(isset($answers[$question->id]))
                                    ✓
                                @else
                                    ⊙
                                @endif
                            </span>
                        </button>
                        @endforeach
                    </div>
                    
                    <!-- Progress Summary -->
                    <div class="mt-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200">
                        <p class="text-sm text-gray-700">
                            <span class="font-bold text-blue-600" id="answeredCount">0</span> 
                            <span class="text-gray-600">dari {{ count($questions) }} terjawab</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-3">
                <!-- Question Container -->
                <div class="bg-white rounded-xl shadow-lg p-8 mb-6">
                    <!-- Question Header -->
                    <div class="mb-8">
                        <span class="inline-block px-4 py-2 bg-blue-100 text-blue-700 rounded-lg font-semibold text-sm mb-4">
                            Soal <span id="currentQuestion">1</span> dari <span id="totalQuestions">{{ count($questions) }}</span>
                        </span>
                    <h2 class="text-2xl font-bold text-gray-900" id="questionText"></h2>
                    </div>

                    <!-- Question Text -->
                    <div id="questionContainer">
                        @foreach($questions as $index => $question)
                        <div class="question-block {{ $index === 0 ? '' : 'hidden' }}"
                            data-question-id="{{ $question->id }}"
                            data-question-index="{{ $index }}"
                            data-question-text="{{ $question->pertanyaan }}">
                            <!-- Answer Options -->
                            <div class="space-y-3">
                                @php
                                    $labels = ['A', 'B', 'C', 'D'];
                                @endphp
                                @foreach($question->pilihan as $key => $pilihan)
                                <button 
                                    type="button"
                                    class="answer-option w-full p-4 border-2 rounded-xl text-left transition transform hover:scale-105 flex items-start space-x-4
                                        {{ isset($answers[$question->id]) && $answers[$question->id] == $key ? 'border-green-500 bg-green-50 shadow-md' : 'border-gray-300 bg-white hover:border-gray-400' }}"
                                    data-question-id="{{ $question->id }}"
                                    data-answer="{{ $labels[$key] }}"
                                >
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full font-bold text-sm flex-shrink-0
                                        {{ isset($answers[$question->id]) && $answers[$question->id] == $key ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-700' }}">
                                        {{ $labels[$key]}}
                                    </span>
                                    <span class="text-gray-900 font-medium flex-1 text-left pt-1">{{ $pilihan }}</span>
                                </button>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex items-center justify-between gap-4">
                    <button 
                        type="button" 
                        id="prevButton" 
                        class="px-8 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-bold transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-2"
                        disabled
                    >
                        <span>← Sebelumnya</span>
                    </button>
                    
                    <div class="flex-1"></div>
                    
                    <button 
                        type="button" 
                        id="nextButton" 
                        class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold transition flex items-center space-x-2"
                    >
                        <span>Selanjutnya →</span>
                    </button>
                    <button 
                        type="button" 
                        id="finishButton" 
                        class="px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:shadow-lg font-bold transition hidden flex items-center space-x-2"
                    >
                        <span>✓ Selesai</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentQuestionIndex = 0;
        const questions = document.querySelectorAll('.question-block');
        const totalQuestions = questions.length;
        let remainingTime = Number('{{ $remainingTime }}');
        const timerElement = document.getElementById('timer');
        const currentQuestionSpan = document.getElementById('currentQuestion');
        const prevButton = document.getElementById('prevButton');
        const nextButton = document.getElementById('nextButton');
        const finishButton = document.getElementById('finishButton');
        const answeredCountSpan = document.getElementById('answeredCount');
        const answers = JSON.parse(`{!! addslashes(json_encode($answers)) !!}`);

        // Show first question
        showQuestion(0);

        // Timer
        function updateTimer() {
            if (remainingTime <= 0) {
                finishQuiz();
                return;
            }
            
            const minutes = Math.floor(remainingTime / 60);
            const seconds = Math.floor(remainingTime % 60);
            
            timerElement.textContent =
                String(minutes).padStart(2, '0') + ':' +
                String(seconds).padStart(2, '0');

            remainingTime = Math.floor(remainingTime - 1);
        }

        setInterval(updateTimer, 1000);
        updateTimer();

        // Show question
        function showQuestion(index) {
            questions.forEach((q, i) => {
                q.classList.toggle('hidden', i !== index);
            });
            
            currentQuestionIndex = index;
            currentQuestionSpan.textContent = index + 1;

            const activeQuestion = questions[index];
            document.getElementById('questionText').textContent =
                activeQuestion.dataset.questionText;
            
            // Update navigation buttons
            prevButton.disabled = index === 0;
            nextButton.classList.toggle('hidden', index === totalQuestions - 1);
            finishButton.classList.toggle('hidden', index !== totalQuestions - 1);
            
            // Update question nav buttons styling
            document.querySelectorAll('.question-nav-btn').forEach((btn, i) => {
                if (i === index) {
                    btn.classList.add('bg-blue-600', 'text-white');
                    btn.classList.remove('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
                } else {
                    btn.classList.remove('bg-blue-600', 'text-white');
                    btn.classList.add('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
                }
            });
            
            // Highlight selected answer
            const questionId = questions[index].dataset.questionId;
            highlightAnswer(questionId);
        }

        // Highlight selected answer
        function highlightAnswer(questionId) {
            const questionBlock = document.querySelector(`[data-question-id="${questionId}"]`);
            const answerOptions = questionBlock.querySelectorAll('.answer-option');
            
            answerOptions.forEach(option => {
                const answer = option.dataset.answer;
                if (answers[questionId] === answer) {
                    option.classList.add('border-green-500', 'bg-green-50', 'shadow-md');
                    option.classList.remove('border-gray-300', 'bg-white');
                    option.querySelector('span:first-child').classList.add('bg-green-500', 'text-white');
                    option.querySelector('span:first-child').classList.remove('bg-gray-200', 'text-gray-700');
                } else {
                    option.classList.remove('border-green-500', 'bg-green-50', 'shadow-md');
                    option.classList.add('border-gray-300', 'bg-white');
                    option.querySelector('span:first-child').classList.remove('bg-green-500', 'text-white');
                    option.querySelector('span:first-child').classList.add('bg-gray-200', 'text-gray-700');
                }
            });
        }

        // Answer selection
        document.querySelectorAll('.answer-option').forEach(button => {
            button.addEventListener('click', function() {
                const questionId = this.dataset.questionId;
                const answer = this.dataset.answer;
                
                // Update UI
                const questionBlock = document.querySelector(`[data-question-id="${questionId}"]`);
                questionBlock.querySelectorAll('.answer-option').forEach(opt => {
                    opt.classList.remove('border-green-500', 'bg-green-50', 'shadow-md');
                    opt.classList.add('border-gray-300', 'bg-white');
                    opt.querySelector('span:first-child').classList.remove('bg-green-500', 'text-white');
                    opt.querySelector('span:first-child').classList.add('bg-gray-200', 'text-gray-700');
                });
                
                this.classList.add('border-green-500', 'bg-green-50', 'shadow-md');
                this.classList.remove('border-gray-300', 'bg-white');
                this.querySelector('span:first-child').classList.add('bg-green-500', 'text-white');
                this.querySelector('span:first-child').classList.remove('bg-gray-200', 'text-gray-700');
                
                // Save answer
                answers[questionId] = answer;
                
                // Update nav button status
                updateNavButtonStatus(questionId);
                updateAnsweredCount();
                
                // Save to server
                fetch(`{{ route('quiz.submit-answer', $attempt->id) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        question_id: questionId,
                        answer: answer
                    })
                });
            });
        });

        // Update nav button status to show checkmark
        function updateNavButtonStatus(questionId) {
            const questionIndex = Array.from(questions).findIndex(q => q.dataset.questionId === String(questionId));
            const navBtn = document.querySelector(`.question-nav-btn[data-question-index="${questionIndex}"]`);
            if (navBtn) {
                const statusSpan = navBtn.querySelector('.question-nav-status');
                statusSpan.textContent = '✓';
            }
        }

        // Update answered count
        function updateAnsweredCount() {
            const answeredCount = Object.keys(answers).length;
            answeredCountSpan.textContent = answeredCount;
        }

        // Question nav buttons
        document.querySelectorAll('.question-nav-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const index = parseInt(this.dataset.questionIndex);
                showQuestion(index);
            });
        });

        // Navigation
        prevButton.addEventListener('click', () => {
            if (currentQuestionIndex > 0) {
                showQuestion(currentQuestionIndex - 1);
            }
        });

        nextButton.addEventListener('click', () => {
            if (currentQuestionIndex < totalQuestions - 1) {
                showQuestion(currentQuestionIndex + 1);
            }
        });

        finishButton.addEventListener('click', () => {
            finishQuiz();
        });

        function finishQuiz() {
            if (confirm('Apakah Anda yakin ingin menyelesaikan quiz?')) {
                document.getElementById('finish-form').submit();
            }
        }

        // Create hidden form for finish
        const finishForm = document.createElement('form');
        finishForm.id = 'finish-form';
        finishForm.method = 'POST';
        finishForm.action = '{{ route("quiz.finish", $attempt->id) }}';
        finishForm.innerHTML = '@csrf';
        document.body.appendChild(finishForm);

        // Initialize answered count
        updateAnsweredCount();
    </script>
</body>
</html>

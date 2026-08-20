@php
use Illuminate\Support\Str;
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Soal - {{ $quiz->judul }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="bg-green-600 text-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('landing') }}" class="flex items-center space-x-4 hover:opacity-90 transition">
                    <img src="{{ asset('img/logo.svg') }}" alt="SDN Susukan 08 Pagi" class="h-14 w-auto">
                    <span class="text-lg font-semibold hidden sm:inline">SDN Susukan 08 Pagi</span>
                </a>
                <div class="flex items-center space-x-4">
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-white/90 hover:text-white text-sm">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb -->
        <div class="mb-8">
            <a href="{{ route('teacher.dashboard') }}" class="text-purple-600 hover:text-purple-700 font-semibold text-sm">← Kembali ke Dashboard</a>
            <div class="mt-2">
                <h1 class="text-4xl font-bold text-gray-900">Kelola Soal</h1>
                <p class="text-gray-600 mt-2">Kuis: <span class="font-semibold">{{ $quiz->judul }}</span></p>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Total Soal</p>
                <p class="text-3xl font-bold text-purple-600">{{ $quiz->questions->count() }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Durasi Kuis</p>
                <p class="text-3xl font-bold text-blue-600">{{ $quiz->durasi }} menit</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Status</p>
                <p class="text-3xl font-bold {{ $quiz->is_active ? 'text-green-600' : 'text-red-600' }}">
                    {{ $quiz->is_active ? 'Aktif' : 'Nonaktif' }}
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mb-8">
            <button id="addQuestionBtn" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                + Tambah Soal
            </button>
        </div>

        <!-- Questions List -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Pertanyaan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Jawaban Benar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($quiz->questions()->orderBy('urutan')->get() as $question)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ Str::limit($question->pertanyaan, 60) }}</td>
                                <td class="px-6 py-4 text-sm font-semibold">
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs">
                                        {{ strtoupper($question->jawaban_benar) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm space-x-2">
                                    <button type="button" class="editQuestionBtn text-blue-600 hover:text-blue-900 font-semibold" 
                                        data-question-id="{{ $question->id }}">
                                        Edit
                                    </button>
                                    <form action="{{ route('teacher.question.destroy', $question->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-semibold" onclick="return confirm('Yakin hapus soal ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada soal. Klik tombol "Tambah Soal" untuk memulai.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-8">
            <a href="{{ route('teacher.dashboard') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-3 rounded-lg font-semibold transition">
                Kembali ke Dashboard
            </a>
        </div>
    </div>

    <!-- Modal Tambah/Edit Soal -->
    <div id="questionModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-6 border w-full max-w-2xl shadow-lg rounded-lg bg-white">
            <h2 class="text-2xl font-bold text-gray-900 mb-6" id="modalTitle">Tambah Soal Baru</h2>

            <form id="questionForm" method="POST" action="{{ route('teacher.question.store', $quiz->id) }}">
                @csrf
                <input type="hidden" id="questionId" name="question_id">

                <!-- Pertanyaan -->
                <div class="mb-6">
                    <label for="pertanyaan" class="block text-sm font-semibold text-gray-900 mb-2">Pertanyaan <span class="text-red-600">*</span></label>
                    <textarea id="pertanyaan" name="pertanyaan" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" required></textarea>
                </div>

                <!-- Opsi A -->
                <div class="mb-6">
                    <label for="optionA" class="block text-sm font-semibold text-gray-900 mb-2">Opsi A <span class="text-red-600">*</span></label>
                    <input type="text" id="optionA" name="option_a" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" required>
                </div>

                <!-- Opsi B -->
                <div class="mb-6">
                    <label for="optionB" class="block text-sm font-semibold text-gray-900 mb-2">Opsi B <span class="text-red-600">*</span></label>
                    <input type="text" id="optionB" name="option_b" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" required>
                </div>

                <!-- Opsi C -->
                <div class="mb-6">
                    <label for="optionC" class="block text-sm font-semibold text-gray-900 mb-2">Opsi C <span class="text-red-600">*</span></label>
                    <input type="text" id="optionC" name="option_c" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" required>
                </div>

                <!-- Opsi D -->
                <div class="mb-6">
                    <label for="optionD" class="block text-sm font-semibold text-gray-900 mb-2">Opsi D <span class="text-red-600">*</span></label>
                    <input type="text" id="optionD" name="option_d" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" required>
                </div>

                <!-- Jawaban Benar -->
                <div class="mb-8">
                    <label for="jawaban_benar" class="block text-sm font-semibold text-gray-900 mb-2">Jawaban Benar <span class="text-red-600">*</span></label>
                    <select id="jawaban_benar" name="jawaban_benar" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" required>
                        <option value="">Pilih jawaban benar</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex gap-4 justify-end">
                    <button type="button" onclick="closeModal()" class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-3 rounded-lg font-semibold">
                        Batal
                    </button>
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold">
                        Simpan Soal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('questionModal');
        const addBtn = document.getElementById('addQuestionBtn');
        const form = document.getElementById('questionForm');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Open modal for new question
        addBtn.addEventListener('click', function() {
            document.getElementById('modalTitle').textContent = 'Tambah Soal Baru';
            form.action = '{{ route("teacher.question.store", $quiz->id) }}';
            form.method = 'POST';
            document.querySelector('input[name="_method"]')?.remove();
            document.getElementById('questionId').value = '';
            form.reset();
            modal.classList.remove('hidden');
        });

        // Edit question
        document.querySelectorAll('.editQuestionBtn').forEach(btn => {
            btn.addEventListener('click', function() {
                const questionId = this.getAttribute('data-question-id');
                fetch(`/api/question/${questionId}`)
                    .then(r => r.json())
                    .then(data => {
                        document.getElementById('modalTitle').textContent = 'Edit Soal';
                        document.getElementById('questionId').value = questionId;
                        document.getElementById('pertanyaan').value = data.pertanyaan;
                        document.getElementById('optionA').value = data.pilihan[0] || '';
                        document.getElementById('optionB').value = data.pilihan[1] || '';
                        document.getElementById('optionC').value = data.pilihan[2] || '';
                        document.getElementById('optionD').value = data.pilihan[3] || '';
                        document.getElementById('jawaban_benar').value = data.jawaban_benar;
                        
                        form.action = `/teacher/question/${questionId}`;
                        form.method = 'POST';
                        
                        const methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'PUT';
                        form.appendChild(methodInput);
                        
                        modal.classList.remove('hidden');
                    });
            });
        });

        function closeModal() {
            modal.classList.add('hidden');
        }

        // Close modal when clicking outside
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });

        // Handle form submission
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const action = this.action;
            const method = formData.get('_method') || 'POST';

            fetch(action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    alert('Soal berhasil disimpan!');
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Gagal menyimpan soal'));
                }
            })
            .catch(err => {
                console.error(err);
                alert('Error: ' + err.message);
            });
        });
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Guru - SDN Susukan 08 Pagi</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .tab-button {
            transition: all 0.3s ease;
        }
        .tab-button.active {
            border-bottom: 3px solid #059669;
            font-weight: 600;
        }
    </style>
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
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('teacher.dashboard') }}" class="font-semibold border-b-2 border-white text-sm">Dashboard</a>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- User Menu Dropdown -->
                    <div class="relative group">
                        <button class="flex items-center space-x-2 text-white hover:text-green-100 transition px-3 py-2 rounded-lg">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                            <span class="text-sm hidden sm:inline">{{ auth()->user()->name }}</span>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 mt-2 w-48 bg-white text-gray-800 rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <div class="px-4 py-3 border-b border-gray-200">
                                <p class="text-sm font-semibold">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('teacher.profile.edit') }}" class="block px-4 py-2 text-sm hover:bg-gray-100 transition">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit Profil
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 transition text-red-600 font-medium cursor-pointer">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Welcome Section -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Dashboard Guru</h1>
            <p class="text-gray-600">Kelola materi, kuis, dan pantau perkembangan siswa Anda</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                <p class="text-sm text-gray-600">Total Siswa</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalStudents }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                <p class="text-sm text-gray-600">Total Materi</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalMateri }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
                <p class="text-sm text-gray-600">Total Kuis</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalQuizzes }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-orange-500">
                <p class="text-sm text-gray-600">Rata-rata Nilai</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($averageScore, 1) }}</p>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="bg-white rounded-lg shadow mb-8 border-b border-gray-200">
            <div class="flex flex-wrap border-b border-gray-200">
                <button onclick="switchTab('dashboard')" class="tab-button active px-6 py-4 text-gray-700 hover:text-gray-900 font-medium" data-tab="dashboard">
                    📊 Dashboard
                </button>
                <button onclick="switchTab('materi')" class="tab-button px-6 py-4 text-gray-700 hover:text-gray-900 font-medium" data-tab="materi">
                    📚 Kelola Materi
                </button>
                <button onclick="switchTab('kuis')" class="tab-button px-6 py-4 text-gray-700 hover:text-gray-900 font-medium" data-tab="kuis">
                    ✏️ Kelola Kuis
                </button>
                <button onclick="switchTab('nilai')" class="tab-button px-6 py-4 text-gray-700 hover:text-gray-900 font-medium" data-tab="nilai">
                    📈 Nilai Siswa
                </button>
                <button onclick="switchTab('badge')" class="tab-button px-6 py-4 text-gray-700 hover:text-gray-900 font-medium" data-tab="badge">
                    🏆 Badge
                </button>
            </div>
        </div>

        <!-- Dashboard Tab -->
        <div id="dashboard" class="tab-content active">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white">Aktivitas Terbaru - Pengerjaan Kuis</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Siswa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Kuis</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Nilai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($quizAttempts->take(10) as $attempt)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $attempt->user->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $attempt->quiz->judul ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm font-bold">
                                        <span class="px-3 py-1 rounded-full {{ $attempt->nilai >= 70 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $attempt->nilai }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-xs">
                                            Selesai
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $attempt->created_at->format('d M Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada aktivitas kuis</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Materi Tab -->
        <div id="materi" class="tab-content">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-white">Kelola Materi Pelajaran</h2>
                    <a href="{{ route('teacher.materi.create') }}" class="bg-white text-green-600 px-4 py-2 rounded-lg font-semibold hover:bg-green-50 transition">
                        + Tambah Materi
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Judul</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Deskripsi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($materi as $item)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $item->judul }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ Str::limit($item->deskripsi, 50) }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="px-3 py-1 rounded-full {{ $item->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} text-xs font-semibold">
                                            {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm space-x-2 flex">
                                        <a href="{{ route('teacher.materi.edit', $item->id) }}" class="text-blue-600 hover:text-blue-900 font-semibold">Edit</a>
                                        <form action="{{ route('teacher.materi.destroy', $item->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-semibold" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada materi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Kuis Tab -->
        <div id="kuis" class="tab-content">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-white">Kelola Kuis</h2>
                    <a href="{{ route('teacher.quiz.create') }}" class="bg-white text-purple-600 px-4 py-2 rounded-lg font-semibold hover:bg-purple-50 transition">
                        + Tambah Kuis
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Judul</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Jumlah Soal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Durasi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Waktu Mulai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Waktu Selesai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($quizzes as $quiz)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $quiz->judul }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $quiz->questions->count() }} soal</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $quiz->durasi }} menit</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $quiz->waktu_mulai ? $quiz->waktu_mulai->format('d M H:i') : '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $quiz->waktu_selesai ? $quiz->waktu_selesai->format('d M H:i') : '-' }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="px-3 py-1 rounded-full {{ $quiz->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} text-xs font-semibold">
                                            {{ $quiz->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm space-x-2 flex">
                                        <button type="button" class="adjust-time-btn text-green-600 hover:text-green-900 font-semibold text-xs bg-green-50 px-2 py-1 rounded"
                                            data-quiz-id="{{ $quiz->id }}"
                                            data-quiz-judul="{{ $quiz->judul }}"
                                            data-waktu-mulai="{{ $quiz->waktu_mulai?->format('Y-m-d H:i') ?? '' }}"
                                            data-waktu-selesai="{{ $quiz->waktu_selesai?->format('Y-m-d H:i') ?? '' }}">
                                            ⏱ Adjust Waktu
                                        </button>
                                        <a href="{{ route('teacher.quiz.edit', $quiz->id) }}" class="text-blue-600 hover:text-blue-900 font-semibold">Edit</a>
                                        <form action="{{ route('teacher.quiz.destroy', $quiz->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-semibold" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada kuis</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Nilai Siswa Tab -->
        <div id="nilai" class="tab-content">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white">Nilai Siswa</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">NISN</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Nama Siswa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Total Kuis</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Rata-rata Nilai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($studentScores as $studentId => $score)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $score['nisn'] ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $score['name'] }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $score['total_attempts'] }}</td>
                                    <td class="px-6 py-4 text-sm font-bold">
                                        <span class="px-3 py-1 rounded-full {{ $score['average_score'] >= 70 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ number_format($score['average_score'], 1) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="px-3 py-1 rounded-full {{ $score['average_score'] >= 70 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }} text-xs font-semibold">
                                            {{ $score['average_score'] >= 70 ? 'Lulus' : 'Perlu Bimbingan' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada nilai siswa</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Badge Tab -->
        <div id="badge" class="tab-content">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white">Badge dan Penghargaan Siswa</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($students as $student)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition">
                                <h3 class="font-semibold text-gray-900 mb-2">{{ $student->name }}</h3>
                                <p class="text-sm text-gray-600 mb-4">NISN: {{ $student->nisn ?? '-' }}</p>
                                <div class="flex gap-2 flex-wrap">
                                    @if($studentScores[$student->id]['average_score'] >= 90)
                                        <span class="text-3xl" title="Bintang Emas - Nilai >= 90">⭐</span>
                                    @endif
                                    @if($studentScores[$student->id]['average_score'] >= 80 && $studentScores[$student->id]['average_score'] < 90)
                                        <span class="text-3xl" title="Bintang Perak - Nilai 80-89">🥈</span>
                                    @endif
                                    @if($studentScores[$student->id]['average_score'] >= 70 && $studentScores[$student->id]['average_score'] < 80)
                                        <span class="text-3xl" title="Bintang Perunggu - Nilai 70-79">🥉</span>
                                    @endif
                                    @if($studentScores[$student->id]['total_attempts'] >= 5)
                                        <span class="text-3xl" title="Peserta Aktif - 5+ kuis">🎯</span>
                                    @endif
                                    @if($studentScores[$student->id]['average_score'] >= 85)
                                        <span class="text-3xl" title="Juara - Nilai >= 85">🏆</span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600 mt-4">Rata-rata: <span class="font-bold">{{ number_format($studentScores[$student->id]['average_score'], 1) }}</span></p>
                            </div>
                        @empty
                            <div class="col-span-3 text-center text-gray-500 py-8">
                                Belum ada siswa terdaftar
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Badge Legend -->
            <div class="bg-white rounded-lg shadow overflow-hidden mt-6">
                <div class="bg-gray-100 px-6 py-4">
                    <h3 class="text-lg font-bold text-gray-900">Keterangan Badge</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="flex items-center gap-3">
                        <span class="text-3xl">⭐</span>
                        <div>
                            <p class="font-semibold text-gray-900">Bintang Emas</p>
                            <p class="text-sm text-gray-600">Nilai rata-rata ≥ 90</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-3xl">🥈</span>
                        <div>
                            <p class="font-semibold text-gray-900">Bintang Perak</p>
                            <p class="text-sm text-gray-600">Nilai rata-rata 80-89</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-3xl">🥉</span>
                        <div>
                            <p class="font-semibold text-gray-900">Bintang Perunggu</p>
                            <p class="text-sm text-gray-600">Nilai rata-rata 70-79</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-3xl">🎯</span>
                        <div>
                            <p class="font-semibold text-gray-900">Peserta Aktif</p>
                            <p class="text-sm text-gray-600">Mengerjakan 5+ kuis</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-3xl">🏆</span>
                        <div>
                            <p class="font-semibold text-gray-900">Juara</p>
                            <p class="text-sm text-gray-600">Nilai rata-rata ≥ 85</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tabName) {
            // Hide all tab contents
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => {
                content.classList.remove('active');
            });

            // Remove active class from all buttons
            const tabButtons = document.querySelectorAll('.tab-button');
            tabButtons.forEach(button => {
                button.classList.remove('active');
            });

            // Show selected tab content
            document.getElementById(tabName).classList.add('active');

            // Add active class to clicked button
            event.target.classList.add('active');
        }

        // Adjust time modal
        document.querySelectorAll('.adjust-time-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const quizId = this.dataset.quizId;
                const quizJudul = this.dataset.quizJudul;
                let waktuMulai = this.dataset.waktuMulai;
                let waktuSelesai = this.dataset.waktuSelesai;

                // Convert space to T for datetime-local format
                if (waktuMulai) {
                    waktuMulai = waktuMulai.replace(' ', 'T');
                }
                if (waktuSelesai) {
                    waktuSelesai = waktuSelesai.replace(' ', 'T');
                }

                document.getElementById('modal-quiz-id').value = quizId;
                document.getElementById('modal-quiz-judul').textContent = quizJudul;
                document.getElementById('waktu_mulai').value = waktuMulai;
                document.getElementById('waktu_selesai').value = waktuSelesai;
                
                document.getElementById('adjustTimeModal').classList.remove('hidden');
            });
        });

        function closeAdjustModal() {
            document.getElementById('adjustTimeModal').classList.add('hidden');
        }

        document.getElementById('adjustTimeForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const quizId = document.getElementById('modal-quiz-id').value;
            let waktuMulai = document.getElementById('waktu_mulai').value;
            let waktuSelesai = document.getElementById('waktu_selesai').value;

            // Convert T to space for backend format (Y-m-d H:i)
            if (waktuMulai) {
                waktuMulai = waktuMulai.replace('T', ' ');
            }
            if (waktuSelesai) {
                waktuSelesai = waktuSelesai.replace('T', ' ');
            }

            try {
                const response = await fetch(`/api/quiz/${quizId}/adjust-time`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        waktu_mulai: waktuMulai,
                        waktu_selesai: waktuSelesai
                    })
                });

                if (response.ok) {
                    alert('Waktu kuis berhasil diperbarui');
                    closeAdjustModal();
                    location.reload();
                } else {
                    alert('Gagal memperbarui waktu kuis');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan');
            }
        });

        // Close modal when clicking outside
        document.getElementById('adjustTimeModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAdjustModal();
            }
        });
    </script>

    <!-- Adjust Time Modal -->
    <div id="adjustTimeModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Atur Waktu Kuis: <span id="modal-quiz-judul"></span></h3>
            
            <form id="adjustTimeForm" class="space-y-4">
                <input type="hidden" id="modal-quiz-id">
                
                <div>
                    <label for="waktu_mulai" class="block text-sm font-medium text-gray-700">Waktu Mulai</label>
                    <input type="datetime-local" id="waktu_mulai" name="waktu_mulai" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 border px-3 py-2">
                </div>

                <div>
                    <label for="waktu_selesai" class="block text-sm font-medium text-gray-700">Waktu Selesai</label>
                    <input type="datetime-local" id="waktu_selesai" name="waktu_selesai" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 border px-3 py-2">
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="closeAdjustModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

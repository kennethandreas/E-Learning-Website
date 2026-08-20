<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Guru - SDN Susukan 08 Pagi</title>
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
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 transition text-red-600 font-medium">
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
            <!-- Total Students -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Siswa</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalStudents }}</p>
                    </div>
                    <svg class="w-12 h-12 text-blue-500 opacity-20" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </div>
            </div>

            <!-- Total Materials -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Materi</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalMateri }}</p>
                    </div>
                    <svg class="w-12 h-12 text-green-500 opacity-20" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                    </svg>
                </div>
            </div>

            <!-- Total Quizzes -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Kuis</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalQuizzes }}</p>
                    </div>
                    <svg class="w-12 h-12 text-purple-500 opacity-20" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zm-5.04-6.71l-2.75 3.54-2.08-2.49-.9 1.13L11.45 18l4.48-5.67-.97-.04z"/>
                    </svg>
                </div>
            </div>

            <!-- Average Score -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-orange-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Rata-rata Nilai</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($averageScore, 1) }}</p>
                    </div>
                    <svg class="w-12 h-12 text-orange-500 opacity-20" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zm-5-8h3v2h-3zm0-4h3v2h-3zm0 8h3v2h-3zM8 7h3v2H8zm0 4h3v2H8zm0 4h3v2H8z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Manage Materials -->
            <a href="{{ route('teacher.materi.index') }}" class="bg-white rounded-lg shadow hover:shadow-lg transition p-6 border-t-4 border-green-500">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Kelola Materi</h3>
                        <p class="text-gray-600 text-sm mt-1">Tambah, edit, atau hapus materi pelajaran</p>
                    </div>
                    <svg class="w-8 h-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <p class="text-green-600 font-semibold text-sm">{{ $totalMateri }} Materi Aktif</p>
            </a>

            <!-- Manage Quizzes -->
            <a href="{{ route('teacher.quiz.index') }}" class="bg-white rounded-lg shadow hover:shadow-lg transition p-6 border-t-4 border-purple-500">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Kelola Kuis</h3>
                        <p class="text-gray-600 text-sm mt-1">Buat dan kelola soal kuis siswa</p>
                    </div>
                    <svg class="w-8 h-8 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-purple-600 font-semibold text-sm">{{ $totalQuizzes }} Kuis Tersedia</p>
            </a>

            <!-- View Student Scores -->
            <a href="{{ route('teacher.scores') }}" class="bg-white rounded-lg shadow hover:shadow-lg transition p-6 border-t-4 border-blue-500">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Lihat Nilai Siswa</h3>
                        <p class="text-gray-600 text-sm mt-1">Pantau nilai kuis setiap siswa</p>
                    </div>
                    <svg class="w-8 h-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <p class="text-blue-600 font-semibold text-sm">{{ $totalStudents }} Siswa</p>
            </a>

            <!-- View Badges -->
            <a href="#" class="bg-white rounded-lg shadow hover:shadow-lg transition p-6 border-t-4 border-yellow-500">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Lihat Badge</h3>
                        <p class="text-gray-600 text-sm mt-1">Penghargaan dan prestasi siswa</p>
                    </div>
                    <svg class="w-8 h-8 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                <p class="text-yellow-600 font-semibold text-sm">Penghargaan Siswa</p>
            </a>
        </div>

        <!-- Recent Quiz Attempts -->
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
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $attempt->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $attempt->user->nisn }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-900">{{ $attempt->quiz->judul ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @if($attempt->nilai >= 80)
                                            bg-green-100 text-green-800
                                        @elseif($attempt->nilai >= 60)
                                            bg-yellow-100 text-yellow-800
                                        @else
                                            bg-red-100 text-red-800
                                        @endif">
                                        {{ $attempt->nilai }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        @if($attempt->status === 'completed')
                                            bg-blue-100 text-blue-800
                                        @else
                                            bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($attempt->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $attempt->created_at->format('d M Y H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <p>Belum ada aktivitas kuis</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Students -->
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Top Performers -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white">Siswa Berprestasi</h2>
                </div>
                <div class="divide-y">
                    @php
                        $topStudents = collect($studentScores)
                            ->sortByDesc('average_score')
                            ->take(5);
                    @endphp
                    @forelse($topStudents as $score)
                        <div class="px-6 py-4 hover:bg-gray-50 transition">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $score['name'] }}</p>
                                    <p class="text-xs text-gray-500">{{ $score['nisn'] }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-green-600">{{ number_format($score['average_score'], 1) }}</p>
                                    <p class="text-xs text-gray-500">{{ $score['total_attempts'] }} kuis</p>
                                </div>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                <div class="bg-green-500 h-2 rounded-full progress-bar" data-score="{{ $score['average_score'] }}"></div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center text-gray-500">
                            <p>Belum ada data nilai siswa</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Need Attention -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white">Perlu Perhatian</h2>
                </div>
                <div class="divide-y">
                    @php
                        $lowPerformers = collect($studentScores)
                            ->filter(fn($s) => $s['total_attempts'] > 0 && $s['average_score'] < 60)
                            ->sortBy('average_score')
                            ->take(5);
                    @endphp
                    @forelse($lowPerformers as $score)
                        <div class="px-6 py-4 hover:bg-gray-50 transition">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $score['name'] }}</p>
                                    <p class="text-xs text-gray-500">{{ $score['nisn'] }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-orange-600">{{ number_format($score['average_score'], 1) }}</p>
                                    <p class="text-xs text-gray-500">{{ $score['total_attempts'] }} kuis</p>
                                </div>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                <div class="bg-orange-500 h-2 rounded-full progress-bar" data-score="{{ $score['average_score'] }}"></div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center text-gray-500">
                            <p>Semua siswa memiliki nilai baik!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t mt-12">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-gray-500 text-sm">&copy; {{ date('Y') }} SDN Susukan 08 Pagi. All rights reserved.</p>
        </div>
    </footer>

    <script>
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Set progress bar widths
        document.querySelectorAll('.progress-bar').forEach(bar => {
            const score = Math.min(parseFloat(bar.getAttribute('data-score')), 100);
            bar.style.width = score + '%';
        });
    </script>
</body>
</html>

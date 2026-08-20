<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laporan Perkembangan Anak - SDN Susukan 08 Pagi</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-b from-green-50 to-white">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <div class="bg-white border-b border-gray-200 sticky top-0 z-10">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between">
                    <a href="{{ route('landing') }}" class="flex items-center space-x-4 hover:opacity-90 transition">
                        <img src="{{ asset('img/logo.svg') }}" alt="SDN Susukan 08 Pagi" class="h-12 w-auto">
                        <div class="hidden sm:block">
                            <h1 class="text-lg font-bold text-gray-900">SDN Susukan 08 Pagi</h1>
                            <p class="text-xs text-gray-600">Portal Laporan Orang Tua</p>
                        </div>
                    </a>
                    <a href="{{ route('landing') }}" class="inline-block bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg transition text-sm">
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 max-w-6xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8">
            <!-- Student Selection Card -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8 border-l-4 border-green-500">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Masukkan NISN Siswa</h2>
                <form method="GET" action="{{ route('report.student') }}" class="space-y-4">
                    <div>
                        <div class="flex gap-3">
                            <input type="text" name="nisn" placeholder="NISN Siswa Anda" 
                                value="{{ $student?->nisn ?? request('nisn', '') }}"
                                class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                required>
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded-lg transition">
                                Tampilkan
                            </button>
                        </div>
                        @error('nisn')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </form>
            </div>

            @if($student)
            <!-- Student Report -->
            <div class="space-y-8">
                <!-- Title Section -->
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">
                        Berikut Adalah Perkembangan Akademik Anak Anda
                    </h2>
                    <p class="text-2xl font-semibold text-gray-800">{{ $student->name }}</p>
                    @if($student->kelas)
                        <p class="text-gray-600 mt-1">Kelas: {{ $student->kelas }}</p>
                    @endif
                </div>

                <!-- Quiz Results Section -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                        <h3 class="text-lg font-bold text-white">Hasil Kuis</h3>
                    </div>
                    <div class="divide-y">
                        @if($quizResults->count() > 0)
                            @foreach($quizResults as $attempt)
                                <div class="px-6 py-4 hover:bg-gray-50 transition">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="font-semibold text-gray-900">{{ $attempt['quiz_title'] }}</h4>
                                        <div class="flex items-center space-x-4">
                                            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                                                @if($attempt['status'] === 'completed')
                                                    bg-blue-100 text-blue-800
                                                @elseif($attempt['status'] === 'ongoing')
                                                    bg-yellow-100 text-yellow-800
                                                @else
                                                    bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst(str_replace('_', ' ', $attempt['status'])) }}
                                            </span>
                                            <div class="text-right">
                                                <p class="text-2xl font-bold 
                                                    @if($attempt['score'] >= 80)
                                                        text-green-600
                                                    @elseif($attempt['score'] >= 60)
                                                        text-yellow-600
                                                    @else
                                                        text-red-600
                                                    @endif">
                                                    {{ $attempt['score'] }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between text-sm text-gray-500">
                                        <span>
                                            ✓ {{ $attempt['correct'] }} | ✗ {{ $attempt['wrong'] }}
                                            @if($attempt['duration'] !== null)
                                                @php
                                                    $minutes = intdiv($attempt['duration'], 60);
                                                    $seconds = $attempt['duration'] % 60;
                                                @endphp
                                                | ⏱ {{ $minutes }} menit {{ $seconds }} detik
                                            @endif
                                        </span>
                                        <span>{{ $attempt['date']->format('d M Y H:i') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-sm">Belum ada hasil kuis</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Footer -->
        <footer class="bg-white border-t mt-8">
            <div class="max-w-6xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                <p class="text-center text-gray-500 text-sm">&copy; {{ date('Y') }} SDN Susukan 08 Pagi. All rights reserved.</p>
            </div>
        </footer>
    </div>
    <script>
        // Set CSRF token untuk Axios
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    </script>
</body>
</html>
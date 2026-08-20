<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pilih Kuis Anda - SDN Susukan 08 Pagi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white min-h-screen">
    <!-- Header -->
    <header class="bg-green-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('landing') }}" class="flex items-center space-x-4 hover:opacity-90 transition">
                    <img src="{{ asset('img/logo.svg') }}" alt="SDN Susukan 08 Pagi" class="h-14 w-auto">
                    <span class="text-lg font-semibold hidden sm:inline">SDN Susukan 08 Pagi</span>
                </a>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('landing') }}" class="hover:text-green-100 transition">Beranda</a>
                    <a href="{{ route('dashboard') }}" class="hover:text-green-100 transition">Dashboard</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Title Section -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-3">Pilih Kuis Anda</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Pilih kuis yang ingin Anda ikuti hari ini. Setiap kuis dirancang untuk membantu Anda belajar dan berkembang!
            </p>
        </div>

        <!-- Quiz Cards -->
        @if($quizzes->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($quizzes as $quiz)
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition">
                <!-- Illustration Section -->
                <div class="relative h-48 bg-gradient-to-br from-blue-50 to-green-50 overflow-hidden">
                    @php
                        $title = strtolower($quiz->judul ?? '');
                        $illustration = '';
                        
                        // Matematika illustration
                        if (strpos($title, 'matematika') !== false || strpos($title, 'math') !== false) {
                            $illustration = '<svg class="w-full h-full" viewBox="0 0 400 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect width="400" height="300" fill="#E0F2FE"/>
                                <rect x="50" y="150" width="40" height="40" fill="#F472B6" rx="5"/>
                                <rect x="100" y="150" width="40" height="40" fill="#60A5FA" rx="5"/>
                                <rect x="150" y="150" width="40" height="40" fill="#FBBF24" rx="5"/>
                                <rect x="200" y="150" width="40" height="40" fill="#34D399" rx="5"/>
                                <circle cx="100" cy="120" r="20" fill="#FED7AA"/>
                                <circle cx="200" cy="120" r="20" fill="#A78BFA"/>
                                <circle cx="300" cy="120" r="20" fill="#FBBF24"/>
                                <text x="70" y="175" font-family="Arial" font-size="24" font-weight="bold" fill="#1F2937">1</text>
                                <text x="120" y="175" font-family="Arial" font-size="24" font-weight="bold" fill="#1F2937">2</text>
                                <text x="170" y="175" font-family="Arial" font-size="24" font-weight="bold" fill="#1F2937">3</text>
                            </svg>';
                        }
                        // Bahasa Indonesia illustration
                        elseif (strpos($title, 'bahasa indonesia') !== false || strpos($title, 'indonesia') !== false) {
                            $illustration = '<svg class="w-full h-full" viewBox="0 0 400 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect width="400" height="300" fill="#F0FDF4"/>
                                <circle cx="100" cy="100" r="25" fill="#FED7AA"/>
                                <circle cx="200" cy="100" r="25" fill="#FED7AA"/>
                                <circle cx="300" cy="100" r="25" fill="#FED7AA"/>
                                <rect x="80" y="130" width="40" height="30" fill="#22C55E" rx="3"/>
                                <rect x="180" y="130" width="40" height="30" fill="#F59E0B" rx="3"/>
                                <rect x="280" y="130" width="40" height="30" fill="#3B82F6" rx="3"/>
                                <line x1="90" y1="140" x2="110" y2="140" stroke="#FFFFFF" stroke-width="2"/>
                                <line x1="90" y1="150" x2="110" y2="150" stroke="#FFFFFF" stroke-width="2"/>
                            </svg>';
                        }
                        // IPA/Sains illustration
                        elseif (strpos($title, 'ipa') !== false || strpos($title, 'sains') !== false || strpos($title, 'science') !== false) {
                            $illustration = '<svg class="w-full h-full" viewBox="0 0 400 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect width="400" height="300" fill="#F0FDF4"/>
                                <rect x="0" y="200" width="400" height="100" fill="#86EFAC"/>
                                <circle cx="100" cy="180" r="8" fill="#F472B6"/>
                                <circle cx="200" cy="190" r="8" fill="#FBBF24"/>
                                <circle cx="300" cy="185" r="8" fill="#60A5FA"/>
                                <circle cx="250" cy="120" r="20" fill="#FED7AA"/>
                                <circle cx="250" cy="100" r="15" fill="#FED7AA"/>
                                <circle cx="280" cy="90" r="20" fill="none" stroke="#1F2937" stroke-width="3"/>
                                <line x1="295" y1="105" x2="310" y2="120" stroke="#1F2937" stroke-width="3"/>
                            </svg>';
                        }
                        // Default illustration
                        else {
                            $illustration = '<svg class="w-full h-full" viewBox="0 0 400 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect width="400" height="300" fill="#F3F4F6"/>
                                <circle cx="200" cy="150" r="50" fill="#9CA3AF"/>
                                <text x="200" y="160" font-family="Arial" font-size="24" fill="#FFFFFF" text-anchor="middle">?</text>
                            </svg>';
                        }
                    @endphp
                    {!! $illustration !!}
                    
                    <!-- Difficulty Badge -->
                    <div class="absolute bottom-3 left-3">
                        @php
                            $difficultyColors = [
                                'mudah' => 'bg-purple-600',
                                'sedang' => 'bg-blue-600',
                                'sulit' => 'bg-purple-600'
                            ];
                            $difficulty = strtolower($quiz->kesulitan ?? 'sedang');
                            $color = $difficultyColors[$difficulty] ?? $difficultyColors['sedang'];
                        @endphp
                        <span class="{{ $color }} text-white px-3 py-1 rounded-full text-sm font-semibold">
                            {{ ucfirst($difficulty) }}
                        </span>
                    </div>
                </div>

                <!-- Content Section -->
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $quiz->judul }}</h3>
                    <p class="text-gray-600 text-sm mb-4 leading-relaxed">
                        {{ $quiz->deskripsi ?? 'Uji pemahaman Anda dengan kuis ini!' }}
                    </p>
                    <a href="{{ route('quiz.show', $quiz->id) }}" class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition">
                        Mulai Kuis
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Tidak ada kuis</h3>
            <p class="text-gray-500">Belum ada kuis yang tersedia saat ini.</p>
        </div>
        @endif
    </div>

</body>
</html>

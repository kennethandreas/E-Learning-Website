<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $materi->judul }} - SDN Susukan 08 Pagi</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .konten-materi {
            line-height: 1.8;
        }
        .konten-materi h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-top: 2rem;
            margin-bottom: 1rem;
            color: #111827;
        }
        .konten-materi h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
            color: #374151;
        }
        .konten-materi p {
            margin-bottom: 1rem;
            color: #4b5563;
        }
        .konten-materi ul, .konten-materi ol {
            margin-left: 2rem;
            margin-bottom: 1rem;
        }
        .konten-materi li {
            margin-bottom: 0.5rem;
        }
        .btn-mark-complete {
            transition: all 0.3s ease;
        }
        .btn-mark-complete:active {
            transform: scale(0.95);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="bg-green-600 text-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-4 hover:opacity-90 transition">
                    <img src="{{ asset('img/logo.svg') }}" alt="SDN Susukan 08 Pagi" class="h-14 w-auto">
                    <span class="text-lg font-semibold hidden sm:inline">SDN Susukan 08 Pagi</span>
                </a>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('dashboard') }}" class="hover:text-green-100 transition text-sm">Dashboard</a>
                    <a href="{{ route('materi.index') }}" class="font-semibold border-b-2 border-white text-sm">Materi</a>
                    <a href="{{ route('quiz.index') }}" class="hover:text-green-100 transition text-sm">Kuis</a>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- User Menu -->
                    <div class="relative group">
                        <button class="flex items-center space-x-2 text-white hover:text-green-100 transition px-3 py-2 rounded-lg">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                            <span class="text-sm hidden sm:inline">{{ auth()->user()->name }}</span>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white text-gray-800 rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <div class="px-4 py-3 border-b border-gray-200">
                                <p class="text-sm font-semibold">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500">{{ auth()->user()->nisn }}</p>
                            </div>
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

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Back Button -->
        <a href="{{ route('materi.index') }}" class="inline-flex items-center text-green-600 hover:text-green-700 font-semibold mb-6">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar Materi
        </a>

        <!-- Main Content -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 sm:px-8 py-8">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-3xl sm:text-4xl font-bold text-white mb-2">{{ $materi->judul }}</h1>
                        <p class="text-green-100">Materi Urutan #{{ $materi->urutan }}</p>
                    </div>
                    @if($isCompleted)
                        <div class="text-5xl">⭐</div>
                    @endif
                </div>
            </div>

            <!-- Content -->
            <div class="px-6 sm:px-8 py-8">
                <!-- Deskripsi -->
                <div class="mb-8 pb-8 border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Deskripsi</h2>
                    <p class="text-gray-700 leading-relaxed text-lg">{{ $materi->deskripsi }}</p>
                </div>

                <!-- Konten Utama -->
                <div class="mb-8 pb-8 border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Konten Materi</h2>
                    <div class="konten-materi prose prose-sm max-w-none text-gray-700">
                        {!! nl2br(
                            preg_replace(
                                '/(https?:\/\/[^\s]+)/',
                                '<a href="$1" target="_blank" class="text-blue-600 underline font-semibold hover:text-blue-800">$1</a>',
                                e($materi->konten)
                            )
                        ) !!}
                    </div>
                </div>

                <!-- File Attachment -->
                @if($materi->file)
                    <div class="mb-8 pb-8 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">File Pendukung</h3>
                        <a href="{{ asset('storage/' . $materi->file) }}" target="_blank" class="inline-flex items-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Download File
                        </a>
                    </div>
                @endif

                <!-- Mark as Complete Section -->
                <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-lg p-6 border border-green-200">
                    @if($isCompleted)
                        <div class="flex items-center space-x-3">
                            <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/>
                            </svg>
                            <div>
                                <p class="font-bold text-green-700">Materi Selesai!</p>
                                <p class="text-sm text-green-600">Anda telah menyelesaikan materi ini</p>
                            </div>
                        </div>
                    @else
                        <form action="{{ route('materi.complete', $materi->id) }}" method="POST" class="flex flex-col sm:flex-row items-start sm:items-center justify-between space-y-4 sm:space-y-0">
                            @csrf
                            <div>
                                <p class="font-semibold text-gray-900">Apakah Anda sudah memahami materi ini?</p>
                                <p class="text-sm text-gray-600">Klik tombol di bawah untuk menandai materi sebagai selesai</p>
                            </div>
                            <button type="submit" class="btn-mark-complete flex-shrink-0 px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 font-semibold transition shadow-lg">
                                ✓ Tandai Selesai
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Navigation Buttons -->
                <div class="mt-12 flex flex-col sm:flex-row justify-between gap-4">
                    @if($previousMateri)
                        <a href="{{ route('materi.show', $previousMateri->id) }}" class="flex items-center px-4 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition font-semibold">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Materi Sebelumnya
                        </a>
                    @else
                        <div></div>
                    @endif

                    @if($nextMateri)
                        <a href="{{ route('materi.show', $nextMateri->id) }}" class="flex items-center px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold justify-center sm:justify-start">
                            Materi Selanjutnya
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}"
                        class="px-4 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition inline-flex items-center justify-center">
                            ✓ Terakhir - Semua Materi Selesai!
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>

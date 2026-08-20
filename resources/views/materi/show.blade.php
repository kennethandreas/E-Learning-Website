<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $materi->judul }} - SDN Susukan 08 Pagi</title>
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
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Illustration Card -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 mb-6 overflow-hidden">
            <div class="p-6">
                <!-- Illustration Placeholder - bisa diganti dengan gambar asli -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-8 mb-4 relative overflow-hidden">
                    <div class="text-center">
                        <!-- Ilustrasi pohon dan anak membaca -->
                        <svg class="w-full h-64 mx-auto" viewBox="0 0 400 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Pohon -->
                            <rect x="280" y="80" width="40" height="120" fill="#8B4513"/>
                            <circle cx="300" cy="60" r="50" fill="#228B22"/>
                            <circle cx="320" cy="70" r="45" fill="#228B22"/>
                            <circle cx="280" cy="70" r="45" fill="#228B22"/>
                            <!-- Rumput -->
                            <rect x="0" y="200" width="400" height="100" fill="#90EE90"/>
                            <!-- Anak membaca -->
                            <circle cx="150" cy="180" r="25" fill="#FFDBAC"/>
                            <rect x="130" y="200" width="40" height="50" fill="#FF6B6B"/>
                            <rect x="125" y="195" width="50" height="30" fill="#4ECDC4"/>
                            <!-- Buku -->
                            <rect x="140" y="210" width="30" height="20" fill="#FF0000" rx="2"/>
                            <line x1="155" y1="210" x2="155" y2="230" stroke="#000" stroke-width="2"/>
                        </svg>
                    </div>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 text-center">{{ $materi->judul }}</h1>
            </div>
        </div>

        <!-- Content Area -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-8">
            @if($materi->deskripsi)
                <div class="mb-6">
                    <p class="text-lg text-gray-700 leading-relaxed">{{ $materi->deskripsi }}</p>
                </div>
            @endif

            <!-- Main Content -->
            <div class="prose max-w-none mb-8">
                <div class="text-gray-800 leading-relaxed whitespace-pre-line">
                    {!! nl2br(e($materi->konten)) !!}
                </div>
            </div>

            <!-- File Download -->
            @if($materi->file)
                <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <svg class="w-8 h-8 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900">File Materi</p>
                                <p class="text-xs text-gray-500">Klik untuk mengunduh</p>
                            </div>
                        </div>
                        <a href="{{ asset('storage/' . $materi->file) }}" download class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Download
                        </a>
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('materi.index') }}" class="text-green-600 hover:text-green-700 font-medium flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Daftar Materi
                </a>
                
                <div class="flex items-center space-x-4">
                    @if($aktivitas && $aktivitas->status == 'belum_selesai')
                        <form action="{{ route('materi.complete', $materi->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium transition">
                                Tandai sebagai Selesai
                            </button>
                        </form>
                    @elseif($aktivitas && $aktivitas->status == 'selesai')
                        <span class="px-6 py-2 bg-green-100 text-green-800 rounded-lg font-medium flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Selesai
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="mt-12 py-4 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-start space-x-2 text-sm text-gray-600">
                <span>Made with</span>
                <div class="w-4 h-4 bg-purple-600 rounded flex items-center justify-center">
                    <span class="text-white text-xs font-bold">V</span>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>

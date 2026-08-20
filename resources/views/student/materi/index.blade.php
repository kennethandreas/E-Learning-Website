<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Materi Pelajaran - SDN Susukan 08 Pagi</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .materi-card {
            transition: all 0.3s ease;
        }
        .materi-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        .badge-completed {
            animation: slideIn 0.3s ease-out;
        }
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
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

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">📚 Materi Pelajaran</h1>
            <p class="text-gray-600">Pelajari materi dengan membaca dan memahami konten di bawah ini</p>
        </div>

        <!-- Progress Bar -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-lg font-semibold text-gray-900">Progres Belajar Anda</h3>
                <span class="text-2xl font-bold text-green-600">{{ count($completedMateri) }}/{{ $materi->count() }}</span>
            </div>
            @php
                $totalMateri = $materi->count();
                $completedCount = count($completedMateri);
                $progressPercentage = $totalMateri > 0 ? ($completedCount / $totalMateri * 100) : 0;
            @endphp
            <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-green-600 h-full rounded-full transition-all duration-500" 
                     @style("width: ${progressPercentage}%")></div>
            </div>
            <p class="text-sm text-gray-600 mt-2">Selesaikan semua materi untuk membuka kuis</p>
        </div>

        <!-- Materi List -->
        @if($materi->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($materi as $m)
                    <a href="{{ route('materi.show', $m->id) }}" class="materi-card bg-white rounded-lg shadow overflow-hidden border-l-4 border-green-500 hover:border-green-600">
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                            <div class="flex justify-between items-start">
                                <h3 class="text-lg font-bold text-white">{{ $m->judul }}</h3>
                                @if(in_array($m->id, $completedMateri))
                                    <span class="badge-completed">
                                        <svg class="w-6 h-6 text-yellow-300" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div class="p-6">
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $m->deskripsi }}</p>
                            
                            <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200">
                                <div class="text-xs text-gray-500">
                                    <p>Urutan: <span class="font-semibold">{{ $m->urutan }}</span></p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if(in_array($m->id, $completedMateri))
                                        <span class="inline-block px-3 py-1 rounded-full bg-green-100 text-green-800 text-xs font-semibold">
                                            ✓ Selesai
                                        </span>
                                    @else
                                        <span class="inline-block px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-semibold">
                                            → Baca
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Materi</h3>
                <p class="text-gray-600">Materi pelajaran belum tersedia. Silakan cek kembali nanti.</p>
            </div>
        @endif
    </div>
</body>
</html>

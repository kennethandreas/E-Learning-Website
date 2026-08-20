<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Aktivitas Pembelajaran - SDN Susukan 08 Pagi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="bg-green-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('landing') }}" class="flex items-center space-x-4 hover:opacity-90 transition">
                    <img src="{{ asset('img/logo.svg') }}" alt="SDN Susukan 08 Pagi" class="h-14 w-auto">
                    <span class="text-lg font-semibold hidden sm:inline">SDN Susukan 08 Pagi</span>
                </a>
                <div class="flex items-center space-x-2">
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- NISN Input Card -->
        <div class="bg-green-50 border-2 border-green-200 rounded-xl shadow-md p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Masukkan NISN Siswa</h2>
            <form method="GET" action="{{ route('aktivitas.index') }}" class="space-y-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400 inline-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14v9M12 14l-9-5M12 14l9-5M12 23v-9" />
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        name="nisn" 
                        id="nisn" 
                        value="{{ request('nisn', auth()->check() && auth()->user()->role == 'student' ? auth()->user()->nisn : '') }}" 
                        placeholder="NISN Siswa Anda" 
                        class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-500 focus:border-green-500 focus:bg-white"
                        @if(auth()->check() && auth()->user()->role == 'student') readonly @endif
                    >
                </div>
                <button 
                    type="submit" 
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition"
                >
                    Tampilkan
                </button>
            </form>
        </div>

        <!-- Academic Progress Section -->
        @if($student)
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 text-center">
                    Berikut Adalah Pekembangan Akademik Ananda {{ $student->name }}
                </h1>
            </div>

            <!-- Results Card -->
            <div class="bg-white border-2 border-gray-900 rounded-xl shadow-lg p-6">
                @if(count($academicProgress) > 0)
                    <div class="space-y-3">
                        @foreach($academicProgress as $progress)
                        <div class="bg-gray-100 rounded-lg px-4 py-3 flex items-center justify-between">
                            <span class="text-gray-900 font-medium">{{ $progress['subject'] }}</span>
                            <span class="text-gray-900 font-bold text-lg">{{ $progress['score'] }}</span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-gray-500">Belum ada data perkembangan akademik untuk siswa ini.</p>
                        <p class="text-sm text-gray-400 mt-2">Data akan muncul setelah siswa menyelesaikan quiz.</p>
                    </div>
                @endif
            </div>
        @elseif(request('nisn'))
            <div class="bg-yellow-50 border-2 border-yellow-200 rounded-xl p-6 text-center">
                <svg class="mx-auto h-12 w-12 text-yellow-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <p class="text-yellow-800 font-medium">NISN tidak ditemukan</p>
                <p class="text-sm text-yellow-600 mt-2">Silakan periksa kembali NISN yang Anda masukkan.</p>
            </div>
        @else
            <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-6 text-center">
                <svg class="mx-auto h-12 w-12 text-blue-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-blue-800 font-medium">Masukkan NISN untuk melihat perkembangan akademik</p>
                <p class="text-sm text-blue-600 mt-2">Jika Anda adalah siswa, NISN Anda akan terisi otomatis.</p>
            </div>
        @endif
    </div>
</body>
</html>

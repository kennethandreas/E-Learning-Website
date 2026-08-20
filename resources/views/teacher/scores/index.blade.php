<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Nilai Siswa - SDN Susukan 08 Pagi</title>
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
                    <a href="{{ route('teacher.dashboard') }}" class="text-white/90 hover:text-white text-sm">Dashboard</a>
                    <a href="{{ route('teacher.materi.index') }}" class="text-white/90 hover:text-white text-sm">Materi</a>
                    <a href="{{ route('teacher.quiz.index') }}" class="text-white/90 hover:text-white text-sm">Kuis</a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('teacher.profile.edit') }}" class="flex items-center space-x-2 text-white/90 hover:text-white">
                        <span class="text-sm">{{ auth()->user()->name }}</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-white/90 hover:text-white text-sm">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <a href="{{ route('teacher.dashboard') }}" class="text-blue-600 hover:text-blue-700 font-semibold text-sm mb-4 inline-block">← Kembali ke Dashboard</a>
            <h1 class="text-4xl font-bold text-gray-900">Nilai Siswa</h1>
            <p class="text-gray-600 mt-2">Pantau nilai kuis setiap siswa Anda</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                <p class="text-sm text-gray-600">Total Siswa</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $studentScores->count() }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                <p class="text-sm text-gray-600">Rata-rata Nilai</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($studentScores->avg('average_score'), 1) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-orange-500">
                <p class="text-sm text-gray-600">Nilai Tertinggi</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($studentScores->max('average_score'), 1) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
                <p class="text-sm text-gray-600">Nilai Terendah</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($studentScores->min('average_score'), 1) }}</p>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <form method="GET" action="{{ route('teacher.scores') }}" class="flex gap-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NISN siswa..." 
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <select name="sort" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="name" @if(request('sort') === 'name') selected @endif>Urutkan Nama</option>
                    <option value="score_desc" @if(request('sort') === 'score_desc') selected @endif>Nilai Tertinggi</option>
                    <option value="score_asc" @if(request('sort') === 'score_asc') selected @endif>Nilai Terendah</option>
                </select>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition">
                    Cari
                </button>
            </form>
        </div>

        <!-- Students Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Nama Siswa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">NISN</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Total Kuis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Rata-rata Nilai</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($studentScores as $score)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $score['name'] }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="text-sm text-gray-600">{{ $score['nisn'] }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        {{ $score['total_attempts'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <span class="text-2xl font-bold
                                            @if($score['average_score'] >= 80)
                                                text-green-600
                                            @elseif($score['average_score'] >= 60)
                                                text-yellow-600
                                            @else
                                                text-red-600
                                            @endif">
                                            {{ number_format($score['average_score'], 1) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($score['average_score'] >= 80)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Lulus
                                        </span>
                                    @elseif($score['average_score'] >= 60)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Cukup
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Perlu Perbaikan
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="#" class="text-blue-600 hover:text-blue-900 font-semibold text-sm">Lihat Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <p>Belum ada data nilai siswa</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if(is_object($studentScores) && method_exists($studentScores, 'links'))
            <div class="mt-6">
                {{ $studentScores->links() }}
            </div>
        @endif
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t mt-12">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-gray-500 text-sm">&copy; {{ date('Y') }} SDN Susukan 08 Pagi. All rights reserved.</p>
        </div>
    </footer>

    <script>
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    </script>
</body>
</html>

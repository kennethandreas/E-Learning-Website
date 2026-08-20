<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Kuis - SDN Susukan 08 Pagi</title>
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
                    <a href="{{ route('teacher.quiz.index') }}" class="font-semibold border-b-2 border-white text-sm">Kuis</a>
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
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-gray-900">Kelola Kuis</h1>
                <p class="text-gray-600 mt-2">Buat, edit, dan kelola kuis untuk siswa Anda</p>
            </div>
            <a href="{{ route('teacher.quiz.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                + Buat Kuis Baru
            </a>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-lg mb-6">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <!-- Quizzes Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Judul Kuis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Kelas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Soal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Durasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Nilai Lulus</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($quizzes as $quiz)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $quiz->judul }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ Str::limit($quiz->deskripsi, 40) }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ is_array($quiz->kelas) ? implode(', ', $quiz->kelas) : $quiz->kelas }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        {{ $quiz->questions_count ?? 0 }} soal
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="text-sm text-gray-900">{{ $quiz->durasi ?? 30 }} menit</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="text-sm text-gray-900">{{ $quiz->passing_score ?? 60 }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($quiz->is_active ?? true)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('teacher.quiz.edit', $quiz->id) }}" class="text-blue-600 hover:text-blue-900 font-semibold text-sm">Edit</a>
                                    <form method="POST" action="{{ route('teacher.quiz.destroy', $quiz->id) }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-semibold text-sm ml-4" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <p>Belum ada kuis. <a href="{{ route('teacher.quiz.create') }}" class="text-purple-600 font-semibold">Buat sekarang</a></p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($quizzes->hasPages())
            <div class="mt-6">
                {{ $quizzes->links() }}
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

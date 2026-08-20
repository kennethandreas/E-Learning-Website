<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($quiz) ? 'Edit' : 'Buat' }} Kuis - SDN Susukan 08 Pagi</title>
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

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <a href="{{ route('teacher.quiz.index') }}" class="text-purple-600 hover:text-purple-700 font-semibold text-sm mb-4 inline-block">← Kembali ke Daftar Kuis</a>
            <h1 class="text-4xl font-bold text-gray-900">{{ isset($quiz) ? 'Edit Kuis' : 'Buat Kuis Baru' }}</h1>
            <p class="text-gray-600 mt-2">{{ isset($quiz) ? 'Perbarui informasi kuis Anda' : 'Lengkapi semua field untuk membuat kuis pembelajaran' }}</p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow p-8">
            @php
                $selectedKelas = [];

                if (old('kelas')) {
                    $selectedKelas = old('kelas');
                } elseif (isset($quiz)) {
                    if (is_array($quiz->kelas)) {
                        $selectedKelas = $quiz->kelas;
                    } elseif (is_string($quiz->kelas)) {
                        $decoded = json_decode($quiz->kelas, true);
                        $selectedKelas = is_array($decoded)
                            ? $decoded
                            : [$quiz->kelas];
                    }
                }
            @endphp
            <form method="POST" action="{{ isset($quiz) ? route('teacher.quiz.update', $quiz->id) : route('teacher.quiz.store') }}">
                @csrf
                @if(isset($quiz))
                    @method('PUT')
                @endif

                <!-- Judul -->
                <div class="mb-6">
                    <label for="judul" class="block text-sm font-semibold text-gray-900 mb-2">Judul Kuis <span class="text-red-600">*</span></label>
                    <input type="text" id="judul" name="judul" value="{{ old('judul', $quiz->judul ?? '') }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('judul') border-red-500 @enderror"
                        placeholder="Contoh: Kuis Bilangan Bulat Bab 2" required>
                    @error('judul')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kelas -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                        Kelas <span class="text-red-600">*</span>
                    </label>

                    <div class="grid grid-cols-3 gap-4">
                        @for ($kelas = 6; $kelas >= 1; $kelas--)
                            @foreach (['A', 'B', 'C'] as $huruf)
                                @php
                                    $kodeKelas = $kelas . $huruf;
                                @endphp

                                <label class="flex items-center space-x-2">
                                    <input
                                        type="checkbox"
                                        name="kelas[]"
                                        value="{{ $kodeKelas }}"
                                        class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                                        {{ in_array($kodeKelas, $selectedKelas) ? 'checked' : '' }}
                                    >
                                    <span class="text-sm">Kelas {{ $kodeKelas }}</span>
                                </label>
                            @endforeach
                        @endfor
                    </div>
                    @error('kelas')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div class="mb-6">
                    <label for="deskripsi" class="block text-sm font-semibold text-gray-900 mb-2">Deskripsi <span class="text-red-600">*</span></label>
                    <textarea id="deskripsi" name="deskripsi" rows="3" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('deskripsi') border-red-500 @enderror"
                        placeholder="Jelaskan apa yang diuji dalam kuis ini..." required>{{ old('deskripsi', $quiz->deskripsi ?? '') }}</textarea>
                    @error('deskripsi')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Durasi -->
                <div class="mb-6">
                    <label for="durasi" class="block text-sm font-semibold text-gray-900 mb-2">Durasi Kuis (menit) <span class="text-red-600">*</span></label>
                    <input type="number" id="durasi" name="durasi" value="{{ old('durasi', $quiz->durasi ?? 30) }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('durasi') border-red-500 @enderror"
                        placeholder="30" min="1" required>
                    @error('durasi')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Passing Score -->
                <div class="mb-6">
                    <label for="passing_score" class="block text-sm font-semibold text-gray-900 mb-2">Nilai Lulus (KKM) <span class="text-red-600">*</span></label>
                    <input type="number" id="passing_score" name="passing_score" value="{{ old('passing_score', $quiz->passing_score ?? 60) }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('passing_score') border-red-500 @enderror"
                        placeholder="60" min="0" max="100" required>
                    @error('passing_score')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="mb-8">
                    <label for="is_active" class="flex items-center">
                        <input type="checkbox" id="is_active" name="is_active" value="1" 
                            @if(old('is_active', $quiz->is_active ?? true))
                                checked
                            @endif
                            class="w-4 h-4 text-purple-600 rounded focus:ring-purple-500 border-gray-300">
                        <span class="ml-2 text-sm font-semibold text-gray-900">Aktifkan Kuis Ini</span>
                    </label>
                    <p class="text-gray-600 text-xs mt-2 ml-6">Siswa hanya dapat mengerjakan kuis yang aktif</p>
                </div>

                <!-- Buttons -->
                <div class="flex gap-4">
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-8 py-3 rounded-lg font-semibold transition">
                        {{ isset($quiz) ? 'Update Kuis' : 'Simpan Kuis' }}
                    </button>
                    <a href="{{ route('teacher.quiz.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-8 py-3 rounded-lg font-semibold transition">
                        Batal
                    </a>
                </div>

                @if(isset($quiz))
                    <div class="mt-8 pt-8 border-t">
                        <p class="text-sm text-gray-600 mb-4">
                            Setelah menyimpan, Anda dapat menambahkan soal ke kuis ini.
                        </p>
                        <a href="{{ route('teacher.quiz.questions', $quiz->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold text-sm transition">
                            Kelola Soal Kuis
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t mt-12">
        <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-gray-500 text-sm">&copy; {{ date('Y') }} SDN Susukan 08 Pagi. All rights reserved.</p>
        </div>
    </footer>

    <script>
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    </script>
</body>
</html>

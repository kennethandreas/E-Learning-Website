<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($materi) ? 'Edit' : 'Tambah' }} Materi - SDN Susukan 08 Pagi</title>
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
                    <a href="{{ route('teacher.materi.index') }}" class="font-semibold border-b-2 border-white text-sm">Materi</a>
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

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <a href="{{ route('teacher.materi.index') }}" class="text-green-600 hover:text-green-700 font-semibold text-sm mb-4 inline-block">← Kembali ke Daftar Materi</a>
            <h1 class="text-4xl font-bold text-gray-900">{{ isset($materi) ? 'Edit Materi' : 'Tambah Materi Baru' }}</h1>
            <p class="text-gray-600 mt-2">Lengkapi semua field untuk {{ isset($materi) ? 'mengupdate' : 'membuat' }} materi pembelajaran</p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow p-8">
            @php
                $selectedKelas = [];

                if (old('kelas')) {
                    $selectedKelas = old('kelas');
                } elseif (isset($materi)) {
                    if (is_array($materi->kelas)) {
                        $selectedKelas = $materi->kelas;
                    } elseif (is_string($materi->kelas)) {
                        $decoded = json_decode($materi->kelas, true);
                        $selectedKelas = is_array($decoded)
                            ? $decoded
                            : [$materi->kelas];
                    }
                }
            @endphp
            <form method="POST" action="{{ isset($materi) ? route('teacher.materi.update', $materi->id) : route('teacher.materi.store') }}">
                @csrf
                @if(isset($materi))
                    @method('PUT')
                @endif

                <!-- Judul -->
                <div class="mb-6">
                    <label for="judul" class="block text-sm font-semibold text-gray-900 mb-2">Judul Materi <span class="text-red-600">*</span></label>
                    <input type="text" id="judul" name="judul" value="{{ old('judul', $materi->judul ?? '') }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('judul') border-red-500 @enderror"
                        placeholder="Contoh: Pembelajaran Bilangan Bulat" required>
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
                    <textarea id="deskripsi" name="deskripsi" rows="4" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('deskripsi') border-red-500 @enderror"
                        placeholder="Jelaskan konten materi ini..." required>{{ old('deskripsi', $materi->deskripsi ?? '') }}</textarea>
                    @error('deskripsi')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Konten -->
                <div class="mb-6">
                    <label for="konten" class="block text-sm font-semibold text-gray-900 mb-2">Konten Materi <span class="text-red-600">*</span></label>
                    <textarea id="konten" name="konten" rows="8" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('konten') border-red-500 @enderror"
                        placeholder="Tuliskan konten lengkap materi pembelajaran..." required>{{ old('konten', $materi->konten ?? '') }}</textarea>
                    @error('konten')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Keyword Materi (untuk Chatbot) -->
                <div class="mb-6">
                    <label for="keywords" class="block text-sm font-semibold text-gray-900 mb-2">
                        Keyword Materi
                        <span class="text-gray-500 text-xs">(untuk chatbot)</span>
                    </label>

                    <input
                        type="text"
                        id="keywords"
                        name="keywords"
                        value="{{ old('keywords', $materi->keywords ?? '') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="contoh: fotosintesis, tumbuhan, daun"
                    >

                    <p class="text-gray-600 text-xs mt-2">
                        Masukkan 3–5 kata penting, dipisahkan dengan koma.  
                        Digunakan untuk menjawab pertanyaan siswa di chatbot.
                    </p>
                </div>

                <!-- Urutan -->
                <div class="mb-6">
                    <label for="urutan" class="block text-sm font-semibold text-gray-900 mb-2">Urutan Materi <span class="text-red-600">*</span></label>
                    <input type="number" id="urutan" name="urutan" value="{{ old('urutan', $materi->urutan ?? 1) }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('urutan') border-red-500 @enderror"
                        placeholder="1" min="1" required>
                    @error('urutan')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="mb-8">
                    <label for="is_active" class="flex items-center">
                        <input type="checkbox" id="is_active" name="is_active" value="1" 
                            @if(old('is_active', $materi->is_active ?? true))
                                checked
                            @endif
                            class="w-4 h-4 text-green-600 rounded focus:ring-green-500 border-gray-300">
                        <span class="ml-2 text-sm font-semibold text-gray-900">Aktifkan Materi Ini</span>
                    </label>
                    <p class="text-gray-600 text-xs mt-2 ml-6">Siswa hanya dapat melihat materi yang aktif</p>
                </div>

                <!-- Buttons -->
                <div class="flex gap-4">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-semibold transition">
                        {{ isset($materi) ? 'Update Materi' : 'Simpan Materi' }}
                    </button>
                    <a href="{{ route('teacher.materi.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-8 py-3 rounded-lg font-semibold transition">
                        Batal
                    </a>
                </div>
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

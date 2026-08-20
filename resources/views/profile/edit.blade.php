<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Profil - SDN Susukan 08 Pagi</title>
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
                    <a href="{{ route('dashboard') }}" class="hover:text-green-100 transition">Dashboard</a>
                    <a href="{{ route('profile.edit') }}" class="font-semibold border-b-2 border-white">Profilku</a>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Greeting -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-green-600 mb-2">Selamat Datang, Pahlawan Pembelajar!</h1>
            <p class="text-xl text-gray-700">Edit Profilmu Yuk!</p>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Foto Profilku Section -->
                <div class="bg-green-50 border-2 border-green-200 rounded-xl p-6">
                    <h2 class="text-xl font-bold text-green-600 mb-4">Foto Profilku</h2>
                    <div class="text-center">
                        <div class="mb-4">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-white shadow-lg">
                            @else
                                <div class="w-32 h-32 rounded-full mx-auto bg-green-500 flex items-center justify-center text-white text-4xl font-bold border-4 border-white shadow-lg">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <label class="cursor-pointer inline-block">
                            <input type="file" name="avatar" accept="image/*" class="hidden" id="avatarInput" onchange="previewAvatar(this)">
                            <span class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-medium">
                                Ubah Foto
                            </span>
                        </label>
                        <p class="text-xs text-gray-500 mt-2">PNG, JPG, GIF hingga 2MB</p>
                        @error('avatar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Informasi Pribadi Section -->
                <div class="lg:col-span-2 bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-green-600 mb-6">Informasi Pribadi</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Left Column -->
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-500 focus:border-green-500 focus:bg-white @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir', $user->tanggal_lahir ? $user->tanggal_lahir->format('Y-m-d') : '') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-500 focus:border-green-500 focus:bg-white @error('tanggal_lahir') border-red-500 @enderror">
                                @error('tanggal_lahir')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="nomor_telepon_orang_tua" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon Orang Tua</label>
                                <input type="text" name="nomor_telepon_orang_tua" id="nomor_telepon_orang_tua" value="{{ old('nomor_telepon_orang_tua', $user->nomor_telepon_orang_tua) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-500 focus:border-green-500 focus:bg-white @error('nomor_telepon_orang_tua') border-red-500 @enderror">
                                @error('nomor_telepon_orang_tua')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-4">
                            <div>
                                <label for="kelas" class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                                <select name="kelas" id="kelas" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-500 focus:border-green-500 focus:bg-white @error('kelas') border-red-500 @enderror">
                                    <option value="">Pilih Kelas</option>
                                    @for($i = 1; $i <= 6; $i++)
                                        @foreach(['A', 'B', 'C'] as $abjad)
                                            <option value="{{ $i }}{{ $abjad }}" {{ old('kelas', $user->kelas) == $i.$abjad ? 'selected' : '' }}>
                                                Kelas {{ $i }}{{ $abjad }}
                                            </option>
                                        @endforeach
                                    @endfor
                                </select>
                                @error('kelas')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email_orang_tua" class="block text-sm font-medium text-gray-700 mb-2">Email Orang Tua</label>
                                <input type="email" name="email_orang_tua" id="email_orang_tua" value="{{ old('email_orang_tua', $user->email_orang_tua) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-500 focus:border-green-500 focus:bg-white @error('email_orang_tua') border-red-500 @enderror">
                                @error('email_orang_tua')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="nisn" class="block text-sm font-medium text-gray-700 mb-2">NISN</label>
                                <input type="text" name="nisn" id="nisn" value="{{ old('nisn', $user->nisn) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-500 focus:border-green-500 focus:bg-white @error('nisn') border-red-500 @enderror">
                                @error('nisn')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tentang Aku Section -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-6">
                <h2 class="text-xl font-bold text-green-600 mb-4">Tentang Aku</h2>
                <textarea 
                    name="tentang_aku" 
                    id="tentang_aku" 
                    rows="5" 
                    placeholder="Ceritakan tentang dirimu, hobi, cita-cita, atau hal-hal yang kamu sukai..."
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-500 focus:border-green-500 focus:bg-white @error('tentang_aku') border-red-500 @enderror"
                >{{ old('tentang_aku', $user->tentang_aku) }}</textarea>
                @error('tentang_aku')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-center space-x-4">
                <button type="submit" class="px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold text-lg transition">
                    Simpan Perubahan
                </button>
                <a href="{{ route('dashboard') }}" class="px-8 py-3 bg-white border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-semibold text-lg transition">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <script>
        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const avatarContainer = document.querySelector('.w-32.h-32');
                    if (avatarContainer) {
                        avatarContainer.innerHTML = `<img src="${e.target.result}" alt="Avatar" class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-white shadow-lg">`;
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>

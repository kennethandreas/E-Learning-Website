<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Profil Guru - SDN Susukan 08 Pagi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white min-h-screen">
    <!-- Header -->
    <header class="bg-blue-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('landing') }}" class="flex items-center space-x-4 hover:opacity-90 transition">
                    <img src="{{ asset('img/logo.svg') }}" alt="SDN Susukan 08 Pagi" class="h-14 w-auto">
                    <span class="text-lg font-semibold hidden sm:inline">SDN Susukan 08 Pagi</span>
                </a>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('teacher.dashboard') }}" class="hover:text-blue-100 transition">Dashboard</a>
                    <a href="{{ route('teacher.profile.edit') }}" class="font-semibold border-b-2 border-white">Profilku</a>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Greeting -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-blue-600 mb-2">Selamat Datang, Pendidik Inspiratif!</h1>
            <p class="text-xl text-gray-700">Edit Profilmu Yuk!</p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Error Messages -->
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-lg mb-6">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('teacher.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Foto Profil Section -->
                <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-6">
                    <h2 class="text-xl font-bold text-blue-600 mb-4">Foto Profilku</h2>
                    <div class="text-center">
                        <div class="mb-4">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-white shadow-lg">
                            @else
                                <div class="w-32 h-32 rounded-full mx-auto bg-blue-500 flex items-center justify-center text-white text-4xl font-bold border-4 border-white shadow-lg">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <label class="cursor-pointer inline-block">
                            <input type="file" name="avatar" accept="image/*" class="hidden" id="avatarInput" onchange="previewAvatar(this)">
                            <span class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
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
                    <h2 class="text-xl font-bold text-blue-600 mb-6">Informasi Pribadi</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Left Column -->
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white @error('email') border-red-500 @enderror">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir', $user->tanggal_lahir ? $user->tanggal_lahir->format('Y-m-d') : '') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white @error('tanggal_lahir') border-red-500 @enderror">
                                @error('tanggal_lahir')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-4">
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white @error('phone') border-red-500 @enderror">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                                <input type="text" name="address" id="address" value="{{ old('address', $user->address) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white @error('address') border-red-500 @enderror">
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="nip" class="block text-sm font-medium text-gray-700 mb-2">NIP (Opsional)</label>
                                <input type="text" name="nip" id="nip" value="{{ old('nip', $user->nip ?? '') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white @error('nip') border-red-500 @enderror">
                                @error('nip')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tentang Aku Section -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-6">
                <h2 class="text-xl font-bold text-blue-600 mb-4">Tentang Aku</h2>
                <textarea 
                    name="tentang_aku" 
                    id="tentang_aku" 
                    rows="5" 
                    placeholder="Ceritakan tentang pengalaman mengajar, spesialisasi, hobi, atau hal-hal yang Anda sukai..."
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white @error('tentang_aku') border-red-500 @enderror"
                >{{ old('tentang_aku', $user->tentang_aku ?? '') }}</textarea>
                @error('tentang_aku')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Section -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-6">
                <h2 class="text-xl font-bold text-blue-600 mb-4">Ubah Password (Opsional)</h2>
                <p class="text-sm text-gray-600 mb-4">Biarkan kosong jika tidak ingin mengubah password</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                        <input type="password" name="password" id="password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white @error('password') border-red-500 @enderror"
                            placeholder="Minimal 8 karakter">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white"
                            placeholder="Ulangi password baru">
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-center space-x-4">
                <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold text-lg transition">
                    Simpan Perubahan
                </button>
                <a href="{{ route('teacher.dashboard') }}" class="px-8 py-3 bg-white border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-semibold text-lg transition">
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

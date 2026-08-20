<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Guru - E-Learning</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mb-4">
                <a href="{{ route('landing') }}"
                class="inline-flex items-center text-sm text-gray-600 hover:text-green-600 transition">
                    ← Kembali ke Beranda
                </a>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Masuk Guru / Admin</h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Masukkan username dan password Anda untuk masuk
            </p>
        </div>
        @if(session('success'))
            <div class="mb-4 px-4 py-3 rounded-lg bg-yellow-100 text-yellow-800 text-sm">
                {{ session('success') }}
            </div>
        @endif
        <form class="mt-8 space-y-6" action="{{ route('login.teacher.post') }}" method="POST">
            @csrf
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="username" class="sr-only">Username</label>
                    <input id="username" name="username" type="text" required
                        class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm @error('username') border-red-500 @enderror"
                        placeholder="Username (atau email)" value="{{ old('username') }}">
                    @error('username')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" name="password" type="password" required
                        class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm @error('password') border-red-500 @enderror"
                        placeholder="Password">
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">Masuk</button>
            </div>

            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-gray-50 text-gray-500">
                        Belum punya akun?
                    </span>
                </div>
            </div>

            <a href="{{ route('register.teacher') }}"
            class="w-full flex justify-center py-2 px-4 border border-green-600
                    text-sm font-medium rounded-md text-green-600
                    hover:bg-green-50 transition">
                Daftar sebagai Guru
            </a>
        </form>
    </div>
</body>
</html>

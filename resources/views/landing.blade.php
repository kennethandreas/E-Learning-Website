<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SDN Susukan 08 Pagi - Portal Sekolah</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @php
        use Illuminate\Support\Facades\Storage;
    @endphp
    <style>
        .green-header {
            background-color: #22c55e; /* green-500 */
        }
        .welcome-card {
            background-color: #dcfce7; /* green-100 */
            border: 2px solid #86efac; /* green-300 */
        }
        .purple-card {
            background-color: #f3e8ff; /* purple-100 */
        }
        .purple-button {
            background-color: #c084fc; /* purple-400 */
        }
        .purple-button:hover {
            background-color: #a855f7; /* purple-500 */
        }
        
        /* Portal cards styling */
        .portal-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            border: 1px solid #e5e7eb;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        
        .portal-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }
        
        .portal-icon {
            display: flex;
            justify-content: center;
            margin-bottom: 1rem;
        }
        
        .portal-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            max-width: 100%;
        }
    </style>
</head>
<body class="bg-white min-h-screen">
    <!-- Header -->
    <header class="green-header text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('landing') }}" class="flex items-center space-x-4 hover:opacity-90 transition">
                    <img src="{{ asset('img/logo.svg') }}" alt="SDN Susukan 08 Pagi" class="h-14 w-auto">
                    <span class="text-lg font-semibold hidden sm:inline">SDN Susukan 08 Pagi</span>
                </a>
                <a href="{{ route('ai.index') }}" class="flex items-center space-x-1 bg-white/20 hover:bg-white/30 px-4 py-2 rounded-md text-sm transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Bantuan AI</span>
                </a>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 text-center">
                {{ session('success') }}
            </div>
        @endif

        <!-- Welcome Section -->
        <div class="welcome-card rounded-2xl p-6 md:p-8 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        Selamat Datang di SDN Susukan 08 Pagi!
                    </h1>
                    <p class="text-gray-700 text-base md:text-lg leading-relaxed">
                        Sekolah dasar yang ceria dan suportif untuk membentuk generasi penerus bangsa yang cerdas, kreatif, dan berakhlak mulia. Kami berkomitmen menyediakan lingkungan belajar terbaik.
                    </p>
                </div>
                <div>
                    <div class="bg-gradient-to-br from-green-200 to-green-300 rounded-xl overflow-hidden h-64 flex items-center justify-center relative">
                        @if($landingImageUrl)
                            <div class="h-[60vh] md:h-[70vh] lg:h-[80vh] overflow-hidden">
                                <img src="{{ $landingImageUrl }}"
                                    class="w-full h-full object-contain">
                            </div>
                        @else
                            <!-- Placeholder untuk foto sekolah -->
                            <div class="text-center">
                                <svg class="w-32 h-32 mx-auto text-green-600 opacity-60 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="text-green-700 font-semibold text-sm">Foto Sekolah</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Login Portals Section -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Masuk ke Portal Anda</h2>
            <div class="portal-grid">
                <!-- Student Login Card -->
                <div class="portal-card">
                    <div class="portal-icon">
                        <div class="bg-green-100 rounded-full p-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2 text-center">Masuk Siswa</h3>
                    <p class="text-gray-600 text-sm text-center mb-4 flex-grow">Akses dashboard belajar Anda</p>
                    <a href="{{ route('login.student') }}" class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition">
                        Masuk dengan NISN
                    </a>
                </div>

                <!-- Teacher Login Card -->
                <div class="portal-card">
                    <div class="portal-icon">
                        <div class="bg-blue-100 rounded-full p-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2 text-center">Masuk Guru</h3>
                    <p class="text-gray-600 text-sm text-center mb-4 flex-grow">Kelola materi dan kuis</p>
                    <a href="{{ route('login.teacher') }}" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition">
                        Masuk dengan Username
                    </a>
                </div>

                <!-- Parent Login Card -->
                <div class="portal-card">
                    <div class="portal-icon">
                        <div style="background-color: #f3f4f6; border-radius: 50%; padding: 1rem;">
                            <svg class="w-8 h-8" style="color: #7c3aed;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 8.048M12 20h9m-9 0a8 8 0 100-16 8 8 0 000 16z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2 text-center">Akses Orang Tua</h3>
                    <p class="text-gray-600 text-sm text-center mb-4 flex-grow">Pantau perkembangan anak</p>
                    <a href="{{ route('report.student') }}" class="block w-full text-center text-white font-semibold py-3 px-4 rounded-lg transition" style="background-color: #7c3aed;" onmouseover="this.style.backgroundColor='#6d28d9'" onmouseout="this.style.backgroundColor='#7c3aed'">
                        Lihat Laporan Anak
                    </a>
                </div>
            </div>
        </div>

        <!-- AI Assistant Section -->
        <div class="max-w-2xl mx-auto mb-8">
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200">
                <div class="purple-card rounded-xl p-6 text-center">
                    <div class="flex justify-center mb-4">
                        <div class="bg-purple-200 rounded-full p-4">
                            <svg class="w-12 h-12 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Butuh Bantuan Belajar?</h3>
                    <p class="text-gray-700 mb-6 text-sm md:text-base">
                        Asisten AI kami siap membantu Anda dengan semua pertanyaan pelajaran. Dapatkan jawaban cepat dan penjelasan mudah!
                    </p>
                    <a href="{{ route('ai.index') }}" class="inline-block purple-button text-white font-semibold py-3 px-6 rounded-lg transition hover:shadow-lg">
                        Tanya AI Sekarang
                    </a>
                </div>
            </div>
        </div>

    <!-- Footer -->
    <footer class="bg-gray-50 border-t mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <p class="text-center text-gray-600 text-sm">
                &copy; {{ date('Y') }} SDN Susukan 08 Pagi. All rights reserved.
            </p>
        </div>
    </footer>
</body>
</html>

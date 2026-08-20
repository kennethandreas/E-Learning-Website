<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'E-Learning Platform')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
    @auth
        <!-- Navigation -->
        <nav class="bg-green-600 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-20">
                    <a href="{{ route('landing') }}" class="flex items-center space-x-4 hover:opacity-90 transition">
                        <img src="{{ asset('img/logo.svg') }}" alt="SDN Susukan 08 Pagi" class="h-14 w-auto" onerror="this.style.display='none'">
                        <span class="text-lg font-semibold hidden md:inline">SDN Susukan 08 Pagi</span>
                    </a>

                    <div class="hidden md:flex items-center space-x-6">
                        <a href="{{ route('landing') }}" class="hover:text-green-100">Beranda</a>
                        <a href="{{ route('dashboard') }}" class="font-semibold">Dashboard</a>
                    </div>

                    <div class="flex items-center space-x-3">
                        <a href="{{ route('ai.index') }}" class="bg-white text-green-700 px-3 py-1 rounded-md text-sm hover:opacity-95">Bantuan AI</a>
                        <div class="hidden md:flex items-center space-x-3">
                            <a href="{{ route('profile.edit') }}" class="flex items-center space-x-2 text-white/90">
                                @if(auth()->user()->avatar)
                                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar" class="h-8 w-8 rounded-full object-cover border-2 border-white">
                                @else
                                    <div class="h-8 w-8 rounded-full bg-white/20 flex items-center justify-center text-white font-semibold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                                @endif
                                <span class="text-sm">{{ auth()->user()->name }}</span>
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-white/90 text-sm">Logout</button>
                            </form>
                        </div>
                        <!-- Mobile menu button -->
                        <div class="md:hidden">
                            <button type="button" class="mobile-menu-button inline-flex items-center justify-center p-2 rounded-md text-white/90">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Mobile menu -->
            <div class="mobile-menu hidden md:hidden bg-white">
                <div class="pt-2 pb-3 space-y-1">
                    <a href="{{ route('dashboard') }}" class="block pl-3 pr-4 py-2 text-base font-medium">Dashboard</a>
                    <a href="{{ route('materi.index') }}" class="block pl-3 pr-4 py-2 text-base font-medium">Materi</a>
                    <a href="{{ route('quiz.index') }}" class="block pl-3 pr-4 py-2 text-base font-medium">Quiz</a>
                    <a href="{{ route('aktivitas.index') }}" class="block pl-3 pr-4 py-2 text-base font-medium">Aktivitas</a>
                    <a href="{{ route('ai.index') }}" class="block pl-3 pr-4 py-2 text-base font-medium">AI Assistant</a>
                    <a href="{{ route('profile.edit') }}" class="block pl-3 pr-4 py-2 text-base font-medium">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left pl-3 pr-4 py-2 text-base font-medium">Logout</button>
                    </form>
                </div>
            </div>
        </nav>
    @endauth

    <!-- Main Content -->
    <main class="py-6">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-auto">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-gray-500 text-sm">&copy; {{ date('Y') }} E-Learning Platform. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.querySelector('.mobile-menu-button')?.addEventListener('click', function() {
            document.querySelector('.mobile-menu').classList.toggle('hidden');
        });
    </script>
    @yield('scripts')
</body>
</html>


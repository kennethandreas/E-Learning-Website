<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - SDN Susukan 08 Pagi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Fade-in animation for reminder modal */
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes fadeOut { from { opacity: 1; } to { opacity: 0; } }
        @keyframes slideIn { from { transform: scale(0.95); opacity: 0; } to { transform: scale(1); opacity: 1; } }

        .fade-in { animation: fadeIn 0.5s ease-in-out; }
        .fade-out { animation: fadeOut 0.3s ease-in-out forwards; }
        .slide-in { animation: slideIn 0.3s ease-out; }

        .reminder-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            width: 100%; height: 100%;
            z-index: 9999 !important;
            opacity: 0;
            pointer-events: none;
            align-items: center;
            justify-content: center;
        }

        .reminder-overlay.active {
            display: flex;
            opacity: 1;
            pointer-events: auto;
            animation: fadeIn 0.5s ease-in-out forwards;
        }

        .reminder-overlay.hiding {
            animation: fadeOut 0.5s ease-in-out forwards;
            pointer-events: none;
        }

        body.reminder-showing { overflow: hidden; }
        body.reminder-showing #dashboard-content {
            opacity: 0.5;
            pointer-events: none;
            filter: blur(2px);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div id="dailyReminderOverlay" class="reminder-overlay">
        <div class="absolute inset-0 bg-black/60 top-0 left-0 right-0 bottom-0" id="reminderBackdrop"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl p-8 max-w-2xl w-full mx-4 slide-in z-50" id="reminderModal">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Pengingat Hari Ini</h3>
                <button id="reminderClose" class="text-gray-400 hover:text-gray-600 text-3xl leading-none transition" type="button">&times;</button>
            </div>
            <div id="reminderContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-96 overflow-y-auto">
                @forelse($pending as $item)
                <div class="bg-gradient-to-br from-green-50 to-blue-50 rounded-lg border border-green-100 p-4 flex flex-col items-center reminder-card" data-key="{{ $item['key'] }}">
                    <div class="w-full h-24 bg-gradient-to-br from-green-100 to-blue-100 rounded-md mb-3 flex items-center justify-center">
                        @if($item['key'] == 'bangun_pagi')
                            <svg class="w-12 h-12 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        @elseif($item['key'] == 'beribadah')
                            <svg class="w-12 h-12 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        @else
                            <svg class="w-12 h-12 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        @endif
                    </div>
                    <div class="text-center text-gray-800 mb-3 font-medium text-sm">
                        {{ $item['label'] }}
                    </div>
                    <button type="button" data-key="{{ $item['key'] }}" class="mark-done w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-3 rounded-lg transition text-sm">SUDAH</button>
                </div>
                @empty
                <div class="col-span-full text-center text-gray-500 py-6">
                    Tidak ada pengingat hari ini
                </div>
                @endforelse
            </div>
            <div class="mt-6 pt-6 border-t border-gray-200 flex justify-end">
                <button id="continueBtn" type="button" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition">
                    Lanjut ke Dashboard
                </button>
            </div>
        </div>
    </div>

    <div id="dashboard-content">
    <header class="bg-green-600 text-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('landing') }}" class="flex items-center space-x-4 hover:opacity-90 transition">
                    <img src="{{ asset('img/logo.svg') }}" alt="SDN Susukan 08 Pagi" class="h-14 w-auto">
                    <span class="text-lg font-semibold hidden sm:inline">SDN Susukan 08 Pagi</span>
                </a>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('landing') }}" class="hover:text-green-100 transition text-sm">Beranda</a>
                    <a href="{{ route('dashboard') }}" class="font-semibold border-b-2 border-white text-sm">Dashboard</a>
                </div>
                <div class="flex items-center space-x-4">
                    <span id="header-points" class="bg-white/20 px-3 py-1 rounded-full text-sm font-bold">Poin: {{ auth()->user()->points ?? 0 }}</span>
                    <a href="{{ route('ai.index') }}" class="bg-white text-green-700 px-3 py-1 rounded-md text-sm font-medium hover:opacity-95">Bantuan AI</a>
                    <div class="relative group">
                        <button class="flex items-center space-x-2 text-white hover:text-green-100 transition px-3 py-2 rounded-lg">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                            <span class="text-sm hidden sm:inline">{{ auth()->user()->name }}</span>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white text-gray-800 rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <div class="px-4 py-3 border-b border-gray-200">
                                <p class="text-sm font-semibold">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500">{{ auth()->user()->email ?? auth()->user()->nisn }}</p>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm hover:bg-gray-100 transition">Edit Profil</a>
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 transition text-red-600 font-medium cursor-pointer">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-green-50 border border-green-200 rounded-3xl p-8 mb-12 shadow-sm flex items-center gap-8">
            <div class="flex-1">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Selamat Datang, {{ auth()->user()->name }}!</h1>
                <p class="text-gray-700 text-base">Mari belajar hal-hal baru dan raih prestasi yang lebih tinggi setiap hari. Semangat!</p>
                <div class="mt-4">
                    <a href="{{ route('ai.index') }}" class="inline-block bg-purple-500 hover:bg-purple-600 text-white px-6 py-2 rounded-lg font-medium transition">Bantuan AI</a>
                </div>
            </div>
            <div class="hidden lg:block flex-shrink-0">
                <div class="w-40 h-40 bg-white rounded-2xl shadow-md flex items-center justify-center overflow-hidden">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                            class="w-full h-full object-cover rounded-2xl">
                    @else
                        <div class="w-full h-full bg-green-500 flex items-center justify-center text-white text-6xl font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="mb-12">
            <div class="flex items-center space-x-3 mb-6">
                <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                <h2 class="text-2xl font-bold text-gray-900">Materi Pembelajaran</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($materiList as $materi)
                <a href="{{ route('materi.show', $materi->id) }}" class="block bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="h-40 bg-gradient-to-br from-green-100 to-blue-100 flex items-center justify-center overflow-hidden">
                        @if($materi->thumbnail) <img src="{{ asset('storage/' . $materi->thumbnail) }}" class="w-full h-full object-cover">
                        @else <svg class="w-16 h-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253" /></svg> @endif
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $materi->judul }}</h3>
                        <p class="text-sm text-gray-600 line-clamp-2">{{ $materi->deskripsi ?? 'Pelajari materi ini dengan baik' }}</p>
                    </div>
                </a>
                @empty <div class="col-span-3 text-center text-gray-500 py-6">Belum ada materi pembelajaran</div> @endforelse
            </div>
        </div>

        <div class="mb-12">
            <div class="flex items-center space-x-3 mb-6">
                <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <h2 class="text-2xl font-bold text-gray-900">Kuis Harian</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($dailyQuizzes as $quiz)
                <div class="bg-white rounded-2xl shadow-md p-6 flex flex-col">
                    <div class="flex items-start justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">{{ $quiz->judul }}</h3>
                        @if($quiz->is_new) <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-bold rounded">Baru</span> @endif
                    </div>
                    <p class="text-sm text-gray-600 mb-6 flex-1">Uji pemahamanmu dengan kuis ini!</p>
                    <a href="{{ route('quiz.show', $quiz->id) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition text-center font-medium">Halaman Kuis</a>
                </div>
                @empty <div class="col-span-3 text-center text-gray-500 py-6">Belum ada kuis harian</div> @endforelse
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Profil Saya</h2>
                <div class="bg-green-50 rounded-2xl p-6 shadow-sm border border-green-100 text-center">
                    <div class="mb-4">
                        @if(auth()->user()->avatar) <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-white shadow-md">
                        @else <div class="w-32 h-32 rounded-full mx-auto bg-green-500 flex items-center justify-center text-white text-4xl font-bold border-4 border-white shadow-md">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div> @endif
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ auth()->user()->name }}</h3>
                    <p class="text-gray-700 text-sm mb-4">Kelas {{ auth()->user()->kelas ?? '-' }}, NISN: {{ auth()->user()->nisn ?? '-' }}</p>
                    <a href="{{ route('profile.edit') }}" class="w-full block bg-white text-green-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-100 transition">Edit Profil</a>
                </div>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Lencana yang Diperoleh</h2>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6" id="badge-display-container">
                    @if(auth()->user()->badge)
                        <div class="grid grid-cols-1 gap-4" id="badge-list">
                            <div class="text-center">
                                <div id="badge-box" class="w-20 h-20 mx-auto mb-3 rounded-xl shadow-md flex items-center justify-center border transition-all duration-500">
                                    <svg id="badge-icon" class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                </div>
                                <p class="text-sm font-bold text-gray-800" id="badge-name-text">{{ auth()->user()->badge->name }}</p>
                            </div>
                        </div>
                    @else
                        <p class="text-center text-gray-500" id="empty-badge-text">Belum ada lencana yang diperoleh</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const overlay = document.getElementById('dailyReminderOverlay');
            const closeBtn = document.getElementById('reminderClose');
            const continueBtn = document.getElementById('continueBtn');
            const backdrop = document.getElementById('reminderBackdrop');
            const pendingItems = parseInt('{{ count($pending) }}');

            if (pendingItems > 0) {
                overlay.classList.add('active');
                document.body.classList.add('reminder-showing');
            }

            function hideReminder() {
                overlay.classList.remove('active');
                overlay.classList.add('hiding');
                setTimeout(() => {
                    overlay.style.display = 'none';
                    document.body.classList.remove('reminder-showing');
                }, 500);
            }

            if (closeBtn) closeBtn.onclick = hideReminder;
            if (continueBtn) continueBtn.onclick = hideReminder;
            if (backdrop) backdrop.onclick = hideReminder;

            // FUNGSI UNTUK MENGUBAH ANIMASI & WARNA BADGE SESUAI PERINGKAT
            function updateBadgeVisual(points, badgeName) {
                const badgeBox = document.getElementById('badge-box');
                const badgeIcon = document.getElementById('badge-icon');
                const badgeText = document.getElementById('badge-name-text');

                if (!badgeBox) return;
                badgeText.textContent = badgeName;

                // Logika Peringkat: Silver (1-20), Gold (21-30), Platinum (31-50), Diamond (51+)
                if (points <= 20) { 
                    // SILVER
                    badgeBox.className = "w-20 h-20 mx-auto mb-3 bg-gradient-to-br from-gray-100 to-gray-300 rounded-xl shadow-md flex items-center justify-center border border-gray-400 fade-in";
                    badgeIcon.setAttribute("class", "w-12 h-12 text-gray-500");
                } 
                else if (points <= 30) { 
                    // GOLD
                    badgeBox.className = "w-20 h-20 mx-auto mb-3 bg-gradient-to-br from-yellow-100 to-yellow-400 rounded-xl shadow-md flex items-center justify-center border border-yellow-500 fade-in";
                    badgeIcon.setAttribute("class", "w-12 h-12 text-yellow-600");
                }
                else if (points <= 50) { 
                    // PLATINUM
                    badgeBox.className = "w-20 h-20 mx-auto mb-3 bg-gradient-to-br from-blue-50 to-indigo-200 rounded-xl shadow-md flex items-center justify-center border border-blue-300 fade-in";
                    badgeIcon.setAttribute("class", "w-12 h-12 text-indigo-500");
                }
                else { 
                    // DIAMOND
                    badgeBox.className = "w-20 h-20 mx-auto mb-3 bg-gradient-to-br from-cyan-100 to-blue-400 rounded-xl shadow-md flex items-center justify-center border border-cyan-400 animate-pulse";
                    badgeIcon.setAttribute("class", "w-12 h-12 text-blue-700");
                }
            }

            // Inisialisasi visualisasi saat halaman dimuat
            @if(auth()->user()->badge)
                updateBadgeVisual({{ auth()->user()->points ?? 0 }}, '{{ auth()->user()->badge->name }}');
            @endif

            // HANDLE MARK DONE (LOGIKA AUTOMATIC BADGE & POINT)
            document.querySelectorAll('.mark-done').forEach(button => {
                button.addEventListener('click', function(e) {
                    const key = this.getAttribute('data-key');
                    const card = this.closest('.reminder-card');
                    this.disabled = true;
                    this.textContent = '...';

                    fetch('{{ route("checklist.complete") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ key: key })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Checklist Selesai 🎉',
                                text: '+2 poin berhasil ditambahkan',
                                timer: 1500,
                                showConfirmButton: false
                            });

                            const headerPoints = document.getElementById('header-points');
                            if(headerPoints) headerPoints.textContent = 'Poin: ' + data.points;

                            if (data.naikBadge) {
                                setTimeout(() => {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Pangkat Naik! 🎖️',
                                        text: 'Selamat! Kamu sekarang peringkat ' + data.badge,
                                    });

                                    const badgeDisplay = document.getElementById('badge-display-container');
                                    const emptyText = document.getElementById('empty-badge-text');
                                    if(emptyText) emptyText.remove();

                                    // Jika kontainer belum ada struktur lencana, buatkan
                                    if (!document.getElementById('badge-list')) {
                                        badgeDisplay.innerHTML = `
                                            <div class="grid grid-cols-1 gap-4" id="badge-list">
                                                <div class="text-center">
                                                    <div id="badge-box" class="w-20 h-20 mx-auto mb-3 rounded-xl shadow-md flex items-center justify-center border transition-all duration-500">
                                                        <svg id="badge-icon" class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                                    </div>
                                                    <p class="text-sm font-bold text-gray-800" id="badge-name-text"></p>
                                                </div>
                                            </div>
                                        `;
                                    }
                                    updateBadgeVisual(data.points, data.badge);
                                }, 1600);
                            }

                            card.classList.add('fade-out');
                            setTimeout(() => {
                                card.remove();
                                if (document.querySelectorAll('.reminder-card').length === 0) hideReminder();
                            }, 300);
                        }
                    })
                    .catch(err => {
                        this.disabled = false;
                        this.textContent = 'SUDAH';
                        alert('Terjadi kesalahan.');
                    });
                });
            });
        });
    </script>
</body>
</html>
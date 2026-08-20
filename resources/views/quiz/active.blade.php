@extends('layouts.app')

@section('title', 'Quiz yang Sedang Berlangsung')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Quiz yang Sedang Berlangsung</h1>
        <p class="mt-2 text-gray-600">Daftar quiz yang sedang aktif dan dapat dikerjakan</p>
    </div>

    @if($activeQuizzes->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($activeQuizzes as $quiz)
        <div class="bg-white rounded-lg shadow-md overflow-hidden border-2 border-blue-200">
            <div class="bg-blue-600 text-white px-6 py-3">
                <h3 class="text-xl font-semibold">{{ $quiz->judul }}</h3>
            </div>
            <div class="p-6">
                @if($quiz->deskripsi)
                    <p class="text-gray-600 text-sm mb-4">{{ Str::limit($quiz->deskripsi, 100) }}</p>
                @endif
                
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Durasi: {{ $quiz->durasi }} menit
                    </div>
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Jumlah Soal: {{ $quiz->jumlah_soal }}
                    </div>
                    @if($quiz->waktu_selesai)
                        <div class="flex items-center text-sm text-orange-600">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Berakhir: {{ $quiz->waktu_selesai->format('d M Y, H:i') }}
                        </div>
                    @endif
                </div>

                <a href="{{ route('quiz.show', $quiz->id) }}" class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                    Mulai Quiz
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-lg shadow p-12 text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada quiz aktif</h3>
        <p class="mt-1 text-sm text-gray-500">Tidak ada quiz yang sedang berlangsung saat ini.</p>
    </div>
    @endif
</div>
@endsection


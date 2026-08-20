@extends('layouts.app')

@section('title', $quiz->judul)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $quiz->judul }}</h1>
        
        @if($quiz->deskripsi)
            <p class="text-gray-600 mb-6">{{ $quiz->deskripsi }}</p>
        @endif

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Quiz</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-gray-700">Durasi: <strong>{{ $quiz->durasi }} menit</strong></span>
                </div>
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span class="text-gray-700">
                        Jumlah Soal: <strong>{{ $quiz->questions()->count() }}</strong>
                    </span>
                </div>
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-gray-700">Total Nilai: <strong>{{ $quiz->total_nilai }}</strong></span>
                </div>
            </div>
        </div>

        @if($completedAttempt)
            <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-green-900 mb-2">Anda sudah menyelesaikan quiz ini</h3>
                <p class="text-green-700 mb-4">Nilai Anda: <strong>{{ $completedAttempt->nilai }}/{{ $quiz->total_nilai }}</strong></p>
                <a href="{{ route('quiz.result', $completedAttempt->id) }}" class="text-green-700 hover:text-green-900 font-medium">
                    Lihat Hasil Detail →
                </a>
            </div>
        @endif

        <div class="flex items-center justify-between">
            <a href="{{ route('quiz.index') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                ← Kembali ke Daftar Quiz
            </a>
            
            @if(!$completedAttempt)
                <form action="{{ route('quiz.start', $quiz->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold text-lg">
                        Mulai Quiz
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection


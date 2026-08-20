@extends('layouts.app')

@section('title', 'Hasil Quiz')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">
            Hasil Quiz: {{ $attempt->quiz->judul }}
        </h1>
        
        <!-- Score Summary -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-8 text-white mb-8">
            <div class="text-center">
                <p class="text-lg mb-2">Nilai Anda</p>
                <p class="text-5xl font-bold mb-2">
                    {{ $attempt->nilai }}/{{ $attempt->quiz->total_nilai }}
                </p>
                <p class="text-blue-100">
                    Benar: {{ $attempt->jumlah_benar }} |
                    Salah: {{ $attempt->jumlah_salah }}
                </p>
            </div>
        </div>

        <!-- Questions Review -->
        <div class="space-y-6">
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Review Jawaban</h2>

            @foreach($questions as $index => $question)
                @php
                    $userAnswer = $answers->get($question->id);
                    $isCorrect = $userAnswer && $userAnswer->is_correct;
                    $labels = ['A', 'B', 'C', 'D'];
                @endphp

                <div class="border-2 rounded-lg p-6 {{ $isCorrect ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50' }}">
                    <div class="flex items-start mb-4">
                        <span class="flex-shrink-0 w-8 h-8 {{ $isCorrect ? 'bg-green-600' : 'bg-red-600' }} text-white rounded-full flex items-center justify-center font-semibold mr-3">
                            {{ $index + 1 }}
                        </span>
                        <div class="flex-1">
                            <p class="text-lg font-medium text-gray-900 mb-2">
                                {{ $question->pertanyaan }}
                            </p>
                            <div class="flex items-center space-x-2 mb-4">
                                @if($isCorrect)
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                                        ✓ Benar
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-semibold">
                                        ✗ Salah
                                    </span>
                                @endif
                                <span class="text-sm text-gray-600">
                                    Poin: {{ $userAnswer ? $userAnswer->poin : 0 }}/{{ $question->poin }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Pilihan Jawaban -->
                    <div class="ml-11 space-y-2">
                        @foreach($question->pilihan as $i => $pilihan)
                            @php
                                $label = $labels[$i];
                            @endphp

                            <div class="p-3 rounded-lg {{
                                $label == $question->jawaban_benar
                                    ? 'bg-green-100 border-2 border-green-500'
                                    : ($userAnswer && $userAnswer->jawaban == $label && !$isCorrect
                                        ? 'bg-red-100 border-2 border-red-500'
                                        : 'bg-gray-50 border border-gray-200')
                            }}">
                                <div class="flex items-center">
                                    <span class="font-semibold mr-2">{{ $label }}.</span>
                                    <span class="text-gray-700">{{ $pilihan }}</span>

                                    @if($label == $question->jawaban_benar)
                                        <span class="ml-auto text-green-700 font-semibold">
                                            ✓ Jawaban Benar
                                        </span>
                                    @endif

                                    @if($userAnswer && $userAnswer->jawaban == $label && $label != $question->jawaban_benar)
                                        <span class="ml-auto text-red-700 font-semibold">
                                            ✗ Jawaban Anda
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8 flex justify-center space-x-4">
            <a href="{{ route('quiz.index') }}"
               class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold">
                Kembali ke Daftar Quiz
            </a>
            <a href="{{ route('dashboard') }}"
               class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold">
                Ke Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
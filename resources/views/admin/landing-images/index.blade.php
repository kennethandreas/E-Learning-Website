@extends('layouts.app')

@section('title', 'Kelola Gambar Landing - Admin')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Kelola Gambar Landing</h1>
                    <p class="text-gray-600 mt-1">Upload, update, hapus gambar yang ditampilkan di halaman landing</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                    ← Kembali ke Dashboard
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Upload Form -->
        <div class="bg-white shadow rounded-lg mb-8">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Upload Gambar Baru</h3>
                <form method="POST" action="{{ route('admin.landing-images.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700">Pilih Gambar</label>
                        <input type="file" name="image" id="image" accept="image/*" required class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                        @error('image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md text-sm font-medium">Upload</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Images Grid -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Gambar yang Ada</h3>
                @if(count($images) > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($images as $image)
                            <div class="relative group overflow-hidden rounded-lg">
                                <img
                                    src="{{ asset('storage/' . $image) }}"
                                    alt="Landing Image"
                                    class="w-full h-32 object-cover rounded-lg"
                                />
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                    <form method="POST"
                                        action="{{ route('admin.landing-images.destroy', basename($image)) }}"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus gambar ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">Belum ada gambar yang diupload</p>
                @endif
            </div>
        </div>
    </main>
</div>
@endsection
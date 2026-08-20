@extends('layouts.app')

@section('content')
<div class="w-full mt-8">

    <div class="max-w-5xl mx-auto px-4">

        {{-- Header --}}
        <div class="flex items-start justify-between mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Persetujuan Akun
                </h1>
                <p class="mt-1 text-sm text-gray-600">
                    Kelola persetujuan akun siswa dan guru yang baru mendaftar.
                </p>
            </div>

            <a href="{{ route('admin.dashboard') }}"
               class="inline-flex items-center px-4 py-2 rounded-md
                      bg-gray-600 text-white text-sm
                      hover:bg-gray-700 transition">
                ← Kembali ke Dashboard
            </a>
        </div>

        <hr class="my-6">

        {{-- Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">

            <h2 class="text-lg font-semibold text-gray-700 mb-4">
                Daftar Akun Pending
            </h2>

            @if(session('success'))
                <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if($pendingUsers->isEmpty())
                <div class="p-6 bg-gray-50 border border-gray-200 rounded-lg text-center">
                    <p class="text-gray-600">
                        Tidak ada akun yang menunggu persetujuan.
                    </p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 text-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left">Nama</th>
                                <th class="px-4 py-3 text-left">Role</th>
                                <th class="px-4 py-3 text-left">Identitas</th>
                                <th class="px-4 py-3 text-center">Status</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y">
                            @foreach($pendingUsers as $user)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-3">
                                        {{ $user->name }}
                                    </td>

                                    <td class="px-4 py-3">
                                        @if($user->role === 'student')
                                            <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-800">
                                                Siswa
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">
                                                Guru
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3">
                                        @if($user->role === 'student')
                                            NISN: {{ $user->nisn }}
                                        @else
                                            Email: {{ $user->email ?? '-' }}
                                        @endif
                                    </td>

                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                            bg-yellow-100 text-yellow-800">
                                            Menunggu Persetujuan
                                        </span>
                                    </td>

                                    <td class="px-4 py-3 text-center">
                                        <div class="flex justify-center gap-2">
                                            <form action="{{ route('admin.approvals.approve', $user->id) }}" method="POST">
                                                @csrf
                                                <button
                                                    class="px-3 py-1.5 text-sm rounded-md bg-green-500 text-white hover:bg-green-600 transition cursor-pointer"
                                                    onclick="return confirm('Setujui akun ini?')">
                                                    Approve
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.approvals.reject', $user->id) }}" method="POST">
                                                @csrf
                                                <button
                                                    class="px-3 py-1.5 text-sm rounded-md bg-red-500 text-white hover:bg-red-600 transition cursor-pointer"
                                                    onclick="return confirm('Tolak akun ini?')">
                                                    Reject
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection

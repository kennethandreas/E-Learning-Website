<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\AktivitasPembelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $materi = Materi::where('is_active', true)
            ->where(function ($query) use ($user) {
                $query->where('kelas', $user->kelas)
                    ->orWhere('kelas', 'LIKE', '%"' . $user->kelas . '"%')
                    ->orWhereNull('kelas');
            })
            ->orderBy('urutan')
            ->get();

        $completedMateri = AktivitasPembelajaran::where('user_id', $user->id)
            ->where('jenis', 'materi')
            ->where('status', 'selesai')
            ->pluck('materi_id')
            ->toArray();

        return view('student.materi.index', [
            'materi' => $materi,
            'completedMateri' => $completedMateri
        ]);
    }

    public function show($id)
    {
        try {
            $user = Auth::user();

            $materi = Materi::where('id', $id)
                ->where('is_active', true)
                ->where(function ($query) use ($user) {
                    $query->where('kelas', $user->kelas)
                        ->orWhere('kelas', 'LIKE', '%"' . $user->kelas . '"%')
                        ->orWhereNull('kelas');
                })
                ->firstOrFail();
            
            if (!$materi->is_active) {
                abort(404);
            }

            // Get all materials ordered by urutan
            $allMateri = Materi::where('is_active', true)
                ->where(function ($query) use ($user) {
                    $query->where('kelas', $user->kelas)
                        ->orWhere('kelas', 'LIKE', '%"' . $user->kelas . '"%')
                        ->orWhereNull('kelas');
                })
                ->orderBy('urutan')
                ->get();  

            // Get completed materials
            $completedMateri = AktivitasPembelajaran::where('user_id', $user->id)
                ->where('jenis', 'materi')
                ->where('status', 'selesai')
                ->pluck('materi_id')
                ->toArray();
            
            // Check if current material is completed
            $isCompleted = in_array($materi->id, $completedMateri);
            
            // Get previous and next materials
            $currentIndex = $allMateri->search(function ($item) use ($materi) {
                return $item->id === $materi->id;
            });
            
            $previousMateri = $currentIndex > 0 ? $allMateri[$currentIndex - 1] : null;
            $nextMateri = $currentIndex < $allMateri->count() - 1 ? $allMateri[$currentIndex + 1] : null;

            // Record aktivitas pembelajaran
            $aktivitas = AktivitasPembelajaran::firstOrCreate(
                [
                    'user_id' => Auth::id(),
                    'materi_id' => $materi->id,
                    'jenis' => 'materi',
                ],
                [
                    'status' => 'belum_selesai',
                    'waktu_mulai' => now(),
                ]
            );
        } catch (\Exception $e) {
            return abort(500, 'Tidak dapat memuat materi. Silakan hubungi administrator.');
        }

        return view('student.materi.show', [
            'materi' => $materi,
            'isCompleted' => $isCompleted,
            'previousMateri' => $previousMateri,
            'nextMateri' => $nextMateri,
            'aktivitas' => $aktivitas
        ]);
    }

    public function markComplete($id)
    {
        try {
            $materi = Materi::findOrFail($id);
            
            $aktivitas = AktivitasPembelajaran::where('user_id', Auth::id())
                ->where('materi_id', $materi->id)
                ->where('jenis', 'materi')
                ->first();

            if ($aktivitas) {
                $aktivitas->update([
                    'status' => 'selesai',
                    'waktu_selesai' => now(),
                    'durasi' => now()->diffInSeconds($aktivitas->waktu_mulai),
                ]);
            }
            
            return redirect()->back()->with('success', 'Materi berhasil ditandai selesai');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Tidak dapat menandai materi sebagai selesai');
        }
    }
}

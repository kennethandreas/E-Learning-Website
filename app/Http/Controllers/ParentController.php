<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\QuizAttempt;

class ParentController extends Controller
{
    public function dashboard(Request $request)
    {
        $studentId = session('parent_view_id');
        if (! $studentId) {
            abort(403);
        }

        $student = User::find($studentId);
        if (! $student) abort(404);

        // Load student-related reports: progress, quiz results, checklist status, login activity
        return view('parent.dashboard', ['student' => $student]);
    }

    public function reportStudent(Request $request)
    {
        $studentId = session('parent_view_id');
        $student = null;
        $quizResults = [];

        // If NISN is in query parameter, look up student
        if ($request->has('nisn') && $request->nisn) {
            $student = User::where('nisn', $request->nisn)
                ->where('role', 'student')
                ->first();
            
            if (!$student) {
                return back()->withErrors(['nisn' => 'NISN siswa tidak ditemukan']);
            }
        } elseif ($studentId) {
            // If session parent_view_id exists, use it
            $student = User::find($studentId);
        }

        if ($student) {
            // Get quiz results (latest first)
            $quizResults = QuizAttempt::where('user_id', $student->id)
                ->with('quiz')
                ->latest('created_at')
                ->get()
                ->map(function ($attempt) {
                    return [
                        'id' => $attempt->id,
                        'quiz_title' => $attempt->quiz->judul ?? 'Quiz',
                        'score' => $attempt->nilai,
                        'correct' => $attempt->jumlah_benar,
                        'wrong' => $attempt->jumlah_salah,
                        'status' => $attempt->status,
                        'date' => $attempt->created_at,
                        'duration' => ($attempt->waktu_mulai && $attempt->waktu_selesai)
                            ? $attempt->waktu_mulai->diffInSeconds($attempt->waktu_selesai)
                            : null
                    ];
                });
        }

        return view('parent.report', [
            'student' => $student,
            'quizResults' => $quizResults,
        ]);
    }
}

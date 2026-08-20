<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizAttempt;
use App\Models\QuizAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $quizzes = Quiz::where('is_active', true)
            ->where(function ($query) use ($user) {
                $query->where('kelas', $user->kelas)
                    ->orWhere('kelas', 'LIKE', '%"' . $user->kelas . '"%')
                    ->orWhereNull('kelas');
            })
            ->get();

        $userAttempts = QuizAttempt::where('user_id', $user->id)
            ->pluck('quiz_id', 'quiz_id')
            ->toArray();

        return view('quiz.index', compact('quizzes', 'userAttempts'));
    }

    public function show($id)
    {
        try {
            $user = Auth::user();

            $quiz = Quiz::where('id', $id)
                ->where('is_active', true)
                ->where(function ($query) use ($user) {
                    $query->where('kelas', $user->kelas)
                        ->orWhere('kelas', 'LIKE', '%"' . $user->kelas . '"%')
                        ->orWhereNull('kelas');
                })
                ->firstOrFail();
            
            if (!$quiz->isActive()) {
                return redirect()->route('quiz.index')
                    ->with('error', 'Quiz tidak tersedia saat ini.');
            }

            // Check if user already has an attempt
            $attempt = QuizAttempt::where('user_id', Auth::id())
                ->where('quiz_id', $quiz->id)
                ->where('status', 'ongoing')
                ->first();

            if ($attempt) {
                return redirect()->route('quiz.attempt', $attempt->id);
            }

            // Check if user already completed this quiz
            $completedAttempt = QuizAttempt::where('user_id', Auth::id())
                ->where('quiz_id', $quiz->id)
                ->where('status', 'completed')
                ->first();
        } catch (\Exception $e) {
            return redirect()->route('quiz.index')
                ->with('error', 'Tidak dapat memuat quiz. Silakan hubungi administrator.');
        }

        return view('quiz.show', compact('quiz', 'completedAttempt'));
    }

    public function start($id)
    {
        $user = Auth::user();

        $quiz = Quiz::where('id', $id)
            ->where('is_active', true)
            ->where(function ($query) use ($user) {
                $query->where('kelas', $user->kelas)
                    ->orWhere('kelas', 'LIKE', '%"' . $user->kelas . '"%')
                    ->orWhereNull('kelas');
            })
            ->firstOrFail();      

        if (!$quiz->isActive()) {
            return redirect()->route('quiz.index')
                ->with('error', 'Quiz tidak tersedia saat ini.');
        }

        // Check if user already has an ongoing attempt
        $existingAttempt = QuizAttempt::where('user_id', Auth::id())
            ->where('quiz_id', $quiz->id)
            ->where('status', 'ongoing')
            ->first();

        if ($existingAttempt) {
            return redirect()->route('quiz.attempt', $existingAttempt->id);
        }

        // Create new attempt
        $attempt = QuizAttempt::create([
            'user_id' => Auth::id(),
            'quiz_id' => $quiz->id,
            'waktu_mulai' => now(),
            'waktu_selesai'=> now()->addMinutes($quiz->durasi),
            'status'  => 'ongoing',
        ]);

        return redirect()->route('quiz.attempt', $attempt->id);
    }

    public function attempt($id)
    {
        $attempt = QuizAttempt::with(['quiz.questions'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        if ($attempt->status !== 'ongoing') {
            return redirect()->route('quiz.result', $attempt->id);
        }

        // Check if time is up
        $remainingTime = $attempt->remaining_time;
        if ($remainingTime <= 0) {
            $this->finishAttempt($attempt);
            return redirect()->route('quiz.result', $attempt->id)
                ->with('error', 'Waktu quiz telah habis!');
        }

        $questions = $attempt->quiz->questions()->orderBy('urutan')->get();
        $answers = QuizAnswer::where('quiz_attempt_id', $attempt->id)
            ->pluck('jawaban', 'quiz_question_id')
            ->toArray();

        return view('quiz.attempt', compact('attempt', 'questions', 'answers', 'remainingTime'));
    }

    public function submitAnswer(Request $request, $attemptId)
    {
        $attempt = QuizAttempt::where('user_id', Auth::id())
            ->findOrFail($attemptId);

        if ($attempt->status !== 'ongoing') {
            return response()->json(['error' => 'Quiz sudah selesai'], 400);
        }

        $request->validate([
            'question_id' => 'required|exists:quiz_questions,id',
            'answer' => 'required|in:A,B,C,D',
        ]);

        $question = QuizQuestion::findOrFail($request->question_id);
        
        // Check if answer already exists
        $existingAnswer = QuizAnswer::where('quiz_attempt_id', $attempt->id)
            ->where('quiz_question_id', $question->id)
            ->first();

        $isCorrect = $question->jawaban_benar === $request->answer;
        $poin = $isCorrect ? $question->poin : 0;

        if ($existingAnswer) {
            $existingAnswer->update([
                'jawaban' => $request->answer,
                'is_correct' => $isCorrect,
                'poin' => $poin,
            ]);
        } else {
            QuizAnswer::create([
                'quiz_attempt_id' => $attempt->id,
                'quiz_question_id' => $question->id,
                'jawaban' => $request->answer,
                'is_correct' => $isCorrect,
                'poin' => $poin,
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function finish(Request $request, $id)
    {
        $attempt = QuizAttempt::where('user_id', Auth::id())
            ->findOrFail($id);

        if ($attempt->status !== 'ongoing') {
            return redirect()->route('quiz.result', $attempt->id);
        }

        $this->finishAttempt($attempt);

        return redirect()->route('quiz.result', $attempt->id);
    }

    private function finishAttempt(QuizAttempt $attempt)
    {
        $answers = QuizAnswer::where('quiz_attempt_id', $attempt->id)->get();
        
        $jumlahBenar = $answers->where('is_correct', true)->count();
        $jumlahSalah = $answers->where('is_correct', false)->count();
        $totalPoin = $answers->sum('poin');
        
        // Calculate score percentage
        $totalQuestions = $attempt->quiz->questions()->count();
        $maxPoin = $attempt->quiz->questions()->sum('poin');
        $nilai = $maxPoin > 0 ? round(($totalPoin / $maxPoin) * $attempt->quiz->total_nilai) : 0;

        $attempt->update([
            'waktu_selesai' => now(),
            'nilai' => $nilai,
            'jumlah_benar' => $jumlahBenar,
            'jumlah_salah' => $jumlahSalah,
            'status' => 'completed',
        ]);
    }

    public function result($id)
    {
        $attempt = QuizAttempt::with(['quiz.questions', 'answers'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        $questions = $attempt->quiz->questions()->orderBy('urutan')->get();
        $answers = QuizAnswer::where('quiz_attempt_id', $attempt->id)
            ->with('question')
            ->get()
            ->keyBy('quiz_question_id');

        return view('quiz.result', compact('attempt', 'questions', 'answers'));
    }

    public function active()
    {
        $activeQuizzes = Quiz::where('is_active', true)
            ->where(function($query) {
                $query->whereNull('waktu_mulai')
                    ->orWhere('waktu_mulai', '<=', now());
            })
            ->where(function($query) {
                $query->whereNull('waktu_selesai')
                    ->orWhere('waktu_selesai', '>=', now());
            })
            ->orderBy('waktu_selesai', 'asc')
            ->get();

        return view('quiz.active', compact('activeQuizzes'));
    }
}

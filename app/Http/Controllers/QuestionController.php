<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    /**
     * Show questions management page
     */
    public function index($quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        
        // Check authorization
        if (auth()->user()->role !== 'teacher') {
            abort(403);
        }

        return view('teacher.quiz.questions', compact('quiz'));
    }

    /**
     * Get question data (API endpoint)
     */
    public function show($id)
    {
        $question = QuizQuestion::findOrFail($id);
        
        return response()->json([
            'id' => $question->id,
            'pertanyaan' => $question->pertanyaan,
            'pilihan' => $question->pilihan,
            'jawaban_benar' => $question->jawaban_benar,
            'poin' => $question->poin,
            'urutan' => $question->urutan,
        ]);
    }

    /**
     * Store a new question
     */
    public function store(Request $request, $quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        
        // Check authorization
        if (auth()->user()->role !== 'teacher') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'pertanyaan' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'jawaban_benar' => 'required|in:A,B,C,D',
        ]);

        // Format pilihan as array
        $pilihan = [
            $request->input('option_a'),
            $request->input('option_b'),
            $request->input('option_c'),
            $request->input('option_d'),
        ];

        // Get the next urutan
        $maxUrutan = $quiz->questions()->max('urutan') ?? 0;

        $question = QuizQuestion::create([
            'quiz_id' => $quizId,
            'pertanyaan' => $request->input('pertanyaan'),
            'pilihan' => $pilihan,
            'jawaban_benar' => $request->input('jawaban_benar'),
            'poin' => 10,
            'urutan' => $maxUrutan + 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Soal berhasil ditambahkan',
            'question' => $question
        ]);
    }

    /**
     * Update a question
     */
    public function update(Request $request, $id)
    {
        $question = QuizQuestion::findOrFail($id);
        
        // Check authorization
        if (auth()->user()->role !== 'teacher') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'pertanyaan' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'jawaban_benar' => 'required|in:A,B,C,D',
        ]);

        // Format pilihan as array
        $pilihan = [
            $request->input('option_a'),
            $request->input('option_b'),
            $request->input('option_c'),
            $request->input('option_d'),
        ];

        $question->update([
            'pertanyaan' => $request->input('pertanyaan'),
            'pilihan' => $pilihan,
            'jawaban_benar' => $request->input('jawaban_benar'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Soal berhasil diperbarui',
            'question' => $question
        ]);
    }

    /**
     * Delete a question
     */
    public function destroy($id)
    {
        $question = QuizQuestion::findOrFail($id);
        
        // Check authorization
        if (auth()->user()->role !== 'teacher') {
            abort(403);
        }

        $question->delete();

        return redirect()->back()->with('success', 'Soal berhasil dihapus');
    }
}

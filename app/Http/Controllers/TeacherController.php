<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Materi;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class TeacherController extends Controller
{
    public function index()
    {
        return $this->materiIndex();
    }

    // GET /teacher/materi/create
    public function create()
    {
        return $this->materiCreate();
    }

    // POST /teacher/materi
    public function store(Request $request)
    {
        return $this->materiStore($request);
    }

    // GET /teacher/materi/{materi}/edit
    public function edit($materi)
    {
        return $this->materiEdit($materi);
    }

    // PUT /teacher/materi/{materi}
    public function update(Request $request, $materi)
    {
        return $this->materiUpdate($request, $materi);
    }

    // DELETE /teacher/materi/{materi}
    public function destroy($materi)
    {
        return $this->materiDestroy($materi);
    }

    public function dashboard()
    {
        // Ambil semua materi
        $materi = Materi::orderBy('urutan')->get();

        // Ambil semua kuis + soal
        $quizzes = Quiz::with('questions')->latest()->get();

        // Ambil semua siswa
        $students = User::where('role', 'student')->get();

        // Ambil semua attempt kuis
        $quizAttempts = QuizAttempt::with(['user', 'quiz'])
            ->latest()
            ->get();

        // Statistik
        $totalStudents = $students->count();
        $totalMateri   = $materi->count();
        $totalQuizzes  = $quizzes->count();
        $averageScore  = $quizAttempts->count() > 0
            ? round($quizAttempts->avg('nilai'), 2)
            : 0;

        // Nilai per siswa
        $studentScores = [];
        foreach ($students as $student) {
            $attempts = $student->quizAttempts()->latest()->get();

            $studentScores[$student->id] = [
                'name' => $student->name,
                'nisn' => $student->nisn,
                'total_attempts' => $attempts->count(),
                'average_score' => $attempts->count() > 0
                    ? round($attempts->avg('nilai'), 2)
                    : 0,
                'latest_attempt' => $attempts->first(),
            ];
        }

        return view('teacher.dashboard-comprehensive', compact(
            'materi',
            'quizzes',
            'students',
            'quizAttempts',
            'totalStudents',
            'totalMateri',
            'totalQuizzes',
            'averageScore',
            'studentScores'
        ));
    }

    
    // Manage Materi
    public function materiIndex()
    {
        try {
            $materis = Materi::orderBy('urutan')->paginate(10);
        } catch (\Exception $e) {
            $materis = collect([]);
        }
        return view('teacher.materi.index', ['materis' => $materis]);
    }
    
    public function materiCreate()
    {
        return view('teacher.materi.form');
    }
    
    public function materiStore(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kelas' => 'required|array|min:1',
            'kelas.*' => 'string',
            'deskripsi' => 'required|string',
            'konten' => 'required|string',
            'keywords' => 'nullable|string',
            'urutan' => 'required|integer',
            'is_active' => 'boolean'
        ]);

        if (!empty($validated['keywords'])) {
            $validated['keywords'] = collect(explode(',', $validated['keywords']))
                ->map(fn ($k) => strtolower(trim($k)))
                ->filter()
                ->unique()
                ->implode(',');
        }

        $validated['is_active'] = $request->has('is_active');

        Materi::create($validated);

        return redirect()
            ->route('teacher.materi.index')
            ->with('success', 'Materi berhasil ditambahkan');
    }
    
    public function materiEdit($id)
    {
        $materi = Materi::findOrFail($id);
        return view('teacher.materi.form', ['materi' => $materi]);
    }
    
    public function materiUpdate(Request $request, $id)
    {
        $materi = Materi::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kelas' => 'required|array|min:1',
            'kelas.*' => 'string',
            'deskripsi' => 'required|string',
            'konten' => 'required|string',
            'keywords' => 'nullable|string',
            'urutan' => 'required|integer',
            'is_active' => 'boolean'
        ]);

        if (!empty($validated['keywords'])) {
            $validated['keywords'] = collect(explode(',', $validated['keywords']))
                ->map(fn ($k) => strtolower(trim($k)))
                ->filter()
                ->unique()
                ->implode(',');
        }

        $validated['is_active'] = $request->has('is_active');

        $materi->update($validated);

        return redirect()
            ->route('teacher.materi.index')
            ->with('success', 'Materi berhasil diperbarui');
    }
    
    public function materiDestroy($id)
    {
        $materi = Materi::findOrFail($id);
        $materi->delete();
        return redirect()->route('teacher.materi.index')->with('success', 'Materi berhasil dihapus');
    }
    
    // Manage Quiz
    public function quizIndex()
    {
        try {
            $quizzes = Quiz::with('questions')->latest()->paginate(10);
        } catch (\Exception $e) {
            $quizzes = collect([]);
        }
        return view('teacher.quiz.index', ['quizzes' => $quizzes]);
    }
    
    public function quizCreate()
    {
        return view('teacher.quiz.form');
    }
    
    public function quizStore(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kelas' => 'required|array|min:1',
            'kelas.*' => 'string',
            'deskripsi' => 'required|string',
            'durasi' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100'
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        
        Quiz::create($validated);
        return redirect()->route('teacher.quiz.index')->with('success', 'Kuis berhasil dibuat');
    }
    
    public function quizEdit($id)
    {
        $quiz = Quiz::with('questions')->findOrFail($id);
        return view('teacher.quiz.form', ['quiz' => $quiz]);
    }
    
    public function quizUpdate(Request $request, $id)
    {
        $quiz = Quiz::findOrFail($id);
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kelas' => 'required|array|min:1',
            'kelas.*' => 'string',
            'deskripsi' => 'required|string',
            'durasi' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100'
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        
        $quiz->update($validated);
        return redirect()->route('teacher.quiz.index')->with('success', 'Kuis berhasil diperbarui');
    }
    
    public function quizDestroy($id)
    {
        $quiz = Quiz::findOrFail($id);
        $quiz->delete();
        return redirect()->route('teacher.quiz.index')->with('success', 'Kuis berhasil dihapus');
    }
    
    // View student scores
    public function studentScores(Request $request)
    {
        try {
            $query = User::where('role', 'student');
            
            // Search by name or NISN
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('nisn', 'like', "%{$search}%");
                });
            }
            
            $students = $query->get();
        
        $studentScores = [];
        foreach ($students as $student) {
            $attempts = $student->quizAttempts()->latest()->get();
            $studentScores[] = [
                'id' => $student->id,
                'name' => $student->name,
                'nisn' => $student->nisn,
                'kelas' => $student->kelas ?? 'N/A',
                'total_attempts' => $attempts->count(),
                'average_score' => $attempts->count() > 0 ? 
                    round($attempts->avg('nilai'), 2) : 0,
                'attempts' => $attempts
            ];
        }
        
        // Sort
        if ($request->has('sort')) {
            if ($request->sort === 'score_desc') {
                usort($studentScores, fn($a, $b) => $b['average_score'] <=> $a['average_score']);
            } elseif ($request->sort === 'score_asc') {
                usort($studentScores, fn($a, $b) => $a['average_score'] <=> $b['average_score']);
            } else {
                usort($studentScores, fn($a, $b) => strcasecmp($a['name'], $b['name']));
            }
        }
        
            return view('teacher.scores.index', [
                'studentScores' => $studentScores,
                'students' => $students
            ]);
        } catch (\Exception $e) {
            return view('teacher.scores.index', [
                'studentScores' => [],
                'students' => []
            ]);
        }
    }

    /**
     * Adjust quiz execution time
     */
    public function adjustQuizTime(Request $request, $quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        
        // Check authorization
        if (Auth::user()->role !== 'teacher') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'waktu_mulai' => 'nullable|date_format:Y-m-d H:i',
            'waktu_selesai' => 'nullable|date_format:Y-m-d H:i',
        ]);

        if ($request->filled('waktu_mulai')) {
            $quiz->waktu_mulai = $validated['waktu_mulai'];
        }
        if ($request->filled('waktu_selesai')) {
            $quiz->waktu_selesai = $validated['waktu_selesai'];
        }

        $quiz->save();

        return response()->json([
            'message' => 'Waktu kuis berhasil diperbarui',
            'quiz' => $quiz
        ]);
    }

    /**
     * Show teacher profile edit form
     */
    public function profileEdit()
    {
        $user = Auth::user();
        return view('teacher.profile.edit', compact('user'));
    }

    /**
     * Update teacher profile
     */
    public function profileUpdate(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'nip' => 'nullable|string|max:20|unique:users,nip,' . $user->id,
            'tanggal_lahir' => 'nullable|date',
            'tentang_aku' => 'nullable|string|max:1000',
            'password' => 'nullable|string|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'nip' => $request->nip,
            'tanggal_lahir' => $request->tanggal_lahir,
            'tentang_aku' => $request->tentang_aku,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        if ($request->hasFile('avatar')) {
            try {
                // Delete old avatar if exists
                if ($user->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
                }
                
                // Store the new avatar with a unique timestamp-based filename
                $file = $request->file('avatar');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '_' . uniqid() . '.' . $extension;
                
                $path = \Illuminate\Support\Facades\Storage::disk('public')->putFileAs('avatars', $file, $filename);
                if ($path) {
                    $data['avatar'] = $path;
                }
            } catch (\Exception $e) {
                // Log the error but don't fail the update
                \Illuminate\Support\Facades\Log::error('Avatar upload failed: ' . $e->getMessage());
                // Continue with profile update without avatar
            }
        }

        $user->update($data);

        return redirect()->route('teacher.profile.edit')
            ->with('success', 'Profil berhasil diperbarui!');
    }
}

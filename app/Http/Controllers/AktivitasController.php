<?php

namespace App\Http\Controllers;

use App\Models\AktivitasPembelajaran;
use App\Models\User;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AktivitasController extends Controller
{
    public function index(Request $request)
    {
        $student = null;
        $academicProgress = [];
        
        try {
            // If user is authenticated student, show their own data
            if (Auth::check() && Auth::user()->role == 'student') {
                $student = Auth::user();
            }
            
            // If NISN is provided (for parent view)
            if ($request->has('nisn') && $request->nisn) {
                $student = User::where('nisn', $request->nisn)
                    ->where('role', 'student')
                    ->first();
            }
            
            if ($student) {
                // Get quiz attempts with scores grouped by subject
                $quizAttempts = QuizAttempt::where('user_id', $student->id)
                    ->where('status', 'completed')
                    ->with(['quiz'])
                    ->get();
                
                // Group by subject (extract from quiz title)
                $subjects = [];
                foreach ($quizAttempts as $attempt) {
                    $quizTitle = $attempt->quiz->judul ?? '';
                    
                    // Extract subject from quiz title
                    $subject = $this->extractSubject($quizTitle);
                    
                    if (!isset($subjects[$subject])) {
                        $subjects[$subject] = [];
                    }
                    
                    $subjects[$subject][] = $attempt->nilai;
                }
            }
            
            // Calculate average for each subject
            foreach ($subjects as $subject => $scores) {
                $average = round(array_sum($scores) / count($scores));
                $academicProgress[] = [
                    'subject' => $subject,
                    'score' => $average,
                    'count' => count($scores)
                ];
            }
            
            // Sort by subject name
            usort($academicProgress, function($a, $b) {
                return strcmp($a['subject'], $b['subject']);
            });
        } catch (\Exception $e) {
            $academicProgress = [];
        }
        
        return view('aktivitas.index', compact('student', 'academicProgress'));
    }
    
    private function extractSubject($quizTitle)
    {
        $title = strtolower($quizTitle);
        
        if (strpos($title, 'matematika') !== false || strpos($title, 'math') !== false) {
            return 'Matematika';
        }
        
        if (strpos($title, 'bahasa indonesia') !== false || strpos($title, 'indonesia') !== false) {
            return 'Bahasa Indonesia';
        }
        
        if (strpos($title, 'bahasa inggris') !== false || strpos($title, 'english') !== false) {
            return 'Bahasa Inggris';
        }
        
        if (strpos($title, 'ipa') !== false || strpos($title, 'sains') !== false || strpos($title, 'science') !== false) {
            return 'IPA';
        }
        
        if (strpos($title, 'ips') !== false || strpos($title, 'social') !== false) {
            return 'IPS';
        }
        
        // Default: use first part of title or "Lainnya"
        $parts = explode(':', $quizTitle);
        return !empty($parts[0]) ? trim($parts[0]) : 'Lainnya';
    }
}

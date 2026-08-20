<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\Quiz;
use App\Models\AktivitasPembelajaran;
use App\Models\DailyChecklist;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $materiList = [];
        $dailyQuizzes = [];
        $badges = [];
        $pending = [];
        
        try {
            // Get materi pembelajaran
            $materiList = Materi::where('is_active', true)
                ->where(function ($query) use ($user) {
                    $query->where('kelas', $user->kelas)
                        ->orWhere('kelas', 'LIKE', '%"' . $user->kelas . '"%')
                        ->orWhereNull('kelas');
                })
                ->orderBy('urutan')
                ->get();
            
            // Get daily quizzes with attempt status
            $dailyQuizzes = Quiz::where('is_active', true)
                ->where(function ($query) use ($user) {
                    $query->where('kelas', $user->kelas)
                        ->orWhere('kelas', 'LIKE', '%"' . $user->kelas . '"%')
                        ->orWhereNull('kelas');
                })
                ->where(function($query) {
                    $query->whereNull('waktu_mulai')
                        ->orWhere('waktu_mulai', '<=', now());
                })
                ->where(function($query) {
                    $query->whereNull('waktu_selesai')
                        ->orWhere('waktu_selesai', '>=', now());
                })
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($quiz) use ($user) {
                    $attempt = QuizAttempt::where('user_id', $user->id)
                        ->where('quiz_id', $quiz->id)
                        ->where('status', 'completed')
                        ->first();
                    
                    $quiz->is_completed = $attempt ? true : false;
                    $quiz->is_new = !$attempt && $quiz->created_at->isToday();
                    return $quiz;
                });
            
            // Get user badges (simplified - bisa dikembangkan dengan tabel badges)
            $badges = $this->getUserBadges($user);

            // Daily checklist items (keys and labels)
            $checklistItems = [
                'bangun_pagi' => 'Hari ini bangun pagi ?',
                'beribadah' => 'Sudahkah beribadah ?',
                'berolahraga' => 'Sudah berolahraga ?',
                'makan_sehat' => 'Makan makanan sehat dan bergizi ?',
                'belajar' => 'Belajar untuk hari ini ?',
                'bermasyarakat' => 'Bermasyarakat ?',
                'tidur_cepat' => 'Tidur cepat semalam ?'
            ];

            // Determine pending checklist items for this user within the last 24 hours
            $pending = [];
            foreach ($checklistItems as $key => $label) {
                $exists = DailyChecklist::where('user_id', $user->id)
                    ->where('key', $key)
                    ->where('status', 'selesai')
                    ->whereNotNull('completed_at')
                    ->whereDate('completed_at', today())
                    ->exists();

                if (! $exists) {
                    $pending[] = ['key' => $key, 'label' => $label];
                }
            }
        } catch (\Exception $e) {
            // If database tables don't exist, show empty dashboard
            $materiList = collect([]);
            $dailyQuizzes = collect([]);
            $badges = [];
            $pending = [];
        }

        return view('dashboard', compact(
            'materiList',
            'dailyQuizzes',
            'badges',
            'pending'
        ));
    }
    
    private function getUserBadges($user)
    {
        // Simplified badges system - bisa dikembangkan dengan tabel badges
        $badges = [];
        
        // Check for Math Whiz badge
        $mathQuizzes = QuizAttempt::where('user_id', $user->id)
            ->whereHas('quiz', function($q) {
                $q->where('judul', 'like', '%Matematika%');
            })
            ->where('status', 'completed')
            ->where('nilai', '>=', 80)
            ->count();
        
        if ($mathQuizzes >= 3) {
            $badges[] = ['name' => 'Jago Matematika', 'icon' => 'star'];
        }
        
        // Check for Great Reader badge
        $materiCompleted = AktivitasPembelajaran::where('user_id', $user->id)
            ->where('jenis', 'materi')
            ->where('status', 'selesai')
            ->count();
        
        if ($materiCompleted >= 5) {
            $badges[] = ['name' => 'Pembaca Hebat', 'icon' => 'document'];
        }
        
        // Check for Little Scientist badge
        $scienceQuizzes = QuizAttempt::where('user_id', $user->id)
            ->whereHas('quiz', function($q) {
                $q->where('judul', 'like', '%IPA%')->orWhere('judul', 'like', '%Sains%');
            })
            ->where('status', 'completed')
            ->where('nilai', '>=', 80)
            ->count();
        
        if ($scienceQuizzes >= 2) {
            $badges[] = ['name' => 'Ilmuwan Cilik', 'icon' => 'lightbulb'];
        }
        
        // Check for Diligent Learner badge
        $totalCompleted = AktivitasPembelajaran::where('user_id', $user->id)
            ->where('status', 'selesai')
            ->count();
        
        if ($totalCompleted >= 10) {
            $badges[] = ['name' => 'Rajin Belajar', 'icon' => 'bookmark'];
        }
        
        // Check for Quiz Champion badge
        $highScores = QuizAttempt::where('user_id', $user->id)
            ->where('status', 'completed')
            ->where('nilai', '>=', 90)
            ->count();
        
        if ($highScores >= 5) {
            $badges[] = ['name' => 'Kuis Juara', 'icon' => 'puzzle'];
        }
        
        return $badges;
    }
}

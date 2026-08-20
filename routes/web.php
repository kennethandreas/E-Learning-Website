<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\AktivitasController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AIController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ParentController;


Route::get('/', function () {
    $landingImageUrl = null;

    if (Storage::disk('public')->exists('landing')) {
        $images = collect(Storage::disk('public')->files('landing'))
            ->filter(fn ($file) => in_array(pathinfo($file, PATHINFO_EXTENSION), ['jpg','jpeg','png','gif']))
            ->values();

        if ($images->isNotEmpty()) {
            $landingImageUrl = asset('storage/' . $images->first());
        }
    }

    return view('landing', compact('landingImageUrl'));
})->name('landing');


Route::middleware('guest')->group(function () {
    Route::get('/login/student', [AuthController::class, 'showStudentLogin'])->name('login.student');
    Route::post('/login/student', [AuthController::class, 'studentLogin'])->name('login.student.post');

    Route::get('/login/teacher', [AuthController::class, 'showTeacherLogin'])->name('login.teacher');
    Route::post('/login/teacher', [AuthController::class, 'teacherLogin'])->name('login.teacher.post');

    Route::get('/register/student', [AuthController::class, 'showStudentRegister'])
        ->name('register.student');
    Route::post('/register/student', [AuthController::class, 'studentRegister'])
        ->name('register.student.post');

    Route::get('/register/teacher', [AuthController::class, 'showTeacherRegister'])
        ->name('register.teacher');
    Route::post('/register/teacher', [AuthController::class, 'teacherRegister'])
        ->name('register.teacher.post');

});


Route::get('/login/parent', [AuthController::class, 'showParentLogin'])->name('login.parent');
Route::post('/login/parent', [AuthController::class, 'parentLogin'])->name('login.parent.post');


Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('ensure_auth');


Route::middleware(['ensure_auth', 'redirect_role', 'role:student'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/checklist/complete', [ChecklistController::class, 'complete'])->name('checklist.complete');

    Route::get('/materi', [MateriController::class, 'index'])->name('materi.index');
    Route::get('/materi/{id}', [MateriController::class, 'show'])->name('materi.show');
    Route::post('/materi/{id}/complete', [MateriController::class, 'markComplete'])->name('materi.complete');

    Route::get('/aktivitas', [AktivitasController::class, 'index'])->name('aktivitas.index');

    Route::get('/quiz', [QuizController::class, 'index'])->name('quiz.index');
    Route::get('/quiz/active', [QuizController::class, 'active'])->name('quiz.active');
    Route::get('/quiz/{id}', [QuizController::class, 'show'])->name('quiz.show');
    Route::post('/quiz/{id}/start', [QuizController::class, 'start'])->name('quiz.start');
    Route::get('/quiz/attempt/{id}', [QuizController::class, 'attempt'])->name('quiz.attempt');
    Route::post('/quiz/attempt/{id}/answer', [QuizController::class, 'submitAnswer'])->name('quiz.submit-answer');
    Route::post('/quiz/attempt/{id}/finish', [QuizController::class, 'finish'])->name('quiz.finish');
    Route::get('/quiz/result/{id}', [QuizController::class, 'result'])->name('quiz.result');

    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});



Route::get('/ai', [AIController::class, 'index'])->name('ai.index');
Route::post('/ai/chat', [AIController::class, 'chat'])
    ->middleware('ensure_auth')
    ->name('ai.chat');


Route::middleware(['ensure_auth', 'role:teacher'])->group(function () {

    Route::get('/teacher/dashboard', [TeacherController::class, 'dashboard'])->name('teacher.dashboard');
    Route::get('/teacher/profile/edit', [TeacherController::class, 'profileEdit'])
        ->name('teacher.profile.edit');

    Route::put('/teacher/profile', [TeacherController::class, 'profileUpdate'])
        ->name('teacher.profile.update');

    Route::resource('/teacher/materi', TeacherController::class)
        ->except(['show'])
        ->names('teacher.materi');

    Route::get('/teacher/quiz', [TeacherController::class, 'quizIndex'])->name('teacher.quiz.index');
    Route::get('/teacher/quiz/create', [TeacherController::class, 'quizCreate'])->name('teacher.quiz.create');
    Route::post('/teacher/quiz', [TeacherController::class, 'quizStore'])->name('teacher.quiz.store');
    Route::get('/teacher/quiz/{id}/edit', [TeacherController::class, 'quizEdit'])->name('teacher.quiz.edit');
    Route::put('/teacher/quiz/{id}', [TeacherController::class, 'quizUpdate'])->name('teacher.quiz.update');
    Route::delete('/teacher/quiz/{id}', [TeacherController::class, 'quizDestroy'])->name('teacher.quiz.destroy');

    Route::get('/teacher/quiz/{id}/questions', [QuestionController::class, 'index'])->name('teacher.quiz.questions');
    Route::post('/teacher/quiz/{id}/question', [QuestionController::class, 'store'])->name('teacher.question.store');
    Route::put('/teacher/question/{id}', [QuestionController::class, 'update']);
    Route::delete('/teacher/question/{id}', [QuestionController::class, 'destroy'])->name('teacher.question.destroy');

    Route::get('/teacher/scores', [TeacherController::class, 'studentScores'])->name('teacher.scores');
});


Route::middleware('role:parent')->group(function () {
    Route::get('/parent/dashboard', [ParentController::class, 'dashboard'])->name('parent.dashboard');
});

Route::get('/parent/report/student', [ParentController::class, 'reportStudent'])->name('report.student');


Route::middleware(['ensure_auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        Route::get('/students', [AdminController::class, 'students'])->name('students');
        Route::get('/students/create', [AdminController::class, 'createStudent'])->name('students.create');
        Route::post('/students', [AdminController::class, 'storeStudent'])->name('students.store');
        Route::get('/students/{student}/edit', [AdminController::class, 'editStudent'])->name('students.edit');
        Route::put('/students/{student}', [AdminController::class, 'updateStudent'])->name('students.update');
        Route::delete('/students/{student}', [AdminController::class, 'destroyStudent'])->name('students.destroy');

        Route::get('/teachers', [AdminController::class, 'teachers'])->name('teachers');
        Route::get('/teachers/create', [AdminController::class, 'createTeacher'])->name('teachers.create');
        Route::post('/teachers', [AdminController::class, 'storeTeacher'])->name('teachers.store');
        Route::get('/teachers/{teacher}/edit', [AdminController::class, 'editTeacher'])->name('teachers.edit');
        Route::put('/teachers/{teacher}', [AdminController::class, 'updateTeacher'])->name('teachers.update');
        Route::delete('/teachers/{teacher}', [AdminController::class, 'destroyTeacher'])->name('teachers.destroy');

        Route::get('/landing-images', [AdminController::class, 'landingImages'])->name('landing-images');
        Route::post('/landing-images', [AdminController::class, 'storeLandingImage'])->name('landing-images.store');
        Route::delete('/landing-images/{filename}', [AdminController::class, 'destroyLandingImage'])->name('landing-images.destroy');

        Route::get('/approvals', [AdminController::class, 'approvals'])->name('approvals');
        Route::post('/approvals/{user}/approve', [AdminController::class, 'approveUser'])->name('approvals.approve');
        Route::post('/approvals/{user}/reject', [AdminController::class, 'rejectUser'])->name('approvals.reject');
    });

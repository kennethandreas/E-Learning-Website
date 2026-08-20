<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        // Redirect to student login by default (separate login pages exist)
        return redirect()->route('login.student');
    }

    public function login(Request $request)
    {
        // Legacy generic login is not used; prefer explicit student/teacher/parent routes.
        return redirect()->route('login.student');
    }

    // -------- Student Login --------
    public function showStudentLogin()
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'student') {
                return redirect()->route('dashboard');
            }
            // Other users should logout first
            return redirect()->route('logout');
        }
        return view('auth.login-student');
    }

    public function studentLogin(Request $request)
    {
        $request->validate([
            'nisn' => 'required|string',
            'password' => 'required',
        ]);

        $user = User::where('nisn', $request->nisn)
            ->where('role', 'student')
            ->first();

        if ($user && Hash::check($request->password, $user->password)) {

            if ($user->status !== 'approved') {
                throw ValidationException::withMessages([
                    'nisn' => 'Akun belum disetujui admin.',
                ]);
            }

            if (! $user) {
                throw ValidationException::withMessages([
                    'nisn' => __('NISN atau password tidak sesuai.'),
                ]);
            }

            if ($user->status !== 'approved') {
                throw ValidationException::withMessages([
                    'nisn' => __('Akun Anda belum disetujui oleh admin.'),
                ]);
            }
            
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        throw ValidationException::withMessages([
            'nisn' => __('NISN atau password tidak sesuai.'),
        ]);
    }

    // -------- Teacher Login --------
    public function showTeacherLogin()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'teacher') {
                return redirect()->route('teacher.dashboard');
            } elseif ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            // Other users should logout first
            return redirect()->route('logout');
        }
        return view('auth.login-teacher');
    }

    public function teacherLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->username)
            ->orWhere('username', $request->username)
            ->whereIn('role', ['teacher', 'admin'])
            ->first();

        if ($user && Hash::check($request->password, $user->password)) {

            if ($user->status !== 'approved') {
                throw ValidationException::withMessages([
                    'username' => 'Akun belum disetujui admin.',
                ]);
            }

            if (! $user) {
                throw ValidationException::withMessages([
                    'username' => __('Username atau password tidak sesuai.'),
                ]);
            }

            if ($user->status !== 'approved') {
                throw ValidationException::withMessages([
                    'username' => __('Akun Anda belum disetujui oleh admin.'),
                ]);
            }

            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();
            
            // Redirect based on role
            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            } else {
                return redirect()->intended(route('teacher.dashboard'));
            }
        }

        throw ValidationException::withMessages([
            'username' => __('Username atau password tidak sesuai.'),
        ]);
    }

    // -------- Parent login (view-only) --------
    public function showParentLogin()
    {
        return view('auth.login-parent');
    }

    public function parentLogin(Request $request)
    {
        $request->validate([
            'nisn' => 'required|string',
            'tanggal_lahir' => 'required|date',
        ]);

        $student = User::where('nisn', $request->nisn)
            ->where('role', 'student')
            ->first();

        if (! $student) {
            throw ValidationException::withMessages(['nisn' => __('Data murid tidak ditemukan.')]);
        }

        // Compare date of birth (stored as date)
        if ($student->tanggal_lahir && $student->tanggal_lahir->toDateString() === now()->parse($request->tanggal_lahir)->toDateString()) {
            // Set session to identify parent view (no auth)
            session(['parent_view_id' => $student->id]);
            return redirect()->route('parent.dashboard');
        }

        throw ValidationException::withMessages(['tanggal_lahir' => __('Tanggal lahir tidak cocok.')]);
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student',
        ]);

        Auth::login($user);

            // Redirect based on role
            if ($user->role === 'teacher') {
                return redirect()->route('teacher.dashboard');
            }
            if ($user->role === 'student') {
                return redirect()->route('student.dashboard');
            }
            if ($user->role === 'parent') {
                return redirect()->route('parent.dashboard');
            }
            return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Always redirect to landing page after logout
        return redirect('/')->with('success', 'Anda telah logout. Sampai jumpa lagi!');
    }

    // -------- Student Registration --------
    public function showStudentRegister()
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'student') {
                return redirect()->route('dashboard');
            }
            return redirect()->route('logout');
        }
        return view('auth.register-student');
    }

    public function studentRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nisn' => 'required|string|unique:users,nisn',
            'kelas' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'email_orang_tua' => 'required|email|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'nisn' => $request->nisn,
            'kelas' => $request->kelas,
            'tanggal_lahir' => $request->tanggal_lahir,
            'email_orang_tua' => $request->email_orang_tua,
            'password' => Hash::make($request->password),
            'role' => 'student',
            'status' => 'pending',
        ]);

        return redirect()->route('login.student')
            ->with('success', 'Akun berhasil dibuat. Menunggu persetujuan admin.');
        // Auth::login($user);
        // return redirect()->route('dashboard');
    }

    // -------- Teacher Registration --------
    public function showTeacherRegister()
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'teacher') {
                return redirect()->route('teacher.dashboard');
            }
            return redirect()->route('logout');
        }
        return view('auth.register-teacher');
    }

    public function teacherRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'teacher',
            'status' => 'pending',
        ]);

        return redirect()->route('login.teacher')
            ->with('success', 'Pendaftaran berhasil. Menunggu persetujuan admin.');
        // Auth::login($user);
        // return redirect()->route('teacher.dashboard');
    }
}

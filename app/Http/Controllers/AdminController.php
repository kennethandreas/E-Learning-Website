<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function dashboard()
    {
        $students = User::where('role', 'student')->get();
        $teachers = User::where('role', 'teacher')->get();

        $pendingCount = User::whereIn('role', ['student', 'teacher'])
            ->where('status', 'pending')
            ->count();

        $landingImages = Storage::disk('public')->files('landing');

        return view(
            'admin.dashboard',
            compact('students', 'teachers', 'pendingCount', 'landingImages')
        );
    }

    // Student CRUD
    public function students()
    {
        $students = User::where('role', 'student')->paginate(10);
        return view('admin.students.index', compact('students'));
    }

    public function createStudent()
    {
        return view('admin.students.create');
    }

    public function storeStudent(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'nullable|email|unique:users',
            'password' => 'required|string|min:8',
            'nisn' => 'required|string|max:255|unique:users',
            'kelas' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'tentang_aku' => 'nullable|string',
            'email_orang_tua' => 'nullable|email',
            'nomor_telepon_orang_tua' => 'nullable|string|max:255',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student',
            'nisn' => $request->nisn,
            'kelas' => $request->kelas,
            'phone' => $request->phone,
            'address' => $request->address,
            'tanggal_lahir' => $request->tanggal_lahir,
            'tentang_aku' => $request->tentang_aku,
            'email_orang_tua' => $request->email_orang_tua,
            'nomor_telepon_orang_tua' => $request->nomor_telepon_orang_tua,
        ]);

        return redirect()->route('admin.students')->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function editStudent(User $student)
    {
        return view('admin.students.edit', compact('student'));
    }

    public function updateStudent(Request $request, User $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($student->id)],
            'email' => ['nullable', 'email', Rule::unique('users')->ignore($student->id)],
            'nisn' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($student->id)],
            'kelas' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'tentang_aku' => 'nullable|string',
            'email_orang_tua' => 'nullable|email',
            'nomor_telepon_orang_tua' => 'nullable|string|max:255',
        ]);

        $student->update($request->only([
            'name', 'username', 'email', 'nisn', 'kelas', 'phone', 'address', 'tanggal_lahir', 'tentang_aku', 'email_orang_tua', 'nomor_telepon_orang_tua'
        ]));

        return redirect()->route('admin.students')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroyStudent(User $student)
    {
        $student->delete();
        return redirect()->route('admin.students')->with('success', 'Siswa berhasil dihapus.');
    }

    // Teacher CRUD
    public function teachers()
    {
        $teachers = User::where('role', 'teacher')->paginate(10);
        return view('admin.teachers.index', compact('teachers'));
    }

    public function createTeacher()
    {
        return view('admin.teachers.create');
    }

    public function storeTeacher(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'nullable|email|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'teacher',
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('admin.teachers')->with('success', 'Guru berhasil ditambahkan.');
    }

    public function editTeacher(User $teacher)
    {
        return view('admin.teachers.edit', compact('teacher'));
    }

    public function updateTeacher(Request $request, User $teacher)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($teacher->id)],
            'email' => ['nullable', 'email', Rule::unique('users')->ignore($teacher->id)],
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        $teacher->update($request->only(['name', 'username', 'email', 'phone', 'address']));

        return redirect()->route('admin.teachers')->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroyTeacher(User $teacher)
    {
        $teacher->delete();
        return redirect()->route('admin.teachers')->with('success', 'Guru berhasil dihapus.');
    }

    // Landing Images
    public function landingImages()
    {
        $images = Storage::disk('public')->files('landing');
        return view('admin.landing-images.index', compact('images'));
    }

    public function storeLandingImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('landing', 'public'); // Use public disk
            \Log::info('Image uploaded to: ' . $path);
        } else {
            return redirect()->route('admin.landing-images')->with('error', 'File tidak ditemukan.');
        }

        return redirect()->route('admin.landing-images')->with('success', 'Gambar berhasil diupload.');
    }

    public function destroyLandingImage($filename)
    {
        Storage::disk('public')->delete('landing/' . $filename);

        return redirect()
            ->route('admin.landing-images')
            ->with('success', 'Gambar berhasil dihapus.');
    }

    public function approvals()
    {
        $pendingUsers = User::whereIn('role', ['student', 'teacher'])
            ->where('status', 'pending')
            ->get();

        return view('admin.approvals.index', compact('pendingUsers'));
    }

    public function approveUser(User $user)
    {
        $user->update(['status' => 'approved']);
        return back()->with('success', 'Akun disetujui.');
    }

    public function rejectUser(User $user)
    {
        $user->delete();

        return redirect()
            ->route('admin.approvals')
            ->with('success', 'Akun berhasil ditolak dan dihapus.');
    }
}
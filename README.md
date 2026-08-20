# E-Learning Website

Platform e-learning berbasis Laravel untuk sekolah, dengan empat peran pengguna (siswa, guru, orang tua, dan admin), dilengkapi manajemen materi, kuis interaktif, checklist harian, sistem poin/badge, dan chatbot tanya-jawab seputar materi pembelajaran.

![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?logo=laravel&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?logo=mysql&logoColor=white)

<!-- Tambahkan screenshot/GIF di sini: landing page, dashboard siswa, halaman kuis, dan dashboard admin -->

## Fitur Utama

**Siswa**
- Dashboard dengan checklist aktivitas harian
- Akses materi pembelajaran per kelas, dengan penanda materi selesai dibaca
- Aktivitas pembelajaran terkait materi
- Kuis interaktif — mulai attempt, jawab soal, submit jawaban, lihat hasil dan passing score
- Sistem poin dan badge sebagai gamifikasi
- Chatbot tanya-jawab (`/ai`) — mencocokkan pertanyaan siswa dengan kata kunci pada materi maupun basis pengetahuan (keyword matching), dan menawarkan pilihan bila ada beberapa materi relevan

**Guru**
- Dashboard guru
- CRUD materi pembelajaran per kelas
- CRUD kuis beserta bank soal (create, edit, delete pertanyaan)
- Rekap nilai siswa

**Orang Tua**
- Dashboard orang tua
- Laporan progres belajar anak

**Admin**
- Dashboard admin
- Manajemen akun siswa dan guru (CRUD)
- Manajemen gambar landing page
- Approval pendaftaran akun baru (approve/reject)

## Tech Stack

| Bagian | Teknologi |
|---|---|
| Framework | Laravel 12.x |
| Database | MySQL |
| Autentikasi | Custom (multi-role: student/teacher/parent/admin, dengan middleware `ensure_auth`, `role`, `redirect_role`) |
| Frontend | Blade Templates |
| Testing | PHPUnit (Feature + Unit) |

> Catatan: fitur chatbot pada `/ai` bekerja dengan pencocokan kata kunci (keyword matching) terhadap tabel `materis` dan `ai_keywords`, bukan model machine learning/LLM. Cocok dijelaskan sebagai "rule-based assistant" saat wawancara, bukan "AI generatif".

## Instalasi

**Requirement:** XAMPP (Apache + MySQL), Composer, Node.js + npm

```bash
git clone https://github.com/kennethandreas/E-Learning-Website.git
cd E-Learning-Website

npm install
composer install

cp .env.example .env
php artisan key:generate
```

Buat database baru melalui phpMyAdmin, lalu sesuaikan kredensial di `.env`.

```bash
php artisan migrate:fresh --seed
php artisan storage:link
npm run dev
php artisan serve
```

Website dapat diakses melalui `http://127.0.0.1:8000`.

## Struktur Peran & Alur Autentikasi

Aplikasi memisahkan alur login/register untuk tiap peran:

- `/login/student`, `/register/student`
- `/login/teacher`, `/register/teacher`
- `/login/parent`
- Akun siswa dan guru baru melalui proses **approval** oleh admin sebelum dapat login sepenuhnya

Middleware `role:*` membatasi akses masing-masing grup route (`student`, `teacher`, `parent`, `admin`) sesuai peran user yang sedang login.

## Struktur Project

```
app/Http/Controllers/   AuthController, AdminController, DashboardController,
                         ChecklistController, MateriController, AktivitasController,
                         QuizController, QuestionController, TeacherController,
                         ParentController, AIController, ProfileController
app/Models/              User, Materi, AktivitasPembelajaran, Quiz, QuizQuestion,
                         QuizAttempt, QuizAnswer, DailyChecklist, Badge, AiKeyword
database/migrations/     skema untuk role & profil user, materi, aktivitas,
                         kuis & soal, attempt & jawaban, checklist harian,
                         poin & badge, ai_keywords
routes/web.php           seluruh routing per peran
```

## Testing

```bash
php artisan test
```


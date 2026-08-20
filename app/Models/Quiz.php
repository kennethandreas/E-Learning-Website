<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Quiz extends Model
{
    use HasFactory;

    protected $table = 'quiz';

    protected $fillable = [
        'judul',
        'kelas',
        'deskripsi',
        'kesulitan',
        'durasi',
        'passing_score',
        'jumlah_soal',
        'total_nilai',
        'waktu_mulai',
        'waktu_selesai',
        'is_active',
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'is_active' => 'boolean',
        'kelas' => 'array',
    ];

    // Relationships
    public function questions()
    {
        return $this->hasMany(QuizQuestion::class);
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function aktivitasPembelajaran()
    {
        return $this->hasMany(AktivitasPembelajaran::class);
    }

    // Helper methods
    public function isActive()
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();
        if ($this->waktu_mulai && $now->lt($this->waktu_mulai)) {
            return false;
        }

        if ($this->waktu_selesai && $now->gt($this->waktu_selesai)) {
            return false;
        }

        return true;
    }
}

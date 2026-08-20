<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quiz_id',
        'waktu_mulai',
        'waktu_selesai',
        'nilai',
        'jumlah_benar',
        'jumlah_salah',
        'status',
    ];

    public function getRemainingTimeAttribute()
    {
        if (!$this->waktu_selesai) {
            return 0;
        }

        return max(
            0,
            Carbon::now()->diffInSeconds($this->waktu_selesai, false)
        );
    }


    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'is_finished' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke quiz
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    // Relasi ke jawaban siswa
    public function answers()
    {
        return $this->hasMany(QuizAnswer::class, 'quiz_attempt_id');
    }
}

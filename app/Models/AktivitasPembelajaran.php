<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AktivitasPembelajaran extends Model
{
    use HasFactory;

    /**
     * Explicit table name to avoid incorrect automatic pluralization
     */
    protected $table = 'aktivitas_pembelajaran';

    protected $fillable = [
        'user_id',
        'materi_id',
        'quiz_id',
        'jenis',
        'status',
        'waktu_mulai',
        'waktu_selesai',
        'durasi',
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function materi()
    {
        return $this->belongsTo(Materi::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}

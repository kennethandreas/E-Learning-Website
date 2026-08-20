<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Badge extends Model
{
    protected $fillable = ['name', 'min_point', 'max_point'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}

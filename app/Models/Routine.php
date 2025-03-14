<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Routine extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_id'];

    // Añadir relación con ejercicios
    public function exercises()
    {
        return $this->belongsToMany(Exercise::class)
            ->withPivot('series', 'repetitions')
            ->withTimestamps();
    }
}

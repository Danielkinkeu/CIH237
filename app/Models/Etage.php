<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etage extends Model
{
    use HasFactory;

    protected $fillable = ['numero', 'batiment_id', 'description'];

    public function batiment()
    {
        return $this->belongsTo(Batiment::class);
    }

    public function fonctionnalites()
    {
        return $this->hasMany(Fonctionnalite::class);
    }
}

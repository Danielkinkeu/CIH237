<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cours extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'description',
        'prix',
        'image_couverture',
        'formateur_id',
        'niveau',
        'statut',
        'langue',
    ];

    public function formateur()
    {
        return $this->belongsTo(User::class, 'formateur_id');
    }
    // Vous ajouteriez ici les relations pour ModuleCours, Lecon, etc.
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evenement extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'description',
        'date_debut',
        'date_fin',
        'heure_debut',
        'heure_fin',
        'lieu',
        'type_evenement_id',
        'image',
        'prix',
        'lien_inscription',
        'langue',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    public function typeEvenement()
    {
        return $this->belongsTo(TypeEvenement::class);
    }
}

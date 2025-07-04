<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partenaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'type_partenaire',
        'description',
        'logo',
        'site_web',
        'adresse',
        'contact_email',
        'date_partenariat',
        'statut',
    ];

    protected $casts = [
        'date_partenariat' => 'date',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjetInnovateur extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_projet',
        'description',
        'user_id',
        'lien_video',
        'lien_site_web',
        'image_projet',
        'statut',
        'date_soumission',
        'langue',
    ];

    protected $casts = [
        'date_soumission' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
